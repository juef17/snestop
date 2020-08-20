<div class="container_12">
	<div class="grid_12">
		<h1>Communities dashboard</h1>
	</div>
</div>

<div class="container_12">
	<div class="grid_12">
		<h2>Requests</h1>
	</div>
</div>

<div style="background-color: #dddddd;" class="container_12">
	<div class="grid_2 columnheader">
		<p>Name</p>
	</div>
	<div class="grid_1 columnheader">
		<p>Requester</p>
	</div>
	<div class="grid_5 columnheader">
		<p>URL</p>
	</div>
	<div class="grid_1 columnheader">
		<!--delete -->
	</div>
</div>

<?php if(count($requests) == 0): ?>
<div class="container_12">
	<div class="grid_12">
		<p>No request.</p>
	</div>
</div>
<?php endif; ?>

<?php $b = TRUE; foreach($requests as $request): ?>
	<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
		<div class="grid_2">
			<p><?=$request->name?></p>
		</div>
		<div class="grid_1">
			<a href="<?=base_url()?>/index.php/user_profile/index/<?=$request->userName?>"><?=$request->userName?></a>
		</div>
		<div class="grid_5">
			<a href="<?=$request->URL?>"><?=$request->URL?></a>
		</div>
		<div class="grid_1">
			<?= form_open(base_url() . '/index.php/communities_dashboard/deleteRequest', array('id' => 'deleteRequest_' . $request->idCommunityRequest)) ?>
				<input type="hidden" name="id" value="<?= $request->idCommunityRequest ?>" />
				<a href="#" class="btn btn-xs btn-danger" onclick="deleteRequest_<?= $request->idCommunityRequest ?>.submit();">Delete</a>
			</form>
		</div>
	</div>
<?php endforeach; ?>

<div class="container_12">
	<div class="grid_12">
		<h2>Communities</h1>
	</div>
</div>

<div style="background-color: #dddddd;" class="container_12">
	<div class="grid_2 columnheader">
		<p>Name</p>
	</div>
	<div class="grid_2 columnheader">
		<p>Token</p>
	</div>
	<div class="grid_5 columnheader">
		<p>URL</p>
	</div>
	<div class="grid_1 columnheader">
		<!--update -->
	</div>
	<div class="grid_1 columnheader">
		<!--delete -->
	</div>
</div>

<div class="container_12">
	<?= form_open(base_url() . '/index.php/communities_dashboard/add', array('id' => 'addCommunity')) ?>
		<div class="grid_2 columnheader">
			<input type="text" name="community_name" placeholder="Name" maxlength="45" class="form-control"/>
		</div>
		<div class="grid_2 columnheader">
			<input type="text" name="community_token" placeholder="Token" maxlength="45" class="form-control"/>
		</div>
		<div class="grid_5 columnheader">
			<input type="text" name="community_URL" placeholder="http://" maxlength="255" class="form-control"/>
		</div>
		<div class="grid_1 columnheader">
			<a href="#" class="btn btn-xs btn-default" onclick="addCommunity.submit();">Add</a>
		</div>
		<div class="grid_1 columnheader">
			<!--empty, no need for delete here! Oh well... What a basteurde! -->
		</div>
	</form>
</div>

<?php if(count($communities) == 0): ?>
<div class="container_12">
	<div class="grid_12">
		<p>No community yet!</p>
	</div>
</div>
<?php endif; ?>

<?php $b = FALSE; foreach($communities as $community): ?>
	<div style="<?php if($b = !$b): ?> background-color: #dddddd;<?php endif; ?>" class="container_12">
		<?= form_open(base_url() . '/index.php/communities_dashboard/update', array('id' => 'update_' . $community['idCommunity'])) ?>
			<input type="hidden" name="id" value="<?= $community['idCommunity'] ?>" />
			<div class="grid_2">
				<input type="text" name="community_name" maxlength="45" class="form-control" value="<?=$community['name']?>" />
			</div>
			<div class="grid_2">
				<input type="text" name="community_token" maxlength="45" class="form-control" value="<?=$community['token']?>" />
			</div>
			<div class="grid_5">
				<input type="text" name="community_URL" maxlength="255" class="form-control" value="<?=$community['URL']?>" />
			</div>
			<div class="grid_1">
				<a href="#" class="btn btn-xs btn-default" onclick="update_<?= $community['idCommunity'] ?>.submit();">Update</a>
			</div>
		</form>
		<div class="grid_1">
			<?= form_open(base_url() . '/index.php/communities_dashboard/delete', array('id' => 'delete_' . $community['idCommunity'])) ?>
				<input type="hidden" name="id" value="<?= $community['idCommunity'] ?>" />
				<a href="#" class="btn btn-xs btn-danger" onclick="delete_<?= $community['idCommunity'] ?>.submit();">Delete</a>
			</form>
		</div>
	</div>
<?php endforeach; ?>
