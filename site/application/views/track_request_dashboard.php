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
<?php endif; ?>

<?php $b = FALSE; foreach($trackRequests as $request): ?>
	<div style="border-bottom: 1px; <?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
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
			<?= form_open(base_url() . 'index.php/track_request_dashboard/delete', array('id' => 'delete_' . $request['idTrackRequest'])) ?>
				<input type="hidden" name="id" value="<?= $request['idTrackRequest'] ?>" />
				<a href="#" onclick="delete_<?= $request['idTrackRequest'] ?>.submit();">Delete</a>
			</form>
		</div>
	</div>
<?php endforeach; ?>
