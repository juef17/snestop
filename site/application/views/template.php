<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once('application/views/includes/head.php'); ?>
  <title>SnesTop. Title var goes here.</title>
</head>

<body>
  <?php if(! isset($view)) die('Bad request, view not set.'); ?>
  
  <div class="container_12">
    <div class="grid_3">
      <img src="assets/images/logo.png" />
      <ul id="sliding-navigation">
        <li class="sliding-element"><a href="#">Duels</a></li>
        <li class="sliding-element"><a href="#">Playlists</a></li>
        <li class="sliding-element"><a href="#">Requests</a></li>
        <li class="sliding-element"><a href="#">Mowe's shtuff</a></li>
        <li class="sliding-element"><a href="#">Goody hair brushes</a></li>
      </ul>
      <script type="text/javascript">
        $(document).ready(function()
        {
          slide("#sliding-navigation", 25, 15, 150, .8);
        });
      </script>
    </div>
    <?php require_once($view); ?>
  </div>
</body>
</html>
