<div class="container_12">
	<div class="grid_12">
		<h1>Users management dashboard</h1>
	</div>
</div>

<div style="background-color: #dddddd;" class="container_16">
	<div class="grid_2 columnheader">
		<p>Username</p>
	</div>
	<div class="grid_2 columnheader">
		<p>Registration date</p>
	</div>
	<div class="grid_1 columnheader">
		<p>Confirmed</p>
	</div>
	<div class="grid_3 columnheader">
		<p>Email</p>
	</div>
	<div class="grid_2 columnheader">
		<p>Community</p>
	</div>
	<div class="grid_2 columnheader">
		<p>Language</p>
	</div>
	<div class="grid_1 columnheader">
		<p>Admin</p>
	</div>
	<div class="grid_1 columnheader">
		<p>Enabled</p>
	</div>
</div>

<?php $b = TRUE; foreach($users as $user): ?>
	<div style="border-bottom: 1px; <?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_16">
		<div class="grid_2">
			<p><?= $user->userName ?></p>
		</div>
		<div class="grid_2">
			<p><?= $user->registrationDate ?></p>
		</div>
		<div class="grid_1">
			<p><?= ($user->registrationToken == NULL ? 'yes' : 'no') ?></p>
		</div>
		<div class="grid_3">
			<p><?= $user->email ?></p>
		</div>
		<div class="grid_2">
			<p><?= $user->communityName ?></p>
		</div>
		<div class="grid_2">
			<p><?= $user->language ?></p>
		</div>
		<div class="grid_1">
			<p><?= ($user->isAdmin ? 'yes' : 'no') ?></p>
		</div>
		<div class="grid_1">
			<?php if($user->userName != 'admin'): ?>
				<?= form_open(base_url() . 'users_management_dashboard/' . ($user->enabled ? 'disable' : 'enable') . 'User', array('id' => 'toggleUserEnabledState_' . $user->idUser)) ?>
					<input type="hidden" name="id" value="<?= $user->idUser ?>" />
					<a href="#" onclick="toggleUserEnabledState_<?= $user->idUser ?>.submit();"><?= ($user->enabled ? 'yes' : 'no') ?></a>
				</form>
			<?php else: ?>
				<p><?= ($user->enabled ? 'yes' : 'no') ?></p>
			<?php endif; ?>
		</div>
		<div class="grid_1">
			<?php if($user->userName != 'admin'): ?>
				<?= form_open(base_url() . 'users_management_dashboard/reset_password', array('id' => 'reset_' . $user->idUser)) ?>
					<input type="hidden" name="id" value="<?= $user->idUser ?>" />
					<a href="#" class="btn btn-xs btn-danger" onclick="if(confirm('Are you sure you want to reset this users password? It will be set to iwillnotforgetagain'))reset_<?= $user->idUser ?>.submit();">Rst pwd</a>
				</form>
			<?php else: ?>
				<p>---</p>
			<?php endif; ?>
		</div>
		<div class="grid_1">
			<?php if($user->userName != 'admin'): ?>
				<?= form_open(base_url() . 'users_management_dashboard/delete', array('id' => 'delete_' . $user->idUser)) ?>
					<input type="hidden" name="id" value="<?= $user->idUser ?>" />
					<a href="#" class="btn btn-xs btn-danger" onclick="if(confirm('Are you sure? This will also delete some if not ALL of that user\'s schtuff!!!'))delete_<?= $user->idUser ?>.submit();">Delete</a>
				</form>
			<?php else: ?>
				<p>---</p>
			<?php endif; ?>
		</div>
	</div>
<?php endforeach; ?>
