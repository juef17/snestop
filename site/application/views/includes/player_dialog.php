<div style="display: none;" id="player-dialog" title="SPC Player">
	<object type="application/x-shockwave-flash" data="<?=asset_url()?>swf/GameMusicEmu.swf" style="width:280px; height:120px;" id="spcplayer">
		<param name="flashvars" value="showSeekBar=1"/>
		<param name="wmode" value="transparent" />
	</object>
	<input type="hidden" id="fileUrl" />
	<input type="hidden" id="length" />
	<input type="hidden" id="fadeLength" />
</div>

<script>
	function startPlayer(spcUrl, length, fade, title) {
		if($('#player-dialog').is(':visible')) {
			setTitle(title);
			playFile(spcUrl, length, fade);
		} else {
			playerDialog(spcUrl, length, fade, title);
		}
	}
	
	function playerDialog(spcUrl, length, fade, title) {
		$('#player-dialog #fileUrl').val(spcUrl);
		$('#player-dialog #length').val(length);
		$('#player-dialog #fadeLength').val(fade);
		
		$('#player-dialog').dialog({
			width: 320,
			modal: false,
			resizable: false,
			dialogClass: 'player',
			title: title
		});
	}

	function setTitle(title) {
		$('#player-dialog').dialog('option', 'title', title);
	}
	
	function songEnded() {
		//alert('NAMOUNNE, LA TOUNE EST FINIE!');
	}

	function playerInitialized() {
		var spc = $('#player-dialog #fileUrl').val();
		var length = $('#player-dialog #length').val();
		var fade = $('#player-dialog #fadeLength').val();
		playFile(spc, length, fade);
	}

	function playFile(fileUrl, length, fade) {
		var val = fileUrl + '?' +
			length + '?' +
			fade;
		$('#spcplayer')[0].playUrl(val);
	}
</script>
