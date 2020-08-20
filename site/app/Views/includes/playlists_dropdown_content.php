<?php if($mode == 1): ?>
	<option value="-1">Select a playlist</option>
	<option value="0">&lt;&lt;New playlist&gt;&gt;</option>
<?php endif; ?>

<?php foreach($playlists as $playlist): ?>
	<option value="<?=$playlist->idPlaylist?>"><?=$playlist->name?></option>
<?php endforeach; ?>
