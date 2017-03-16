package
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
        public var myEmbeddedFont:Class;
		[Embed(source="../assets/logo.png")]
		public var Logo:Class;
		public var gameMusicEmu:GameMusicEmu;
		public var labelLength:Label;
		public var sliderSeeking:Boolean;
		public var showSeekBar:Boolean;
		public var showLogo:Boolean;
		public var showPanBar:Boolean;
		public var showPosition:Boolean;
		public var showVolumeBar:Boolean;
		public var sliderPosition:HSlider;
		public var sliderPan:HSlider;
		public var sliderVolume:HSlider;
		public var donePlaying:Boolean;
		public var timer:Timer;
		public var filename:String;
		public var fade:int;
		public var length:int;
		public var oldLength:int;
		public var pauseButton:PushButton;
		public var petit:TextFormat;
		public var textePosition:TextField;
		public var labelPosition:TextField;
		public const heightOffset:int = 7;
		public const ramoutzDelay:int = 20000;
		public var logoOffset:int = 0;
		public var panOffset:int = 0;
		public var seekOffset:int = 0;
		public var positionOffset:int = 0;
		public var volumeOffset:int = 0;
		public var mp3:Sound;
		public var mp3SoundTransform:SoundTransform;
		public var mp3Context:SoundLoaderContext;
		public var mp3Channel:SoundChannel;
		public var mp3Position:int = 0;
		public var mp3IsPlaying:Boolean = false;
		public var ramoutzEnTrainDeRouler:Boolean = false;
		public var url:String = "";
		public var loopMP3:Boolean = false;
		public var loopSPC:Boolean = false;
		public const MAX_VALUE:int = 2147483647;
		
		public function Main():void 
		{
			if (stage)
			{
				stage.scaleMode = StageScaleMode.NO_SCALE;
				stage.align =  StageAlign.TOP_LEFT;
				init();
				ExternalInterface.call("playerInitialized");
			}
			else addEventListener(Event.ADDED_TO_STAGE, init);
		}
		
		public function init(e:Event = null):void 
		{
			removeEventListener(Event.ADDED_TO_STAGE, init);
			
			filename = LoaderInfo(this.root.loaderInfo).parameters.spc;
			fade = int(LoaderInfo(this.root.loaderInfo).parameters.fade) * 1000;
			if (fade > 5000) fade = 5000;
			length = int(LoaderInfo(this.root.loaderInfo).parameters.length) * 1000;
			oldLength = length;
			
			if (LoaderInfo(this.root.loaderInfo).parameters.showSeekBar == 1) showSeekBar = true;
			if (LoaderInfo(this.root.loaderInfo).parameters.showLogo == 1) showLogo = true;
			if (LoaderInfo(this.root.loaderInfo).parameters.showVolumeBar == 1) showVolumeBar = true;
			if (LoaderInfo(this.root.loaderInfo).parameters.showPanBar == 1) showPanBar = true;
			if (LoaderInfo(this.root.loaderInfo).parameters.showPosition == 1) showPosition = true;
			ExternalInterface.addCallback("playUrl", playUrl);
			ExternalInterface.addCallback("play", playSansUrl);
			ExternalInterface.addCallback("rewind", rewind);
			ExternalInterface.addCallback("unloadTrack", unloadTrack);
			ExternalInterface.addCallback("debug", debug);
			ExternalInterface.addCallback("enableLoop", enableLoop);
			
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
					if (gameMusicEmu.isPaused || !gameMusicEmu.isPlaying)
					{
						gameMusicEmu.play();
						pauseButton.label = "Pause";
						donePlaying = false;
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
						donePlaying = false;
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
			sliderVolume = new HSlider(this, 100 + logoOffset, 5 + heightOffset, function (e:Event):void 
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
			sliderPan = new HSlider(this, 100 + logoOffset, 35 + heightOffset + volumeOffset, function (e:Event):void 
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
			
			labelPosition = new TextField;
            labelPosition.embedFonts = true;
            labelPosition.defaultTextFormat = petit;
            labelPosition.text = "Position:";
            labelPosition.x = 100 + logoOffset;
			labelPosition.y = 50 + heightOffset + volumeOffset + panOffset;
			labelPosition.height = 12;
			labelPosition.width = 160;
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
				var onEnvoieLesMessagesDeSeek:Boolean = false;
				if (sliderSeeking == false) return;
				sliderSeeking = false;
				if (gameMusicEmu.isPlaying == false && !mp3IsPlaying) return;
				textePosition.text = toTimeCode(sliderPosition.value) + " / " + toTimeCode(length + fade);
				if (sliderPosition.value - gameMusicEmu.tell() > ramoutzDelay || (sliderPosition.value < gameMusicEmu.tell() && sliderPosition.value > ramoutzDelay))
				{
					onEnvoieLesMessagesDeSeek = true;
					ExternalInterface.call("seekStart");
				}
				if (loadedType() == "spc")
				{
					gameMusicEmu.pause();
					if (gameMusicEmu.tell() - sliderPosition.value > 0) gameMusicEmu.seek(0);
					var coefficient:Number = 100.0;
					var diff:Number;
					var ramoutz:Timer = new Timer(1);
					ramoutz.addEventListener(TimerEvent.TIMER, faireRoulerRamoutz);
					function faireRoulerRamoutz(e:TimerEvent):void
					{
						diff = gameMusicEmu.tell() - sliderPosition.value;
						if (Math.abs(diff) < coefficient) gameMusicEmu.seek(sliderPosition.value);
						else gameMusicEmu.seek(gameMusicEmu.tell() + coefficient);
						if (Math.abs(gameMusicEmu.tell() - sliderPosition.value) < coefficient)
						{
							ramoutz.stop();
							ramoutz = null;
							gameMusicEmu.play();
							ramoutzEnTrainDeRouler = false;
							if(onEnvoieLesMessagesDeSeek) ExternalInterface.call("seekEnd");
						}
					}
					ramoutzEnTrainDeRouler = true;
					ramoutz.start();
				}
				else if (loadedType() == "mp3")
				{
					mp3Channel.stop();
					mp3Channel = mp3.play(sliderPosition.value);
					if(onEnvoieLesMessagesDeSeek) ExternalInterface.call("seekEnd");
				}
				if (Math.abs(gameMusicEmu.tell() - sliderPosition.value) >= 5000)
				{
					ExternalInterface.call("playerFucké");
					debug();
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

		public function onLoadComplete(e:Event):void 
		{
			var timeReached:Boolean = false;
			if (timer != null) timer.stop();
			timer = new Timer(100, 0);
			timer.addEventListener(TimerEvent.TIMER, function ():void 
			{
				if (sliderSeeking || ramoutzEnTrainDeRouler) return;
				var position:uint = (loadedType() == "spc") ? gameMusicEmu.tell() : mp3Channel.position;
				textePosition.text = toTimeCode(position) + " / " + toTimeCode(length + fade);
				sliderPosition.value = position;
				if (position >= length + fade && !donePlaying)
				{
					if (loadedType() == "mp3" && loopMP3)
					{
						mp3Channel.stop();
						mp3Channel = mp3.play();
					}
					else
					{
						(loadedType() == "spc") ? gameMusicEmu.stop() : mp3Channel.stop();
						rewind();
						pauseButton.label = "Play";
						donePlaying = true;
						ExternalInterface.call("songEnded");
					}
				}
				if (loadedType() == "spc") gameMusicEmu.setFade(length);
				if (!timeReached && position > Math.min(60000, length / 2))
				{
					timeReached = true;
					ExternalInterface.call("timeReached");
				}
			});
			timer.start();
		}
		
		public function playUrl(message:String):void
		{
			url = message;
			var tmp:Array = message.split("?");
			filename = tmp[0];
			length = tmp[1] * 1000;
			oldLength = length;
			fade = tmp[2] * 1000;
			if (fade > 5000) fade = 5000;
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
				setVolumeAndPan();
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
		
		public function playSansUrl():void
		{
			donePlaying = false;
			if (loadedType() == "spc")
			{
				if (gameMusicEmu.tell() == 0) playUrl(url);
				else if (gameMusicEmu.isPaused)
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
		
		public function unloadTrack():void
		{
			if (filename == null || filename == "") return;
			donePlaying = true;
			rewind();
			length = 0;
			oldLength = 0;
			fade = 0;
			if (loadedType() == "spc" && gameMusicEmu._loadOK) gameMusicEmu.stop();
			else if (loadedType() == "mp3") mp3Channel.stop();
			filename = "";
			textePosition.text = toTimeCode(0) + " / " + toTimeCode(0);
			pauseButton.label = "Play";
		}
		
		public function rewind():void
		{
			if (filename == null || filename == "") return;
			textePosition.text = toTimeCode(0) + " / " + toTimeCode(length + fade);
			if (loadedType() == "spc")
			{
				if (gameMusicEmu.isPlaying)
				{
					gameMusicEmu.pause();
					if (gameMusicEmu._loadOK) gameMusicEmu.seek(0);
					gameMusicEmu.play();
				}
				else gameMusicEmu.seek(0);
			}
			if (loadedType() == "mp3")
			{
				mp3Position = 0;
				if(mp3IsPlaying)
				{
					mp3Channel.stop();
					mp3Channel = mp3.play(mp3Position);
				}
			}
		}
		
		public function enableLoop(loop:Boolean):void
		{
			if (loadedType() == "mp3")
			{
				loopMP3 = loop;
			}
			else if(loadedType() == "spc")
			{
				var position:uint = gameMusicEmu.tell();
				sliderPosition.visible = !loop;
				textePosition.visible = !loop;
				if (loop && !loopSPC)
				{
					oldLength = length;
					loopSPC = true;
					gameMusicEmu.setFade(MAX_VALUE);
					length = MAX_VALUE;
					labelPosition.text = "Natural loop mode is on!";
				}
				else if(!loop && loopSPC)
				{
					length = oldLength;
					loopSPC = false;
					if (position > length) length = position;
					gameMusicEmu.setFade(length);
					labelPosition.text = "Position:";
					textePosition.text = toTimeCode(sliderPosition.value) + " / " + toTimeCode(length + fade);
				}
			}
			return;
		}
		
		public function debug(msg:String = ""):void
		{
			var dateSti:Date = new Date();
			
			if (msg != "") { ExternalInterface.call("alert", msg); return;}
			
			msg += "datetime: " + dateSti.getFullYear() + "-" + dateSti.getMonth() + "-" + dateSti.getDate() + " " + dateSti.getHours() + ":" + dateSti.getMinutes() + ":" + dateSti.getSeconds() + "\n";
			msg += "filename: " + filename + "\n";
			msg += "fade: " + fade + "\n";
			msg += "length: " + length + "\n";
			msg += "oldLength: " + oldLength + "\n";
			msg += "tell: " + gameMusicEmu.tell() + "\n";
			msg += "sliderPosition: " + sliderPosition.value + "\n";
			msg += "  difference: " + Math.abs(gameMusicEmu.tell() - sliderPosition.value) + "\n";
			msg += "donePlaying: " + (donePlaying ? "true" : "false") + "\n";
			
			ExternalInterface.call("console.log", msg);
		}
		
		public static function toTimeCode(milliseconds:int) : String
		{
			var isNegative:Boolean = false;
			if (milliseconds < 0) {
				isNegative = true;
				milliseconds = Math.abs(milliseconds);			
			}
			var seconds:int = Math.round(Math.floor((milliseconds/1000)) % 60);
			var minutes:int = Math.round(Math.floor((milliseconds / 1000) / 60));
			if (seconds >= 60)
			{
				seconds = 0;
				minutes++;
			}
			var strSeconds:String = (seconds < 10) ? ("0" + String(seconds)) : String(seconds);
			var strMinutes:String = (minutes < 10) ? ("0" + String(minutes)) : String(minutes);
			if (minutes > 99) {
				strSeconds = "59";
				strMinutes = "99";
			}
			var timeCodeAbsolute:String = strMinutes + ":" + strSeconds;
			var timeCode:String = (isNegative) ? "-" + timeCodeAbsolute : timeCodeAbsolute;
			return timeCode;
		}
		
		public function getExtension($url:String):String
		{
			return $url.substring($url.lastIndexOf(".")+1, $url.length);
		}
		
		public function loadedType():String
		{
			if (filename == null || filename == "") return "";
			return getExtension(filename).toLowerCase();
		}
		
		public function setVolumeAndPan():void
		{
			gameMusicEmu.volume = sliderVolume.value;
			gameMusicEmu.pan = sliderPan.value;
		}
	}
}