<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    }
    else
    {
        $js=<<<FUNCTION
$(document).ready(function() {
    $("#chatForm").submit(function(){
            $("#onChatData").append($("#chatEntry").val()+"<br/>");
            $('#onChatData').animate({scrollTop: $('#onChatData').attr("scrollHeight")}, 500);
            $('#offChatForm').animate({scrollTop: $('#offChatData').attr("scrollHeight")}, 500);
            return false;
    });
});
FUNCTION;
render ("chatWindow.php", ["title" => "Table",
        "jquery" => $js]);
    }

?>
