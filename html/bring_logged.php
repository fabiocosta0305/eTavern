<?php

  // configuration
  require("../includes/config.php");

  //taking the users

  $data=query("select user.id as userid, realname, email from user, loggedOn where user.id=loggedOn.id order by user.realname");

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
  <div class="users"><?= $userdata; ?></div>
<?php
        endforeach;
?>
