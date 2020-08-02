<!-- Dialogs. Create them only once. -->
<div id="createPlaylist-dialog" style="display:none;">
	<?php require_once(views_dir() . 'includes/player_create_playlist_dialog_content.php')?>
</div>

<!-- add to playlist dialog -->
<div id="dialog-addToPlaylist" style="display: none;" title="Select a playlist">
	<table style="width: 100%">
		<tr>
			<td>
				<label><input type="radio" name="optradio" value="existing" id="optradio-existing" checked>Existing playlist:</label>
			</td>
			<td>
				<select id="playlistcombo" style="width: 100%">
					<!--ajax loaded content-->
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label><input type="radio" name="optradio" value="new" id="optradio-new">Create new:</label></td>
			<td><input id="newplaylistname" style="width: 100%" disabled /></td>
		</tr>
	</table>
</div>

<script type="text/javascript" src="<?=asset_url()?>js/playlists.js"></script>
