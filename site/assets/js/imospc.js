var seekbar;
var _timeReached = false;
var duelzMode = false;

function initPlayer(options) {
	options = options || {
		allowSeek: function() { return true; }
	};
	setupImoSPC(options);
	setupSeekBar();
	setupVolumeSlider();
	$('.imospc .seek').click(seekBarClick);
}

function setupImoSPC(options) {
	ImoSPC.addEventListener('init', onInitOk);
	ImoSPC.addEventListener('load', onLoadOk);
	ImoSPC.addEventListener('initerror', onInitError);
	ImoSPC.addEventListener('loaderror', onLoadError);
	ImoSPC.addEventListener('playstatechange', onPlayStateChange);
	ImoSPC.init({
		autostart: true,
		preferredRuntime: ImoSPC.Runtime.HTML5
	});
	ImoSPC.allowSeek = options.allowSeek;
}

function setupSeekBar() {
	seekbar = $('.imospc .seek')
		.progressbar({
			max: 1,
			value: 0
		});
	seekbar.find('.ui-progressbar-value')
		.css('background', '#ccc');
}

function seekBarClick(e) {
	if(ImoSPC.allowSeek()) {
		var track = ImoSPC.currentTrack();
		if (track) {
			var to = ((e.pageX - $(this).offset().left) / ($(this).width() - 1)) * track.length;
			track.seek(to);
			setCurrentTimeDisplay(to);
		}
	}
}

function setCurrentTimeDisplay(time) {
	setDisplayedTime($('.imospc .curTimeDisplay'), time);
}

function setTrackLengthDisplay(time) {
	setDisplayedTime($('.imospc .lengthDisplay'), time);
}

function setDisplayedTime(display, t) {
	if (t != null) {
		display.show();
		display.text(formatTime(t));
	} else {
		display.hide();
	}
}

function formatTime(t) {
	t = +t;
	if (isNaN(t))
		return 'NaN';
	var seconds = Math.floor(t) % 60,
			minutes = Math.floor(t / 60) % 60,
			hours   = Math.floor(t / 3600);
	return (hours ? hours + (minutes < 10 ? ':0' : ':') : '') + minutes + (seconds < 10 ? ':0' : ':') + seconds;
}

var _timer;
function timerOn() {
	if (_timer)
		return;
	_timer = setInterval(function() {
		var time = Math.max(0, ImoSPC.time());
		seekbar.progressbar('option', 'value', time);
		setCurrentTimeDisplay(time);
		if(duelzMode)
			checkTimeReached();
	}, 100);
}

function timerOff(isLoading) {
	if (_timer) {
		clearInterval(_timer);
		_timer = null;
	}
	seekbar.progressbar('option', 'value', isLoading ? false : 0);
}

function checkTimeReached() {
	currentTrackShitRatio = tracks.current == 'a' ? tracks.a.shitRatio : tracks.b.shitRatio;
	if (ImoSPC.currentTrack()
		&& !_timeReached
		&& ImoSPC.time() > Math.max(5, Math.min(30, ImoSPC.currentTrack().length/2) - currentTrackShitRatio * (2/5)*ImoSPC.currentTrack().length))
	{
		_timeReached = true;
		if(typeof(timeReached) !== 'undefined')
			timeReached();
	}
}

function setupVolumeSlider() {
	$('.imospc .volume').slider({
		range: 'min',
		value: tryFetch('volume', 90),
		min: 0,
		max: 100,
		slide: function(event, ui) {
			ImoSPC.setVolume(tryStore('volume', ui.value) / 100);
		}
	}).find('.ui-slider-handle')
		.addClass('fa fa-volume-off')
		.css('padding-left', '4px');
}

function tryStore(key, value) {
	if ('localStorage' in window)
		localStorage.setItem(key, value);
	return value;
}

function tryFetch(key, _default) {
	if ('localStorage' in window)
		var value = localStorage.getItem(key);
	return value != null && !isNaN(value = +value) ? value : _default;
}

function onInitOk(evt) {
	if(!duelzMode)
		console.debug('ImoSPC init ok', evt);
	ImoSPC.setVolume(tryFetch('volume', 90) / 100);
	playerInitialized();
}

function onInitError(evt) {
	console.error('ImoSPC init error', evt);
}

function onLoadOk(evt) {
	if(!duelzMode)
		console.debug('ImoSPC load ok', evt);
	_timeReached = false;
}

function onLoadError(evt) {
	console.error('ImoSPC load error', evt);
}

var playerState = ImoSPC.PlaybackState.STOPPED;
var currentTrack = null;
function onPlayStateChange(e) {
	if(!duelzMode)
		console.debug('ImoSPC play state change', e);
	var PS = ImoSPC.PlaybackState;
	switch (e.state) {
		case PS.LOADING:
			currentTrack = ImoSPC.currentTrack();
			seekbar.progressbar('option', 'max', e.track.length);
			setCurrentTimeDisplay(0);
			setTrackLengthDisplay(e.track.length);
			playerState = 'loading';
		case PS.PLAYING:
			timerOn();
			if(typeof(seekEnd) !== 'undefined')
				seekEnd();
			playerState = 'playing';
			break;

		case PS.BUFFERING:
			timerOff(true);
			if(typeof(seekStart) !== 'undefined')
				seekStart();
			playerState = 'buffering';
			break;

		case PS.PAUSED:
			timerOn();
			playerState = 'paused';
			break;

		case PS.STOPPED:
			if(playerState == PS.PLAYING) {
				if(typeof(songEnded) !== 'undefined')
					songEnded();
			} else {
				timerOff();
				setCurrentTimeDisplay(null);
				setTrackLengthDisplay(null);
			}
			playerState = 'stopped';
	}
}
