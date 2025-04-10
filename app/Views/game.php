<style>
	#wait {
		display: block;
		margin-left: auto;
		margin-right: auto;
	}
</style>

<?php if($game == NULL): ?>
	<div class="container_12">
		<div class="grid_12">
			<h1>Game not found</h1>
		</div>
	</div>
<?php else: ?>
	<div class="container_12">
		<div class="grid_12">
			<h1><?=$game->titleEng?><br /><span style="font-style: italic; font-size: 0.8em;"><?=$game->titleJap?></span></h1>
		</div>
	</div>

	<div class="container_12">
		<div class="grid_4">
			<?php if($loggedUser && $loggedUser->isAdmin):?><a href="#!" onclick="showUploadScreenshotDialog(<?=$game->idGame?>, 0); return false;">
			<?php elseif($loggedUser && !$game->isScreenshotSet):?><a href="<?=base_url()?>request_screenshot_game/index/<?=$game->idGame?>"><?php endif;?>
			<div class="tv" style="position: relative; background-image: url('<?=$game->isScreenshotSet ? asset_url() . "images/screenshots/game/{$game->idGame}.png" : asset_url() . 'images/en/no_title_ss.png'?>');">
				<?php if($loggedUser && $loggedUser->isAdmin && $game->isScreenshotSet):?><img id="unset-screenshot" style="position: absolute; top: 24px; right: 24px; width: 24px; height: 24px;" src="<?=asset_url()?>images/delete.png" onclick="unsetScreenshot(<?=$game->idGame?>, 0);"/><?php endif;?>
			</div>
			<?php if($loggedUser && (!$game->isScreenshotSet || $loggedUser->isAdmin)):?></a><?php endif;?>
		</div>
		<div class="grid_8">
			<div>
				<?php if(isset($game->rsnFileURL)): ?>
					<a href="<?=asset_url() . 'rsn/' . rawurlencode($game->rsnFileURL)?>"><img src="<?=asset_url() . 'images/download.png'?>" /> Download soundtrack in RSN format</a>
				<?php endif; ?>
			</div>
			<div>
				<table>
					<tr>
						<td>Composer(s):</td>
						<?php if(count($composers) > 0): ?>
							<td><?=implode('<br />', $composers)?></td>
						<?php else: ?>
							<td>Unknown</td>
						<?php endif; ?>
					</tr>
				</table>
				<?php if($loggedUser): ?>
					<a href="<?= base_url() ?>request_mistake?game=<?=$game->idGame?>">Help improve this information</a>
				<?php else: ?>
					Log in to help improve this information
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="container_12">
		<div class="grid_12">
			<h2>Tracks</h2>
			<div id="filters">
				<div class="checkbox">
					<label>
						<input type="checkbox" id="showNormalTracks" checked> Normal tracks
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" id="showJingles"> Jingles
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" id="showSoundEffects"> Sound effects
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" id="showVoiceEffects"> Voices
					</label>
				</div>
			</div>
			<?php if($loggedUser): ?>
				<p>
					<div class="btn btn-xs btn-default" id="btnPlayAll" onclick="loadPlaylist(gameTracks);">Play all tracks</div>
				</p>
				<p>
					<div class="btn btn-xs btn-default" id="btnAddAllToPlaylist" onclick="addToPlaylistDialog(gameTracks);">Add all tracks to playlist...</div>
				</p>
			<?php endif; ?>
			<?php if($loggedUser && $loggedUser->isAdmin): ?>
				<div>
					<a href="<?=base_url()?>tracks_dashboard/index/<?=$game->idGame?>">Open tracks dashboard</a>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div style="background-color: #dddddd;" class="container_16">
		<p class="grid_1 columnheader">#</p>
		<p class="grid_1 columnheader">&nbsp;</p><!-- play -->
		<p class="grid_3 columnheader">Title</p>
		<p class="grid_1 columnheader">Length</p>
		<p class="grid_1 columnheader"></p>
		<p class="grid_3 columnheader">Composer(s)</p>
		<p class="grid_1 columnheader">SPC</p>
		<?php if($loggedUser): ?>
			<p class="grid_2 columnheader">My playlists</p>
		<?php endif; ?>
	</div>
	
	<div id="tracks-grid" style="display:none;"></div>
	<img id="wait" src="<?=asset_url()?>images/wait.gif" />
	

	<script>
		function detailsDialog(idTrack) {
			$('#dialog-details_' + idTrack).dialog({
				height: 500,
				width: 700,
				modal: true,
				resizable: false,
				show: { effect: 'puff', duration: 200 },
				hide: { effect: 'puff', duration: 200 },
				buttons: {
					Ok: function() {
						$(this).dialog('close');
					}
				}
			});
			$('#dialog-details_' + idTrack + ' #unset-screenshot').click(function(event) { event.stopPropagation(); });
			$.getJSON(
				'<?=base_url()?>game/getReviewsForTrack/' + idTrack,
				function(data) {
					var reviews = '';
					$.each(data, function (index, review) {
						reviews += '<div style="background-color: #dddddd; padding: 0 5px; margin: 2px 0">';
						reviews += '<h4>Review by <a href="<?=base_url()?>user_profile/index/' + review.userName + '">' + review.userName + '</a></h4>';
						reviews += '<p>' + review.text + '</p>';
						reviews += '</div>';
					});
					$('#dialog-details_' + idTrack + ' #reviews-container').html(reviews);
				}
			);
		}
	</script>

	<?php if($loggedUser && $loggedUser->isAdmin): ?>
		<?php require_once(views_dir() . 'includes/screenshot_upload.php'); ?>
		<script>
			$(function() {
				$('#unset-screenshot').click(function(event) { event.stopPropagation(); });
			});

			function unsetScreenshot(id, type) {
				if(confirm('Are you sure to unset this screenshot?')) {
					$.post('<?=base_url()?>screenshot_request_dashboard/unsetScreenshot',
						{type: type, id: id },
						function(data) {
							if(data.success)
								location.reload();
							else
								alert(data.message);
						},
						'json'
					);
				}
			}
		</script>
	<?php endif; //loggedUserIsAdmin ?>

	<script>
		$(function() {
			loadTracksGrid();
			$('#filters input').change(loadTracksGrid);
		});

		function loadTracksGrid() {
			hideTracksGrid(function() {
				var normalTracks = $('#showNormalTracks').is(':checked') ? 1 : 0;
				var jingles = $('#showJingles').is(':checked') ? 1 : 0;
				var sfx = $('#showSoundEffects').is(':checked') ? 1 : 0;
				var vfx = $('#showVoiceEffects').is(':checked') ? 1 : 0;

				var url = '<?=base_url()?>game/getTracks/<?=$game->idGame?>/';
				url += normalTracks + '/' + jingles + '/' + sfx + '/' + vfx;
				
				$('#tracks-grid').load(url, function() {
					<?php if($idTrack): ?>
						detailsDialog(<?=$idTrack?>);
					<?php endif; ?>
					<?php if($loggedUser): ?>
						$('#btnAddAllToPlaylist').attr('disabled', gameTracks.length == 0);
					<?php endif; ?>
					showTracksGrid();
				});
			});
		}

		function shareTrack(idTrack) {
			$('#shareTrack_' + idTrack).toggle(50);
			$('#shareTrack_' + idTrack + ' input').focus();
			$('#shareTrack_' + idTrack + ' input')[0].select();
		}

		function showTracksGrid() {
			$('#wait').fadeOut();
			$('#tracks-grid').slideDown();//.show('slide', { direction: 'up'});
		}

		function hideTracksGrid(callback) {
			$('#wait').fadeIn();
			$('#tracks-grid').slideUp(callback);//.hide('slide', { direction: 'up'});
		}
	</script>
	
<?php endif; //$game == null ?>
