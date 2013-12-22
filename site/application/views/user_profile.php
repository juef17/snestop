<?php if($user == NULL): ?>
	<div class="container_12">
		<div class="grid_12">
			<h1>User not found</h1>
		</div>
	</div>
<?php else: ?>
	<div class="container_12">
		<div class="grid_12">
			<h1><?=$user->userName?>'s profile</h1>
		</div>
	</div>

	<div class="container_12">
		<div class="grid_12 prefix_4 suffix_4 ">
			<div class="form-group">
				<label>Language</label>
				<p><?=$user->language?></p>
			</div>
			<div class="form-group">
				<label>Community</label>
				<p><?= ($user->communityName == NULL) ? 'None' : '<a href="' . $user->communityURL . '">' . $user->communityName . '</a>'?></p>
			</div>
			<div class="form-group">
				<label>Registration date</label>
				<p><?=$user->registrationDate?></p>
			</div>
		</div>
	</div>
<?php endif; ?>
