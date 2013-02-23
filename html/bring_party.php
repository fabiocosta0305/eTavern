<?php

  // configuration
  require("../includes/config.php");

  //taking the users

  // For security and for using on a /kick command
  if (!isset($_SESSION['advid']))
      {
          echo "kicked out";
          return true;
      }

  // TODO: Setting the order (by realname or by character name - master is always firts)

$data=query("select * from parties where advid=? order by char_name='MASTER' desc, char_name asc",$_SESSION['advid']);

  if ($data===false)
      return false;
  else
    foreach($data as $user):
      if($user['userid']==$_SESSION['id'])
          $userdata='<div class="users"><div class="character">'.$user["char_name"].'</div><div class="user">'.$user["realname"].'('.$user["username"].')</div>';
      else
      {
          $hyperlink="/userdata.php?id={$user['userid']}";
          $userdata='<a href onClick="return userdata(\''.$hyperlink.'\');">';
          $userdata.='<div class="users"><div class="character">'.$user["char_name"].'</div><div class="user">'.$user["realname"].'('.$user["username"].')</div></a>';

      }
?>
<div class="users"><?=$userdata;?></div>
<?php
        endforeach;
?>
<!--
<div class="users">Teste</div>
<div class="users">Teste2</div>
<div class="users">Teste3</div>
--!>