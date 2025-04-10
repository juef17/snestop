var PlayerLoopModes = {
	None: 0,
	Single: 1,
	All: 2
}

var playerDialog;
var playingIdTrack = null;
var loadingTrack = false;
var playerLoopMode = PlayerLoopModes.None; //user value set in php

$(function() {
	constructPlayerDialog();
	initPlayer();
});

function bindPlayerModesFunctions() {
	var ajaxPost = function(e) {
		$.post(baseUrl + 'edit_user_profile/setPlayerModes',
		{
			loop: playerLoopMode,
			randomize: $('#player-randomize').is(':checked')
		},
		function(data) {
			if(validateSession(data)) {
				var json = $.parseJSON(data);
				if(!json.success)
					alert(json.message);
			}
		});
	};

	$('#player-randomize').change(ajaxPost);
	$('#player-loop').change(function() {
		if (++playerLoopMode > PlayerLoopModes.All)
			playerLoopMode = PlayerLoopModes.None;
		setLoopButtonVisual();
		applyPlayerLoopMode();
		ajaxPost();
	});
}

function applyPlayerLoopMode() {
	ImoSPC.setRepeat(playerLoopMode == PlayerLoopModes.Single);
}

function setLoopButtonVisual() {
	$('#player-loop').prop('checked', playerLoopMode > 0);
	$('#player-loop').button('refresh')
	if(playerLoopMode == PlayerLoopModes.Single)
		$('#loopSingleBadge').html('1').show();
	else if(playerLoopMode == PlayerLoopModes.All)
		$('#loopSingleBadge').html('A').show();
	else
		$('#loopSingleBadge').hide();
}

function constructPlayerDialog() {
	playerDialog = $('#player-dialog').dialog({
		modal: false,
		resizable: false,
		autoOpen: false,
		minHeight: 'auto',
		height: 'auto',
		maxHeight: 'auto',
		show: { effect: 'clip', duration: 200 },
		hide: { effect: 'fold', duration: 200 },
		dialogClass: 'player',
		close: function(event, ui) { ImoSPC.pause(); }
	});
	
	constructScreenshotDialog();
	
	$('#player-dialog .pause')
	.button()
	.click(pauseButton);
	
	$('#player-dialog .play')
	.button()
	.click(playButton);
	
	if(isUserLogged) {
		$('#player-playlistcombo').menu();
		$('#player-playlistcombo').change(function(){
			playlistSelectionChanged($(this).val());
		});
		
		$('#player-expandPlaylist').click(function() {
			togglePlaylistVisibility();
		});
		$('#player-loop').button();
		$('#player-randomize').button();
		bindPlayerModesFunctions();
		
		refreshPlaylistsList();
		setLoopButtonVisual();
	}
}

function constructScreenshotDialog() {
	var closeFunction = function () {
		$('#track-screenshot-dialog').dialog('close');
	}
	$('#track-screenshot-dialog').dialog({
		modal: true,
		resizable: false,
		autoOpen: false,
		minHeight: 'auto',
		height: 'auto',
		maxHeight: 'auto',
		show: { effect: 'fade', duration: 200 },
		hide: { effect: 'fade', duration: 200 },
		dialogClass: 'transparent-dialog',
		open: function(event, ui) {
			$('.ui-widget-overlay').bind('click', closeFunction);
		}
	})
	.click(closeFunction)
	.siblings('.ui-dialog-titlebar').remove();
}

function playButton() {
	if(currentTrack)
		currentTrack.play();
}

function pauseButton() {
	if(playerState == 'playing')
		ImoSPC.pause();
	else if (playerState == 'paused')
		ImoSPC.unpause();
}

function setScreenshot(track) {
	$('#playerScreenshot').off('click');
	
	if(track.isScreenshotSet) {
		$('#playerScreenshot').css({
			'background-image': 'url(' + assetUrl + 'images/screenshots/track/' + track.idTrack + '.png)',
			'cursor': 'pointer'
		})
		.click(function() {
			$('#track-screenshot-dialog .tv').css({'background-image': 'url(' + assetUrl + 'images/screenshots/track/' + track.idTrack + '.png)'});
			$('#track-screenshot-dialog').dialog('open');
		});
	} else {
		$('#playerScreenshot').css({
			'background-image': 'url(' + assetUrl + 'images/en/no_track_ss.png)',
			'cursor': 'auto'
		});
		if(isUserLogged) {
			$('#playerScreenshot').css({'cursor': 'pointer'})
			.click(function () {
				window.location.href = baseUrl + 'request_screenshot_track/index/' + track.idTrack
			});
		}
	}
}

//main entry function
function playTrack(idTrack) {
	if(!playerDialog.is(':visible'))
		playerDialog.dialog('open');
	
	$.getJSON(
		baseUrl + 'game/getTrack/' + idTrack,
		function(data) {
			if(data['success']) {
				_playing = false;
				var track = data['success'];
				var url = assetUrl + 'spc/' + track.spcURL;
				setScreenshot(track);
				setTitle(track);
				loadingTrack = true;
				ImoSPC.open(url);
				playingIdTrack = idTrack;
			} else {
				showMessageDialog(data['message']);
			}
		}
	);
}

function setTitle(track) {
	var title = track.gameTitleEng + ' - ' + track.title;
	$('#title-current')
		.prop('title', title);
	$('#title-current a')
		.text(title)
		.prop('href', baseUrl + 'game/index/' + track.idGame);
}

//imospc events
function playerInitialized() {
	applyPlayerLoopMode();
}

