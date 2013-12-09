<div class="container_12">
	<div class="grid_12">
		<h1>News details</h1>
	</div>
</div>

<?= form_open(base_url() . 'index.php/request_track/submit') ?>
	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Game
					<span class="errors"><?=form_error('game')?></span>
					<input type="text" name="game" maxlength="45" class="form-control" placeholder="Game" value="<?=set_value('game')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Track title
					<span class="errors"><?=form_error('title')?></span>
					<input type="text" name="title" maxlength="45" class="form-control" placeholder="Track Title" value="<?=set_value('title')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Link to track
					<span class="errors"><?=form_error('trackUrl')?></span>
					<input type="text" name="trackUrl" maxlength="255" class="form-control" placeholder="http://" value="<?=set_value('trackUrl')?>">
				</label>
			</div>

			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url()?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>

<?php
