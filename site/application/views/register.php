<div class="container_12">
	<div class="grid_12">
		<h1>Register</h1>
	</div>
</div>

<?= form_open(base_url() . 'index.php/register/submit') ?>
	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Username
					<span class="errors"><?=form_error('reg_username')?></span>
					<input type="text" name="reg_username" maxlength="45" class="form-control" placeholder="Username" value="<?=set_value('reg_username')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Password
					<span class="errors"><?=form_error('reg_password')?></span>
					<input type="password" name="reg_password" maxlength="45" class="form-control" placeholder="Password" value="<?=set_value('reg_password')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Email
					<span class="errors"><?=form_error('reg_email')?></span>
					<input type="text" name="reg_email" maxlength="255" class="form-control" placeholder="your@email.com" value="<?=set_value('reg_email')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Language
					<span class="errors"><?=form_error('reg_language')?></span>
					<input type="text" name="reg_language" maxlength="255" class="form-control" placeholder="Language" value="<?=set_value('reg_language')?>">
				</label>
			</div>

			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url()?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>

<?php
