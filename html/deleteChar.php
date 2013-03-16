<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $user=$_SESSION["id"];

        /* $sheetid=$_GET["sheetid"]; */

        $data=sscanf($_POST["sheetid"], "%d %s",$sheetid, $tail);

        if (gettype($sheetid)!="integer")
            apologize("Invalid sheet");
        
        $test=query("delete from characters where id=? and userid=?",$sheetid,$_SESSION['id']);

        if ($test === false)
            apologize("This sheet is not on your binder.");

        redirect("/");


    }
    else
    {
        // else render form
        redirect("/");
    }

?>
