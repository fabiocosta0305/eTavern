<?php

  // configuration
  require("../includes/config.php");

  //collect some useful data about the user his sheets

  $sheets=query("select * from characters where userid=? order by char_name,system",$_SESSION["id"]);

  $userinfo=query("select username,realname,email from user where id=?",$_SESSION["id"]);

  // generate some JavaScript it'll be used on the index
  
  $js_function=<<<FUNCTION
$(document).ready(function() {
    whoIsOn();
    open_tables();
});
FUNCTION;

// try to retrieve a avatar based on Gravatar system

// NOTE: I hardcoded the icon for the "default" user, pointing to my GitHub, because of restrictions from the free hosting

$img=get_gravatar($userinfo[0]['email'],80,"https://raw.github.com/hufflepuffbr/eTavern/master/html/img/d20_80.jpg");

  // render tavern

  render("tavern.php", ["title" => "Your Tavern - ".$_SESSION["realname"],
                        "realname" => $userinfo[0]['realname'],
                        "username" => $userinfo[0]['username'],
                        "sheets" => $sheets,
                        "jquery"=>$js_function,
                        "extraJS"=>["js/whoIsOn.js"],
                        "img"=>$img]);
?>
