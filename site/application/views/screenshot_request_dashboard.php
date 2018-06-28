<div class="container_12">
	<div class="grid_12">
		<h1>Screenshot request dashboard</h1>
	</div>
</div>

<?php if(count($trackRequests) == 0 && count($gameRequests) == 0): ?>
	<div class="container_12">
		<div class="grid_12">
			<p>None!</p>
		</div>
	</div>
<?php else: ?>
	<div style="background-color: #dddddd;" class="container_12">
		<div class="grid_1 columnheader">
			<p>Username</p>
		</div>
		<div class="grid_2 columnheader">
			<p>Game</p>
		</div>
		<div class="grid_2 columnheader">
			<p>Track</p>
		</div>
		<div class="grid_1 columnheader">
			<p>isSet</p>
		</div>
		<div class="grid_4 columnheader">
			<p>Screenshot URL</p>
		</div>
		<div class="grid_1">
			&nbsp;
		</div>
		<div class="grid_1 columnheader">
			<!--delete -->
		</div>
	</div>

	<?php $b = TRUE; foreach($trackRequests as $request): //Tracks ?>
		<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
			<div class="grid_1">
				<p><a href="<?=base_url()?>index.php/user_profile/index/<?=$request->userName?>"><?= $request->userName ?></a></p>
			</div>
			<div class="grid_2">
				<p><a href="<?=base_url()?>index.php/game/index/<?=$request->idGame?>" target="_blank"><?= $request->titleEng ?></a></p>
			</div>
			<div class="grid_2">
				<p><a href="<?=base_url()?>index.php/game/index/<?=$request->idGame?>/<?=$request->idTrack?>" target="_blank"><?= $request->title ?></a></p>
			</div>
			<div class="grid_1">
				<p><?= ($request->isScreenshotSet == 1 ? "&#x2714;" : "") ?></p> <!-- Checkmark si on a le screenshot -->
			</div>
			<div class="grid_4 break-word">
				<p><a href="<?= $request->requestSreenshotUrl ?>" target="_blank"><?= $request->requestSreenshotUrl ?></a></p>
			</div>
			<div class="grid_1">
				<a href="#" class="btn btn-xs btn-default" onclick="showUploadScreenshotDialog(<?=$request->idTrack?>, 1)">Upload...</a>
			</div>
			<div class="grid_1">
				<?= form_open(base_url() . 'index.php/screenshot_request_dashboard/deleteTrack', array('id' => 'delete_' . $request->idTrack . $request->idUserRequester)) ?>
					<input type="hidden" name="idUser" value="<?= $request->idUserRequester ?>" />
					<input type="hidden" name="idTrack" value="<?= $request->idTrack ?>" />
					<a href="#" class="btn btn-xs btn-danger" onclick="delete_<?= $request->idTrack . $request->idUserRequester ?>.submit();">Delete</a>
				</form>
			</div>
		</div>
	<?php endforeach; ?>

	<?php foreach($gameRequests as $request): //Games ?>
		<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
			<div class="grid_1">
				<p><a href="<?=base_url()?>index.php/user_profile/index/<?=$request->userName?>"><?= $request->userName ?></a></p>
			</div>
			<div class="grid_2">
				<p><a href="<?=base_url()?>index.php/game/index/<?=$request->idGame?>" target="_blank"><?= $request->titleEng ?></a></p>
			</div>
			<div class="grid_2">
				<p>-</p>
			</div>
			<div class="grid_1">
				<p><?= ($request->isScreenshotSet == 1 ? "&#x2714;" : "") ?></p> <!-- Checkmark si on a le screenshot -->
			</div>
			<div class="grid_4 break-word">
				<p><a href="<?= $request->requestSreenshotUrl ?>" target="_blank"><?= $request->requestSreenshotUrl ?></a></p>
			</div>
			<div class="grid_1">
				<a href="#" class="btn btn-xs btn-default" onclick="showUploadScreenshotDialog(<?=$request->idGame?>, 0);">Upload...</a>
			</div>
			<div class="grid_1">
				<?= form_open(base_url() . 'index.php/screenshot_request_dashboard/deleteGame', array('id' => 'delete_' . $request->idGame . $request->idUserRequester)) ?>
					<input type="hidden" name="idUser" value="<?= $request->idUserRequester ?>" />
					<input type="hidden" name="idGame" value="<?= $request->idGame ?>" />
					<a href="#" class="btn btn-xs btn-danger" onclick="delete_<?= $request->idGame . $request->idUserRequester ?>.submit();">Delete</a>
				</form>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<?php require_once(views_dir() . 'includes/screenshot_upload.php'); ?>
