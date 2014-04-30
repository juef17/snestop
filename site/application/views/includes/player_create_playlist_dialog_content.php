<form method="post" accept-charset="utf-8" id="createPlaylist-form">
	<input type="hidden" id="hasErrors" value="<?=validation_errors() != '' ? 1 : 0?>" />
	<input type="hidden" id="idPlaylist" value="<?=isset($idPlaylist) ? $idPlaylist : -1?>" />
	<div class="form-group">
		<label>Name
			<span class="errors"><?=validation_errors()?></span>
			<input type="text" id="name" name="name" maxlength="45" class="form-control" placeholder="Name" value="<?=set_value('name')?>" />
		</label>
	</div>
	<div class="form-group">
		<label>Public
			<span class="errors"><?=form_error('public')?></span>
			<input type="checkbox" id="public" name="public" value="public" />
		</label>
	</div>
</form>
