<input id="playlist-playlist-public" type="checkbox" <?=$playlist->public ? 'checked' : ''?>>Public
<ul id="playlist-tracks" class="playlist">
	<?php foreach($playlistItems as $item):
		$text = $item->gameTitleEng . ' - ' . $item->title; ?>
		<li class="ui-state-default" title="<?=$text?>" id="<?=$item->idTrack?>">
		<span class="fa fa-unsorted fa-lg"></span>
		<span><?=$text?></span>
		<img src="<?=asset_url()?>images/delete.png" onclick="deleteItem(<?=$item->idTrack?>);"/>
		</li>
  <?php endforeach; ?>
</ul>
<button class="btn btn-xs btn-danger" id="playlist-delete">Delete playlist</button>

<!-- add to playlist dialog -->
<div id="dialog-addToPlaylist" style="display: none;" title="Select a playlist">
	<select id="playlistcombo" style="display: inline-block;"><!--ajax loaded content--></select>
</div>

<script>
	function bindPlaylistDetailsFunctions() {
		$('#playlist-playlist-public').change(function() {
			$.post('<?=base_url()?>index.php/playlist/setPublic',
			{
				idPlaylist: <?=$playlist->idPlaylist?>,
				public: $(this).is(':checked')
			},
			function(data) {
				var json = $.parseJSON(data);
				if(!json.success)
					alert(json.message);
			});
		});

		$('#playlist-delete').click(function() { confirmDeletePlaylist(); });

		$('#playlist-tracks').sortable({
			handle: '.fa-unsorted',
			update: function(event, ui) {
				savePositions();
			}
		}).selectable({
			selecting: function (e, ui) {
				// force single selection
				$(e.target).find('.ui-selectee.ui-selecting').not(ui.selecting).removeClass('ui-selecting');
				$(e.target).find('.ui-selectee.ui-selected').not(ui.selecting).removeClass('ui-selected');
			},
			selected: function( event, ui ) {
				playTrack(ui.selected.id);
			}
		});

		//delete button
		$('.playlist li').hover(
			function() {
				$(this).find('img').show(50);
			},
			function() {
				$(this).find('img').hide(50);
			}
		);
	}

	function savePositions() {
		var idTracks = $('#playlist-tracks').sortable('toArray');
		var idPlaylist = <?=$playlist->idPlaylist?>;
		$.post('<?=base_url()?>index.php/playlist/savePositions',
			{
				idPlaylist: idPlaylist,
				idTracks: idTracks
			},
			function(data) {
				var json = $.parseJSON(data);
				if(!json.success)
					showMessageDialog('', json.message);
			});
	}

	function confirmDeletePlaylist() {
		$('#playlist-deleteConfirmation').dialog({
			title: 'Delete playlist',
			modal: true,
			resizable: false,
			buttons: {
				Ok: function() { deletePlaylist(); },
				Cancel: function() { $(this).dialog('close'); }
			}
		});
	}

	function deletePlaylist() {
		$.post('<?=base_url()?>index.php/playlist/delete',
			{
				idPlaylist: <?=$playlist->idPlaylist?>
			},
			function(data) {
				var json = $.parseJSON(data);
				if(!json.success) {
					showMessageDialog('', json.message);
				} else {
					togglePlaylistVisibility();
					refreshPlaylistsList(function() {
						$('#playlist-deleteConfirmation').dialog('close');
					});
				}
			});
	}

	function deleteItem(idTrack) {
		$.post('<?=base_url()?>index.php/playlist/deleteItem',
			{
				idPlaylist: <?=$playlist->idPlaylist?>,
				idTrack: idTrack
			},
			function(data) {
				var json = $.parseJSON(data);
				if(json.success)
					$('.playlist #' + idTrack).hide(200, function() {
						$('.playlist #' + idTrack).remove();
					});
				else
					showMessageDialog('', json.message);
			});
	}

		function addToPlaylistDialog(idTrack) {
		$('#dialog-addToPlaylist #playlistcombo').load('<?=base_url()?>index.php/playlist/playlists/0',
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
		$.post('<?=base_url()?>index.php/playlist/addPlaylistItem',
			{
				idPlaylist: idPlaylist,
				idTrack: idTrack
			},
			function(data) {
				var json = $.parseJSON(data);
				if(json.success) {
					$('#dialog-addToPlaylist').dialog('close');
					if(idPlaylist == <?=$playlist->idPlaylist?>)
						addTrackToDisplayedPlaylist(idTrack);
				} else {
					showMessageDialog('D\'oh!', json.message);
				}
			});
	}

	function addTrackToDisplayedPlaylist(idTrack) {
		//fade in a placeholder with a spinner
		//load its content with ajax
	}
</script>
