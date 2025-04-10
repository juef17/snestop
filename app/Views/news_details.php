<div class="container_12">
	<div class="grid_12">
		<h1>News details</h1>
	</div>
</div>

<?= form_open(base_url() . 'news_dashboard/submit') ?>
	<input type="hidden" name="idNews" value="<?= $newsitem['idNews'] ?>" />
	<div class="container_12">
		<div class="grid_12">
			<div class="form-group">
				<label class="sr-only">Title</label>
				<input type="text" name="title" maxlength="45" class="form-control" placeholder="Title" value="<?= $newsitem['title'] ?>">
			</div>
			<div class="form-group">
				<label class="sr-only">Date</label>
				<input type="text" readonly="readonly" name="date" maxlength="10" class="form-control" placeholder="Date" value="<?= $newsitem['date'] ?>">
			</div>
			<div class="form-group">
				<label class="sr-only">User Name</label>
				<input type="text" readonly="readonly" name="userName" maxlength="10" class="form-control" placeholder="User Name" value="<?= $newsitem['userName'] ?>">
			</div>
		</div>
	</div>

	<div class="container_12">
		<div class="grid_12">
			<p>Note: uploaded images are hosted on imgur.</p>
			<div class="form-group">
					<textarea id="text" name="text" class="form-control" placeholder="News text" maxlength="4000" rows="10"><?= $newsitem['text'] ?></textarea>
			</div>
			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url() . 'news_dashboard'?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>

<script src="<?=asset_url()?>js/nicEdit-latest.js" type="text/javascript"></script>
<script>
	$(function() {
		new nicEditor({fullPanel : true}).panelInstance('text');
	});
</script>
