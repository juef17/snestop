<div class="container_12">
	<div class="grid_12">
		<h1>Report a mistake</h1>
	</div>
</div>

<?= form_open(base_url() . 'index.php/request_mistake/submit') ?>
	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Description
					<span class="errors"><?=form_error('text')?></span>
					<textarea name="text" class="form-control" placeholder="Description of the mistake" maxlength="255" rows="5"><?=set_value('text')?></textarea>
				</label>
			</div>

			<button type="button" class="btn btn-default" onclick="location.href='<?=base_url()?>';">Return</button>
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>

<?php
