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
			<?php if($loggedUserIsAdmin && $game->screenshotURL == NULL):?><a href="#!" onclick="showUploadScreenshotDialog(<?=$game->idGame?>); return false;"><?php endif;?>
			<?php else if($isUserLogged && $game->screenshotURL == NULL):?><a href="<?=base_url()?>index.php/request_screenshot_game/index/<?=$game->idGame?>"><?php endif;?>
				<div class="tv" style="background-image: url('<?=$game->screenshotURL != NULL ? $game->screenshotURL : asset_url() . 'images/en/no_title_ss.png'?>');"></div>
			<?php if($isUserLogged && $game->screenshotURL == NULL):?></a><?php endif;?>
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
					<img style="width: 24px; height: 24px; cursor: pointer;" src="<?=asset_url() . 'images/play.png'?>" onclick="playTrack(<?=$track->idTrack?>);" />
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
					<a href="<?=asset_url() . 'spc/' . str_replace('&', '%26', $track->spcURL)?>"><img src="<?=asset_url() . 'images/download.png'?>" /></a>
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
				<?php if($isUserLogged && $track->screenshotURL == NULL):?><a href="<?=base_url()?>index.php/request_screenshot_track/index/<?=$track->idTrack?>"><?php endif;?>
					<div class="tv" style="background-image: url('<?=$track->screenshotURL != NULL ? $track->screenshotURL : asset_url() . 'images/en/no_track_ss.png'?>');"></div>
				<?php if($isUserLogged && $track->screenshotURL == NULL):?></a><?php endif;?>
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
	<?php endif; ?>
<?php endif; ?>

<div id="dialog-upload">
	<form id="upload-form" action="<?=base_url()?>index.php/screenshot_request_dashboard/uploadGameScreenshot" enctype="multipart/form-data" method="post">
		<input type="hidden" name="idgame" />
		<input type="file" name="screenshot" />
	</form>
</div>

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

	<?php if($loggedUserIsAdmin):	?> //prevent hacking
		function showUploadScreenshotDialog(idGame) {

			$('#dialog-details_' + idTrack).dialog({
				modal: true,
				resizable: false,
				show: { effect: 'puff', duration: 200 },
				hide: { effect: 'puff', duration: 200 },
				buttons: {
					Ok: function() {
						$('#upload-form').submit();
					},
					Cancel: function() {
						$(this).dialog('close');
					}
				}
			});
		}
	<?php endif; ?>
</script>
