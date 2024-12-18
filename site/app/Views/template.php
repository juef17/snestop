<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once(views_dir() . 'includes/head.php'); ?>
	<?php helper('url'); ?>
	<?php helper('form'); ?>
	<link rel="icon" href="<?=base_url()?>/favicon.png" type="image/png">
	<?php require_once(views_dir() . 'includes/social_meta.php'); ?>
</head>

<body>
	<?php if(! isset($view)) die('Bad request, view not set.'); ?>

	<script>
		//some basic global variables
		var isUserLogged = <?=$loggedUser ? 'true' : 'false'?>;
		var assetUrl = '<?=asset_url()?>';
		var baseUrl = '<?=base_url()?>';
	</script>
	
	<div class="container_12">
		<div class="grid_2">
			<a href="<?= base_url() ?>">
				<img src="<?= base_url() ?>/assets/images/logo.png" />
			</a>
		</div>
		<div class="grid_10">
			<div class="errors" style="display: inline-block;">
				<!--TODO printer les erreurs de login / validation-->
			</div>
			<?php if(!$loggedUser):
					$attributes = array('class' => 'form-inline', 'role' => 'form', 'style' => 'float: right; padding: 5px 0;');
					echo form_open('account/login?returnUrl=' . rawurlencode("$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"), $attributes);
				?>
					<div class="form-group">
						<label class="sr-only" for="exampleInputEmail2">Username</label>
						<input type="text" name="username" maxlength="45" class="form-control" placeholder="Username">
					</div>
					<div class="form-group">
						<label class="sr-only" for="exampleInputPassword2">Password</label>
						<input style="width: 100px;" type="password" name="password" maxlength="45" class="form-control" placeholder="Password">
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="rememberme"> Remember me
						</label>
					</div>
					<button type="submit" class="btn btn-default">Sign in</button>
					<a href="<?= base_url() ?>/index.php/register" class="btn btn-success">Register!</a>
				</form>
			<?php else: ?>
				<p style="float: right; padding: 5px 0;">Hi <a href="<?=base_url() . "/index.php/user_profile/index/{$loggedUser->userName}"?>"><?= $loggedUser->userName ?>!</a> | <a href="<?=base_url()?>/index.php/edit_user_profile">Edit your profile</a> | <a href="<?= base_url() ?>/index.php/account/logout">Logout</a></p>
			<?php endif; ?>
			
			<ul id="menuprincipal" style="width: 100%; margin: 5px 0;" class="jdropdown-menu dropdown-menu-skin">
				<?php if(!isset($duelzMode)): require_once(views_dir() . 'includes/search_box.php'); endif;?>
				<li><a href="<?=base_url()?>">Home</a></li>
				<?php if($loggedUser): ?>
					<li><a href="#!">Requests</a>
						<ul>
							<li><a href="<?= base_url() ?>/index.php/request_community">Request a community</a></li>
							<li><a href="<?= base_url() ?>/index.php/request_track">Request a track</a></li>
						</ul>
					</li>
				<?php endif; ?>
				<li><a href="#!">The project</a>
					<ul>
						<li><a href="<?= base_url() ?>/index.php/About">About us</a></li>
						<li><a href="<?= base_url() ?>/index.php/search/browse/0">Browse games</a></li>
						<?php if($loggedUser): ?>
							<li><a href="<?= base_url() ?>/index.php/request_mistake">Report a mistake</a></li>
						<?php endif; ?>
					</ul>
				</li>
				<?php if($loggedUser): ?>
					<li><a href="#!">Tracks duelz</a>
						<ul>
							<li><a href="<?= base_url() ?>/index.php/duelz">Take the duelz!</a>
							<li><a href="<?= base_url() ?>/index.php/duelz_history">Duelz history</a></li>
						</ul>
					</li>
				<?php endif; ?>
				<?php if($loggedUser && $loggedUser->isAdmin): ?>
					<li><a href="#!">Administration</a>
						<ul>
							<li><a href="<?= base_url() ?>/index.php/communities_dashboard">Communities dashboard</a></li>
							<li><a href="<?= base_url() ?>/index.php/games_dashboard/index/1">Games dashboard</a></li>
							<li><a href="<?= base_url() ?>/index.php/news_dashboard">News dashboard</a></li>
							<li><a href="<?= base_url() ?>/index.php/mistake_requests_dashboard">Mistake reports dashboard</a></li>
							<li><a href="<?= base_url() ?>/index.php/reviews_dashboard">Reviews dashboard</a></li>
							<li><a href="<?= base_url() ?>/index.php/screenshot_request_dashboard">Screenshot requests dashboard</a></li>
							<li><a href="<?= base_url() ?>/index.php/track_request_dashboard">Track requests dashboard</a></li>
							<li><a href="<?= base_url() ?>/index.php/users_management_dashboard">Users dashboard</a></li>
						</ul>
					</li>
				<?php endif; ?>
				<?php if($loggedUser && $winape_enabled): ?>
					<li>
						<a href="#!" onclick="playerDialog.dialog('open');"><img style="width: 24px; height: 24px; cursor: pointer;" src="<?=asset_url() . 'images/play.png'?>" /></a>
					</li>
				<?php endif; ?>
			</ul>
			
			<?php if(!isset($duelzMode)): ?>
				<ul id="search-menu">
					<li id="search-games"><a href="#!" onclick="setSearchTarget(0);"><span class="fa fa-fw fa-gamepad"></span>Games</a></li>
					<li id="search-tracks"><a href="#!" onclick="setSearchTarget(1);"><span class="fa fa-fw fa-music"></span>Tracks</a></li>
					<li id="search-users"><a href="#!" onclick="setSearchTarget(2);"><span class="fa fa-fw fa-user"></span>Users</a></li>
				</ul>
			<?php endif; ?>

			<script type="text/javascript">
				$(function() {
					$('#menuprincipal').dropdown_menu({
							sub_indicator_class  : 'dropdown-menu-sub-indicator',   // Class given to LI's with submenus
							vertical_class       : 'dropdown-menu-vertical',        // Class for a vertical menu
							shadow_class         : 'dropdown-menu-shadow',          // Class for drop shadow on submenus
							hover_class          : 'dropdown-menu-hover',           // Class applied to hovered LI's
							open_delay           : 150,                             // Delay on menu open
							close_delay          : 300,                             // Delay on menu close
							animation_open       : { opacity : 'show' },            // Animation for menu open
							speed_open           : 'fast',                          // Animation speed for menu open
							animation_close      : { opacity : 'hide' },            // Animation for menu close
							speed_close          : 'fast',                          // Animation speed for menu close
							sub_indicators       : true,                            // Whether to show arrows for submenus
							drop_shadows         : true,                            // Whether to apply drop shadow class to submenus
							vertical             : false,                           // Whether the root menu is vertically aligned
							viewport_overflow    : 'auto',                          // Handle submenu opening offscreen: "auto", "move", "scroll", or false
							init                 : function() {}                    // Callback function applied on init
					});
				});
			</script>
		</div>
	</div>
	<?php require_once(views_dir() . $view); ?>
	<?php if($winape_enabled) require_once(views_dir() . 'includes/player_dialog.php'); ?>
	<?php require_once(views_dir() . 'includes/playlist_dialogs.php'); ?>
	<?php require_once(views_dir() . 'includes/message_dialog.php'); ?>

	<div class="waitmodal"></div>
</body>
</html>
