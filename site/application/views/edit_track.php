<div class="container_12">
	<div class="grid_12">
		<h1><?=$track->idTrack == 0 ? 'Create' : 'Edit'?> a track for <?=$gameTitle?></h1>
	</div>
</div>

<?= form_open(base_url() . "index.php/edit_track/submit/{$track->idTrack}") ?>
	<input type="hidden" name="id" value="<?=$track->idTrack?>">
	<input type="hidden" name="idGame" value="<?=$track->idGame?>">
	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Title
					<span class="errors"><?=form_error('title')?></span>
					<input type="text" name="title" maxlength="255" class="form-control" placeholder="Title" value="<?=set_value('title', $track->title)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Composer
					<span class="errors"><?=form_error('composer')?></span>
					<input type="text" name="composer" maxlength="45" class="form-control" placeholder="Composer" value="<?=set_value('composer', $track->composer)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Length
					<span class="errors"><?=form_error('length')?></span>
					<input type="text" name="length" maxlength="4" class="form-control" placeholder="Length in seconds" value="<?=set_value('length', $track->length)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Fade length
					<span class="errors"><?=form_error('fadeLength')?></span>
					<input type="text" name="fadeLength" maxlength="4" class="form-control" placeholder="Fade length in seconds" value="<?=set_value('fadeLength', $track->fadeLength)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Screenshot
					<?php if($track->isScreenshotSet): ?>
						<p><a href="<?=asset_url() . "images/screenshots/track/{$track->idTrack}.png"?>">Yes</a></p>
					<?php else: ?>
						<p>No</p>
					<?php endif; ?>
				</label>
			</div>
			<div class="form-group">
				<label>SPC URL
					<span class="errors"><?=form_error('spcURL')?></span>
					<input type="text" name="spcURL" maxlength="255" class="form-control" placeholder="http://" value="<?=set_value('spcURL', $track->spcURL)?>">
				</label>
			</div>
			<div class="form-group">
				<label>SPC encoded URL
					<span class="errors"><?=form_error('spcEncodedURL')?></span>
					<input type="text" name="spcEncodedURL" maxlength="255" class="form-control" placeholder="http://" value="<?=set_value('spcEncodedURL', $track->spcEncodedURL)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Glicko 2 RD
					<span class="errors"><?=form_error('glicko2RD')?></span>
					<input type="number" step="any" min="0" name="glicko2RD" maxlength="255" class="form-control" placeholder="Float value" value="<?=set_value('glicko2RD', $track->glicko2RD)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Glicko 2 rating
					<span class="errors"><?=form_error('glicko2rating')?></span>
					<input type="number" step="any" min="0" name="glicko2rating" maxlength="255" class="form-control" placeholder="Float value" value="<?=set_value('glicko2rating', $track->glicko2rating)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Glicko 2 sigma
					<span class="errors"><?=form_error('glicko2sigma')?></span>
					<input type="number" step="any" min="0" name="glicko2sigma" maxlength="255" class="form-control" placeholder="Float value" value="<?=set_value('glicko2sigma', $track->glicko2sigma)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Elo rating
					<span class="errors"><?=form_error('eloRating')?></span>
					<input type="number" step="any" min="0" name="eloRating" maxlength="255" class="form-control" placeholder="Float value" value="<?=set_value('eloRating', $track->eloRating)?>">
				</label>
			</div>
			<div class="form-group">
				<span class="errors"><?=form_error('active')?></span>
				<label>Active
					<input type="checkbox" name="active" class="form-control" <?=set_value('active', !$track->turnedOffByAdmin) ? 'checked' : ''?>>
				</label>
			</div>
			<div class="form-group">
				<span class="errors"><?=form_error('isJingle')?></span>
				<label>Jingle
					<input type="checkbox" name="isJingle" class="form-control" <?=set_value('isJingle', $track->isJingle) ? 'checked' : ''?>>
				</label>
			</div>
			<div class="form-group">
				<span class="errors"><?=form_error('eloReached2400')?></span>
				<label>Elo reached 2400
					<input type="checkbox" name="eloReached2400" class="form-control" <?=set_value('eloReached2400', $track->eloReached2400) ? 'checked' : ''?>>
				</label>
			</div>
			
			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url() . "index.php/tracks_dashboard/index/{$track->idGame}"?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>
