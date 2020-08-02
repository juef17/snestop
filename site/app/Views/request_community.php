<div class="container_12">
	<div class="grid_12">
		<h1>Request a community</h1>
	</div>
</div>

<?= form_open(base_url() . '/index.php/request_community/submit') ?>
	<div class="container_12">
		<div class="grid_12">
			<p>Different communities on the Internet have different tastes in music. That's why we will provide track rankings based on your favorite community's efforts! If said community isn't listed on our site, then we might very well add it, assuming at least 10 different users plan on participating regularly. Is that the case? Then please fill the following form.</p>
			<p>We will send an email to your registered address with the token once the community has been approved. Thank you!</p>
		</div>
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Community name
					<span class="errors"><?// TODO bâtard de forms validations cossins qui ont changé=form_error('name')?></span>
					<input type="text" name="name" maxlength="45" class="form-control" placeholder="Name" value="<?=set_value('name')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Link to community
					<span class="errors"><?// TODO bâtard de forms validations cossins qui ont changé=form_error('url')?></span>
					<input type="text" name="url" maxlength="255" class="form-control" placeholder="http://" value="<?=set_value('url')?>">
				</label>
			</div>

			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url()?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>

<?php
