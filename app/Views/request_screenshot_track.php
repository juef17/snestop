<div class="container_12">
	<div class="grid_12">
		<h1>Submit a track screenshot</h1>
	</div>
</div>

<?php if($track == null): ?>
	<h3>Track not found!</h3>
<?php else: ?>

	<div class="container_12">
		<div class="grid_12">
			<p>
				Good gracious! A track you like has no in-game screenshot available? You have to help us and make this right! Please submit a screenshot using the following guidelines:
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
						Play the game, and <b>take a screenshot when the track is playing</b>. If the moment the track plays is far into the game, it might be easier to go through the game with an input file from <a href="http://tasvideos.org/" target="_blank">TASVideos</a>.
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

	<?= form_open(base_url() . 'request_screenshot_track/submit') ?>
		<div class="container_12">
			<div class="grid_12 prefix_2 suffix_2 ">
				<div class="form-group">
					<label>Track
						<input type="hidden" name="idtrack" value="<?=$track->idTrack?>" />
					</label>
					<p><?=$track->title?></p>
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
