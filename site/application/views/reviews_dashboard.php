<div class="container_12">
	<div class="grid_12">
		<h1>Reviews dashboard</h1>
		<label style="cursor: pointer;">
			<input type="checkbox" id="unapprovedonly" <?=$unapprovedOnly ? 'checked' : '' ?> />
			Unapproved only
		</label>
	</div>
</div>

<?php if(count($reviews) == 0): ?>
	<div class="container_12">
		<div class="grid_12">
			<p>None!</p>
		</div>
	</div>
<?php else: ?>
	<div style="background-color: #dddddd;" class="container_12">
		<p class="grid_1 columnheader"><!--view--></p>
		<p class="grid_2 columnheader">Username</p>
		<p class="grid_3 columnheader">Game</p>
		<p class="grid_3 columnheader">Track</p>
		<p class="grid_1 columnheader">Approved</p>
		<div class="grid_1 columnheader"><!--delete --></div>
	</div>

	<?php $b = TRUE; foreach($reviews as $review): ?>
		<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
			<button class="grid_1 btn btn-xs btn-default" onclick="showReviewDialog(<?=$review->idUser?>, <?=$review->idTrack?>)">View</button>
			<p class="grid_2"><?= $review->userName ?></p>
			<p class="grid_3"><?= $review->titleEng ?></p>
			<p class="grid_3"><?= $review->title ?></p>
			<div class="grid_1">
				<?php if($review->approved):?>
					<p>Yes</p>
				<?php else:?>
					<form action="<?=base_url()?>index.php/reviews_dashboard/approveReview" method="post">
						<input type="hidden" name="idUser" value="<?=$review->idUser?>"/>
						<input type="hidden" name="idTrack" value="<?=$review->idTrack?>"/>
						<button class="btn btn-xs btn-default" title="Approve this review" onclick="return confirm('Are you sure? This cannot be undone!');">No</button>
					</form>
				<?php endif?>
			</div>
			<div class="grid_1">
				<form action="<?=base_url()?>index.php/reviews_dashboard/deleteReview" method="post">
					<input type="hidden" name="idUser" value="<?=$review->idUser?>"/>
					<input type="hidden" name="idTrack" value="<?=$review->idTrack?>"/>
					<button class="btn btn-xs btn-danger" onclick="return confirm('Are you sure? This cannot be undone!');">Delete</button>
				</form>
			</div>
		</div>
	<?php endforeach; ?>

	<div id="review-dialog" style="display: none">
		<h4 id="review-dialog-gameandtrack"></h4>
		<h4 id="review-dialog-by"></h4>
		<textarea class="form-control" id="review-edit"></textarea>
	</div>
	
	<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
	<script>
		$(function() {
			$('#unapprovedonly').change(function() {
				if(this.checked)
					window.location.href = '<?=base_url()?>index.php/reviews_dashboard/index/1';
				else
					window.location.href = '<?=base_url()?>index.php/reviews_dashboard/index/0';
			});
		});
		function showReviewDialog(idUser, idTrack) {
			$.getJSON(
				'<?=base_url()?>index.php/reviews_dashboard/getReviewForEdit/' + idUser + '/' + idTrack,
				function(review) {
					$('#review-dialog-by').html('<a target="_blank" href="<?=base_url()?>index.php/user_profile/index/' + review.userName + '">Author: ' + review.userName + '</a>');
					$('#review-dialog-gameandtrack').html('<a target="_blank" href="<?=base_url()?>index.php/game/index/' + review.idGame + '">For: ' + review.titleEng + ' - ' + review.title + '</a>');
					$('#review-dialog').dialog({
						modal: true,
						title: 'Edit review',
						width: 800,
						height: 600,
						buttons: {
							Ok: function() { saveReview(idUser, idTrack); },
							Cancel: function() { $(this).dialog('close'); }
						}
					});
					if($('.nicEdit-main').length == 0)
						new nicEditor({fullPanel : true}).panelInstance('review-edit');
					nicEditors.findEditor('review-edit').setContent(review.text);
				}
			);
		}

		function saveReview(idUser, idTrack) {
			$.post(
				'<?=base_url()?>index.php/reviews_dashboard/editReview',
				{
					idUser: idUser,
					idTrack: idTrack,
					text: nicEditors.findEditor('review-edit').getContent()
				},
				function(data) {
					if(data.success) {
						window.location.reload();
					} else {
						alert(data.message);
					}
				},
				'json'
			);
		}
	</script>
<?php endif; ?>
