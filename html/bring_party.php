<?php

  // configuration
  require("../includes/config.php");

  //taking the users

  // TODO: Setting the order (by realname or by character name - master is always firts)

$data=query("select * from user, adv_table, characters where user.id=adv_table.userid and adv_table.charid=characters.id and advid=? and stillOn order by characters.id=0 desc, user.realname asc ",$_SESSION['advid']);

  if ($data===false)
      return false;
  else
    foreach($data as $user):
?>
        <div class="users">
          <div class="character"><?= $user["char_name"]; ?></div>
          <div class="user"><?= $user["realname"]; ?>(<?= $user["username"]; ?>)</div>
        </div>
<?php
        endforeach;
?>
<!--
<div class="users">Teste</div>
<div class="users">Teste2</div>
<div class="users">Teste3</div>
--!>