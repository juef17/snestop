<?php
	if(isset($currentPage)):
		$menu = '';
		if(count($games) > 0) {
			$menuItems = array_merge(array('#'), range('A', 'Z'));
			foreach($menuItems as $p) {
				if($p == $currentPage || $p === '#' && $currentPage === 'numbers')
					$menu .= '<span style="font-weight:bolder; font-size:200%;">' . $p . '</span>&nbsp;&nbsp;';
				else
					$menu .= '<a href="' . base_url() . 'index.php/search/browse/0/' . ($p == '#' ? 'numbers' : $p) . '">' . $p . '</a>&nbsp;&nbsp;';
			}
		}
?>
	<div class="container_12">
		<h1 class="grid_12">Browse games</h1>
	</div>
	<div class="container_12">
		<p style="margin: 5px 0;"><?=$menu?></p>
	</div>

<?php else: //isset($currentPage) ?>
	<div class="container_12">
		<h1 class="grid_12">Search results</h1>
	</div>
	<div class="container_12">
		<h4 class="grid_12">
			<?php if(count($games) < 150): ?>
				<?=count($games)?> game(s) matching '<?=htmlentities($searchString)?>'
			<?php else: ?>
				Over <?=count($games)?> game(s) matching '<?=htmlentities($searchString)?>'. Please refine your criteria.
			<?php endif; ?>
		</h4>
	</div>
<?php endif; //isset($currentPage) ?>

<?php if(count($games) == 0): ?>
	<div class="container_12">
		<p class="grid_12">None</p>
	</div>
<?php else: ?>
	<div style="background-color: #dddddd;" class="container_16">
		<p class="grid_2 columnheader"><!-- screenshot --></p>
		<p class="grid_6 columnheader">Title (Eng)</p>
		<p class="grid_6 columnheader">Title (Jap)</p>
	</div>
	<?php $b = TRUE; foreach($games as $game): ?>
		<div style="border-bottom: 1px; <?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_16">
			<?php if($game->isScreenshotSet): ?>
				<p class="grid_2"><img src="data:image/jpg;base64,<?=thumbnail($game->idGame)?>" /></p>
			<?php else: ?>
				<p class="grid_2"><img style="width: 64px;" src="<?=asset_url()?>images/en/no_title_ss.png" /></p>
			<?php endif; ?>
			<a class="grid_6" href="<?=base_url()?>index.php/game/index/<?=$game->idGame?>"><?=$game->titleEng?></a>
			<a class="grid_6" href="<?=base_url()?>index.php/game/index/<?=$game->idGame?>"><?=$game->titleJap?></a>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<?php if(isset($currentPage)): ?>
	<div class="container_12">
		<p style="margin: 5px 0;"><?=$menu?></p>
	</div>
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
