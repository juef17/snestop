<div class="container_12">
	<h1 class="grid_12">Write a review</h1>
</div>
<div class="container_12">
	<h4 class="grid_12">For <?=$track->gameTitleEng?> - <?=$track->title?></h4>
</div>


<form action="<?=base_url()?>request_review/submit" method="post">
	<input type="hidden" name="track" value="<?=$track->idTrack?>" />
	<div class="container_12">
		<textarea class="grid_12 form-control" id="text" name="text"></textarea>
		<button type="submit" class="btn btn-default">Submit</button>
	</div>
</form>


<script src="<?=asset_url()?>js/nicEdit-latest.js" type="text/javascript"></script>
<script>
	$(function() {
		new nicEditor({fullPanel : true}).panelInstance('text');
	});
</script>
