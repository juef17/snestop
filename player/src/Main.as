﻿package 
{
	import flash.display.Graphics;
	import flash.display.Bitmap;
	import flash.display.SimpleButton;
	import flash.display.Sprite;
	import flash.display.LoaderInfo;
	import flash.display.StageScaleMode;
	import flash.display.StageAlign;
	import flash.net.URLRequest;
	import flash.net.navigateToURL;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.events.TimerEvent;
	import flash.media.Sound;
	import flash.media.SoundLoaderContext;
	import flash.media.SoundChannel;
	import flash.media.SoundTransform;
	import flash.text.TextFormat;
	import flash.text.TextField;
	import flash.utils.Timer;
	import flash.external.ExternalInterface;
	import com.bit101.components.*;
	import gme.EmulatorType;
	import gme.GameMusicEmu;
	import gme.SampleRate;
	
	public class Main extends Sprite 
	{
		[Embed(source="../assets/snes.ttf", fontName = "SNES", mimeType = "application/x-font", fontWeight="normal", fontStyle="normal", unicodeRange="U+0020-U+007E", advancedAntiAliasing="true", embedAsCFF="false")]
        private var myEmbeddedFont:Class;
		[Embed(source="../assets/logo.png")]
		private var Logo:Class;
		private var gameMusicEmu:GameMusicEmu;
		private var labelLength:Label;
		private var sliderSeeking:Boolean;
		private var showSeekBar:Boolean;
		private var showLogo:Boolean;
		private var showPanBar:Boolean;
		private var showPosition:Boolean;
		private var showVolumeBar:Boolean;
		private var sliderPosition:HSlider;
		private var donePlaying:Boolean;
		private var timer:Timer;
		private var filename:String;
		private var fade:int;
		private var length:int;
		private var pauseButton:PushButton;
		private var petit:TextFormat;
		private var textePosition:TextField;
		private const heightOffset:int = 7;
		private var logoOffset:int = 0;
		private var panOffset:int = 0;
		private var seekOffset:int = 0;
		private var positionOffset:int = 0;
		private var volumeOffset:int = 0;
		private var mp3:Sound;
		private var mp3SoundTransform:SoundTransform;
		private var mp3Context:SoundLoaderContext;
		private var mp3Channel:SoundChannel;
		private var mp3Position:int = 0;
		private var mp3IsPlaying:Boolean = false;
		
		public function Main():void 
		{
			if (stage)
			{
				stage.scaleMode = StageScaleMode.NO_SCALE;
				stage.align =  StageAlign.TOP_LEFT;
				init();
			}
			else addEventListener(Event.ADDED_TO_STAGE, init);
			ExternalInterface.call("playerInitialized");
		}
		
		private function init(e:Event = null):void 
		{
			removeEventListener(Event.ADDED_TO_STAGE, init);
			ExternalInterface.addCallback("playUrl", playUrl);
			ExternalInterface.addCallback("play", playSansUrl);
			ExternalInterface.addCallback("rewind", rewind);
			
			filename = LoaderInfo(this.root.loaderInfo).parameters.spc;
			fade = int(LoaderInfo(this.root.loaderInfo).parameters.fade)*1000;
			length = int(LoaderInfo(this.root.loaderInfo).parameters.length)*1000;
			
			if (LoaderInfo(this.root.loaderInfo).parameters.showSeekBar == 1) showSeekBar = true;
			if (LoaderInfo(this.root.loaderInfo).parameters.showLogo == 1) showLogo = true;
			if (LoaderInfo(this.root.loaderInfo).parameters.showVolumeBar == 1) showVolumeBar = true;
			if (LoaderInfo(this.root.loaderInfo).parameters.showPanBar == 1) showPanBar = true;
			if (LoaderInfo(this.root.loaderInfo).parameters.showPosition == 1) showPosition = true;
			
			if (!showLogo) logoOffset = -95;
			if (!showVolumeBar) volumeOffset = -30;
			if (!showPanBar) panOffset = -30;
			if (!showSeekBar) seekOffset = -8;
			if (!showPosition && !showSeekBar) positionOffset = -22;
			
			if (showLogo)
			{
				var logo:Bitmap = new Logo();
				logo.y = -10 + heightOffset;
				var sprite: Sprite = new Sprite();
				sprite.addChild(logo);
				sprite.addEventListener(MouseEvent.CLICK, function ():void 
				{
					var req:URLRequest = new URLRequest("http://www.google.ca/");
					navigateToURL(req, "_blank");
				});
				addChild(sprite);
			}
			
			petit = new TextFormat( "SNES", 12, 0xFFFFFF);
			
			pauseButton = new PushButton(this, 135 + logoOffset, 78 + heightOffset + volumeOffset + panOffset + seekOffset + positionOffset, "Pause", function (e:Event):void 
			{
				if (loadedType() == "spc")
				{
					if (gameMusicEmu.isPaused)
					{
						gameMusicEmu.play();
						pauseButton.label = "Pause";
					}
					else if (gameMusicEmu.isPlaying)
					{
						gameMusicEmu.pause();
						pauseButton.label = "Resume";
					}
				}
				else if (loadedType() == "mp3")
				{
					if(mp3IsPlaying)
					{
						mp3Position = mp3Channel.position;
						mp3Channel.stop();
						mp3IsPlaying = false;
						pauseButton.label = "Resume";
					}
					else if(!mp3IsPlaying)
					{
						mp3Channel = mp3.play(mp3Position);
						mp3IsPlaying = true;
						pauseButton.label = "Pause";
					}
				}
			});
			pauseButton.visible = true;
			
            var labelVolume:TextField = new TextField;
            labelVolume.embedFonts = true;
            labelVolume.defaultTextFormat = petit;
            labelVolume.text = "Volume";
            labelVolume.x = 100 + logoOffset;
			labelVolume.y = -10 + heightOffset;
			labelVolume.height = 12;
            this.addChild(labelVolume);
			var sliderVolume:HSlider = new HSlider(this, 100 + logoOffset, 5 + heightOffset, function (e:Event):void 
			{
				gameMusicEmu.volume = sliderVolume.value;
				mp3Channel.soundTransform = new SoundTransform(sliderVolume.value, sliderPan.value);
			});
			sliderVolume.value = 1.0;
			sliderVolume.maximum = 1.0;
			sliderVolume.width = 170;
			labelVolume.visible = showVolumeBar;
			sliderVolume.visible = showVolumeBar;
			
			var labelPan:TextField = new TextField;
            labelPan.embedFonts = true;
            labelPan.defaultTextFormat = petit;
            labelPan.text = "Pan";
            labelPan.x = 100 + logoOffset;
			labelPan.y = 20 + heightOffset + volumeOffset;
			labelPan.height = 12;
            this.addChild(labelPan);
			var sliderPan:HSlider = new HSlider(this, 100 + logoOffset, 35 + heightOffset + volumeOffset, function (e:Event):void 
			{
				gameMusicEmu.pan = sliderPan.value;
				mp3Channel.soundTransform = new SoundTransform(sliderVolume.value, sliderPan.value);
			});
			sliderPan.value = 0.0;
			sliderPan.minimum = -1.0;
			sliderPan.maximum = 1.0;
			sliderPan.width = 170;
			labelPan.visible = showPanBar;
			sliderPan.visible = showPanBar;
			
			var labelPosition:TextField = new TextField;
            labelPosition.embedFonts = true;
            labelPosition.defaultTextFormat = petit;
            labelPosition.text = "Position:";
            labelPosition.x = 100 + logoOffset;
			labelPosition.y = 50 + heightOffset + volumeOffset + panOffset;
			labelPosition.height = 12;
            this.addChild(labelPosition);
			labelPosition.visible = showPosition;
			
			sliderPosition = new HSlider(this, 100 + logoOffset, 65 + heightOffset + volumeOffset + panOffset, function (e:Event):void 
			{
				if (loadedType() == "spc") if (gameMusicEmu.emulatorType == "") return;
				sliderSeeking = true;
				textePosition.text = toTimeCode(sliderPosition.value) + " / " + toTimeCode(length + fade);
			});
			stage.addEventListener(MouseEvent.MOUSE_UP, function (e:MouseEvent):void 
			{
				if (sliderSeeking == false) return;
				sliderSeeking = false;
				if (gameMusicEmu.isPlaying == false && !mp3IsPlaying) return;
				textePosition.text = toTimeCode(sliderPosition.value) + " / " + toTimeCode(length + fade);
				if (loadedType() == "spc")
				{
					gameMusicEmu.pause();
					gameMusicEmu.seek(sliderPosition.value);
					gameMusicEmu.play();
				}
				else if (loadedType() == "mp3")
				{
					mp3Channel.stop();
					mp3Channel = mp3.play(sliderPosition.value);
				}
			});
			sliderPosition.width = 170;
			sliderPosition.visible = showSeekBar;
			
			textePosition = new TextField;
            textePosition.embedFonts = true;
            textePosition.defaultTextFormat = petit;
            textePosition.text = "00:00 / 00:00";
            textePosition.x = 160 + logoOffset;
			textePosition.y = 50 + heightOffset + volumeOffset + panOffset;
			textePosition.height = 12;
            this.addChild(textePosition);
			textePosition.visible = showPosition;
			
			gameMusicEmu = new GameMusicEmu(SampleRate.HIGH);
		}

		private function onLoadComplete(e:Event):void 
		{
			if (timer != null) timer.stop();
			timer = new Timer(100, 0);
			timer.addEventListener(TimerEvent.TIMER, function ():void 
			{
				if (sliderSeeking) return;
				var position:uint = (loadedType() == "spc") ? gameMusicEmu.tell() : mp3Channel.position;
				textePosition.text = toTimeCode(position) + " / " + toTimeCode(length + fade);
				sliderPosition.value = position;
				if (position >= length + fade && !donePlaying)
				{
					(loadedType() == "spc") ? gameMusicEmu.stop() : mp3Channel.stop();
					donePlaying = true;
					ExternalInterface.call("songEnded");
				}
				if (loadedType() == "spc") gameMusicEmu.setFade(length);
			});
			timer.start();
		}
		
		private function playUrl(message:String):void
		{
			var tmp:Array = message.split("?");
			filename = tmp[0];
			length = tmp[1] * 1000;
			fade = tmp[2] * 1000;
			donePlaying = false;
			this.pauseButton.label = "Pause";
			sliderPosition.maximum = fade + length;
			sliderPosition.value = 0;
			if(mp3IsPlaying) mp3Channel.stop();
			gameMusicEmu.stop();
			mp3IsPlaying = false;
			if (loadedType() == "spc")
			{
				gameMusicEmu = new GameMusicEmu(SampleRate.HIGH);
				gameMusicEmu.addEventListener(Event.COMPLETE, onLoadComplete);
				gameMusicEmu.init(EmulatorType.SPC);
				gameMusicEmu.load(filename);
				gameMusicEmu.play();
				gameMusicEmu.setFade(length);
			}
			else if (loadedType() == "mp3")
			{
				mp3IsPlaying = true;
				mp3 = new Sound();
				mp3Context = new SoundLoaderContext(8000, false);
				mp3.load(new URLRequest(filename), mp3Context);
				mp3.addEventListener(Event.COMPLETE, onLoadComplete);
				mp3Channel = mp3.play();
			}
		}
		
		private function playSansUrl():void
		{
			if (loadedType() == "spc")
			{
				if (gameMusicEmu.isPaused)
				{
					gameMusicEmu.play();
					pauseButton.label = "Pause";
				}
			}
			else if (loadedType() == "mp3")
			{
				if(!mp3IsPlaying)
				{
					mp3Channel = mp3.play(mp3Position);
					mp3IsPlaying = true;
					pauseButton.label = "Pause";
				}
			}
		}
		
		private function rewind():void
		{
			textePosition.text = toTimeCode(0) + " / " + toTimeCode(length + fade);
			if (loadedType() == "spc")
			{
				if (gameMusicEmu.isPaused)
				{
					gameMusicEmu.seek(0);
				}
				else if (gameMusicEmu.isPlaying)
				{
					gameMusicEmu.pause();
					gameMusicEmu.seek(0);
					gameMusicEmu.play();
				}
			}
			else if (loadedType() == "mp3")
			{
				mp3Position = 0;
				if(mp3IsPlaying)
				{
					mp3Channel.stop();
					mp3Channel = mp3.play(mp3Position);
				}
			}
		}
		
		public static function toTimeCode(milliseconds:int) : String
		{
			var isNegative:Boolean = false;
			if (milliseconds < 0) {
				isNegative = true;
				milliseconds = Math.abs(milliseconds);			
			}
			var seconds:int = Math.round((milliseconds/1000) % 60);
			var strSeconds:String = (seconds < 10) ? ("0" + String(seconds)) : String(seconds);
			if(seconds == 60) strMinutes = "00";
			var minutes:int = Math.round(Math.floor((milliseconds/1000)/60));
			var strMinutes:String = (minutes < 10) ? ("0" + String(minutes)) : String(minutes);
			if (minutes > 60) {
				strSeconds = "60";
				strMinutes = "00";
			}
			var timeCodeAbsolute:String = strMinutes + ":" + strSeconds;
			var timeCode:String = (isNegative) ? "-" + timeCodeAbsolute : timeCodeAbsolute;
			return timeCode;
		}
		
		private function getExtension($url:String):String
		{
			return $url.substring($url.lastIndexOf(".")+1, $url.length);
		}
		
		private function loadedType():String
		{
			return getExtension(filename).toLowerCase();
		}
	}
}