<div class="container_12">
	<div class="grid_12">
		<h1>Request a track</h1>
	</div>
</div>

<?= form_open(base_url() . '/index.php/request_track/submit') ?>
	<div class="container_12">
		<div class="grid_12">
			<p>
				Your favorite Super Famicom or Super Nintendo Entertainment System track doesn't seem to be listed in our database? Please make sure it isn't listed under another title (see <a href="http://www.wikipedia.org" target="_blank">Wikipedia</a> or <a href="http://www.gamefaqs.com" target="_blank">GameFAQS</a> for alternate titles), and then please fill the following form, after which we'll do our best to add it promptly. Thank you!
			</p>
		</div>
	</div>
	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Game
					<span class="errors"><?//TODO=form_error('game')?></span>
					<input type="text" name="game" maxlength="45" class="form-control" placeholder="Game" value="<?=set_value('game')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Track title
					<span class="errors"><?//TODO=form_error('title')?></span>
					<input type="text" name="title" maxlength="45" class="form-control" placeholder="Track Title" value="<?=set_value('title')?>">
				</label>
			</div>
			<div class="form-group">
				<label>Link to track
					<span class="errors"><?//TODO=form_error('trackUrl')?></span>
					<input type="text" name="trackUrl" maxlength="255" class="form-control" placeholder="http://" value="<?=set_value('trackUrl')?>">
				</label>
			</div>

			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url()?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>

<?php