function songEnded() {
	var sortedIdTracks = $('#playlist-tracks').sortable('toArray');
	if(!loadingTrack && sortedIdTracks.length > 0) {
		var trackPosition = sortedIdTracks.indexOf(playingIdTrack);
		if(playerLoopMode == PlayerLoopModes.All && sortedIdTracks.length == 1) {	// la 2e condition est pour qu'on n'essaie pas
			playButton();																	// de trouver une autre track si on en a juste 1
		} else if($('#player-randomize').is(':checked')) {
			var nextIdTrack = 0;
			do { //joue pas la meme!
				nextIdTrack = sortedIdTracks[Math.floor(Math.random()*sortedIdTracks.length)];
			} while(nextIdTrack == playingIdTrack)
			
			var nextTrackPosition = sortedIdTracks.indexOf(nextIdTrack) + 1;
			selectSelectableElement($('#playlist-tracks'), $('#playlist-tracks li:nth-child(' + nextTrackPosition + ')'));
		} else if(trackPosition < sortedIdTracks.length - 1) {
			selectSelectableElement($('#playlist-tracks'), $('#playlist-tracks li:nth-child(' + (trackPosition + 2) + ')'));
		} else if(trackPosition == sortedIdTracks.length - 1 && playerLoopMode == PlayerLoopModes.All) {
			selectSelectableElement($('#playlist-tracks'), $('#playlist-tracks li:nth-child(1)'));
		} else {
			_playing = false;
		}
	}
}

function seekStart() {
	$('#player-dialog #wait').show('slide', { direction: 'down'});
}

function seekEnd() {
	$('#player-dialog #wait').hide('slide', { direction: 'down'});
	loadingTrack = false;
}

//Playlist management

function togglePlaylistVisibility(callback) {
	if($('#player-playlist').is(':visible')) {
		hidePlaylist(callback);
	} else {
		showPlaylist(callback);
	}
}

function hidePlaylist(callback) {
	$('#player-playlist').hide(200, callback);
	$('#player-expandPlaylist span').removeClass('fa-angle-double-up');
	$('#player-expandPlaylist span').addClass('fa-angle-double-down');
}

function showPlaylist(callback) {
	$('#player-playlist').show(200, callback);
	$('#player-expandPlaylist span').removeClass('fa-angle-double-down');
	$('#player-expandPlaylist span').addClass('fa-angle-double-up');
}

function createPlayList(source) {
	$('#createPlaylist-dialog').dialog({
		title: 'New playlist',
		modal: true,
		resizable: false,
		buttons: {
			Ok: function() {
				$('#createPlaylist-form').submit();
			}
		}
	});
	clearNewPlaylistForm();
	if(source != null) {
		$('#createPlaylist-form #source').val(source);
	}
	attachAjaxSubmitCallback();
}

function attachAjaxSubmitCallback() {
	$('#createPlaylist-form').submit(function() {
		$.post(baseUrl + 'playlist/create',
			$(this).serialize(),
			function(data, textStatus) {
				if(validateSession(data)) {
					$('#createPlaylist-dialog').html(data);
					if($('#createPlaylist-dialog #hasErrors').val() == '0') {
						refreshPlaylistsList(function() {
							selectPlaylist($('#createPlaylist-dialog #idPlaylist').val());
							$('#createPlaylist-dialog').dialog('close');
							$('#createPlaylist-form #name').val('');
						});
					} else {
						attachAjaxSubmitCallback();
					}
				}
			}
		);
		return false;
	});
}

function refreshPlaylistsList(callback) {
	var currentPlaylist = $('#player-playlistcombo').val();
	if(callback)
		selectPlaylist(-1);
	else if (currentPlaylist)
		callback = function() { selectPlaylist(currentPlaylist); };
		
	$('#player-playlistcombo').load(baseUrl + 'playlist/playlists/1', callback);
}

function selectPlaylist(idPlaylist) {
	$('#player-playlistcombo').val(idPlaylist);
	playlistSelectionChanged(idPlaylist); //event doesn't trigger otherwise...
}

function playlistSelectionChanged(idPlaylist) {
	if(idPlaylist == -1) { //select a playlist
		hidePlaylist();
		$('#player-expandPlaylist').prop('disabled', true);
	} else if(idPlaylist == 0) { //create a playlist
		hidePlaylist();
		$('#player-expandPlaylist').prop('disabled', true);
		createPlayList();
		$('#player-playlistcombo').val(-1);
	} else { //playlist
		loadPlaylist(idPlaylist);
	}
}

function loadPlaylist(idPlaylist) {
	//idPlaylist is either idPlaylist or an array of songs.
	playerDialog.dialog('open');
	hidePlaylist(function() {
		$(this).load(baseUrl + 'playlist/playlistDetails/' + encodeURIComponent(idPlaylist), function() {
			bindPlaylistDetailsFunctions();
			$('#player-expandPlaylist').prop('disabled', false);
			showPlaylist(function() {
				selectFirstTrack();
			});
		})
	});
}

function selectFirstTrack() {
	//http://stackoverflow.com/a/9421157/2498426
	var elementsToSelect = $('li:first', '#playlist-tracks');
	$('.ui-selected', '#playlist-tracks')
		.not(elementsToSelect)
		.removeClass('ui-selected')
		.addClass('ui-unselecting');

	$(elementsToSelect)
		.not('.ui-selected')
		.addClass('ui-selecting');

	$('#playlist-tracks')
		.data('ui-selectable')
		._mouseStop(null);
}
