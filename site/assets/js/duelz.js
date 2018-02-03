var playerIsInitialized = false;
var disclaimerAccepted = false;

var tracks = {
	current: 'a',
	a: {
		idTrack: undefined,
		listened: false,
		winner: false,
		shit: false
	},
	b: {
		idTrack: undefined,
		listened: false,
		winner: false,
		shit: false
	}
};

var previousTracks = {
	a: {
		idTrack: undefined
	},
	b: {
		idTrack: undefined
	}
};

$(function() {
	duelzMode = true;
	waitModalVisible({visible: true, fade: false});
	configPlayButtons();
	configVoteButtons();
	configShitCheckboxes();
	constructDialogs();
	initPlayer({seek_enabled: false});
	startNewDuel();
});

function constructDialogs() {
	contructVoteDialog();
	constructNoMoreDialog();
	constructDisclaimerDialog();
}

function configShitCheckboxes() {
	$('#shitA').change(function() { tracks.a.shit = this.checked; });
	$('#shitB').change(function() { tracks.b.shit = this.checked; });
}

function configVoteButtons() {
	$('#btnWinTrackA').click(function() {
		tracks.a.winner = true;
		tracks.b.winner = false;
		confirmVote();
	});
	
	$('#btnWinTrackB').click(function() {
		tracks.a.winner = false;
		tracks.b.winner = true;
		confirmVote();
	});
}

function configPlayButtons() {
	$('#btnPlayTrackA').click(function() {
		tracks.current = 'a';
		playTrack();
		$('#btnPlayTrackA').css("border", '2px solid #FF0000');
		$('#btnPlayTrackB').css("border", '');
	});
	
	$('#btnPlayTrackB').click(function() {
		tracks.current = 'b';
		playTrack();
		$('#btnPlayTrackA').css("border", '');
		$('#btnPlayTrackB').css("border", '2px solid #FF0000');
	});
	
	$('.duelPlayer .pause')
	.button()
	.click(duelzPauseButton);
}

function duelzPauseButton() {
	if(playerState == 'playing')
		ImoSPC.pause();
	else if (playerState == 'paused')
		ImoSPC.unpause();
}

