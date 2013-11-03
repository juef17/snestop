<div class="container_12">
	<div class="grid_12">
		<h2>News dashboard</h2>
		<p><a href="<?= base_url() . 'index.php/news_dashboard/create' ?>">Create</a></p>
	</div>
</div>

<?php if(count($news) == 0): ?>
<div class="container_12">
	<div class="grid_12">
		<p>None!</p>
	</div>
</div>
<?php endif; ?>

<?php $b = FALSE; foreach($news as $newsitem): ?>
	<div style="border-bottom: 1px; <?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
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
			<p><a href="<?= base_url() . 'index.php/news_dashboard/edit/' . $newsitem['idNews'] ?>">Edit</a></p>
		</div>
		<div class="grid_1">
			<?= form_open(base_url() . 'index.php/news_dashboard/delete', array('id' => 'delete_' . $newsitem['idNews'])) ?>
				<input type="hidden" name="id" value="<?= $newsitem['idNews'] ?>" />
				<a href="#" onclick="delete_<?= $newsitem['idNews'] ?>.submit();">Delete</a>
			</form>
		</div>
	</div>
<?php endforeach; ?>
