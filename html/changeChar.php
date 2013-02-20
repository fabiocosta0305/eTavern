<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["sheetid"]))
            {
                $test=query("select * from characters where id=?", $_POST["sheetid"]);

                if ($test === false)
                    apologize("We are going into problems wih the database. Please try again.");

                if (count($test)==0)
                    apologize("Sheet not found on your binder");

                $data=$test[0];

                        $js_function=<<<FUNCTION

            $(document).ready(charFormValidation);

            function openWikiHelp()
        {
            window.open("/help/wikiHelp.html",help,"dependent,height=300,widht=300");
        }
FUNCTION;
                // else render form
render("newChar_registry.php", ["title" => "Change character".$data["char_name"],
                                "extraJS" => ["js/newCharVal.js","js/jquery.validate.js"],
                                "jquery" => $js_function,
                                "controller" => "changeChar.php",
                                "data"=>$data]);
            }
            else
            {
                // mandatory fields check
                if(empty($_POST["char_name"]))
                    apologize("You need to insert a name for your character");
                if(empty($_POST["system"]))
                    apologize("You need to set the RPG system for your character");
                if(empty($_POST["base_desc"]))
                    apologize("You need to insert a basic description for your character");

                $charname=htmlspecialchars($_POST["char_name"]);
                $system=htmlspecialchars($_POST["system"]);
                $basedesc=htmlspecialchars($_POST["base_desc"]);
                // checking if there's already a character with this name and system on user's
                // sheets

                $sheet=trim($_POST["sheet"])==="Character Sheet(optional)"?"":trim(htmlspecialchars($_POST["sheet"]));
                $history=trim($_POST["history"])==="History(optional)"?"":trim(htmlspecialchars($_POST["history"]));

                $test=query("update characters set char_name=?,system=?,base_desc=?,sheet=?,history=? where id=?",
                            $charname,$system,$basedesc,$sheet,$history,$_POST["id"]);
        
        if ($test === false)
            apologize("We had a problem with the database! Please try later or contact the administration.");

        $data["char_name"]=$charname;
        $data["system"]=$system;
        $data["base_desc"]=$basedesc;
        $data["sheet"]=$sheet;
        $data["history"]=$history;
        $data["id"]=$_POST['id'];

       
        render("newChar_result.php", ["title" => "Character {$_POST["char_name"]} changed!",
                                      "data"=>$data,"permissions"=>true,"id"=>$_SESSION['id']]);
            }
    }
    else
    {
        redirect("/");
    }

?>
