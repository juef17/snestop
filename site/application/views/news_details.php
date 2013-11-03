<div class="container_12">
	<div class="grid_12">
		<h1>News details</h1>
	</div>
</div>

<?= form_open(base_url() . 'index.php/news_dashboard/submit') ?>
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
			<div class="form-group">
					<textarea name="text" class="form-control" placeholder="News text" maxlength="4000" rows="10"><?= $newsitem['text'] ?></textarea>
			</div>
			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url() . 'index.php/news_dashboard'?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>
