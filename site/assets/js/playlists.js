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
						click: function() {
							$('#addToPlaylist-btnOk span').text('Please wait...');
							$(this).dialog('widget').find('.ui-button').prop('disabled', true);
							if($('#dialog-addToPlaylist input[type=radio]:checked').val() == 'existing')
								addToPlaylist(idTracks, $('#dialog-addToPlaylist #playlistcombo').val());
							else
								addToNewPlaylist(idTracks, $('#dialog-addToPlaylist #newplaylistname').val());
						}
					},
					{
						text: 'Cancel',
						click: function() {
							$(this).dialog('close');
						}
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

function addToPlaylist(idTracks, idPlaylist) {
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
						loadPlaylist(idPlaylist);
				} else {
					showMessageDialog('D\'oh!', json.message);
				}
			}
		});
}

function addToNewPlaylist(idTracks, playlistName) {
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
		}
	});
}
