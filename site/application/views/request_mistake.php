<div class="container_12">
	<div class="grid_12">
		<h1>Report a mistake</h1>
	</div>
</div>

<?= form_open(base_url() . 'index.php/request_mistake/submit') ?>
	<div class="container_12">
		<div class="grid_12">
			<p>
				This project uses a lot of data, some of which might very well be incorrect for different reasons. Of course, we'd be happy to provide the most accurate information that we can, so please use the following form should you notice any of these problems:
				<ul>
					<li>
						information regarding a game's title is incorrect;
					</li>
					<li>
						a link is broken;
					</li>
					<li>
						information regarding a track's title, length or composer(s) is incorrect;
					</li>
					<li>
						a screenshot isn't from the right game or moment, or doesn't follow our screenshot submission guidelines;
					</li>
					<li>
						you hold the copyright to something we used without your permission and wish to see it removed.
					</li>
				</ul>
			</p>
		</div>
	</div>
	<div class="container_12">
		<div class="grid_12 prefix_2 suffix_2">
			<div class="form-group">
				<label>Description
					<span class="errors"><?=form_error('text')?></span>
					<textarea name="text" class="form-control" placeholder="Description of the mistake" maxlength="255" rows="5"><?=set_value('text') != null ? set_value('text') : $message?></textarea>
				</label>
			</div>

			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url()?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>

<?php