function contructVoteDialog() {
	$('#dialog-vote').dialog({
		resizable: false,
		modal: true,
		autoOpen: false,
		show: { effect: 'puff', duration: 200 },
		hide: { effect: 'puff', duration: 200 },
		buttons: {
			'Cast my vote!': function() {
				waitModalVisible({visible: true});
				castVote();
				$(this).dialog('close');
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		}
	});
}

function constructNoMoreDialog() {
	$('#dialog-nomore').dialog({
		resizable: false,
		modal: true,
		closeOnEscape: false,
		autoOpen: false,
		dialogClass: 'no-close',
		buttons : {
			'Take me home': function() {
				window.location.href = baseUrl;
			}
		}
	});
}

function constructDisclaimerDialog() {
	$('#dialog-disclaimer').dialog({
		resizable: false,
		modal: true,
		closeOnEscape: false,
		autoOpen: false,
		width: 600,
		dialogClass: 'no-close',
		buttons : {
			'I don\'t care': function() {
				window.location.href = baseUrl;
			},
			'I understand': function() {
				disclaimerAccepted = true;
				$(this).dialog('close');
			}
		}
	});
}

function confirmVote() {
	if(tracks.a.shit)
		$('#dialog-shitA').show();
	else
		$('#dialog-shitA').hide();

	if(tracks.b.shit)
		$('#dialog-shitB').show();
	else
		$('#dialog-shitB').hide();

	if(tracks.a.winner)
		$('#dialog-winnerA').show();
	else
		$('#dialog-winnerA').hide();

	if(tracks.b.winner)
		$('#dialog-winnerB').show();
	else
		$('#dialog-winnerB').hide();

	$('#dialog-vote').dialog('open');
}

function castVote() {
	$.post(baseUrl + 'index.php/duelz/castVote', { tracks: tracks }, function(data) {
		if(validateSession(data)) {
			var json = $.parseJSON(data);
			if(json.success) {
				updatePreviousTracks();
				startNewDuel();
			} else {
				alert(json.message);
			}
		} else {
			hideWaitModal();
		}
	});
}

function startNewDuel() {
	fetchNumberOfDuelsTaken();
	resetTracksInformations();
	$.getJSON(baseUrl + 'index.php/duelz/getNewDuel', function(idTracks, status, jqXHR) {
		if(validateSession(jqXHR.responseText)) {
			if(idTracks.length == 2) {
				tracks.a.idTrack = idTracks[0];
				tracks.b.idTrack = idTracks[1];
				$('.voting-tools').hide(500);
				$('#shit-a-group, #shit-b-group').fadeTo(500, 0);
				$('#enough-a, #enough-b').fadeTo(500, 0);
			} else {
				tracks.a.idTrack = -1;
				tracks.b.idTrack = -1;
				$('#dialog-nomore').dialog('open');
			}
		}
		hideWaitModal();
	});
}

function resetTracksInformations() {
	$.each(['a', 'b'], function(i, track) {
		tracks[track].idTrack = undefined;
		tracks[track].listened = false;
		tracks[track].winner = false;
		tracks[track].shit = false;
	});
	
	if(playerIsInitialized)
		ImoSPC.stop();
	
	$('#shitA, #shitB').prop('checked', false);
	$('#player-message').hide(500);

	$('#btnPlayTrackA').css("border", '');
	$('#btnPlayTrackB').css("border", '');
}

//Player --> JS
function timeReached() {
	tracks[tracks.current].listened = true;
	$('#shit-' + tracks.current + '-group').fadeTo(500, 1);
	$('#enough-' + tracks.current).fadeTo(500, 1);

	if(tracks.a.listened && tracks.b.listened)
		$('.voting-tools').show(500);
}

function updatePreviousTracks() {
	$.each(['a', 'b'], function(i, track) { previousTracks[track].idTrack = tracks[track].idTrack; });
		
	if(previousTracks.a.idTrack) {
		fetchTrackTitle('a');
		fetchTrackTitle('b');
		$('.previous-tracks').show(500);
	}
}

function fetchTrackTitle(track) {
	$.getJSON(baseUrl + 'index.php/duelz/getTrack/' + previousTracks[track].idTrack, function(result, status, jqXHR){
		if(validateSession(jqXHR.responseText)) {
			if(result.success) {
				var trackUrl = baseUrl + 'index.php/game/index/' + result.success.idGame + '/' + result.success.idTrack;
				var tagContent = result.success.gameTitleEng + ' - ' + result.success.title;
				var trackTag = '<a target="_blank" href="' + trackUrl + '">' + 
						tagContent + 
					'</a>'
				$('#lastTrack-' + track + '-title').html(trackTag);
				$('#btn-addtoplaylist-' + track).attr('onclick', 'addToPlaylistDialog([' + result.success.idTrack + ']);')
			} else {
				alert(result.message);
			}
		}
	});
}

function fetchNumberOfDuelsTaken() {
	$.getJSON(baseUrl + 'index.php/duelz/getNbDuelzTaken', function(result, status, jqXHR){
		if(validateSession(jqXHR.responseText))
			$('#nbDuelzTaken').text(result);
	});
}

function hideWaitModal() {
	if(playerIsInitialized && tracks.a.idTrack) {
		waitModalVisible({visible: false});
		if(!disclaimerAccepted)
			$('#dialog-disclaimer').dialog('open');
	}
}

function playerInitialized() {
	playerIsInitialized = true;
	hideWaitModal();
}

function playTrack() {
	$.getJSON(baseUrl + 'index.php/duelz/ping', function(result, status, jqXHR) {
		if(validateSession(jqXHR.responseText)) {
			var idTrack = tracks[tracks.current].idTrack;
			ImoSPC.open('duelz/getSpc/' + idTrack);
			$('#current-track').text(tracks.current.toUpperCase());
			$('#player-message').show(500);
		}
	});
}
