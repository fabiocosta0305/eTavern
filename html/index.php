<?php

  // configuration
  require("../includes/config.php");

  //collect some useful data about the user his sheets

  $sheets=query("select * from characters where userid=? order by char_name,system",$_SESSION["id"]);

  
  $js_function=<<<FUNCTION
$(document).ready(function() {
    whoIsOn();
    open_tables();
});
FUNCTION;

  // render tavern

  render("tavern.php", ["title" => "Your Tavern - ".$_SESSION["realname"],
                        "realname" => $_SESSION["realname"],
                        "username" => $_SESSION["username"],
                        "sheets" => $sheets,
                        "jquery"=>$js_function,
                        "extraJS"=>["js/whoIsOn.js"]])?>
?>
