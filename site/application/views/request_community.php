<div class="container_12">
	<div class="grid_12">
		<h1>Request a community</h1>
	</div>
</div>

<?= form_open(base_url() . 'index.php/request_community/submit') ?>
	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Name
					<span class="errors"><?=form_error('name')?></span>
					<input type="text" name="name" maxlength="45" class="form-control" placeholder="Name" value="<?=set_value('name')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Link to community
					<span class="errors"><?=form_error('url')?></span>
					<input type="text" name="url" maxlength="255" class="form-control" placeholder="http://" value="<?=set_value('url')?>">
				</label>
			</div>

			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url()?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>

<?php
