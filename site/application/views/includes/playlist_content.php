<?php $playlistEditable = $loggedUser && $playlist->idUser == $loggedUser->idUser; ?>

<div class="playlist-container">
	<ul id="playlist-tracks" class="playlist">
		<?php foreach($playlistItems as $item):
			$text = $item->gameTitleEng . ' - ' . $item->title; ?>
			<li class="ui-state-default" title="<?=$text?>" id="<?=$item->idTrack?>">
			<?php if($playlistEditable): ?>
				<span class="fa fa-unsorted fa-lg"></span>
			<?php endif; ?>
			<span><?=$text?></span>
			<?php if($playlistEditable): ?>
				<img id="deleteItemButton" src="<?=asset_url()?>images/delete.png" onclick="deleteItem(this, <?=$item->idTrack?>);"/>
			<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<div style="<?= $playlistEditable ? '' : 'display: none;' ?>">
	<button class="btn btn-xs btn-danger" id="playlist-delete" onclick="confirmDeletePlaylist();">Delete playlist</button>
	<label title="Shares this playlist on your profile for other members to play!" class="label-checkbox" style="color: #dddddd;">
		<input id="playlist-playlist-public" type="checkbox" <?=$playlist->public ? 'checked' : ''?> />
		Shared
	</label>
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
				if(!json.success) {
					$('#playlist-playlist-public').prop('checked', !$('#playlist-playlist-public').is(':checked'));
					alert(json.message);
				}
			});
		});

		$('#player-playlistcombo').val(<?=$playlist->idPlaylist?>);

		$('#playlist-tracks').sortable({
			disabled: <?= $playlistEditable ? 'false' : 'true' ?>,
			handle: '.fa-unsorted',
			update: function(event, ui) {
				savePositions();
			}
		}).selectable({
			cancel: '#deleteItemButton',
			selecting: function (e, ui) {
				// force single selection
				if($(ui.selecting).is('li')) {
					$(e.target).find('.ui-selectee.ui-selecting').not(ui.selecting).removeClass('ui-selecting');
					$(e.target).find('.ui-selectee.ui-selected').not(ui.selecting).removeClass('ui-selected');
				}
			},
			selected: function(e, ui) {
				playTrack(ui.selected.id);
			}
		});

		//show delete button
		$('.playlist li').hover(
			function() {
				$(this).find('img').fadeTo(50, 1); //hack sur l'opacity pcq hide et show ont arrete de marcher.
			},
			function() {
				$(this).find('img').fadeTo(50, 0);
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
					$('#playlist-deleteConfirmation').dialog('close');
				} else {
					togglePlaylistVisibility();
					refreshPlaylistsList(function() {
						$('#playlist-deleteConfirmation').dialog('close');
					});
				}
			});
	}

	function deleteItem(sender, idTrack) {
		$(sender).attr('src', '<?=asset_url()?>images/wait.gif');
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
				else {
					showMessageDialog('', json.message);
					$(sender).attr('src', '<?=asset_url()?>images/delete.png');
				}
			}
		);
	}
</script>
