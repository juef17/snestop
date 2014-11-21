<?php if($user == NULL): ?>
	<div class="container_12">
		<div class="grid_12">
			<h1>User not found</h1>
		</div>
	</div>
<?php else: ?>
	<div class="container_12">
		<div class="grid_12">
			<h1><?=$user->userName?>'s profile</h1>
		</div>
	</div>

	<div class="container_12">
		<div class="grid_12">
			<div class="form-group">
				<label>Language</label>
				<p><?=$user->language?></p>
			</div>
			<div class="form-group">
				<label>Community</label>
				<p><?= ($user->communityName == NULL) ? 'None' : '<a href="' . $user->communityURL . '">' . $user->communityName . '</a>'?></p>
			</div>
			<div class="form-group">
				<label>Registration date</label>
				<p><?=$user->registrationDate?></p>
			</div>
		</div>
	</div>
	<div class="container_12">
			<div class="grid_12">
				<h3>Shared playlists</h3>
			</div>
	</div>
	<?php if(!$isUserLogged): ?>
		<div class="container_16">
			<p class="grid_16">Log in to view <?=$user->userName?>'s playlists!</p>
		</div>
	<?php else: ?>
		<div class="container_16" style="background-color: #dddddd;">
			<p class="grid_4 columnheader">Title</p>
			<p class="grid_1">&nbsp;</p><!-- play -->
			<p class="grid_2 ">&nbsp;</p><!-- copy -->
		</div>
		<?php $b = TRUE; foreach($playlists as $playlist): ?>
			<div <?php if($b = !$b): ?> style="background-color: #dddddd;" <?php endif; ?> class="container_16">
				<a href="#!" onclick="playlistDialog(<?=$playlist->idPlaylist?>)">
					<p class="grid_4"><?=$playlist->name?></p>
				</a>
				<img class="grid_1" style="width: 24px; height: 24px; cursor: pointer;" title="Play the playlist" src="<?=asset_url() . 'images/play.png'?>" onclick="loadPlaylist(<?=$playlist->idPlaylist?>);" />
					<div class="grid_2 btn btn-xs btn-default" title="Take a copy of this playlist as your own" onclick="createPlayList(<?=$playlist->idPlaylist?>);">Copy...</div>
			</div>
		<?php endforeach; ?>


		<div id="dialog-playlist"><ol id="tracks"><!--Ajax loaded content--></ol></div>
		<script>
			function playlistDialog(idPlaylist) {
				$.getJSON(
					'<?=base_url()?>index.php/playlist/playlistDetails/' + idPlaylist + '/1',
					function(data) {
						var items = [];
						$.each(data, function(index, track) {
							items.push('<li><a href="<?=base_url()?>index.php/game/index/' + track.idGame + '">' + track.gameTitleEng + ' - ' + track.title + '</a></li>');
						});
						$('#tracks').html(items.join(''));
						$('#dialog-playlist').dialog({
							title: 'Tracks',
							modal: true,
							resizable: true,
							width: 600
						});
					}
				);
			}
		</script>
	<?php endif; ?>
<?php endif; ?>

