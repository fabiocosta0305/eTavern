<?php

  // configuration
  require("../includes/config.php");

  //collect some useful data about the user his sheets

  $sheets=query("select * from characters where userid=? order by char_name,system",$_SESSION["id"]);

  $userinfo=query("select username,realname from user where id=?",$_SESSION["id"]);

  
  $js_function=<<<FUNCTION
$(document).ready(function() {
    whoIsOn();
    open_tables();
});
FUNCTION;

  // render tavern

  render("tavern.php", ["title" => "Your Tavern - ".$_SESSION["realname"],
                        "realname" => $userinfo[0]['realname'],
                        "username" => $userinfo[0]['username'],
                        "sheets" => $sheets,
                        "jquery"=>$js_function,
                        "extraJS"=>["js/whoIsOn.js"]]);
?>
