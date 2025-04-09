<style>
	#search-menu-item input {
		padding-right:55px;
		width: 180px;
	}

	#search-menu-item .fa-search,
	#search-menu-item #search-menu-button {
		display: inline-block;
		color: #393939;
		cursor: pointer;
		position: absolute;
		top: 4px;
		padding: 5px 0;
	}

	#search-menu-item .fa-search {
		right: 12px;
	}

	#search-menu-item #search-menu-button {
		right: 36px;
	}

	#search-menu {
		width: 100px;
		position: absolute;
		display: none;
	}
</style>

<li id="search-menu-item" style="position: relative; float: right;">
	<form id="search-form" style="margin: 0;" action="<?=base_url()?>search/index" method="POST">
		<input id="search-string" type="text" name="searchString" class="form-control" placeholder="Search"/>
		<input id="search-target" type="hidden" name="target" value="0"/>
		<a href="#!" id="search-menu-button" class="fa fa-gamepad" title="What to search"></a>
		<a href="#!" class="fa fa-search" title="Search!" onclick="$(this).parent().submit();"></a>
	</form>
</li>

<script>
	$(function(){
		$('#search-menu').menu();

		$(document).mouseup(function(e) {
			var menu = $('#search-menu')
			if(!menu.is(e.target) && menu.has(e.target).length == 0)
				menu.fadeOut('fast');
		});

		$('#search-menu-button').click(function() {
			var offset = $(this).offset();
			$('#search-menu').css({'top':offset.top,'left':offset.left}).fadeIn('fast');
		});

		$('#search-menu-item input').keyup(function(event){
			if(event.keyCode == 13)
				submitSearch();
		});

		$('#search-form').submit(function() {
			return $('#search-string').val().trim() != '';
		});
	});

	function setSearchTarget(target) {
		$('#search-target').val(target);
		var button = $('#search-menu-button');
		button
			.removeClass('fa-gamepad')
			.removeClass('fa-music')
			.removeClass('fa-user');
			
		if(target == 0) {
			button.addClass('fa-gamepad');
		} else if(target == 1) {
			button.addClass('fa-music');
		} else if(target == 2) {
			button.addClass('fa-user');
		}
		$('#search-menu').fadeOut('fast');
	}
</script>
