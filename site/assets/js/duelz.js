var playerIsInitialized = false;

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
	waitModalVisible({visible: true, fade: false});
	configPlayButtons();
	configVoteButtons();
	configShitCheckboxes();
	contructVoteDialog();
	constructNoMoreDialog();
	startNewDuel();
});

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
	});
	
	$('#btnPlayTrackB').click(function() {
		tracks.current = 'b';
		playTrack();
	});
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
		var json = $.parseJSON(data);
		if(json.success) {
			updatePreviousTracks();
			startNewDuel();
		} else {
			alert(json.message);
		}
	});
}

function startNewDuel() {
	fetchNumberOfDuelsTaken();
	resetTracksInformations();
	$.getJSON(baseUrl + 'index.php/duelz/getNewDuel', function(idTracks) {
		if(idTracks.length == 2) {
			tracks.a.idTrack = idTracks[0];
			tracks.b.idTrack = idTracks[1];
			$('.voting-tools').fadeTo(500, 0);
			$('#shit-a-group, #shit-b-group').fadeTo(500, 0);
			$('#enough-a, #enough-b').fadeTo(500, 0);
		} else {
			tracks.a.idTrack = -1;
			tracks.b.idTrack = -1;
			$('#dialog-nomore').dialog('open');
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
	//unload track from player
	$('#shitA, #shitB').prop('checked', false);
	$('#player-message').fadeTo(500, 0);
}

function targetTimeReached() {
	tracks[tracks.current].listened = true;
	$('#shit-' + tracks.current + '-group').fadeTo(500, 1);
	$('#enough-' + tracks.current).fadeTo(500, 1);

	if(tracks.a.listened && tracks.b.listened)
		$('.voting-tools').fadeTo(500, 1);
}

function updatePreviousTracks() {
	$.each(['a', 'b'], function(i, track) { previousTracks[track].idTrack = tracks[track].idTrack; });
		
	if(previousTracks.a.idTrack) {
		fetchTrackTitle('a');
		fetchTrackTitle('b');
		$('.previous-tracks').fadeTo(500, 1);
	}
}

function fetchTrackTitle(track) {
	$.getJSON(baseUrl + 'index.php/game/getTrack/' + previousTracks[track].idTrack, function(result){
		if(result.success)
			$('#lastTrack-' + track + '-title').text(result.success.gameTitleEng + ' - ' + result.success.title);
		else
			alert(result.message);
	});
}

function fetchNumberOfDuelsTaken() {
	$.getJSON(baseUrl + 'index.php/duelz/getNbDuelzTaken', function(result){
		$('#nbDuelzTaken').text(result);
	});
}

function hideWaitModal() {
	if(playerIsInitialized && tracks.a.idTrack) {
		waitModalVisible({visible: false});
	}
}

function playerInitialized() {
	playerIsInitialized = true;
	hideWaitModal();
}

function playTrack() {
	var idTrack = tracks[tracks.current].idTrack;
	$.getJSON(
		baseUrl + 'index.php/game/getTrack/' + idTrack,
		function(data) {
			if(data['success']) {
				$('#current-track').text(tracks.current.toUpperCase());
				$('#player-message').fadeTo(500, 1);
				var track = data['success'];
				var url = assetUrl + 'spc/' + track.spcEncodedURL + '?' + track.length + '?' + track.fadeLength;
				$('#spcplayer')[0].playUrl(url);
			} else {
				showMessageDialog(data['message']);
			}
		}
	);
}
