<?php foreach($playlistItems as $item):
	$text = $item->gameTitleEng . ' - ' . $item->title; ?>
	<li class="ui-state-default" title="<?=$text?>" id="<?=$item->idTrack?>">
	<?php if($playlistEditable): ?>
		<span class="fa fa-unsorted fa-lg"></span>
	<?php endif; ?>
	<span><?=$text?></span>
	<?php if($playlistEditable): ?>
		<img id="deleteItemButton" src="<?=asset_url()?>images/delete.png" onclick="deleteItem(this, <?=$item->idTrack?>);"/>
	<?php endif; ?>
	</li>
<?php endforeach; ?>
