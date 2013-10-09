<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once('application/views/includes/head.php'); ?>
  <title>SnesTop. Title var goes here.</title>
</head>

<body>
<div class="header">
  <ul id="jMenu">
    <li><a>Category 1</a>
      <ul>
        <li><a>Category 1.2</a>
          <ul>
            <li><a>Category 1.3</a></li>
            <li><a>Category 1.3</a></li>
            <li><a>Category 1.3</a></li>
          </ul>
        </li>
        <li><a>Category 1.2</a></li>
        <li><a>Category 1.2</a>
          <ul>
            <li><a>Category 1.3</a></li>
            <li><a>Category 1.3</a>
              <ul>
                <li><a>Category 1.4</a></li>
                <li><a>Category 1.4</a></li>
                <li><a>Category 1.4</a></li>
                <li><a>Category 1.4</a></li>
                <li><a>Category 1.4</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li><a>Category 1.2</a></li>
      </ul>
    </li>
  </ul>
  <h1>SnesTop</h1>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#jMenu").jMenu({
      ulWidth : 'auto',
      effects : {
        effectSpeedOpen : 300,
        effectTypeClose : 'slide',
        effectTypeOpen : 'slide'
      },
      animatedText : false
    });
  });
</script>

<div id="mainContainer">
