<div style="display: none; overflow: visible" id="player-dialog" title="SPC Player">
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
	<div>
		<select id="player-playlistcombo" style="display: inline-block; width: 90%"><!--ajax loaded content--></select>
		<button id="player-expandPlaylist" class="btn btn-xs"><span class="fa fa-angle-double-down"></span></button>
	</div>
	<div style="display: none; max-height: 200px;" id="player-playlist"><!--ajax loaded content--></div>
</div>

<!-- Dialogs. Create them only once. -->
<div id="createPlaylist-dialog" style="display:none;">
	<?php require_once(views_dir() . 'includes/player_create_playlist_dialog_content.php')?>
</div>

<div id="playlist-deleteConfirmation" style="display: none;">
	<span>Are you sure you want to delete this playlist?</span>
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

	function showPlayer() {
		var dialog = $('#player-dialog');
		
		if(! dialog.is(':visible')) {
			if(dialog.hasClass('ui-dialog-content'))
				dialog.dialog('open');
			else
				constructPlayerDialog();
		}
	}

	function constructPlayerDialog() {
		$('#player-dialog').dialog({
			modal: false,
			resizable: false,
			dialogClass: 'player'
		});
		$('#player-playlistcombo').menu();
		$('#player-playlistcombo').change(function(){
			playlistSelectionChanged($(this).val());
		});

		$('#player-expandPlaylist').click(function() {
			togglePlaylistVisibility();
		});
		refreshPlaylistsList();
	}

	function togglePlaylistVisibility() {
		if($('#player-playlist').is(':visible')) {
			$('#player-playlist').hide(200);
			$('#player-expandPlaylist span').removeClass('fa-angle-double-up');
			$('#player-expandPlaylist span').addClass('fa-angle-double-down');
		} else {
			$('#player-playlist').show(200);
			$('#player-expandPlaylist span').removeClass('fa-angle-double-down');
			$('#player-expandPlaylist span').addClass('fa-angle-double-up');
		}
	}
	
	function playerDialog(spcUrl, length, fade, title, screenshotUrl) {
		$('#player-dialog #fileUrl').val(spcUrl);
		$('#player-dialog #length').val(length);
		$('#player-dialog #fadeLength').val(fade);
		$('#player-dialog #screenshotUrl').val(screenshotUrl);
		
		showPlayer();
		setTitle(title);
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
	}

	function playFile(fileUrl, length, fade, screenshotUrl) {
		var val = fileUrl + '?' +
			length + '?' +
			fade;
		setScreenshot(screenshotUrl);
		$('#spcplayer')[0].playUrl(val);
	}

	function playTrack(idTrack) {
		$.getJSON(
			'<?=base_url()?>index.php/playlist/getTrack/' + idTrack,
			function(data) {
				if(!data['success']) {
					showMessageDialog(data['message']);
				} else {
					var track = data['success'];
					playFile('<?=asset_url()?>spc/' + track.spcURL, track.length, track.fade, track.screenshotURL);
				}
			}
		);
	}

	//Playlist management

	function createPlayList() {
		$('#createPlaylist-dialog').dialog({
			title: 'New playlist',
			modal: true,
			resizable: false,
			buttons: {
				Ok: function() {
					$('#createPlaylist-form').submit();
				}
			}
		});
		attachAjaxSubmitCallback();
	}

	function attachAjaxSubmitCallback() {
		$('#createPlaylist-form').submit(function() {
			$.post('<?=base_url()?>index.php/playlist/create',
			 $(this).serialize(),
			 function(data, textStatus) {
				$('#createPlaylist-dialog').html(data);
				if($('#createPlaylist-dialog #hasErrors').val() == '0') {
					refreshPlaylistsList(function() {
						selectPlaylist($('#createPlaylist-dialog #idPlaylist').val());
						$('#createPlaylist-dialog').dialog('close');
					});
				} else {
					attachAjaxSubmitCallback();
				}
			});
			return false;
		});
	}

	function refreshPlaylistsList(callback) {
		selectPlaylist(-1);
		$('#player-playlistcombo').load('<?=base_url()?>index.php/playlist/playlists/1', callback);
	}
	
	function selectPlaylist(idPlaylist) {
		$('#player-playlistcombo').val(idPlaylist);
		playlistSelectionChanged(idPlaylist); //event doesn't trigger otherwise...
	}

	function playlistSelectionChanged(idPlaylist) {
		if(idPlaylist == -1) {
			$('#player-playlist').hide(200);
			$('#player-expandPlaylist').prop('disabled', true);
		} else if(idPlaylist == 0) {
			$('#player-playlist').hide(200);
			$('#player-expandPlaylist').prop('disabled', true);
			createPlayList();
			$('#player-playlistcombo option[value=-1]').attr('selected', 'selected'); 
		} else {
			$('#player-playlist').load('<?=base_url()?>index.php/playlist/playlistDetails/' + idPlaylist, function() {
				bindPlaylistDetailsFunctions();
				$('#player-expandPlaylist').prop('disabled', false);
			});
		}
	}
</script>
