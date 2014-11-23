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
			<?php if($loggedUserIsAdmin):?><a href="#!" onclick="showUploadScreenshotDialog(<?=$game->idGame?>, 0); return false;">
			<?php elseif($isUserLogged && !$game->isScreenshotSet):?><a href="<?=base_url()?>index.php/request_screenshot_game/index/<?=$game->idGame?>"><?php endif;?>
			<div class="tv" style="position: relative; background-image: url('<?=$game->isScreenshotSet ? asset_url() . "images/screenshots/game/{$game->idGame}.png" : asset_url() . 'images/en/no_title_ss.png'?>');">
				<?php if($loggedUserIsAdmin && $game->isScreenshotSet):?><img id="unset-screenshot" style="position: absolute; top: 24px; right: 24px; width: 24px; height: 24px;" src="<?=asset_url()?>images/delete.png" onclick="unsetScreenshot(<?=$game->idGame?>, 0);"/><?php endif;?>
			</div>
			<?php if($isUserLogged && !$game->isScreenshotSet || $loggedUserIsAdmin):?></a><?php endif;?>
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
			<p class="grid_1 columnheader">&nbsp;</p><!-- play -->
			<p class="grid_3 columnheader">Title</p>
			<p class="grid_1 columnheader">Length</p>
			<p class="grid_2 columnheader">Composer(s)</p>
			<p class="grid_1 columnheader">SPC</p>
			<?php if($isUserLogged): ?>
				<p class="grid_3 columnheader">My playlists</p>
			<?php endif; ?>
			<div class="grid_2 columnheader"><!-- details --></div>
		</div>
		<?php $b = TRUE; foreach($tracks as $track): ?>
			<div <?php if($b = !$b): ?> style="background-color: #dddddd;" <?php endif; ?> class="container_16">
				<img class="grid_1" style="width: 24px; height: 24px; cursor: pointer;" src="<?=asset_url() . 'images/play.png'?>" onclick="playTrack(<?=$track->idTrack?>);" />
				<p class="grid_3"><?=$track->title?></p>
				<p class="grid_1"><?=$track->length?></p>
				<p class="grid_2"><?=$track->composer?></p>
				<div class="grid_1">
					<a href="<?=asset_url() . 'spc/' . str_replace('&', '%26', $track->spcURL)?>"><img src="<?=asset_url() . 'images/download.png'?>" /></a>
				</div>
				<?php if($isUserLogged): ?>
					<div class="grid_2 btn btn-xs btn-default" onclick="addToPlaylistDialog(<?=$track->idTrack?>);">Add to playlist...</div>
				<?php endif; ?>
				<div class="grid_2 btn btn-xs btn-default" onclick="detailsDialog(<?=$track->idTrack?>)">Details</div>
			</div>

			<!-- details dialog -->
			<div style="display: none; padding-top: 15px;" id="dialog-details_<?=$track->idTrack?>" title="<?=$game->titleEng . ' - ' . $track->title?>">
				<?php if($loggedUserIsAdmin):?><a href="#!" onclick="showUploadScreenshotDialog(<?=$track->idTrack?>, 1); return false;">
				<?php elseif($isUserLogged && !$track->isScreenshotSet):?><a href="<?=base_url()?>index.php/request_screenshot_track/index/<?=$track->idTrack?>"><?php endif;?>
					<div class="tv" style="position: relative; background-image: url('<?=$track->isScreenshotSet ? asset_url() . "images/screenshots/track/{$track->idTrack}.png" : asset_url() . 'images/en/no_track_ss.png'?>');">
						<?php if($loggedUserIsAdmin && $track->isScreenshotSet):?><img id="unset-screenshot" style="position: absolute; top: 24px; right: 24px; width: 24px; height: 24px;" src="<?=asset_url()?>images/delete.png" onclick="unsetScreenshot(<?=$track->idTrack?>, 1);"/><?php endif;?>
					</div>
				<?php if($isUserLogged && !$track->isScreenshotSet || $loggedUserIsAdmin):?></a><?php endif;?>
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
				<?php if($isUserLogged):?>
					<a href="<?=base_url()?>index.php/request_review/index/<?=$track->idTrack?>">Write a review</a>
				<?php else:?>
					<p>Log in to write a review!</p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>

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
				$('#dialog-details_' + idTrack + ' #unset-screenshot').click(function(event) { event.stopPropagation(); });
			}
		</script>

		<?php if($loggedUserIsAdmin): ?>
			<div style="display: none;" id="dialog-upload">
				<?= form_open_multipart(base_url() . 'index.php/screenshot_request_dashboard/uploadScreenshot') ?>
					<input type="hidden" id="id" name="id" />
					<input type="hidden" id="type" name="type" />
					<input type="file" id="file" name="userfile" />
					<div id="progress"></div>
					<div class="errors"></div>
				</form>
			</div>

			<script src="<?=asset_url()?>js/jquery.fileupload.js"></script>
			<script>
				$(function() {
					$('#unset-screenshot').click(function(event) { event.stopPropagation(); });
				});
				
				function showUploadScreenshotDialog(id, type) {
					$('#dialog-upload #id').val(id);
					$('#dialog-upload #type').val(type);
					$('#dialog-upload #progress').progressbar({ value: 0 });
					$('#dialog-upload #file').fileupload({
						dataType: 'json',
						progressall: function (e, data) {
							$('#dialog-upload .errors').text('');
							var progress = parseInt(data.loaded / data.total * 100, 10);
							$('#dialog-upload #progress').progressbar('option', { value: progress < 100 ? progress : false });
						},
						done: function (e, data) {
							if(data.result.success) {
								location.reload();
							} else {
								$('#dialog-upload .errors').text(data.result.message);
							}
						}
					});
					
					$('#dialog-upload').dialog({
						width: 400,
						modal: true,
						resizable: false,
						title: 'Upload screenshot',
						show: { effect: 'puff', duration: 200 },
						hide: { effect: 'puff', duration: 200 }
					});
				}

				function unsetScreenshot(id, type) {
					if(confirm('Are you sure to unset this screenshot?')) {
						$.post('<?=base_url()?>index.php/screenshot_request_dashboard/unsetScreenshot',
							{type: type, id: id },
							function(data) {
								if(data.success)
									location.reload();
								else
									alert(data.message);
							},
							'json'
						);
					}
				}
			</script>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
