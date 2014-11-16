<div class="container_12">
	<div class="grid_12">
		<h1>Games dashboard</h1>
	</div>
</div>

<?php $menu = ''; if(count($games) > 0)
	for($p = 1; $p <= $nbPages; $p++)
		if($p == $currentPage)
			$menu .= '<span style="font-weight:bolder; font-size:200%;">' . $p . '</span>&nbsp;&nbsp;';
		else
			$menu .= '<a href="' . base_url() . 'index.php/games_dashboard/index/' . $p . '">' . $p . '</a>&nbsp;&nbsp;';
?>

<div class="container_12">
	<p style="margin: 5px 0;"><?=$menu?></p>
</div>

<div style="background-color: #dddddd;" class="container_16">
	<div class="grid_2 columnheader">
		<p>Title (Eng)</p>
	</div>
	<div class="grid_2 columnheader">
		<p>Title (Jap)</p>
	</div>
	<div class="grid_4 columnheader">
		<p>Screenshot</p>
	</div>
	<div class="grid_4 columnheader">
		<p>RSN</p>
	</div>
	<div class="grid_1 columnheader">
		<!--update -->
	</div>
	<div class="grid_1 columnheader">
		<!--delete -->
	</div>
	<div class="grid_1 columnheader">
		<!--tracks -->
	</div>
</div>

<div class="container_12">
	<div class="grid_2">
		<span class="errors"><?=form_error('game_titleeng')?>&nbsp;</span>
	</div>
	<div class="grid_2">
		<span class="errors"><?=form_error('game_titlejap')?>&nbsp;</span>
	</div>
	<div class="grid_4">
		<span class="errors">&nbsp;</span>
	</div>
	<div class="grid_4">
		<span class="errors"><?=form_error('game_rsn')?>&nbsp;</span>
	</div>
</div>

<div class="container_16">
	<?= form_open(base_url() . 'index.php/games_dashboard/add', array('id' => 'addGame')) ?>
		<div class="grid_2 columnheader">
			<input type="text" name="game_titleeng" placeholder="English title" maxlength="45" class="form-control" value="<?=set_value('game_titleeng')?>"/>
		</div>
		<div class="grid_2 columnheader">
			<input type="text" name="game_titlejap" placeholder="Japanese title" maxlength="45" class="form-control" value="<?=set_value('game_titlejap')?>"/>
		</div>
		<div class="grid_4 columnheader">
			&nbsp;
		</div>
		<div class="grid_4 columnheader">
			<input type="text" name="game_rsn" placeholder="http://" maxlength="255" class="form-control" value="<?=set_value('game_rsn')?>"/>
		</div>
		<div class="grid_1 columnheader">
			<div class="btn btn-xs btn-default" onclick="addGame.submit();">Add</div>
		</div>
	</form>
</div>

<?php if(count($games) == 0): ?>
<div class="container_12">
	<div class="grid_12">
		<p>No game yet!</p>
	</div>
</div>
<?php endif; ?>

<?php $b = FALSE; foreach($games as $game): ?>
	<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_16">
		<div class="grid_2">
			<span class="errors"><?=form_error("game_{$game->idGame}_titleeng")?>&nbsp;</span>
		</div>
		<div class="grid_2">
			<span class="errors"><?=form_error("game_{$game->idGame}_titlejap")?>&nbsp;</span>
		</div>
		<div class="grid_4">
			<span class="errors">&nbsp;</span>
		</div>
		<div class="grid_4">
			<span class="errors"><?=form_error("game_{$game->idGame}_rsn")?>&nbsp;</span>
		</div>
	</div>
	<div style="<?php if($b): ?> background-color: #dddddd;<?php endif; ?>" class="container_16">
		<?= form_open(base_url() . "index.php/games_dashboard/update/$currentPage", array('id' => 'update_' . $game->idGame)) ?>
			<input type="hidden" name="id" value="<?=$game->idGame?>" />
			<div class="grid_2">
				<input type="text" name="game_<?=$game->idGame?>_titleeng" maxlength="45" class="form-control" value="<?=set_value("game_{$game->idGame}_titleeng", $game->titleEng)?>" />
			</div>
			<div class="grid_2">
				<input type="text" name="game_<?=$game->idGame?>_titlejap" maxlength="45" class="form-control" value="<?=set_value("game_{$game->idGame}_titlejap", $game->titleJap)?>" />
			</div>
			<div class="grid_4">
				<?php if($game->isScreenshotSet): ?>
					<a href="<?=asset_url()?>images/screenshots/game/<?=$game->idGame?>.png"></a>
				<?php else: ?>
					No
				<?php endif; ?>
			</div>
			<div class="grid_4">
				<input type="text" name="game_<?=$game->idGame?>_rsn" maxlength="255" class="form-control" value="<?=set_value("game_{$game->idGame}_rsn", $game->rsnFileURL)?>" />
			</div>
			<div class="grid_1">
				<div class="btn btn-xs btn-default" onclick="update_<?=$game->idGame?>.submit();">Update</div>
			</div>
		</form>
		<div class="grid_1">
			<?= form_open(base_url() . 'index.php/games_dashboard/delete', array('id' => "delete_{$game->idGame}")) ?>
				<input type="hidden" name="id" value="<?=$game->idGame?>" />
				<div class="btn btn-xs btn-danger" onclick="confirmDelete(<?=$game->idGame?>);">Delete</div>
			</form>
		</div>
		<div class="grid_1">
			<a href="<?=base_url()?>index.php/tracks_dashboard/index/<?=$game->idGame?>">Tracks</a>
		</div>
	</div>
<?php endforeach; ?>

<p style="margin: 5px 0;"><?=$menu?></p>

<!-- delete dialog -->

<div style="display: none;" id="dialog-confirm-delete" title="Delete game?">
  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		Deleting a game will also delete all those related entries:
		<ul>
			<li>Tracks
				<ul>
					<li>Review
						<ul>
							<li>Votes</li>
						</ul>
					</li>
					<li>RatingCommunity</li>
					<li>RatingPersonal</li>
					<li>ShitTrack</li>
					<li>DuelResult</li>
					<li>TrackScreenshotRequest</li>
					<li>PlaylistItem</li>
				</ul>
			</li>
			<li>Screenshot requests</li>
		</ul>
	</p>
</div>
<script>
	function confirmDelete(idGame) {
		$('#dialog-confirm-delete').dialog({
			resizable: false,
			height: 400,
			width: 350,
			modal: true,
			buttons: {
				"Delete game": function() {
					$('#delete_' + idGame).submit();
					$(this).dialog("close");
				},
				Cancel: function() {
					$(this).dialog("close");
				}
			}
		});
	}
</script>
