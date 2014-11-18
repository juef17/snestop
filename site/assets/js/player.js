var playerDialog;
var playingIdTrack = null;

$(function() {
	if(isUserLogged)
		constructPlayerDialog();
});

function bindPlayerModesFunctions() {
	var ajaxPost = function() {
		$.post(baseUrl + 'index.php/edit_user_profile/setPlayerModes',
		{
			loop: $('#player-loop').is(':checked'),
			randomize: $('#player-randomize').is(':checked')
		},
		function(data) {
			var json = $.parseJSON(data);
			if(!json.success)
				alert(json.message);
		});
	};
	
	$('#player-loop').change(ajaxPost);
	$('#player-randomize').change(ajaxPost);
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
	}
}

function setScreenshot(track) {
	var screenshotUrl = track.isScreenshotSet
		? assetUrl + 'images/screenshots/track/' + track.idTrack + '.png'
		: assetUrl + 'images/en/no_track_ss.png';
	
	$('#playerScreenshot').css('background-image', 'url(' + screenshotUrl + ')');
}

//main entry function
function playTrack(idTrack) {
	if(playerDialog.is(':visible')) {
		$.getJSON(
			baseUrl + 'index.php/playlist/getTrack/' + idTrack,
			function(data) {
				if(data['success']) {
					var track = data['success'];
					var url = assetUrl + 'spc/' + track.spcURL + '?' + track.length + '?' + track.fadeLength;
					setScreenshot(track);
					playerDialog.dialog('option', 'title', track.title);
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

//Flash events
function playerInitialized() {
	if(playerDialog.is(':visible')) { //seems to trigger when the dialog closes.
		var idTrack = $('#player-dialog #deferred-idTrack').val();
		playTrack(idTrack);
	}
}

function songEnded() {
	if($('#player-loop').is(':checked')) {
		playTrack(playingIdTrack); //à remplacer par ci dessous, plus efficace.
		//$('#spcplayer')[0].rewind();
		//$('#spcplayer')[0].play();
	} else {
		var sortedIdTracks = $('#playlist-tracks').sortable('toArray');
		var trackPosition = sortedIdTracks.indexOf(playingIdTrack);
		if($('#player-randomize').is(':checked')) {
			var nextIdTrack = 0;
			do { //joue pas la meme!
				nextIdTrack = sortedIdTracks[Math.floor(Math.random()*sortedIdTracks.length)];
			} while(nextIdTrack == playingIdTrack)
			
			var nextTrackPosition = sortedIdTracks.indexOf(nextIdTrack) + 1;
			selectSelectableElement($('#playlist-tracks'), $('#playlist-tracks li:nth-child(' + nextTrackPosition + ')'));
		} else if(trackPosition < sortedIdTracks.length - 1) {
			selectSelectableElement($('#playlist-tracks'), $('#playlist-tracks li:nth-child(' + (trackPosition + 2) + ')'));
		}
	}
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

function createPlayList() {
	
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
	attachAjaxSubmitCallback();
}

function attachAjaxSubmitCallback() {
	$('#createPlaylist-form').submit(function() {
		$.post(baseUrl + 'index.php/playlist/create',
			$(this).serialize(),
			function(data, textStatus) {
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
		);
		return false;
	});
}

function refreshPlaylistsList(callback) {
	selectPlaylist(-1);
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
	hidePlaylist(function() {
		$(this).load(baseUrl + 'index.php/playlist/playlistDetails/' + idPlaylist, function() {
			bindPlaylistDetailsFunctions();
			$('#player-expandPlaylist').prop('disabled', false);
			showPlaylist();
		})
	});
}

function addToPlaylistDialog(idTrack) {
	$('#dialog-addToPlaylist #playlistcombo').load(baseUrl + 'index.php/playlist/playlists/0',
		function() {
			if($('#dialog-addToPlaylist #playlistcombo option').length > 0) {
				$('#dialog-addToPlaylist').dialog({
					modal: true,
					resizable: false,
					show: { effect: 'puff', duration: 200 },
					hide: { effect: 'puff', duration: 200 },
					buttons: {
						Ok: function() {
							addToPlaylist(idTrack, $('#dialog-addToPlaylist #playlistcombo').val());
						}
					}
				});
			} else {
				showMessageDialog('No playlist', 'No playlist available. Use the player on the main menu to manage playlists!');
			}
		}
	);
}

function addToPlaylist(idTrack, idPlaylist) {
	$.post(baseUrl + 'index.php/playlist/addPlaylistItem',
		{
			idPlaylist: idPlaylist,
			idTrack: idTrack
		},
		function(data) {
			var json = $.parseJSON(data);
			if(json.success) {
				$('#dialog-addToPlaylist').dialog('close');
				if(idPlaylist == $('#player-playlistcombo').val())
					loadPlaylist(idPlaylist);
			} else {
				showMessageDialog('D\'oh!', json.message);
			}
		});
}
