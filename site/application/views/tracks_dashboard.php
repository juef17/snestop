<div class="container_12">
	<div class="grid_12">
		<h1>Tracks dashboard for <?=$gameTitle?></h1>
		<p><a href="<?= base_url() . "index.php/edit_track/create/{$idGame}" ?>" class="btn btn-default">Create</a></p>
		<p><a href="<?= base_url() . "index.php/game/index/{$idGame}" ?>">Go to game page</a></p>
	</div>
</div>

<div style="background-color: #dddddd;" class="container_16">
	<div class="grid_3 columnheader">
		<p>Title</p>
	</div>
	<div class="grid_1 columnheader">
		<p>Length</p>
	</div>
	<div class="grid_1 columnheader">
		<p>Fade</p>
	</div>
	<div class="grid_1 columnheader">
		<p>Jingle</p>
	</div>
	<div class="grid_1 columnheader">
		<p>Active</p>
	</div>
	<div class="grid_1 columnheader">
		<p>G2rating</p>
	</div>
	<div class="grid_1 columnheader">
		<p>ERating</p>
	</div>
	<div class="grid_1 columnheader">
		<!--play -->
	</div>
	<div class="grid_1 columnheader">
		<!--details -->
	</div>
	<div class="grid_1 columnheader">
		<!--edit -->
	</div>
	<div class="grid_1 columnheader">
		<!--delete -->
	</div>
</div>

<?php if(count($tracks) == 0): ?>
<div class="container_12">
	<div class="grid_12">
		<p>No track yet!</p>
	</div>
</div>
<?php endif; ?>

<?php $b = TRUE; foreach($tracks as $track): ?>
	<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_16">
		<input type="hidden" name="id" value="<?=$track->idTrack?>" />
		<div class="grid_3">
			<p><?=$track->title?></p>
		</div>
		<div class="grid_1">
			<p><?=$track->length?> s</p>
		</div>
		<div class="grid_1">
			<p><?=$track->fadeLength?> s</p>
		</div>
		<div class="grid_1">
			<p><?=$track->isJingle ? 'yes' : 'no'?></p>
		</div>
		<div class="grid_1">
			<p><?=$track->turnedOffByAdmin ? 'no' : 'yes'?></p>
		</div>
		<div class="grid_1">
			<p><?=$track->glicko2rating?></p>
		</div>
		<div class="grid_1">
			<p><?=$track->eloRating?></p>
		</div>
		<div class="grid_1">
			<img style="width: 24px; height: 24px; cursor: pointer;" src="<?=asset_url() . 'images/play.png'?>" onclick="playTrack(<?=$track->idTrack?>);" />
		</div>
		<div class="grid_1">
			<div class="btn btn-xs btn-default" onclick="detailsDialog(<?=$track->idTrack?>)">Details</div>
		</div>
		<div class="grid_1">
			<a class="btn btn-xs btn-default" href="<?=base_url()?>index.php/edit_track/index/<?=$track->idTrack?>">Edit</a>
		</div>
		<div class="grid_1">
			<?= form_open(base_url() . "index.php/tracks_dashboard/delete/{$track->idGame}", array('id' => "delete_{$track->idTrack}")) ?>
				<input type="hidden" name="id" value="<?=$track->idTrack?>" />
				<div class="btn btn-xs btn-danger" onclick="confirmDelete(<?=$track->idTrack?>);">Delete</div>
			</form>
		</div>
	</div>

	<!-- details dialog -->
	<div style="display: none;" id="dialog-details_<?=$track->idTrack?>" title="<?=$track->title?>">
		<label>Composer</label>
		<p><?=$track->composer?></p>
		<label>Screenshot</label>
		<p><a target="_blank" href="<?=$track->isScreenshotSet ? asset_url() . "images/screenshots/track/{$track->idTrack}.png" : '#!'?>"><?=$track->isScreenshotSet ? 'Yes' : 'No' ?></a></p>
		<label>SPC</label>
		<p><?=$track->spcURL?></p>
		<label>Encoded SPC</label>
		<p><?=$track->spcEncodedURL?></p>
		<label>Glicko2RD</label>
		<p><?=$track->glicko2RD?></p>
		<label>Glicko2sigma</label>
		<p><?=$track->glicko2sigma?></p>
		<label>Elo reached 2400</label>
		<p><?=$track->eloReached2400 ? 'yes' : 'no'?></p>
	</div>
<?php endforeach; ?>

<!-- delete dialog -->

<div style="display: none;" id="dialog-confirm-delete" title="Delete game?">
  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		Deleting a track will also delete all those related entries:
		<ul>
			<li>Review
				<ul>
					<li>Votes</li>
				</ul>
			</li>
			<li>RatingCommunity</li>
			<li>RatingPersonal</li>
			<li>ShitTrack</li>
			<li>DuelResult</li>
			<li>TrackScreenshotRequest</li>
			<li>PlaylistItem</li>
		</ul>
	</p>
</div>


<script>
	function detailsDialog(idTrack) {
		$('#dialog-details_' + idTrack).dialog({
			height: 500,
			width: 700,
			modal: true,
			buttons: {
				Ok: function() {
					$(this).dialog('close');
				}
			}
		});
	}

	function confirmDelete(idTrack) {
		$('#dialog-confirm-delete').dialog({
			resizable: false,
			height: 400,
			width: 350,
			modal: true,
			buttons: {
				"Delete track": function() {
					$('#delete_' + idTrack).submit();
					$(this).dialog("close");
				},
				Cancel: function() {
					$(this).dialog("close");
				}
			}
		});
	}
</script>
