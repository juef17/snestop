<div class="container_12">
	<div class="grid_12">
		<h1>Mistake reports dashboard</h1>
	</div>
</div>

<?php if(count($mistakes) == 0): ?>
<div class="container_12">
	<div class="grid_12">
		<p>None!</p>
	</div>
</div>
<?php else: ?>
<div style="background-color: #dddddd;" class="container_12">
	<div class="grid_2 columnheader">
		<p>Username</p>
	</div>
	<div class="grid_9 columnheader">
		<p>Text</p>
	</div>
	<div class="grid_1 columnheader">
		<!--delete -->
	</div>
</div>
<?php endif; ?>

<?php $b = TRUE; foreach($mistakes as $mistake): ?>
	<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
		<div class="grid_2">
			<a href="<?=base_url()?>user_profile/index/<?=$mistake->userName?>"><?=$mistake->userName?></a>
		</div>
		<div class="grid_9">
			<p><?= $mistake->text ?></p>
		</div>
		<div class="grid_1">
			<?= form_open(base_url() . 'mistake_requests_dashboard/delete', array('id' => 'delete_' . $mistake->idMistakeRequest)) ?>
				<input type="hidden" name="id" value="<?= $mistake->idMistakeRequest ?>" />
				<a href="#" class="btn btn-xs btn-danger" onclick="delete_<?= $mistake->idMistakeRequest ?>.submit();">Delete</a>
			</form>
		</div>
	</div>
<?php endforeach; ?>
