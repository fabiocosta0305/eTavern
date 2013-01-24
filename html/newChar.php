<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $user=$_SESSION["id"];
        // mandatory fields check
        if(empty($_POST["char_name"]))
            apologize("You need to insert a name for your character");
        if(empty($_POST["system"]))
            apologize("You need to set the RPG system for your character");
        if(empty($_POST["base_desc"]))
            apologize("You need to insert a basic description for your character");

        $charname=htmlspecialchars(trim($_POST["char_name"]));
        $system=htmlspecialchars(trim($_POST["system"]));
        $basedesc=htmlspecialchars(trim($_POST["base_desc"]));
        // checking if there's already a character with this name and system on user's
        // sheets

        $test=query("select * from characters where userid=? and char_name=? and system=?",
                    $user,$charname,$system);

        if ($test === false)
            apologize("You already have a character for this system with this name.");

        $sheet=trim($_POST["sheet"])==="Character Sheet(optional)"?"":htmlspecialchars(trim($_POST["sheet"]));
        $history=trim($_POST["history"])==="History(optional)"?"":htmlspecialchars(trim($_POST["history"]));

        $test=query("insert into characters (userid,char_name,system,base_desc,sheet,history) values (?,?,?,?,?
,?)",
                    $user,$charname,$system, $basedesc, $sheet,$history);
        
        if ($test === false)
            apologize("We had a problem with the database! Please try later or contact the administration.");

        $data["char_name"]=$charname;
        $data["system"]=$system;
        $data["base_desc"]=$basedesc;
        $data["sheet"]=$sheet;
        $data["history"]=$history;
        
        render("newChar_result.php", ["title" => "new character {$_POST["char_name"]} created!",
                                      "data"=>$data,"permissions"=>false]);

    }
    else
    {
        // else render form
        render("newChar_registry.php", ["title" => "Create new character",
                                        "controller" => "newChar.php"]);
    }

?>
