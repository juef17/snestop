<div class="container_12">
	<h1 class="grid_12">Search results</h1>
</div>
<div class="container_12">
	<h4 class="grid_12">
		<?php if(count($tracks) < 150): ?>
			<?=count($tracks)?> tracks(s) matching '<?=htmlentities($searchString)?>'
		<?php else: ?>
			Over <?=count($tracks)?> tracks(s) matching '<?=htmlentities($searchString)?>'. Please refine your criteria.
		<?php endif; ?>
	</h4>
</div>

<?php if(count($tracks) == 0): ?>
	<div class="container_12">
		<p class="grid_12">None</p>
	</div>
<?php else: ?>
	<div style="background-color: #dddddd;" class="container_16">
		<p class="grid_2 columnheader"><!-- screenshot --></p>
		<p class="grid_4 columnheader">Title</p>
		<p class="grid_4 columnheader">Game</p>
	</div>
	<?php $b = TRUE; foreach($tracks as $track): ?>
		<div style="border-bottom: 1px; <?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_16">
			<?php if($track->isScreenshotSet): ?>
				<p class="grid_2"><img src="data:image/jpg;base64,<?=thumbnail($track->idTrack)?>" /></p>
			<?php else: ?>
				<p class="grid_2"><img style="width: 64px;" src="<?=asset_url()?>images/en/no_track_ss.png" /></p>
			<?php endif; ?>
			<a class="grid_4" href="<?=base_url()?>/index.php/game/index/<?=$track->idGame?>/<?=$track->idTrack?>"><?=$track->title?></a>
			<a class="grid_4" href="<?=base_url()?>/index.php/game/index/<?=$track->idGame?>"><?=$track->gameTitleEng?></a>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<script>
	$(function() {
		setSearchTarget(1);
	});
</script>

<?php
	function thumbnail($id) {
		// TODO j'avais un probl�me sur mon serveur, fopen voulait rien savoir de localhost, j'savais pas trop comment le r�gler fait que j'ai �t� l�che:
		$thumb = new Imagick("https://snestop.jerther.com/snestop/site/assets/" . "images/screenshots/track/{$id}.png");
		//$thumb = new Imagick(asset_url() . "images/screenshots/track/{$id}.png");
		$thumb->resizeImage(64, 56, Imagick::FILTER_POINT, 1, true);
		$retval = base64_encode($thumb->getImageBlob());
		$thumb->destroy();
		return $retval;
	}
?>
