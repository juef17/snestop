<div class="container_12">
	<h1 class="grid_12">Search results</h1>
</div>
<div class="container_12">
	<h4 class="grid_12"><?=count($users)?> user(s) matching '<?=$searchString?>'</h4>
</div>
<?php if(count($users) == 50): ?>
	<div class="container_12">
		<p class="grid_12">Result limit reached. Refine your search!</p>
	</div>
<?php endif; ?>

<?php if(count($users) == 0): ?>
	<div class="container_12">
		<p class="grid_12">None</p>
	</div>
<?php else: ?>
	<div style="background-color: #dddddd;" class="container_16">
		<p class="grid_2 columnheader">Username</p>
		<p class="grid_3 columnheader">Registration date</p>
		<p class="grid_2 columnheader">Community</p>
		<p class="grid_2 columnheader">Language</p>
	</div>
	<?php $b = TRUE; foreach($users as $user): ?>
		<div style="border-bottom: 1px; <?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_16">
			<p class="grid_2"><a href="<?=base_url()?>index.php/user_profile/index/<?=$user->userName?>"><?=$user->userName?></a></p>
			<p class="grid_3"><?=$user->registrationDate?></p>
			<p class="grid_2"><a target="_blank" href="<?=$user->communityUrl?>"><?=$user->communityName?></a></p>
			<p class="grid_2"><?=$user->language?></p>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<script>
	$(function() {
		setSearchTarget(2);
	});
</script>
