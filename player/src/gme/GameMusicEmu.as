﻿package gme
{
	import cmodule.libgme.CLibInit;
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.events.EventDispatcher;
	import flash.events.SampleDataEvent;
	import flash.media.Sound;
	import flash.media.SoundChannel;
	import flash.media.SoundTransform;
	import flash.net.URLLoader;
	import flash.net.URLLoaderDataFormat;
	import flash.net.URLRequest;
	import flash.utils.ByteArray;
	import flash.utils.Endian;
	
	/**
	 * game-music-emu-0.6.0 wrapper.
	 * @author Hikipuro
	 */
	public class GameMusicEmu extends EventDispatcher
	{
		public var _loadOK:Boolean;
		
		/**
		 * game-music-emu library.
		 */
		private var _libgme:Object;
		
		/**
		 * sound object.
		 */
		private var _sound:Sound;
		
		/**
		 * Sound channel object.
		 * it controls to stop sound.
		 */
		private var _soundChannel:SoundChannel;
		
		/**
		 * for control sound volume.
		 */
		private var _soundTransform:SoundTransform;
		
		/**
		 * URL Loader for SPC file.
		 */
		private var _urlLoader:URLLoader;
		
		/**
		 * sound playing flag.
		 */
		private var _isPlaying:Boolean;
		
		/**
		 * sound paused flag.
		 */
		private var _isPaused:Boolean;
		
		/**
		 * ByteArray for wave sound.
		 */
		private var _out:ByteArray;
		
		/**
		 * sampling rate.
		 * (44100, 22050, 11025)
		 */
		private var _sampleRate:uint;
		
		/**
		 * sound volume.
		 */
		private var _volume:Number;
		
		/**
		 * emulator type.
		 * see EmulatorType class.
		 */
		private var _emulatorType:String;
		
		/**
		 * readonly playing flag.
		 * this flag is true if now playing sound.
		 */
		public function get isPlaying():Boolean
		{
			return _isPlaying;
		}
		
		/**
		 * readonly paused flag.
		 * this flag is true if now paused sound.
		 */
		public function get isPaused():Boolean
		{
			return _isPaused;
		}
		
		/**
		 * readonly sound sample rate.
		 * 44100, 22050, 11025
		 */
		public function get sampleRate():uint 
		{
			return _sampleRate;
		}
		
		/**
		 * sound volume.
		 * max: 1.0, min: 0.0.
		 */
		public function get volume():Number
		{
			return _volume;
		}
		
		public function set volume(value:Number):void
		{
			if (value > 1.0)
				value = 1.0;
			if (value < 0.0)
				value = 0.0;
			_volume = value;
			_soundTransform.volume = _volume;
			
			if (_soundChannel != null) {
				_soundChannel.soundTransform = _soundTransform;
			}
		}
		
		/**
		 * Pan.
		 * right: 1.0, center: 0.0, left: -1.0.
		 */
		public function get pan():Number
		{
			return _soundTransform.pan;
		}
		
		public function set pan(value:Number):void
		{
			if (value > 1.0)
				value = 1.0;
			if (value < -1.0)
				value = -1.0;
			_soundTransform.pan = value;
			
			if (_soundChannel != null) {
				_soundChannel.soundTransform = _soundTransform;
			}
		}
		
		/**
		 * emulator type.
		 */
		public function get emulatorType():String
		{
			return _emulatorType;
		}
		
		/**
		 * constructor.
		 */
		public function GameMusicEmu(sampleRate:uint = 44100) 
		{
			switch (sampleRate) 
			{
				case 44100:
					sampleRate = 44100;
					break;
				case 22050:
					sampleRate = 22050;
					break;
				case 11025:
					sampleRate = 11025;
					break;
				default:
					sampleRate = 44100;
					break;
			}
			
			_isPlaying = false;
			_sampleRate = sampleRate;
			_volume = 1.0;
			_sound = new Sound();
			_soundTransform = new SoundTransform();
			_emulatorType = "";
			
			// load game-music-emu-0.6.0
			var loader:CLibInit = new CLibInit;
			_libgme = loader.init();
			
			// prepare output buffer
			_out = new ByteArray();
			_out.endian = Endian.LITTLE_ENDIAN;
		}
		
		/**
		 * init emulator
		 * @param	type	emulator type string (EmulatorType class)
		 */
		public function init(type:String):void 
		{
			_emulatorType = type;
			switch (type) 
			{
				case EmulatorType.AY:
					_libgme.initAy(_sampleRate);
					break;
				case EmulatorType.GBS:
					_libgme.initGbs(_sampleRate);
					break;
				case EmulatorType.GYM:
					_libgme.initGym(_sampleRate);
					break;
				case EmulatorType.HES:
					_libgme.initHes(_sampleRate);
					break;
				case EmulatorType.KSS:
					_libgme.initKss(_sampleRate);
					break;
				case EmulatorType.NSF:
					_libgme.initNsf(_sampleRate);
					break;
				case EmulatorType.NSFE:
					_libgme.initNsfe(_sampleRate);
					break;
				case EmulatorType.SAP:
					_libgme.initSap(_sampleRate);
					break;
				case EmulatorType.SPC:
					_libgme.initSpc(_sampleRate);
					break;
				case EmulatorType.VGM:
					_libgme.initVgm(_sampleRate);
					break;
				case EmulatorType.VGZ:
					_libgme.initVgz(_sampleRate);
					break;
				default:
					_emulatorType = "";
					break;
			}
		}
		
		/**
		 * select track number.
		 * this is used in sound track file type.
		 * ex. KSS
		 * @param	value	track number
		 */
		public function startTrack(value:uint):void 
		{
			_libgme.startTrack(value);
		}
		
		/**
		 * Number of tracks available.
		 * @return track count.
		 */
		public function trackCount():int 
		{
			return _libgme.trackCount();
		}
		
		/**
		 * True if a track has reached its end
		 * @return
		 */
		public function trackEnded():Boolean 
		{
			return _libgme.trackEnded();
		}
		
		/**
		 * Gets information for a particular track.
		 * (length, name, author, etc.)
		 * @param	track	track number.
		 * @return	track infomation.
		 */
		public function trackInfo(track:uint):Object 
		{
			return _libgme.trackInfo(track);
		}
		
		/**
		 * Number of milliseconds (1000 = one second)
		 * played since beginning of track
		 * @return
		 */
		public function tell():int 
		{
			return _libgme.tell();
		}
		
		/**
		 * Seek to new time in track.
		 * Seeking backwards or far forward can take a while.
		 * @param	msec	milli second
		 */
		public function seek(msec:uint):void 
		{
			_libgme.seek(msec);
		}
		
		/**
		 * Adjust song tempo.
		 * 1.0 = normal, 0.5 = half speed, 2.0 = double speed.
		 * Track length as returned by track_info() assumes a tempo of 1.0.
		 * @param	tempo	tempo
		 */
		public function setTempo(tempo:Number):void 
		{
			_libgme.setTempo(tempo);
		}
		
		/**
		 * Set time to start fading track out.
		 * Once fade ends track_ended() returns true.
		 * Fade time can be changed while track is playing.
		 * @param	startMsec
		 */
		public function setFade(startMsec:int):void 
		{
			_libgme.setFade(startMsec);
		}
		
		/**
		 * Adjust stereo echo depth.
		 * 0.0 = off and 1.0 = maximum. 
		 * Has no effect for GYM, SPC, and Sega Genesis VGM music.
		 * @param	depth
		 */
		public function setStereoDepth(depth:Number):void 
		{
			_libgme.setStereoDepth(depth);
		}
		
		/**
		 * load data from url.
		 * @param	url	file's url
		 */
		public function load(url:String):void 
		{
			// load sound file in URL
			_urlLoader = new URLLoader();
			_urlLoader.dataFormat = URLLoaderDataFormat.BINARY;
			_urlLoader.addEventListener(Event.COMPLETE, onLoadComplete);
			_urlLoader.addEventListener(Event.COMPLETE, onLoadFail);
			_urlLoader.load(new URLRequest(url));
		}
		
		/**
		 * load data direct.
		 * @param	data	data contains in ByteArray
		 */
		public function loadData(data:ByteArray):void 
		{
			data.endian = Endian.LITTLE_ENDIAN;
			data.position = 0;
			
			// uncompress data if EmulatorType is VGZ.
			if (_emulatorType == EmulatorType.VGZ) {
				data.writeBytes(data, 10, data.length - 10);
				data.inflate();
			}
			
			_libgme.load(data, data.length);
			
			// dispatch event to parent
			dispatchLoadCompleteEvent();
		}
		
		/**
		 * play sound.
		 */
		public function play():void 
		{
			if (_isPaused) {
				_isPaused = false;
				_isPlaying = true;
				_sound.addEventListener(SampleDataEvent.SAMPLE_DATA, onSampleData);
				_soundChannel = _sound.play(0, 1, _soundTransform);
				return;
			}
			
			_isPaused = false;
			_isPlaying = true;
			_sound.removeEventListener(SampleDataEvent.SAMPLE_DATA, onSampleData);
			_sound.addEventListener(SampleDataEvent.SAMPLE_DATA, onSampleData);
			
			_soundTransform.volume = _volume;
			_soundChannel = _sound.play(0, 1, _soundTransform);
		}
		
		/**
		 * stop sound.
		 */
		public function stop():void 
		{
			_isPaused = false;
			_isPlaying = false;
			_sound.removeEventListener(SampleDataEvent.SAMPLE_DATA, onSampleData);
			
			if (_soundChannel != null) {
				_soundChannel.stop();
			}
		}
		
		/**
		 * pause.
		 */
		public function pause():void 
		{
			_isPaused = true;
			_isPlaying = false;
			_sound.removeEventListener(SampleDataEvent.SAMPLE_DATA, onSampleData);
			
			if (_soundChannel != null) {
				_soundChannel.stop();
			}
		}
		
		
		private function onLoadFail(e:IOErrorEvent):void
		{
			_loadOK = false;
		}
		
		/**
		 * load complete event for URL Loader
		 * @param	e
		 */
		private function onLoadComplete(e:Event):void 
		{
			_loadOK = true;
			// sound file's content into ByteArray
			var data:ByteArray;
			data = _urlLoader.data as ByteArray;
			data.endian = Endian.LITTLE_ENDIAN;
			data.position = 0;
			
			// uncompress data if EmulatorType is VGZ.
			if (_emulatorType == EmulatorType.VGZ) {
				data.writeBytes(data, 10, data.length - 10);
				data.inflate();
			}
			
			_libgme.load(data, data.length);
			
			// dispatch event to parent
			dispatchLoadCompleteEvent();
		}
		
		/**
		 * dispatch UrlLoader's load complete event
		 */
		private function dispatchLoadCompleteEvent():void 
		{
			var event:Event;
			event = new Event(Event.COMPLETE);
			dispatchEvent(event);
		}
		
		/**
		 * audio data require event
		 * @param	event
		 */
		private function onSampleData(e:SampleDataEvent):void 
		{
			var data:ByteArray = e.data;
			
			var length:uint;
			var loop:uint;
			
			switch (_sampleRate) 
			{
				case 44100:
					length = 8192;
					loop = 1;
					break;
				case 22050:
					length = 4096;
					loop = 2;
					break;
				case 11025:
					length = 2048;
					loop = 4;
					break;
			}
			
			// execute SPC emulator for getting wave audio
			_out.position = 0;
			_libgme.play(_out, length);
			_out.position = 0;
			
			// executing while for wave length
			for (var i:uint = 0; i < length; i++) {
				var s1:Number = 0;
				var s2:Number = 0;
				
				// play() method returned for 16 bit wave sound data
				// but it is not playable for Flash Player.
				// converting float type wave sound data.
				s1 = _out.readShort();
				s1 /= 32768;
				s2 = _out.readShort();
				s2 /= 32768;
				
				// write wave sound buffer
				for (var n:uint = 0; n < loop; n++) {
					data.writeFloat(s1); // left channel
					data.writeFloat(s2); // right channel
				}
			}
		}
	}

}