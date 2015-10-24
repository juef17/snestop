<style>
	#player-dialog #title-current {
		margin: 0 0 5px 0;
		overflow: hidden;
		background: #111111;
		white-space: nowrap;
		border-radius: 3px;
		padding: 0 4px;
	}

	#player-dialog #title-current a {
		text-decoration: none;
		outline: 0;
		font-size: 0.6em;
		color: #dddddd; !important
	}

	#player-dialog #wait {
		display: none;
		position: absolute;
		bottom: 0;
		right: 0;
		width: 40px;
	}
</style>

<div style="display: none; overflow: visible" id="player-dialog" class="noselect" title="SPC Player">
	<div id="title-current" title="No track loaded"><a href="#!">No track loaded</a></div>
	<div style="display: inline">
		<div class="tv tv-small" id="playerScreenshot"></div>
	</div>
	<div style="display: inline-block; position: relative;">
		<object type="application/x-shockwave-flash" data="<?=asset_url()?>swf/GameMusicEmu.swf" style="width:180px; height:80px;" id="spcplayer">
			<param name="flashvars" value="showPosition=1&showSeekBar=1&showVolumeBar=1"/>
			<param name="wmode" value="transparent" />
		</object>
		<input type="hidden" id="deferred-idTrack" />
		<img id="wait" src="<?=asset_url()?>images/wait.gif" />
	</div>
	<?php if($loggedUser): ?>
		<input type="checkbox" id="player-randomize" <?=$loggedUser->randomize ? 'checked' : ''?>>
			<label title="Randomize playlist" for="player-randomize" class="label-checkbox" style="position: relative">
				<span class="fa fa-random"></span>
			</label>
		<input type="checkbox" id="player-loop" readonly <?=$loggedUser->loop ? 'checked' : ''?>>
			<label title="Loop track" for="player-loop" class="label-checkbox">
				<span class="fa fa-repeat"></span>
				<span id="loopSingleBadge" class="badge">1</span>
			</label>
		<select id="player-playlistcombo" style="display: inline-block; width: 90%">
			<!--ajax loaded content-->
		</select>
		<button id="player-expandPlaylist" class="btn btn-xs"><span class="fa fa-angle-double-down"></span></button>
		<div style="display: none; max-height: 200px;" id="player-playlist">
			<!--ajax loaded content-->
		</div>
	<?php endif; ?>
</div>

<div id="playlist-deleteConfirmation" style="display: none;">
	<span>Are you sure you want to delete this playlist?</span>
</div>

<script type="text/javascript" src="<?=asset_url()?>js/player.js"></script>
<?php if($loggedUser): ?>
	<script>
		playerLoopMode = <?= $loggedUser->loop ?>;
	</script>
<?php endif; ?>
