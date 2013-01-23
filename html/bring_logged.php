<?php

  // configuration
  require("../includes/config.php");

  //taking the users

  $data=query("select * from user, loggedOn where user.id=loggedOn.id order by user.realname");

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