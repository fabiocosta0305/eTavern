<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $user=$_SESSION["id"];

        /* $sheetid=$_GET["sheetid"]; */

        $data=sscanf($_GET["sheetid"], "%d %s",$sheetid, $tail);

        if (gettype($sheetid)!="integer")
            apologize("Invalid sheet");
        
        $test=query("select * from characters where id=?",$sheetid);

        if ($test === false)
            apologize("We have a problem with your query.");

        $sheet=$test[0];

        if ($sheet["userid"]===$_SESSION["id"])
        {
            $permissions=true;;
        }

       
        render("newChar_result.php", ["title" => "Character sheet for {$sheet["char_name"]}!",
                                      "data"=>$sheet,
                                      "permissions"=>isset($permissions)?true:false,
                                      "id"=>$user]);

    }
    else
    {
        // else render form
        redirect("/");
    }

?>
