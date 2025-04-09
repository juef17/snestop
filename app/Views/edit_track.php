<div class="container_12">
	<div class="grid_12">
		<h1><?=$track->idTrack == 0 ? 'Create' : 'Edit'?> a track for <?=$gameTitle?></h1>
	</div>
</div>

<?= form_open(base_url() . "edit_track/submit/{$track->idTrack}") ?>
	<input type="hidden" name="id" value="<?=$track->idTrack?>">
	<input type="hidden" name="idGame" value="<?=$track->idGame?>">
	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Track number
					<span class="errors"><?//TODO=form_error('trackNumber')?></span>
					<input type="number" step="any" min="0" name="trackNumber" maxlength="255" class="form-control" placeholder="Integer value" value="<?=set_value('trackNumber', $track->trackNumber)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Title
					<span class="errors"><?//TODO=form_error('title')?></span>
					<input type="text" name="title" maxlength="255" class="form-control" placeholder="Title" value="<?=set_value('title', $track->title)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Composer
					<span class="errors"><?//TODO=form_error('composer')?></span>
					<input type="text" name="composer" maxlength="45" class="form-control" placeholder="Composer" value="<?=set_value('composer', $track->composer)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Length
					<span class="errors"><?//TODO=form_error('length')?></span>
					<input type="text" name="length" maxlength="4" class="form-control" placeholder="Length in seconds" value="<?=set_value('length', $track->length)?>">
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
					<span class="errors"><?//TODO=form_error('spcURL')?></span>
					<input type="text" name="spcURL" maxlength="255" class="form-control" placeholder="http://" value="<?=set_value('spcURL', $track->spcURL)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Glicko 2 RD
					<span class="errors"><?//TODO=form_error('glicko2RD')?></span>
					<input type="number" step="any" min="0" name="glicko2RD" maxlength="255" class="form-control" placeholder="Float value" value="<?=set_value('glicko2RD', $track->glicko2RD)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Glicko 2 rating
					<span class="errors"><?//TODO=form_error('glicko2rating')?></span>
					<input type="number" step="any" min="0" name="glicko2rating" maxlength="255" class="form-control" placeholder="Float value" value="<?=set_value('glicko2rating', $track->glicko2rating)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Glicko 2 sigma
					<span class="errors"><?//TODO=form_error('glicko2sigma')?></span>
					<input type="number" step="any" min="0" name="glicko2sigma" maxlength="255" class="form-control" placeholder="Float value" value="<?=set_value('glicko2sigma', $track->glicko2sigma)?>">
				</label>
			</div>
			<div class="form-group">
				<label>Elo rating
					<span class="errors"><?//TODO=form_error('eloRating')?></span>
					<input type="number" step="any" min="0" name="eloRating" maxlength="255" class="form-control" placeholder="Float value" value="<?=set_value('eloRating', $track->eloRating)?>">
				</label>
			</div>
			<div class="form-check">
				<span class="errors"><?//TODO=form_error('active')?></span>
				<label class="form-check-label">Active
					<input type="checkbox" name="active" class="form-check-input" <?=set_value('active', !$track->turnedOffByAdmin) ? 'checked' : ''?>>
				</label>
			</div>
			<div class="form-check">
				<span class="errors"><?//TODO=form_error('isJingle')?></span>
				<label class="form-check-label">Jingle
					<input type="checkbox" name="isJingle" class="form-check-input" <?=set_value('isJingle', $track->isJingle) ? 'checked' : ''?>>
				</label>
			</div>
			<div class="form-check">
				<span class="errors"><?//TODO=form_error('isSoundEffect')?></span>
				<label class="form-check-label">Sound effect
					<input type="checkbox" name="isSoundEffect" class="form-check-input" <?=set_value('isSoundEffect', $track->isSoundEffect) ? 'checked' : ''?>>
				</label>
			</div>
			<div class="form-check">
				<span class="errors"><?//TODO=form_error('isVoice')?></span>
				<label class="form-check-label">Voice
					<input type="checkbox" name="isVoice" class="form-check-input" <?=set_value('isVoice', $track->isVoice) ? 'checked' : ''?>>
				</label>
			</div>
			<div class="form-check">
				<span class="errors"><?//TODO=form_error('eloReached2400')?></span>
				<label class="form-check-label">Elo reached 2400
					<input type="checkbox" name="eloReached2400" class="form-check-input" <?=set_value('eloReached2400', $track->eloReached2400) ? 'checked' : ''?>>
				</label>
			</div>
			
			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url() . "tracks_dashboard/index/{$track->idGame}"?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>
