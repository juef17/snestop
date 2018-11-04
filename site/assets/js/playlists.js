function addToPlaylistDialog(idTracks) {
	$('#dialog-addToPlaylist #playlistcombo').load(baseUrl + 'index.php/playlist/playlists/0',
		function() {
			$('#dialog-addToPlaylist').dialog({
				modal: true,
				width: 400,
				resizable: false,
				show: { effect: 'puff', duration: 200 },
				hide: { effect: 'puff', duration: 200 },
				buttons: [
					{
						id: 'addToPlaylist-btnOk',
						text: 'Ok',
						click: function() { addTractsToPlaylistDialogOkButton(idTracks); }
					},
					{
						text: 'Cancel',
						click: function() { $(this).dialog('close'); }
					}
				]
			});

			var radioGroupCallback = function(button) {
				$('#playlistcombo').attr('disabled', button.val() != 'existing');
				$('#newplaylistname').attr('disabled', button.val() != 'new');
			};

			checkNeededRadioButton(radioGroupCallback);

			$('#dialog-addToPlaylist input[type=radio]').change(function() { radioGroupCallback($(this)); });
		}
	);
}

function addTractsToPlaylistDialogOkButton(idTracks) {
	var playlistName = $('#dialog-addToPlaylist #newplaylistname').val();
	var addToExisting = $('#dialog-addToPlaylist input[type=radio]:checked').val() == 'existing';
	if(addToExisting || playlistName != '') {
		setAddTracksToPlaylistDialogWaitMode(true);
		if(addToExisting)
			addToPlaylist(idTracks, $('#dialog-addToPlaylist #playlistcombo').val(), function() { setAddTracksToPlaylistDialogWaitMode(false);});
		else if(playlistName != '')
			addToNewPlaylist(idTracks, playlistName, function() { setAddTracksToPlaylistDialogWaitMode(false);});
	}
}

function setAddTracksToPlaylistDialogWaitMode(active) {
	var okText = active
		? 'Please wait...'
		: 'Ok';
	$('#addToPlaylist-btnOk span').text(okText);
	$('#dialog-addToPlaylist').dialog('widget').find('.ui-button').prop('disabled', active);
}

function checkNeededRadioButton(radioGroupCallback) {
	var playlistsExist = $('#dialog-addToPlaylist #playlistcombo option').length > 0;
	var newButton = $('#dialog-addToPlaylist #optradio-new');
	var existingButton = $('#dialog-addToPlaylist #optradio-existing');

	newButton.prop('checked', !playlistsExist);
	existingButton.prop('checked', playlistsExist);
	
	radioGroupCallback(playlistsExist ? existingButton : newButton);
	
	existingButton.attr('disabled', !playlistsExist);

	if(!playlistsExist)
		$('#newplaylistname').focus();
}

function addToPlaylist(idTracks, idPlaylist, callback) {
	$.post(baseUrl + 'index.php/playlist/addPlaylistItems',
		{
			idPlaylist: idPlaylist,
			idTracks: idTracks
		},
		function(data) {
			if(validateSession(data)) {
				var json = $.parseJSON(data);
				$('#dialog-addToPlaylist').dialog('close');
				if(json.success) {
					if(idPlaylist == $('#player-playlistcombo').val())
						addTracksToCurrentPlaylist(idTracks);
				} else {
					showMessageDialog('D\'oh!', json.message);
				}
				if(callback != undefined)
					callback(json.success);
			}
		});
}

function addToNewPlaylist(idTracks, playlistName, callback) {
	$.post(baseUrl + 'index.php/playlist/createSimple', { playlistName: playlistName }, function(data) {
		if(validateSession(data)) {
			var json = $.parseJSON(data);
			if(json.success) {
				addToPlaylist(idTracks, json.success); //success is the new idTrack
				$('#dialog-addToPlaylist #newplaylistname').val('')
				if(typeof refreshPlaylistsList !== 'undefined')
					refreshPlaylistsList();
			} else {
				showMessageDialog('D\'oh!', json.message);
			}
			if(callback != undefined)
				callback(json.success);
		}
	});
}

function addTracksToCurrentPlaylist(idTracks) {
	$.get(baseUrl + 'index.php/playlist/playlistItemsForIdTracks/' + encodeURIComponent(idTracks), function(data) {
		$('#playlist-tracks').append(data);
		$('#playlist-tracks').sortable('refresh');
		bindPlaylistDeleteButtonsHover();
	});
}

function bindPlaylistDeleteButtonsHover() {
	$('.playlist li').hover(
		function() {
			$(this).find('img').fadeTo(50, 1); //hack sur l'opacity pcq hide et show ont arrete de marcher.
		},
		function() {
			$(this).find('img').fadeTo(50, 0);
		}
	);
}
