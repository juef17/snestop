<style>
	.duelPlayer {
		padding: 10px;
		border-radius: 4px;
		display: inline-block;
		box-shadow: 4px 4px 8px 2px #999;
	}

	.fullWidth {
		width: 100%;
	}

	.blank {
		height: 40px;
	}

	.duel-message {
		font-weight: bold;
		opacity: 0;
	}
	
	.shitgroup {
		opacity: 0;
	}
	
	.voting-tools, .previous-tracks {
		display: none;
	}

	#player-message {
		text-align: center;
		width: 100%;
	}
</style>

<div class="container_16">
	<div class="grid_16">
		<h1>The duelz</h1>
	</div>
</div>

<div class="container_16">
	<div class="grid_16">
		<h2>You've taken <span id="nbDuelzTaken">0</span> duelz!</h2>
	</div>
</div>

<div class="container_16">
	<div class="grid_16 blank"></div>
</div>

<div class="container_16">
	<div class="grid_16 prefix_6 suffix_6">
		<div class="player duelPlayer">
			<?php require_once(views_dir() . 'includes/imospc.php'); ?>
			<button class="pause"><span class="fa fa-pause"></span></button>
		</div>
	</div>
</div>

<div class="container_16">
	<div class="grid_16 blank">
		<p class="duel-message" id="player-message">Playing: track <span id="current-track"></span></p>
	</div>
</div>

<div class="container_16">
	<div class="grid_7 prefix_3">
		<button class="btn btn-primary btn-lg fullWidth" id="btnPlayTrackA">Play mystery track A</button>
	</div>
	<div class="grid_9 prefix_2 suffix_3">
		<button class="btn btn-primary btn-lg fullWidth" id="btnPlayTrackB">Play mystery track B</button>
	</div>
</div>
<div class="container_16">
	<div class="grid_7 prefix_4">
		<p class="duel-message" id="enough-a">Heard enough!</p>
	</div>
	<div class="grid_9 prefix_3 suffix_3">
		<p class="duel-message" id="enough-b">Heard enough!</p>
	</div>
</div>

<div class="container_16">
	<div class="grid_7 prefix_3">
		<div class="checkbox shitgroup" id="shit-a-group">
			<label><input type="checkbox" id="shitA"> Don't play this track again</label>
		</div>
	</div>
	<div class="grid_9 prefix_2 suffix_3">
		<div class="checkbox shitgroup" id="shit-b-group">
			<label><input type="checkbox" id="shitB"> Don't play this track again</label>
		</div>
	</div>
</div>

<div class="container_16">
	<div class="voting-tools">
		<div class="grid_7 prefix_3">
			<button class="btn btn-success btn-lg fullWidth" id="btnWinTrackA">A winner is track A!</button>
		</div>
		<div class="grid_9 prefix_2 suffix_3">
			<button class="btn btn-success btn-lg fullWidth" id="btnWinTrackB">A winner is track B!</button>
		</div>
	</div>
</div>

<div class="container_16 previous-tracks">
	<div class="grid_16 blank"></div>
	<div class="clear"></div>
	<div class="grid_7 prefix_3">
		<p>Previous track A was: <br /><span id="lastTrack-a-title">???</span></p>
	</div>
	<div class="grid_9 prefix_2 suffix_3">
		<p>Previous track B was: <br /><span id="lastTrack-b-title">???</span></p>
	</div>
	<div class="clear"></div>

	<div class="grid_7 prefix_3">
		<div id="btn-addtoplaylist-a" class="btn btn-xs btn-default">Add to playlist...</div>
	</div>
	<div class="grid_9 prefix_2 suffix_3">
		<div id="btn-addtoplaylist-b" class="btn btn-xs btn-default">Add to playlist...</div>
	</div>
	<div class="clear"></div>

	<div class="grid_7 prefix_3" style="margin-top: 8px;">
		<div id="lastTrack-a-screenshot" class="tv" style="position: relative;"></div>
	</div>
	<div class="grid_9 prefix_2 suffix_3" style="margin-top: 8px;">
		<div id="lastTrack-b-screenshot" class="tv" style="position: relative;"></div>
	</div>
</div>

<div id="dialog-vote" title="Cast a vote">
	<ul>
		<li id="dialog-shitA">I don't want to hear Track A again</li>
		<li id="dialog-shitB">I don't want to hear Track B again</li>
		<li id="dialog-winnerA"><b>Winner is track A</b></li>
		<li id="dialog-winnerB"><b>Winner is track B</b></li>
	</ul>
	<p>Are you sure?</p>
</div>

<div id="dialog-nomore" title="What have you done!">
	<p>There are no more duels! You've taken all of them, you SNESaholic robot!</p>
</div>

<div id="dialog-disclaimer" title="HOW IT WORKS">
	<p>Here's the idea: each button on this page allows you to listen to one mystery SNES track. Listen to each of them for long enough, and choose your favorite!</p>
	<p>Please note:</p>
	<ul>
		<li>Take this seriously!</li>
		<li>Every single vote counts towards creating a Top SNES Tracks list.</li>
		<li>If you think a track has no business in such a list, please check the appropriate checkbox.</li>
		<li>Once submitted, votes can <b>NOT</b> be canceled or altered.</li>
	</ul>
	<p style="text-align: center; margin: 30px 0px 30px 0px;">Have fun!</p>
</div>

<script type="text/javascript" src="<?=asset_url()?>js/imospc.js"></script>
<script type="text/javascript" src="<?=asset_url()?>js/duelz.js"></script>
