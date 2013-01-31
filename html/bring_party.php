<?php

  // configuration
  require("../includes/config.php");

  //taking the users

$data=query("select * from user, adv_table where user.id=adv_table.userid and advid=? and stillOn order by user.realname ",$_SESSION['advid']);

  if ($data===false)
      return false;
  else
    foreach($data as $user):
?>
        <div class="users"><?= $user["realname"]; ?></div>
<?php
        endforeach;
?>
<!--
<div class="users">Teste</div>
<div class="users">Teste2</div>
<div class="users">Teste3</div>
--!>