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
		<div class="player duelPlayer fullWidth">
			<object type="application/x-shockwave-flash" data="<?=asset_url()?>swf/GameMusicEmu.swf" style="margin-left: 10px; width:180px; height:70px;" id="spcplayer">
				<param name="flashvars" value="showPosition=1&showSeekBar=0&showVolumeBar=1"/>
				<param name="wmode" value="transparent" />
			</object>
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
		<button class="btn btn-primary btn-lg fullWidth" id="btnPlayTrackA">Play mistery track A</button>
	</div>
	<div class="grid_9 prefix_2 suffix_3">
		<button class="btn btn-primary btn-lg fullWidth" id="btnPlayTrackB">Play mistery track B</button>
	</div>
</div>
<div class="container_16">
	<div class="grid_7 prefix_4">
		<p class="duel-message" id="enough-a">Got enough!</p>
	</div>
	<div class="grid_9 prefix_3 suffix_3">
		<p class="duel-message" id="enough-b">Got enough!</p>
	</div>
</div>

<div class="container_16">
	<div class="grid_7 prefix_3">
		<div class="checkbox" id="shit-a-group">
			<label><input type="checkbox" id="shitA"> This track is shit! D:</label>
		</div>
	</div>
	<div class="grid_9 prefix_2 suffix_3">
		<div class="checkbox" id="shit-b-group">
			<label><input type="checkbox" id="shitB"> This track is shit! D:</label>
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
	<div>
		<div class="grid_7 prefix_3">
			<p>Previous track A was: <br /><span id="lastTrack-a-title">???</span></p>
		</div>
		<div class="grid_9 prefix_2 suffix_3">
			<p>Previous track B was: <br /><span id="lastTrack-b-title">???</span></p>
		</div>
	</div>

	<div class="grid_7 prefix_3">
		<div class="btn btn-xs btn-default" onclick="addToPlaylistDialog([previousTracks.a.idTrack]);">Add to playlist...</div>
	</div>
	<div class="grid_9 prefix_2 suffix_3">
		<div class="btn btn-xs btn-default" onclick="addToPlaylistDialog([previousTracks.b.idTrack]);">Add to playlist...</div>
	</div>
</div>

<div id="dialog-vote" title="Cast a vote">
	<ul>
		<li id="dialog-shitA">Track A is shit</li>
		<li id="dialog-shitB">Track B is shit</li>
		<li id="dialog-winnerA"><b>Winner is track A</b></li>
		<li id="dialog-winnerB"><b>Winner is track B</b></li>
	</ul>
	<p>Are you sure?</p>
</div>

<div id="dialog-nomore" title="What have you done!">
	<p>There are no more duels! You've taken all of them, you robot!</p>
</div>

<div id="dialog-disclaimer" title="HOW IT WORKS">
	<p>Here's how it works: grabe those nipples and have them steamin'. Then lick an elbow so to have your neighbour sream in agony while he smoc in your window.</p>
	<p>Please, before you procede, know the following:</p>
	<ul>
		<li>Once submitted, votes can <b>NOT</b> be canceled</li>
		<li>Every single vote counts</li>
		<li>Take this seriously</li>
		<li>Have fun</li>
	</ul>
</div>

<script type="text/javascript" src="<?=asset_url()?>js/duelz.js"></script>
