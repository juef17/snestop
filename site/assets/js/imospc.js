$(function() {
	setupImoSPC();
	setupSeekBar();
	setupVolumeSlider();
});

function setupImoSPC() {
	ImoSPC.addEventListener('init', onInitOk);
	ImoSPC.addEventListener('load', onLoadOk);
	ImoSPC.addEventListener('initerror', onInitError);
	ImoSPC.addEventListener('loaderror', onLoadError);
	ImoSPC.addEventListener('playstatechange', onPlayStateChange);
	ImoSPC.init({
		autostart: true,
		preferredRuntime: ImoSPC.Runtime.HTML5
	});
}

function setupSeekBar() {
	seekbar = $('.imospc .seek')
		.progressbar({
			max: 1,
			value: 0
		})
		.click(function(e) {
			var track = ImoSPC.currentTrack();
			if (track) {
				var to = ((e.pageX - $(this).offset().left) / ($(this).width() - 1)) * track.length;
				track.seek(to);
				setCurrentTimeDisplay(to);
			}
		});
	seekbar.find('.ui-progressbar-value').css('background', '#ccc');
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
		}, 100);
}


function timerOff(isLoading) {
		if (_timer) {
				clearInterval(_timer);
				_timer = null;
		}
		seekbar.progressbar('option', 'value', isLoading ? false : 0);
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
	});
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
	console.debug('ImoSPC init ok', evt);
	ImoSPC.setVolume(tryFetch('volume', 90) / 100);
	playerInitialized();
}

function onInitError(evt) {
	console.error('ImoSPC init error', evt);
}

function onLoadOk(evt) {
	console.debug('ImoSPC load ok', evt);
}

function onLoadError(evt) {
	console.error('ImoSPC load error', evt);
}

var _playing = false;
function onPlayStateChange(e) {
	console.debug('ImoSPC play state change', e);
	var PS = ImoSPC.PlaybackState;
	switch (e.state) {
		case PS.LOADING:
			seekbar.progressbar('option', 'max', e.track.length);
			setCurrentTimeDisplay(0);
			setTrackLengthDisplay(e.track.length);
		case PS.PLAYING:
			_playing = true;
			timerOn();
			seekEnd();
			break;

		case PS.BUFFERING:
			timerOff(true);
			seekStart();
			break;

		case PS.PAUSED:
			_playing = false;
			timerOn();
			break;

		case PS.STOPPED:
			if(_playing) {
				songEnded();
			} else {
				_playing = false;
				timerOff();
				setCurrentTimeDisplay(null);
				setTrackLengthDisplay(null);
			}
	}
}
