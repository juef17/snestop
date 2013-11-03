<div class="container_12">
	<div class="grid_12">
		<h1>Welcome to the Top 100 SNES music web site!</h1>
		<p>Here you'll find everythig snes music related! And then some.</p>
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
