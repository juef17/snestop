<div class="container_12">
	<div class="grid_12">
		<h1>Submit a track screenshot</h1>
	</div>
</div>

<?php if($track == null): ?>
	<h3>Track not found!</h3>
<?php else: ?>

	<?= form_open(base_url() . 'index.php/request_screenshot_track/submit') ?>
		<div class="container_12">
			<div class="grid_12 prefix_4 suffix_4 ">
				<div class="form-group">
					<label>Track
						<input type="hidden" name="idtrack" value="<?=$track->idTrack?>" />
						<p><?=$track->title?></p>
					</label>
				</div>
				<div class="form-group">
					<label>Link to screenshot
						<span class="errors"><?=form_error('screenshotUrl')?></span>
						<input type="text" name="screenshotUrl" maxlength="255" class="form-control" placeholder="http://" value="<?=set_value('screenshotUrl')?>">
					</label>
				</div>

				<button type="submit" class="btn btn-default">Submit</button>
			</div>
		</div>
	</form>
	
<?php endif; ?>
