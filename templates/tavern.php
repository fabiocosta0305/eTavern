<div id="logTop">
  <div class="information">
    Hi, <?= $realname;?>(<?= $username?>).
  </div>
  <div id="menu">
    <a href="logout.php">Log Out</a>
  </div>
</div>
<div class="tavern">
  <div class="people">
    <div id="userinfo">
      <p class="headers">Your information:</p>
      <div class="newChar">
        <a href="aboutYou.php">More about you</a>
      </div>
    </div>
    <div id="chars">
      <p class="headers">Your characters:</p>
      <div class="charlist">
        <?php
          if ($sheets === false)
              print("Problem on database!");
          else if (count($sheets)===0)
              print("No sheets on your binder.");
          else
            foreach($sheets as $sheet):
        ?>
        <div class="charEntry">
          <a class="charlinks" href="sheetInfo.php?sheetid=<?= $sheet['id']?>">
            <?= $sheet['char_name']?><span class="charsystem">(<?= $sheet['system']?>)</span></a></div>
        <?php
                endforeach;
        ?>
      </div>
      <div class="newChar">
        <a href="newChar.php">Add New Character</a></div>
    </div>
  </div>
  <div class="info">
    <div class="tables">
      <p class="headers">Tables</p>
    </div>
    <div class="loggedOn">
      <p class="headers">Who is on?</p>
      <div id="userlist">
      </div>
    </div>
  </div>
</div>
