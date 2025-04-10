<form method="post" accept-charset="utf-8" id="createPlaylist-form">
	<input type="hidden" id="hasErrors" value="<?/*=validation_errors() != '' ? 1 : 0 TODO*/ ?>" />
	<input type="hidden" id="idPlaylist" value="<?=isset($idPlaylist) ? $idPlaylist : -1?>" />
	<input type="hidden" id="source" name="source" value="<?=set_value('source')?>" />
	<div class="form-group">
		<label>Name
			<span class="errors"><?/*=validation_errors()TODO*/ ?></span>
			<input type="text" id="name" name="name" maxlength="45" class="form-control" placeholder="Name" value="<?=set_value('name')?>" />
		</label>
	</div>
	<div class="form-group">
		<label>Public
			<span class="errors"><?/*=form_error('public')TODO*/ ?></span>
			<input type="checkbox" id="public" name="public" value="public" <?=set_value('public') == 'public' ? 'checked' : '' ?>/>
		</label>
	</div>
</form>

<script>
	function clearNewPlaylistForm() {
		$('#createPlaylist-form #hasErrors').val('');
		$('#createPlaylist-form #idPlaylist').val('-1');
		$('#createPlaylist-form #source').val('');
		$('#createPlaylist-form .errors').html('');
		$('#createPlaylist-form #name').val('');
		$('#createPlaylist-form #public').prop('checked', false);
	}
</script>
