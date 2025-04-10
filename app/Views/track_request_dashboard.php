<div class="container_12">
	<div class="grid_12">
		<h1>Track request dashboard</h1>
	</div>
</div>

<?php if(count($trackRequests) == 0): ?>
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
		<p>Title</p>
	</div>
	<div class="grid_5 columnheader">
		<p>Track URL</a></p>
	</div>
	<div class="grid_1 columnheader">
		<!--delete -->
	</div>
</div>
<?php endif; ?>

<?php $b = TRUE; foreach($trackRequests as $request): ?>
	<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
		<div class="grid_2">
			<p><?= $request['userName'] ?></p>
		</div>
		<div class="grid_2">
			<p><?= $request['game'] ?></p>
		</div>
		<div class="grid_2">
			<p><?= $request['title'] ?></p>
		</div>
		<div class="grid_5">
			<p><a href="<?= $request['trackURL'] ?>"><?= $request['trackURL'] ?></a></p>
		</div>
		<div class="grid_1">
			<?= form_open(base_url() . 'track_request_dashboard/delete', array('id' => 'delete_' . $request['idTrackRequest'])) ?>
				<input type="hidden" name="id" value="<?= $request['idTrackRequest'] ?>" />
				<a href="#" class="btn btn-xs btn-danger" onclick="delete_<?= $request['idTrackRequest'] ?>.submit();">Delete</a>
			</form>
		</div>
	</div>
<?php endforeach; ?>
