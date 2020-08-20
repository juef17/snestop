<div class="container_12">
	<div class="grid_12">
		<h1>Submit a game screenshot</h1>
	</div>
</div>

<?php if($game == null): ?>
	<h3>Game not found!</h3>
<?php else: ?>

	<div class="container_12">
		<div class="grid_12">
			<p>
				As you have certainly noticed, we don't currently have title screenshots for every game in our database. Too bad, right? Well, how about changing that? You can submit a screenshot to us if you follow these guidelines:
				<ol>
					<li>
						<b>Use an accurate emulator.</b> While ZSNES (among others) is very popular, there are some games in which it will display some details incorrectly. But no worries, there are many excellent alternatives! We will accept submissions that were made using the following emulators:
						<ul>
							<li><a href="http://byuu.org/higan/" target="blank">higan/bsnes</a> v0.73 or newer with the accuracy profile</li>
							<li><a href="https://code.google.com/p/bizhawk/" target="blank">bizhawk</a> with the bsnes accuracy core</li>
							<li><a href="http://themaister.net/retroarch.html" target="blank">RetroArch</a> with the bsnes accuracy core</li>
							<li><a href="http://openemu.org/" target="blank">OpenEmu</a> v1.01 or newer with the bsnes accuracy core</li>
							<li><a href="http://www.snes9x.com/" target="blank">snes9x</a> v1.53 or newer</li>
							<li><a href="http://www.crazysmart.net.au/kindred/" target="blank">kindred</a> (formerly known as Super Sleuth) v1.04 or newer</li>
							<li><a href="http://nocash.emubase.de/sns.htm" target="blank">no$sns</a> v1.5 or newer</li>						
						</ul>
					</li>
					<li>
						Play the game, and <b>take a screenshot of the title screen</b>. By "title screen", we mean the moment when you can read the title of the game; if there is no such moment, then use an easily recognizable image from the opening of the game.
					</li>
					<li>
						<b>Make sure the screenshot is 256x224</b> (for most games) <b>and save it as PNG. Do not convert it, filter it, crop it or modify it in any way</b>, except maybe compress it (losslessly) as much as you can using tools such as <a href="http://css-ig.net/pngslim" target="_blank">pngslim</a> or <a href="http://pmt.sourceforge.net/pngcrush/" target="_blank">Pngcrush</a>!
					</li>
					<li>
						<b>Upload the image to <a href="http://imgur.com/" target="_blank">imgur</a>, and get the direct link to the image</b>. It should look like this: <a href="http://i.imgur.com/ORWts4q.png" target="_blank">http://i.imgur.com/ORWts4q.png</a>.
					</li>
					<li>
						<b>Complete the form below</b>, and enjoy our eternal gratitude. Thank you!
					</li>
				</ol>
			</p>
		</div>
	</div>
	
	<?= form_open(base_url() . '/index.php/request_screenshot_game/submit') ?>
		<div class="container_12">
			<div class="grid_12 prefix_2 suffix_2 ">
				<div class="form-group">
					<label>Game
						<input type="hidden" name="idgame" value="<?=$game->idGame?>" />
					</label>
					<p><?=$game->titleEng?>
						<?php
							if(strcmp($game->titleJap, "") && strcmp($game->titleJap, $game->titleEng)) {
								?> <i>(<?=$game->titleJap?>)</i> <?php
							}
						?>
					</p>
				</div>
				<div class="form-group">
					<label>Link to screenshot
						<span class="errors"><?//TODO=form_error('screenshotUrl')?></span>
						<input type="text" name="screenshotUrl" maxlength="255" class="form-control" placeholder="http://" value="<?=set_value('screenshotUrl')?>">
					</label>
				</div>

				<button type="submit" class="btn btn-default">Submit</button>
			</div>
		</div>
	</form>

<?php endif; ?>
