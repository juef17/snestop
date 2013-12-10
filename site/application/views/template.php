<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once('application/views/includes/head.php'); ?>
	<title>SnesTop. Title var goes here.</title>
</head>

<body>
	<?php if(! isset($view)) die('Bad request, view not set.'); ?>
	
	<div class="container_12">
		<div class="grid_2">
			<a href="<?= base_url() ?>">
				<img src="<?= base_url() ?>assets/images/logo.png" />
			</a>
		</div>
		<div class="grid_10">
			<div class="errors" style="display: inline-block;">
				<?= $loginError ?>
			</div>
			<?php if($loggedUserUserName == NULL):
					$attributes = array('class' => 'form-inline', 'role' => 'form', 'style' => 'float: right; padding: 5px 0;');
					echo form_open('account/login', $attributes);
				?>
					<div class="form-group">
						<label class="sr-only" for="exampleInputEmail2">Username</label>
						<input type="text" name="username" maxlength="45" class="form-control" placeholder="Username">
					</div>
					<div class="form-group">
						<label class="sr-only" for="exampleInputPassword2">Password</label>
						<input type="password" name="password" maxlength="10" class="form-control" placeholder="Password">
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="rememberme"> Remember me
						</label>
					</div>
					<button type="submit" class="btn btn-default">Sign in</button>
				</form>
			<?php else: ?>
				<p style="float: right; padding: 5px 0;">Hi <?= $loggedUserUserName ?>! <a href="<?= base_url() ?>index.php/account/logout">Logout</a></p>
			<?php endif; ?>
			
			<ul id="menuprincipal" style="width: 100%; margin: 5px 0;" class="jdropdown-menu dropdown-menu-skin">
				<li><a href="<?=base_url()?>">Home</a></li>
				<?php if($isUserLogged): ?>
					<li><a href="#">Requests</a>
						<ul>
							<li><a href="<?= base_url(); ?>index.php/request_track">Request a track</a></li>
						</ul>
					</li>
				<?php endif; ?>
				<li><a href="#">Playlists</a>
					<ul>
						<li><a>Category 1.2</a>
							<li><a>Category 1.3</a></li>
							<li><a>Category 1.3</a></li>
							<li><a>Category 1.3</a></li>
						</li>
					</ul>
				</li>
				<li><a href="#">Mowe's shtuff</a>
					<ul>
						<li><a>Category 1.2</a>
							<li><a>Category 1.3</a></li>
							<li><a>Category 1.3</a></li>
							<li><a>Category 1.3</a></li>
						</li>
					</ul>
				</li>
				<?php if($loggedUserIsAdmin): ?>
					<li><a href="#">Administration</a>
						<ul>
							<li><a href="<?= base_url() ?>index.php/news_dashboard">News dashboard</a></li>
							<li><a href="<?= base_url() ?>index.php/track_request_dashboard">Track requests</a></li>
							<li><a href="<?= base_url() ?>index.php/users_management_dashboard">Users management</a></li>
						</ul>
					</li>
				<?php endif; ?>
			</ul>
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
					});});
			</script>
		</div>
	</div>
	<?php require_once(views_dir() . $view); ?>
</body>
</html>
