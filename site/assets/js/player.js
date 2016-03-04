var PlayerLoopModes = {
	None: 0,
	Single: 1,
	All: 2
}

var playerDialog;
var playingIdTrack = null;
var playerLoopMode = PlayerLoopModes.None; //user value set in php

$(function() {
	constructPlayerDialog();
});

function bindPlayerModesFunctions() {
	var ajaxPost = function(e) {
		$.post(baseUrl + 'index.php/edit_user_profile/setPlayerModes',
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
	$('#spcplayer')[0].enableLoop(playerLoopMode == PlayerLoopModes.Single);
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
		show: { effect: 'clip', duration: 200 },
		hide: { effect: 'fold', duration: 200 },
		dialogClass: 'player',
		close: function(event, ui) { $('#player-dialog #deferred-idTrack').val(''); }
	});
	
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

function setScreenshot(track) {
	$('#playerScreenshot').off('click');
	
	if(track.isScreenshotSet) {
		$('#playerScreenshot').css({
			'background-image': 'url(' + assetUrl + 'images/screenshots/track/' + track.idTrack + '.png)',
			'cursor': 'auto'
		});
	} else {
		$('#playerScreenshot').css({
			'background-image': 'url(' + assetUrl + 'images/en/no_track_ss.png)',
			'cursor': 'auto'
		});
		if(isUserLogged) {
			$('#playerScreenshot').css({'cursor': 'pointer'})
			.click(function () {
				window.location.href = baseUrl + 'index.php/request_screenshot_track/index/' + track.idTrack
			});
		}
	}
}

//main entry function
function playTrack(idTrack) {
	if(playerDialog.is(':visible')) {
		$.getJSON(
			baseUrl + 'index.php/game/getTrack/' + idTrack,
			function(data) {
				if(data['success']) {
					var track = data['success'];
					var url = assetUrl + 'spc/' + track.spcURL + '?' + track.length + '?' + track.fadeLength;
					setScreenshot(track);
					setTitle(track);
					$('#spcplayer')[0].playUrl(url);
					playingIdTrack = idTrack;
				} else {
					showMessageDialog(data['message']);
				}
			}
		);
	} else {
		$('#player-dialog #deferred-idTrack').val(idTrack);
		playerDialog.dialog('open');
	}
}

function setTitle(track) {
	var title = track.gameTitleEng + ' - ' + track.title;
	$('#title-current')
		.prop('title', title);
	$('#title-current a')
		.text(title)
		.prop('href', baseUrl + 'index.php/game/index/' + track.idGame);
}

//Flash events
function playerInitialized() {
	if(playerDialog.is(':visible')) { //seems to also trigger when the dialog closes.
		applyPlayerLoopMode();
		var idTrack = $('#player-dialog #deferred-idTrack').val();
		playTrack(idTrack);
	}
}

function songEnded() {
	var sortedIdTracks = $('#playlist-tracks').sortable('toArray');
	if(sortedIdTracks.length == 0) {
		$('#spcplayer')[0].rewind();
	} else {
		var trackPosition = sortedIdTracks.indexOf(playingIdTrack);
		if(playerLoopMode == PlayerLoopModes.All && sortedIdTracks.length == 1) {	// la 2e condition est pour qu'on n'essaie pas
			$('#spcplayer')[0].rewind();										// de trouver une autre track si on en a juste 1
			$('#spcplayer')[0].play();
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
		}
	}
}

function seekStart() {
	$('#player-dialog #wait').show('slide', { direction: 'up'});
}

function seekEnd() {
	$('#player-dialog #wait').hide('slide', { direction: 'up'});
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
		$.post(baseUrl + 'index.php/playlist/create',
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
		
	$('#player-playlistcombo').load(baseUrl + 'index.php/playlist/playlists/1', callback);
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
		$('#player-playlistcombo option[value=-1]').attr('selected', 'selected'); 
	} else { //playlist
		loadPlaylist(idPlaylist);
	}
}

function loadPlaylist(idPlaylist) {
	playerDialog.dialog('open');
	hidePlaylist(function() {
		$(this).load(baseUrl + 'index.php/playlist/playlistDetails/' + idPlaylist, function() {
			bindPlaylistDetailsFunctions();
			$('#player-expandPlaylist').prop('disabled', false);
			showPlaylist();
		})
	});
}
