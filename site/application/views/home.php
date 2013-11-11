<div class="container_12">
	<div class="grid_12">
		<h1>Greetings, and welcome to the Top SNES tracks project!</h1>
		<p>This project aims to please the Super Nintendo or Super Famicom aficionado and the video game music lovers in two different ways:</p>
		<ul>
			<li>Promote musical gems from obscure and/or mediocre games;</li>
			<li>Use rating systems to compile a statistically valid list of the best SNES tracks ever.</li>
		</ul>
		<p>We plan to achieve this by allowing users to listen to high quality & small sized music files, create and share playlists with anybody, and most importantly, have the SNES tracks compete in the most epic 1 vs 1 tournament ever.</p>
		<p>More details about this project, the tracks and the rating algorithms are available in the About page.</p>
		<p>Thank you for checking this out, and please have fun!</p>
	</div>
</div>

<div class="container_12">
	<div class="grid_12">
		<?php foreach($news as $nitem): ?>
			<div>
				<h2><?=$nitem['title']?></h2>
				<p>By <?=$nitem['userName']?> on <?=$nitem['date']?></p>
				<p><?=$nitem['text']?></p>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<?php
