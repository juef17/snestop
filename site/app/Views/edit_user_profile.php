<div class="container_12">
	<div class="grid_12">
		<h1>Update your profile</h1>
	</div>
</div>

<?= form_open(base_url() . '/index.php/edit_user_profile/submit') ?>
	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<h2>General</h2>
			</div>
			<div class="form-group">
				<label>Username
					<span class="errors"><?//TODO=form_error('edit_username')?></span>
					<input type="text" name="edit_username" maxlength="45" class="form-control" placeholder="Username" value="<?=set_value('edit_username', $edit_username)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Email
					<span class="errors"><?//TODO=form_error('edit_email')?></span>
					<input type="text" name="edit_email" maxlength="255" class="form-control" placeholder="your@email.com" value="<?=set_value('edit_email', $edit_email)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Language
					<span class="errors"><?//TODO=form_error('edit_language')?></span>
					<select name="edit_language" class="form-control">
						<?php foreach($languages as $language): ?>
							<?php var_dump($language); ?>
							<option value="<?=$language?>" <?php if(set_value('edit_language', $edit_language) == $language) echo 'selected';?>><?=$language?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>
			<div class="form-group">
				<label>Community
					<span class="errors"><?//TODO=form_error('edit_community')?></span>
					<select id="edit_community" name="edit_community" class="form-control">
						<option value="0">None</option>
						<?php $selectedCommunity = 0; ?> <!-- pour voir si on doit cacher tout de suite le champ du token -->
						<?php foreach($communities as $community): ?>
							<option value="<?=$community->idCommunity?>" <?php if(set_value('edit_community', ($edit_community === null ? "" : $edit_community)) == $community->idCommunity) {echo 'selected'; $selectedCommunity = $community->idCommunity;}?>><?=$community->name?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>
			<div class="form-group">
				<label id="communityToken"<?php if($selectedCommunity == 0): ?> style="display: none;"<?php endif; ?>>Community token
					<span class="errors"><?//TODO=form_error('edit_community_token')?></span>
					<input type="text" name="edit_community_token" maxlength="45" class="form-control" placeholder="Community token" value="<?=set_value('edit_community_token')?>">
				</label>
				<p>Please note that if you change your community, all duels you have already done will still count for the previous community you were a member of.</p>
			</div>
			<div class="form-group">
				<h2>Password</h2>
				<p>Leave blank unless you want to change it.</p>
			</div>
			<div class="form-group">
				<label>Password
					<span class="errors"><?//TODO=form_error('edit_password')?></span>
					<input type="password" name="edit_password" maxlength="45" class="form-control" placeholder="Password" value="<?=set_value('edit_password')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Retype password
					<input type="password" name="edit_password_verif" maxlength="45" class="form-control" placeholder="Password" value="<?=set_value('edit_password')?>">
				</label>
			</div>
			
			
			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url()?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
			<p>Upon submitting, you will be logged out so your changes can take effect when you log back in.</p>
		</div>
	</div>
</form>
<script> 
	$(function() { 
		$('#edit_community').change( function() { 
			if($(this).val() == '0') 
				$('#communityToken').hide(400); 
			else 
				$('#communityToken').show(400); 
		}); 
	}); 
</script> 
