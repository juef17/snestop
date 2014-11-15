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
		<div class="grid_2 columnheader">
			<p>Username</p>
		</div>
		<div class="grid_2 columnheader">
			<p>Game</p>
		</div>
		<div class="grid_2 columnheader">
			<p>Track</p>
		</div>
		<div class="grid_5 columnheader">
			<p>Screenshot URL</a></p>
		</div>
		<div class="grid_1 columnheader">
			<!--delete -->
		</div>
	</div>

	<?php $b = TRUE; foreach($trackRequests as $request): //Tracks ?>
		<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
			<div class="grid_2">
				<p><?= $request->userName ?></p>
			</div>
			<div class="grid_2">
				<p><?= $request->titleEng ?></p>
			</div>
			<div class="grid_2">
				<p><?= $request->title ?></p>
			</div>
			<div class="grid_5">
				<p><a href="<?= $request->requestSreenshotUrl ?>"><?= $request->requestSreenshotUrl ?></a></p>
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

	<?php $b = TRUE; foreach($gameRequests as $request): //Games ?>
		<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
			<div class="grid_2">
				<p><?= $request->userName ?></p>
			</div>
			<div class="grid_2">
				<p><?= $request->titleEng ?></p>
			</div>
			<div class="grid_2">
				<p>-</p>
			</div>
			<div class="grid_5">
				<p><a href="<?= $request->requestSreenshotUrl ?>"><?= $request->requestSreenshotUrl ?></a></p>
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
