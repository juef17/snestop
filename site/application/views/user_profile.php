<?php if($user == NULL): ?>
	<div class="container_12">
		<div class="grid_12">
			<h1>User not found</h1>
		</div>
	</div>
<?php else: ?>
	<div class="container_12">
		<div class="grid_12">
			<h1><?=$user->userName?>'s profile</h1>
		</div>
	</div>

	<div class="container_12">
		<div class="grid_12">
			<h3>About</h3>
		</div>
	</div><div class="container_12">
		<div class="grid_12">
			<div>
				<b>Language: </b><?=$user->language?>
			</div>
			<div>
				<b>Community: </b><?= ($user->communityName == NULL) ? 'None' : '<a href="' . $user->communityURL . '">' . $user->communityName . '</a>'?>
			</div>
			<div>
				<b>Registration date: </b><?=$user->registrationDate?>
			</div>
		</div>
	</div>
	
	<div class="container_12">
		<div class="grid_12">
			<h3>Shared playlists</h3>
		</div>
	</div>
	<?php if (count($playlists) == 0): ?>
		<div class="container_12">
			<p class="grid_12">None</p>
		</div>
	<?php else: ?>
		<div class="container_16">
			<div id="accordion-shared-playlists" class="grid_16">
				<?php foreach($playlists as $playlist): ?>
					<h3><?=$playlist->name?></h3>
					<div>
						<input type="hidden" value="<?=$playlist->idPlaylist?>" />
						<div id="user-playlist"><!-- AJAX loaded content -->allo</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div id="dialog-playlist"><!--Ajax loaded content--></div>
		<script>
			$(function() {
				$('#accordion-shared-playlists').accordion({
					heightStyle: 'content',
					collapsible: true,
					active: false,
					beforeActivate: function(e, ui) {
						if(ui.newPanel) {
							var idPlaylist = ui.newPanel.find('input').val();
							var contentPanel = ui.newPanel.find('#user-playlist');
							contentPanel.html('<img style="height: 64px; width: 64px; margin: 0 auto; display: block;" src="<?=asset_url()?>images/wait.gif" />');
							$.get( '<?=base_url()?>index.php/user_profile/playlistDetails/' + idPlaylist, function(data) {
								contentPanel.fadeOut(function() {
									contentPanel.html(data);
									contentPanel.fadeIn();
								});
							});
						}
						if(ui.oldPanel) {
							ui.oldPanel.find('#user-playlist').fadeOut();
						}
					},
					activate: function (e, ui) {
						if(ui.oldPanel) {
							ui.oldPanel.find('#user-playlist').empty();
							ui.oldPanel.find('#user-playlist').show();
						}
					}
				});
			});
		</script>
	<?php endif; ?>

	<div class="container_12">
		<div class="grid_12">
			<h3>Reviews</h3>
		</div>
	</div>
	<?php if (count($reviews) == 0): ?>
		<div class="container_12">
			<p class="grid_12">None</p>
		</div>
	<?php else: ?>
		<div class="container_12" style="background-color: #dddddd;">
			<p class="grid_1 columnheader"><!--view--></p>
			<p class="grid_3 columnheader">Game</p>
			<p class="grid_3 columnheader">Track</p>
		</div>
		<?php $b = TRUE; foreach($reviews as $review): ?>
			<div <?php if($b = !$b): ?> style="background-color: #dddddd;" <?php endif; ?> class="container_12">
				<button class="grid_1 btn btn-xs btn-default" onclick="showReview(<?=$review->idTrack?>)">View</button>
				<a class="grid_3" href="<?=base_url()?>index.php/game/index/<?=$review->idGame?>"><?=$review->titleEng?></a>
				<p class="grid_3"><?=$review->title?></p>
			</div>
			<div id="review-dialog_<?=$review->idTrack?>" style="display: none" title="<?=$review->titleEng?> - <?=$review->title?>">
				<?=$review->text?>
			</div>
		<?php endforeach; ?>

		<script>
			function showReview(idTrack) {
				$('#review-dialog_' + idTrack).dialog({
					modal: true,
					width: 800,
					height: 400
				});
			}
		</script>
	<?php endif; ?>

	<div class="container_12">
		<div class="grid_12">
			<h3>Duelz</h3>
		</div>
	</div>
	<div class="container_12">
		<p class="grid_12"><?= $nbDuelz ?> duelz taken</p>
	</div>
<?php endif; ?>

