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
	<input type="text" id="search-string" name="search" class="form-control" placeholder="Search"/>
	<input type="hidden" id="search-target" name="target" value="0"/>
	<a href="#!" id="search-menu-button" class="fa fa-gamepad" title="What to search"></a>
	<a href="#!" class="fa fa-search" title="Search!" onclick="submitSearch();"></a>
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

	function submitSearch() {
		if($('#search-string').val() != '') {
			var target = $('#search-target').val();
			var searchString = escape($('#search-string').val());
			window.location.href = '<?=base_url()?>index.php/search/index/' + target + '/' + searchString;
		}
	}
</script>
