<?php if($game == NULL): ?>
	<div class="container_12">
		<div class="grid_12">
			<h1>Game not found</h1>
		</div>
	</div>
<?php else: ?>
	<div class="container_12">
		<div class="grid_12">
			<h1><?=$game->titleEng?><br /><span style="font-style: italic; font-size: 0.8em;"><?=$game->titleJap?></span></h1>
		</div>
	</div>

	<div class="container_12">
		<div class="grid_4">
			<div class="tv" style="background-image: url('<?=$game->screenshotURL != NULL ? $game->screenshotURL : asset_url() . 'images/en/no_title_ss.png'?>');"></div>
		</div>
		<div class="grid_8">
			<div>
				<?php if(count($tracks) > 0): ?>
					<a href="<?=$game->rsnFileURL?>"><img src="<?=asset_url() . 'images/download.png'?>" /> Download soundtrack in RSN format</a>
				<?php endif; ?>
			</div>
			<div>
				<table>
					<tr>
						<td>Composer(s):</td>
						<?php if(count($composers) > 0): ?>
							<td><?=implode('<br />', $composers)?></td>
						<?php else: ?>
							<td>Unknown</td>
						<?php endif; ?>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="container_12">
		<div class="grid_12">
			<h2>Tracks</h2>
			<?php if($loggedUserIsAdmin): ?>
				<div>
					<a href="<?=base_url()?>index.php/tracks_dashboard/index/<?=$game->idGame?>">Open tracks dashboard</a>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if(count($tracks) == 0): ?>
		<div class="container_12">
			<div class="grid_1">
				<p>None</p>
			</div>
		</div>
	<?php else: ?>
		<div style="background-color: #dddddd;" class="container_16">
			<div class="grid_1 columnheader">
				<p>&nbsp;</p><!-- play -->
			</div>
			<div class="grid_3 columnheader">
				<p>Title</p>
			</div>
			<div class="grid_1 columnheader">
				<p>Length</p>
			</div>
			<div class="grid_2 columnheader">
				<p>Composer(s)</p>
			</div>
			<div class="grid_1 columnheader">
				<p>SPC</p>
			</div>
			<?php if($isUserLogged): ?>
				<div class="grid_3 columnheader">
					<p>My playlists</p>
				</div>
			<?php endif; ?>
			<div class="grid_1 columnheader">
				<!-- details -->
			</div>
		</div>
		<?php $b = TRUE; foreach($tracks as $track): ?>
			<div <?php if($b = !$b): ?> style="background-color: #dddddd;" <?php endif; ?> class="container_16">
				<div class="grid_1">
					<img style="width: 24px; height: 24px; cursor: pointer;" src="<?=asset_url() . 'images/play.png'?>" onclick="startPlayer('<?=asset_url() . 'spc/' . $track->spcURL?>', <?=$track->length?>, <?=$track->fadeLength?>, '<?=$track->title?>', '<?=$track->screenshotURL?>');" />
				</div>
				<div class="grid_3">
					<p><?=$track->title?></p>
				</div>
				<div class="grid_1">
					<p><?=$track->length?></p>
				</div>
				<div class="grid_2">
					<p><?=$track->composer?></p>
				</div>
				<div class="grid_1">
					<a href="<?=asset_url() . 'spc/' . $track->spcURL?>"><img src="<?=asset_url() . 'images/download.png'?>" /></a>
				</div>
				<?php if($isUserLogged): ?>
					<div class="grid_2">
						<div class="btn btn-xs btn-default" onclick="addToPlaylistDialog(<?=$track->idTrack?>);">Add to playlist...</div>
					</div>
				<?php endif; ?>
				<div class="grid_1">
					<div class="btn btn-xs btn-default" onclick="detailsDialog(<?=$track->idTrack?>)">Details</div>
				</div>
			</div>

			<!-- details dialog -->
			<div style="display: none; padding-top: 15px;" id="dialog-details_<?=$track->idTrack?>" title="<?=$game->titleEng . ' - ' . $track->title?>">
				<div class="tv" style="background-image: url('<?=$track->screenshotURL != NULL ? $track->screenshotURL : asset_url() . 'images/en/no_track_ss.png'?>');"></div>
				<div style="display: inline-block; margin: 15px 0 0 15px;">
					<h4>Ratings*</h4>
					<table class="datatable">
						<tr class="graybg">
							<th>&nbsp;</th>
							<th>Personal</th>
							<th>Community</th>
							<th>Global</th>
						</tr>
						<tr>
							<th>Elo</th>
							<td>?</td>
							<td>?</td>
							<td>?</td>
						</tr>
						<tr class="graybg">
							<th>Glicko 2</th>
							<td>?</td>
							<td>?</td>
							<td>?</td>
						</tr>
						<tr>
							<th>RD</th>
							<td>?</td>
							<td>?</td>
							<td>?</td>
						</tr>
						<tr class="graybg">
							<th>Sigma</th>
							<td>?</td>
							<td>?</td>
							<td>?</td>
						</tr>
					</table>
					<p style="font-size: 0.6em;">*Ratings will be updated as soon as we have enough data! Come back soon!</p>
				</div>
				<h4>Reviews</h4>
				<p>None yet! Be the first to write one!</p>
				<a href="#">Write a review</a>
			</div>
		<?php endforeach; ?>
		<!-- add to playlist dialog -->
		<div id="dialog-addToPlaylist" style="display: none;" title="Select a playlist">
			<select id="game-playlistcombo" style="display: inline-block;"><!--ajax loaded content--></select>
		</div>
	<?php endif; ?>
<?php endif; ?>

<script>
	function detailsDialog(idTrack) {
		$('#dialog-details_' + idTrack).dialog({
			height: 500,
			width: 700,
			modal: true,
			resizable: false,
			show: { effect: 'puff', duration: 200 },
			hide: { effect: 'puff', duration: 200 },
			buttons: {
				Ok: function() {
					$(this).dialog('close');
				}
			}
		});
	}

	function addToPlaylistDialog(idTrack) {
		$('#game-playlistcombo').load('<?=base_url()?>index.php/playlist/playlists/0');
		$('#dialog-addToPlaylist').dialog({
			modal: true,
			resizable: false,
			show: { effect: 'puff', duration: 200 },
			hide: { effect: 'puff', duration: 200 },
			buttons: {
				Ok: function() {
					addToPlaylist(idTrack, $('#game-playlistcombo').val());
				}
			}
		});
	}

	function addToPlaylist(idTrack, idPlaylist) {
		$.post('<?=base_url()?>index.php/playlist/addPlaylistItem',
			{
				idPlaylist: idPlaylist,
				idTrack: idTrack
			},
			function(data) {
				var json = $.parseJSON(data);
				if(json.success)
					$('#dialog-addToPlaylist').dialog('close');
				else
					showMessageDialog('D\'oh!', json.message);
			});
	}
</script>
