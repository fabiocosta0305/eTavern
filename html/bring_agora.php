<?php

  // configuration

  require("../includes/config.php");

  // check if the user is still on the agora or had timed out

  $kick=query("select * from onAgora where id=?",$_SESSION['id']);

  if (count($kick)===0)
      {
          echo "kicked out";
          return true;
      }

$data=query("select distinct user.id as userid, username, realname, email from onAgora, user where user.id=onAgora.id order by realname, username");

  if ($data===false)
      return false;
  else
    foreach($data as $user):
      $img=get_gravatar($user['email'],32);
  $userdata="<img src='$img'/>";
      if($user['userid']==$_SESSION['id'])
          $userdata.=$user["realname"];
      else
      {
          $hyperlink="/userdata.php?id={$user['userid']}";
          $userdata.='<a href onClick="return userdata(\''.$hyperlink.'\');">'.$user["realname"].'</a>';
      }
?>
<div class="users"><?=$userdata;?></div>
<?php
        endforeach;
?>
