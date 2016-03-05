function addToPlaylistDialog(idTrack) {
	$('#dialog-addToPlaylist #playlistcombo').load(baseUrl + 'index.php/playlist/playlists/0',
		function() {
			$('#dialog-addToPlaylist').dialog({
				modal: true,
				width: 400,
				resizable: false,
				show: { effect: 'puff', duration: 200 },
				hide: { effect: 'puff', duration: 200 },
				buttons: {
					Ok: function() {
						if($('#dialog-addToPlaylist input[type=radio]:checked').val() == 'existing')
							addToPlaylist(idTrack, $('#dialog-addToPlaylist #playlistcombo').val());
						else
							addToNewPlaylist(idTrack, $('#dialog-addToPlaylist #newplaylistname').val());
					},
					Cancel: function() {
						$(this).dialog('close');
					}
				}
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

function addToPlaylist(idTrack, idPlaylist) {
	$.post(baseUrl + 'index.php/playlist/addPlaylistItem',
		{
			idPlaylist: idPlaylist,
			idTrack: idTrack
		},
		function(data) {
			if(validateSession(data)) {
				var json = $.parseJSON(data);
				if(json.success) {
					$('#dialog-addToPlaylist').dialog('close');
					if(idPlaylist == $('#player-playlistcombo').val())
						loadPlaylist(idPlaylist);
				} else {
					showMessageDialog('D\'oh!', json.message);
				}
			}
		});
}

function addToNewPlaylist(idTrack, playlistName) {
	$.post(baseUrl + 'index.php/playlist/createSimple', { playlistName: playlistName }, function(data) {
		if(validateSession(data)) {
			var json = $.parseJSON(data);
			if(json.success) {
				addToPlaylist(idTrack, json.success); //success is the new idTrack
				$('#dialog-addToPlaylist #newplaylistname').val('')
				if(typeof refreshPlaylistsList !== 'undefined')
					refreshPlaylistsList();
			} else {
				showMessageDialog('D\'oh!', json.message);
			}
		}
	});
}
