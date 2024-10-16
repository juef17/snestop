<div class="container_12">
	<div class="grid_12">
		<h1>Duelz history</h1>
		<p>In no specific order:</p>
	</div>
</div>

<div style="background-color: #dddddd;" class="container_16">
	<div class="grid_6 columnheader">
		<p>Winner</p>
	</div>
	<div class="grid_6 columnheader">
		<p>Loser</p>
	</div>
</div>

<?php if(count($duelResults) == 0): ?>
<div class="container_12">
	<div class="grid_12">
		<p>No duelz yet!</p>
	</div>
</div>
<?php endif; ?>

<?php $b = TRUE; foreach($duelResults as $result): ?>
	<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_16">
		<div class="grid_6">
			<p><a href="<?=base_url()?>/index.php/game/index/<?=$result->idGameWon?>/<?=$result->idTrackWon?>"><?=$result->gameTitleWon?> - <?=$result->trackTitleWon?></a></p>
		</div>
		<div class="grid_6">
			<p><a href="<?=base_url()?>/index.php/game/index/<?=$result->idGameLost?>/<?=$result->idTrackLost?>"><?=$result->gameTitleLost?> - <?=$result->trackTitleLost?></a></p>
		</div>
	</div>
<?php endforeach; ?>
