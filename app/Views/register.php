<div class="container_12">
	<div class="grid_12">
		<h1>Register</h1>
	</div>
</div>

<?= form_open(base_url() . 'register/submit') ?>
	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Username
					<span class="errors"><?//TODO=form_error('reg_username')?></span>
					<input type="text" name="reg_username" maxlength="45" class="form-control" placeholder="Username" value="<?=set_value('reg_username')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Password [<a class="popup-tooltip" href="#!" title="Be aware that this site does not use HTTPS. We encrypt your password safely but the data is transmitted over unencrypted HTTP. We suggest you enter a unique password solely for this site's purpose.">?</a>]
					<span class="errors"><?//TODO=form_error('reg_password')?></span>
					<input type="password" name="reg_password" maxlength="45" class="form-control" placeholder="Password" value="<?=set_value('reg_password')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Retype password
					<input type="password" name="reg_password_verif" maxlength="45" class="form-control" placeholder="Password" value="<?=set_value('reg_password_verif')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Email [<a class="popup-tooltip" href="#!" title="Your email account will be used exclusively for this site. We will NOT send any spam ever.">?</a>]
					<span class="errors"><?//TODO=form_error('reg_email')?></span>
					<input type="text" name="reg_email" maxlength="255" class="form-control" placeholder="your@email.com" value="<?=set_value('reg_email')?>">
				</label>
			</div>
			<div class="form-group" style="display: none;">
				<label>Language
					<span class="errors"><?//TODO=form_error('reg_language')?></span>
					<select name="reg_language" class="form-control">
						<?php foreach($languages as $language): ?>
							<option value="<?=$language?>" <?php if(set_value('reg_language', 'English') == $language) echo 'selected';?>><?=$language?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>
			<div class="form-group">
				<label>Community [<a class="popup-tooltip" href="#!" title="If you're a member of a community and have their token, enter them here. Otherwise, leave this field to none.">?</a>] [<a class="popup-tooltip" style="font-size: 0.7em;" href="#!" title="Register without it first, then in the main menu you'll find a place to file a request for your community to be added.">Mine's not listed</a>]
					<span class="errors"><?//TODO=form_error('reg_community')?></span>
					<select id="reg_community" name="reg_community" class="form-control">
						<option value="0">None</option>
						<?php foreach($communities as $community): ?>
							<option value="<?=$community->idCommunity?>" <?php if(set_value('reg_community') == $community->idCommunity) echo 'selected';?>><?=$community->name?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label id="communityToken" style="display: none;">Community token
					<span class="errors"><?//TODO=form_error('reg_community_token')?></span>
					<input type="text" name="reg_community_token" maxlength="45" class="form-control" placeholder="Community token" value="<?=set_value('reg_community_token')?>" autocomplete="off">
				</label>
			</div>
			<div class="form-group">
				<label>Verifications [<a class="popup-tooltip" href="#!" title="Answer the two questions below to prove us you eat, drink and breathe. Sleep is irrelevant.">?</a>]
					<span class="errors"><?//TODO=form_error('reg_calc')?></span>
				</label>
				<input type="text" name="reg_calc" maxlength="10" class="form-control" placeholder="How many seconds in an hour?" autocomplete="off">
			</div>

			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url()?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>

<script>
	$(function() {
		$('#reg_community').change( function() {
			if($(this).val() == '0')
				$('#communityToken').hide(400);
			else
				$('#communityToken').show(400);
		});

		$('.popup-tooltip').tooltip({ track: true });
	});
</script>
