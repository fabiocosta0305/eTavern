<?php

  // configuration
  require("../includes/config.php");

  //collect some useful data about the user his sheets

  $sheets=query("select * from characters where userid=? order by char_name,system",$_SESSION["id"]);

  
  $js_function=<<<FUNCTION
function whoIsOn() {
  $.ajax({
    type: 'GET',
    url: 'bring_logged.php',
    timeout: 2000,
    success: function(data) {
      $("#userlist").html(data);
      window.setTimeout(whoIsOn, 2000);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
              //      $("#notice_div").html('Timeout contacting server..');
              //      window.setTimeout(update, 60000);
    }
});
}

$(document).ready(function() {
    whoIsOn();
});
FUNCTION;

  // render tavern

  render("tavern.php", ["title" => "Your Tavern - ".$_SESSION["realname"],
                        "realname" => $_SESSION["realname"],
                        "username" => $_SESSION["username"],
                        "sheets" => $sheets,
                        "jquery"=>$js_function])?>
?>