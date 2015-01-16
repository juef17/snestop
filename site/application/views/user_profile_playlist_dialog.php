<?php if($loggedUser && count($tracks) > 0):?>
	<div style="margin-bottom: 15px;">
		<a class="btn btn-primary" title="Load the playlist in the player" onclick="loadPlaylist(<?=$playlist->idPlaylist?>);">Play playlist</a>
		<div class="btn btn-default" title="Take a copy of this playlist as your own" onclick="createPlayList(<?=$playlist->idPlaylist?>);">Copy...</div>
	</div>
<?php elseif(count($tracks) > 0): ?>
	<p>Login for more player controls!</p>
<?php endif; ?>
						
<?php if(count($tracks) == 0): ?>
	Empty
<?php else: ?>
	<table style="width:100%;">
		<tr style="background-color: #dddddd">
			<th>&nbsp;</th>
			<th>#</th>
			<th>Game</th>
			<th>Track</th>
		</tr>
		<?php $b = true; foreach($tracks as $track): $b = !$b;?>
			<tr <?php if($b):?> style="background-color: #dddddd" <?php endif;?>>
				<td><a href="#!" onclick="playTrack(<?=$track->idTrack?>);"><img src="<?=asset_url() . 'images/play.png'?>" /></a></td>
				<td><?=$track->position?></td>
				<td><a href="<?=base_url()?>index.php/game/index/<?=$track->idGame?>"><?=$track->gameTitleEng?></a></td>
				<td><?=$track->title?></td>
			</tr>
		<?php endforeach;?>
	</table>
<?php endif; ?>
