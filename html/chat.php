<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    }
    else
    {
        render ("chatWindow.php", ["title" => "Table"]);
    }

?>
