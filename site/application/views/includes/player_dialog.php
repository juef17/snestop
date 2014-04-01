<div style="display: none;" id="player-dialog" title="SPC Player">
	<div style="display: inline">
		<div class="tv tv-small" id="playerScreenshot"></div>
	</div>
	<div style="display: inline-block;">
		<object type="application/x-shockwave-flash" data="<?=asset_url()?>swf/GameMusicEmu.swf" style="width:180px; height:80px;" id="spcplayer">
			<param name="flashvars" value="showPosition=1&showSeekBar=1&showVolumeBar=1"/>
			<param name="wmode" value="transparent" />
		</object>
		<input type="hidden" id="fileUrl" />
		<input type="hidden" id="length" />
		<input type="hidden" id="fadeLength" />
		<input type="hidden" id="screenshotUrl" />
	</div>
</div>

<script>
	function startPlayer(spcUrl, length, fade, title, screenshotUrl) {
		if($('#player-dialog').is(':visible')) {
			setTitle(title);
			playFile(spcUrl, length, fade, screenshotUrl);
		} else {
			playerDialog(spcUrl, length, fade, title, screenshotUrl);
		}
	}
	
	function playerDialog(spcUrl, length, fade, title, screenshotUrl) {
		$('#player-dialog #fileUrl').val(spcUrl);
		$('#player-dialog #length').val(length);
		$('#player-dialog #fadeLength').val(fade);
		$('#player-dialog #screenshotUrl').val(screenshotUrl);
		
		$('#player-dialog').dialog({
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
		var screenshotUrl = $('#player-dialog #screenshotUrl').val();
		playFile(spc, length, fade, screenshotUrl);
	}

	function setScreenshot(screenshotUrl) {
		if(screenshotUrl == null || screenshotUrl == '')
			screenshotUrl = '<?=asset_url()?>images/en/no_track_ss.png';
		
		$('#playerScreenshot').css('background-image', 'url(' + screenshotUrl + ')');
		//<div class="tv" style="display: inline-block; vertical-align: top; background-image: url('<?=$track->screenshotURL != NULL ? $track->screenshotURL : asset_url() . 'images/en/no_track_ss.png'?>');"></div>
	}

	function playFile(fileUrl, length, fade, screenshotUrl) {
		var val = fileUrl + '?' +
			length + '?' +
			fade;
		setScreenshot(screenshotUrl);
		$('#spcplayer')[0].playUrl(val);
	}
</script>
