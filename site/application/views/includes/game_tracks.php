<?php if(count($tracks) == 0): ?>
	<div class="container_12">
		<div class="grid_1">
			<p>None</p>
		</div>
	</div>
<?php else: ?>
	<?php $b = TRUE; date_default_timezone_set("America/New_York"); foreach($tracks as $track): ?>
		<div <?php if($b = !$b): ?> style="background-color: #dddddd;" <?php endif; ?> class="container_16">
			<p class="grid_1"><?=$track->trackNumber?></p>
			<p class="grid_1"><img style="width: 24px; height: 24px; cursor: pointer;" src="<?=asset_url() . 'images/play.png'?>" onclick="playTrack(<?=$track->idTrack?>);" /></p>
			<p class="grid_3"><a href="#!" onclick="detailsDialog(<?=$track->idTrack?>)"><?=$track->title?></a></p>
			<p class="grid_1"><?=intval(date("i", $track->length)) . ":" . date("s", $track->length)?></p>
			<p class="grid_1"><?= implode(', ', array_filter(array(($track->isJingle ? 'Jingle' : NULL), ($track->isVoice ? 'vfx' : NULL), ($track->isSoundEffect ? 'sfx' : NULL)))) ?></p>
			<p class="grid_3"><?=$track->composer?></p>
			<div class="grid_1">
				<a href="<?=asset_url() . 'spc/' . rawurlencode($track->spcURL)?>"><img src="<?=asset_url() . 'images/download.png'?>" /></a>
			</div>
			<?php if($loggedUser): ?>
				<div class="grid_2 btn btn-xs btn-default" onclick="addToPlaylistDialog([<?=$track->idTrack?>]);">Add to playlist...</div>
			<?php endif; ?>
		</div>

		<!-- details dialog -->
		<div style="display: none; padding-top: 15px;" id="dialog-details_<?=$track->idTrack?>" title="<?=$game->titleEng . ' - ' . $track->title?>">
			<?php if($loggedUser && $loggedUser->isAdmin):?><a tabindex="-1" href="#!" onclick="showUploadScreenshotDialog(<?=$track->idTrack?>, 1); return false;">
			<?php elseif($loggedUser && !$track->isScreenshotSet):?><a href="<?=base_url()?>index.php/request_screenshot_track/index/<?=$track->idTrack?>"><?php endif;?>
				<div class="tv" style="position: relative; background-image: url('<?=$track->isScreenshotSet ? asset_url() . "images/screenshots/track/{$track->idTrack}.png" : asset_url() . 'images/en/no_track_ss.png'?>');">
					<?php if($loggedUser && $loggedUser->isAdmin && $track->isScreenshotSet):?><img id="unset-screenshot" style="position: absolute; top: 24px; right: 24px; width: 24px; height: 24px;" src="<?=asset_url()?>images/delete.png" onclick="unsetScreenshot(<?=$track->idTrack?>, 1);"/><?php endif;?>
				</div>
			<?php if($loggedUser && (!$track->isScreenshotSet || $loggedUser->isAdmin)):?></a><?php endif;?>
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
			<p><a href="#!" onClick="shareTrack(<?=$track->idTrack?>)">Share a link to this track</a></p>
			<p>
				<img style="width: 24px; height: 24px; cursor: pointer;" src="<?=asset_url() . 'images/play.png'?>" onclick="playTrack(<?=$track->idTrack?>);">
			</p>
			<p id="shareTrack_<?=$track->idTrack?>" style="display: none"><input type="text" style="cursor: text" class="form-control" onClick="this.select();" value="<?=base_url()?>index.php/game/index/<?=$game->idGame?>/<?=$track->idTrack?>" readonly /></p>
			<h3>Reviews</h3>
			<?php if($loggedUser):?>
				<a href="<?=base_url()?>index.php/request_review/index/<?=$track->idTrack?>">Write a review</a>
			<?php else:?>
				<p>Log in to write a review!</p>
			<?php endif; ?>
			<div id="reviews-container"><!-- Ajax loaded content --></div>
		</div>
	<?php endforeach; ?>
<?php endif; //tracks count > 0 ?>

<script>
	var gameTracks = [<?= implode (', ', array_map(function($track){ return $track->idTrack; }, $tracks)) ?>];
</script>
