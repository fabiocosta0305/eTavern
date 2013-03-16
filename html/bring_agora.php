<?php

  // configuration
  require("../includes/config.php");

  // TODO: Setting the order (by realname or by character name - master is always firts)

$data=query("select distinct user.id as userid, username, realname from onAgora, user where user.id=onAgora.id order by realname, username");

  if ($data===false)
      return false;
  else
    foreach($data as $user):
      if($user['userid']==$_SESSION['id'])
          $userdata=$user["realname"];
      else
      {
          $hyperlink="/userdata.php?id={$user['userid']}";
          $userdata='<a href onClick="return userdata(\''.$hyperlink.'\');">'.$user["realname"].'</a>';
      }
?>
<div class="users"><?=$userdata;?></div>
<?php
        endforeach;
?>
