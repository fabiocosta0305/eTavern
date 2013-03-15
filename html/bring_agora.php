<?php

  // configuration
  require("../includes/config.php");

  // TODO: Setting the order (by realname or by character name - master is always firts)

$data=query("select distinct username, realname from onAgora, user where user.id=onAgora.id order by realname, username");

  if ($data===false)
      return false;
  else
    foreach($data as $user):
          $userdata='</div><div class="agoraUser">'.$user["realname"].'('.$user["username"].')</div>';
?>
<div class="users"><?=$userdata;?></div>
<?php
        endforeach;
?>
