<div class="container_12">
	<div class="grid_12">
		<h1>Request a track</h1>
	</div>
</div>

<?php if($game == null): ?>
	<h3>Game not found!</h3>
<?php endif; ?>

<?= form_open(base_url() . 'index.php/request_screenshot_game/submit') ?>
	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Game
					<input type="hidden" name="idgame" value="<?=$game->idGame?>" />
					<p><?=$game->titleEng?> <i>(<?=$game->titleJap?>)</i></p>
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

<?php
