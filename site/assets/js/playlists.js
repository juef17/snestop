function addToPlaylistDialog(idTrack) {
	$('#dialog-addToPlaylist #playlistcombo').load(baseUrl + 'index.php/playlist/playlists/0',
		function() {
			if($('#dialog-addToPlaylist #playlistcombo option').length > 0) {
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
				//abonner au change du textbox. si pas vide, disable le combo. si vide, enable combo.
				$('#dialog-addToPlaylist input[type=radio]').change(function() {
					$('#playlistcombo').attr('disabled', $(this).val() != 'existing');
					$('#newplaylistname').attr('disabled', $(this).val() != 'new');
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

function addToNewPlaylist(idTrack, playlistName) {
	$.post(baseUrl + 'index.php/playlist/createSimple', { playlistName: playlistName }, function(data) {
		var json = $.parseJSON(data);
		if(json.success) {
			addToPlaylist(idTrack, json.success); //success is the new idTrack
			$('#dialog-addToPlaylist #newplaylistname').val('')
			if(typeof refreshPlaylistsList !== 'undefined')
				refreshPlaylistsList();
		} else {
			showMessageDialog('D\'oh!', json.message);
		}
	});
}
