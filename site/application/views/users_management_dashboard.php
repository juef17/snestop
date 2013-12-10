<div class="container_12">
	<div class="grid_12">
		<h1>Users management dashboard</h1>
	</div>
</div>

<?php $b = FALSE; ?>
<div style="border-bottom: 1px; <?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
	<div class="grid_2">
		<p style="font-weight:bold;">Username</p>
	</div>
	<div class="grid_2">
		<p style="font-weight:bold;">Email</p>
	</div>
	<div class="grid_2">
		<p style="font-weight:bold;">Community</p>
	</div>
	<div class="grid_1">
		<p style="font-weight:bold;">Language</p>
	</div>
	<div class="grid_1">
		<p style="font-weight:bold;">Can stream MP3</p>
	</div>
	<div class="grid_1">
		<p style="font-weight:bold;">Autoplay</p>
	</div>
	<div class="grid_1">
		<p style="font-weight:bold;">Admin</p>
	</div>
	<div class="grid_1">
		<p style="font-weight:bold;">Enabled</p>
	</div>
</div>

<?php foreach($users as $user): ?>
	<div style="border-bottom: 1px; <?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
		<div class="grid_2">
			<p><?= $user->userName ?></p>
		</div>
		<div class="grid_2">
			<p><?= $user->email ?></p>
		</div>
		<div class="grid_2">
			<p><?= $user->communityName ?></p>
		</div>
		<div class="grid_1">
			<p><?= $user->language ?></p>
		</div>
		<div class="grid_1">
			<p><?= ($user->canStreamMP3 ? 'yes' : 'no') ?></p>
		</div>
		<div class="grid_1">
			<p><?= ($user->autoplay ? 'yes' : 'no') ?></p>
		</div>
		<div class="grid_1">
			<p><?= ($user->isAdmin ? 'yes' : 'no') ?></p>
		</div>
		<div class="grid_1">
			<?php if($user->userName != 'admin'): ?>
				<?= form_open(base_url() . 'index.php/users_management_dashboard/' . ($user->enabled ? 'disable' : 'enable') . 'User', array('id' => 'toggleUserEnabledState_' . $user->idUser)) ?>
					<input type="hidden" name="id" value="<?= $user->idUser ?>" />
					<a href="#" onclick="toggleUserEnabledState_<?= $user->idUser ?>.submit();"><?= ($user->enabled ? 'yes' : 'no') ?></a>
				</form>
			<?php else: ?>
				<p><?= ($user->enabled ? 'yes' : 'no') ?></p>
			<?php endif; ?>
		</div>
		<div class="grid_1">
			<?php if($user->userName != 'admin'): ?>
				<?= form_open(base_url() . 'index.php/users_management_dashboard/delete', array('id' => 'delete_' . $user->idUser)) ?>
					<input type="hidden" name="id" value="<?= $user->idUser ?>" />
					<a href="#" onclick="if(confirm('Are you sure? This will also delete some if not ALL of that user\'s schtuff!!!'))delete_<?= $user->idUser ?>.submit();">Delete</a>
				</form>
			<?php else: ?>
				<p>---</p>
			<?php endif; ?>
		</div>
	</div>
<?php endforeach; ?>
