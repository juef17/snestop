<div class="container_12">
	<div class="grid_12">
		<h1>News dashboard</h1>
		<p><a href="<?= base_url() . 'news_dashboard/create' ?>" class="btn btn-default">Create</a></p>
	</div>
</div>

<div style="background-color: #dddddd;" class="container_12">
	<div class="grid_2 columnheader">
		<p>Date</p>
	</div>
	<div class="grid_2 columnheader">
		<p>User</p>
	</div>
	<div class="grid_2 columnheader">
		<p>Title</p>
	</div>
	<div class="grid_1 columnheader">
		<!--edit-->
	</div>
	<div class="grid_1 columnheader">
		<!--delete-->
	</div>
</div>

<?php if(count($news) == 0): ?>
<div class="container_12">
	<div class="grid_12">
		<p>None!</p>
	</div>
</div>
<?php endif; ?>

<?php $b = TRUE; foreach($news as $newsitem): ?>
	<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
		<div class="grid_2">
			<p><?= $newsitem['date'] ?></p>
		</div>
		<div class="grid_2">
			<p><?= $newsitem['userName'] ?></p>
		</div>
		<div class="grid_2">
			<p><?= $newsitem['title'] ?></p>
		</div>
		<div class="grid_1">
			<p><a href="<?= base_url() . 'news_dashboard/edit/' . $newsitem['idNews'] ?>">Edit</a></p>
		</div>
		<div class="grid_1">
			<?= form_open(base_url() . 'news_dashboard/delete', array('id' => 'delete_' . $newsitem['idNews'])) ?>
				<input type="hidden" name="id" value="<?= $newsitem['idNews'] ?>" />
				<a href="#" class="btn btn-xs btn-danger" onclick="delete_<?= $newsitem['idNews'] ?>.submit();">Delete</a>
			</form>
		</div>
	</div>
<?php endforeach; ?>
