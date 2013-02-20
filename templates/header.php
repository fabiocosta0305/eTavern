<!DOCTYPE html>

<html>

    <head>

        <link href="css/bootstrap.css" rel="stylesheet"/>
        <link href="css/bootstrap-responsive.css" rel="stylesheet"/>
        <link href="css/styles.css" rel="stylesheet"/>
        <link href="css/chat.css" rel="stylesheet"/>

        <?php if (isset($title)): ?>
            <title>eTavern - Your Tabletop RPG Online: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>eTavern - Your Tabletop RPG Online</title>
        <?php endif ?>

        <script src="js/jquery-1.9.0.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/scripts.js"></script>
<?php
   if (isset($extraJS))
      foreach ($extraJS as $file):
?>
<script src="<?= $file;?>"></script>
<?php
      endforeach;
?>

        <?php if (isset($jquery)) :?>
          <script>
        <?= $jquery; ?>
          </script>
        <?php endif; ?>

    </head>

    <body>

        <div class="container-fluid">

            <div id="top">
                <a href="/"><img alt="eTavern - Your Tabletop RPG Online" src="img/logo.png"/></a>
            </div>

            <div id="middle">

