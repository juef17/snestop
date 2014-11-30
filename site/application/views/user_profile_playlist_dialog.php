<style>
	.dialog-tracks tr {
		padding: 3px 0;
	}

	.dialog-tracks td {
		padding: 0 5px;
	}
</style>

<h2><?=$playlist->name?></h2>
<?php if(count($tracks) == 0): ?>
	Empty
<?php else: ?>
	<table class="dialog-tracks">
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
<?endif;?>
