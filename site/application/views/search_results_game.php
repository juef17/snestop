<div class="container_12">
	<h1 class="grid_12">Search results</h1>
</div>
<div class="container_12">
	<h4 class="grid_12">
		<?php if(count($games) < 150): ?>
			<?=count($games)?> game(s) matching '<?=$searchString?>'
		<?php else: ?>
			Over <?=count($games)?> game(s) matching '<?=$searchString?>'. Please refine your criterias.
		<?php endif; ?>
	</h4>
</div>

<?php if(count($games) == 0): ?>
	<div class="container_12">
		<p class="grid_12">None</p>
	</div>
<?php else: ?>
	<div style="background-color: #dddddd;" class="container_16">
		<p class="grid_2 columnheader"><!-- screenshot --></p>
		<p class="grid_4 columnheader">Title (Eng)</p>
		<p class="grid_4 columnheader">Title (Jap)</p>
	</div>
	<?php $b = TRUE; foreach($games as $game): ?>
		<div style="border-bottom: 1px; <?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_16">
			<?php if($game->isScreenshotSet): ?>
				<p class="grid_2"><img src="data:image/jpg;base64,<?=thumbnail($game->idGame)?>" /></p>
			<?php else: ?>
				<p class="grid_2"><img style="width: 64px;" src="<?=asset_url()?>images/en/no_title_ss.png" /></p>
			<?php endif; ?>
			<a class="grid_4" href="<?=base_url()?>index.php/game/index/<?=$game->idGame?>"><?=$game->titleEng?></a>
			<a class="grid_4" href="<?=base_url()?>index.php/game/index/<?=$game->idGame?>"><?=$game->titleJap?></a>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<script>
	$(function() {
		setSearchTarget(0);
	});
</script>

<?php
	function thumbnail($id) {
		$thumb = new Imagick(asset_url() . "images/screenshots/game/{$id}.png");
		$thumb->resizeImage(64, 56, Imagick::FILTER_POINT, 1, true);
		$retval = base64_encode($thumb->getImageBlob());
		$thumb->destroy();
		return $retval;
	}
?>
