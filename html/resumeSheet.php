<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // If the link was copied, it will redirect the user back to tavern
        if (!isset($_SESSION['advid']))
            redirect("/");

        // Get all the information for the sheet, using parties view and character table        
        $test=query("
select *
  from parties p, characters c
 where c.id=?
   and p.advid=?
   and c.id=p.charid", $_GET["charid"], $_SESSION['advid']);

        error_log("Estive aqui!");
        
        if ($test === false)
            apologize("We are going into problems wih the database. Please try again.");

        // If the link was copied, it will redirect the user back to tavern
        if (count($test) === 0)
            redirect("/");

        error_log("Estive aqui!");
        // Get the character conditions
        $conditions=query("
select *
  from conditions
 where charid=? and not goneAway", $_GET["charid"]);

        error_log("Estive aqui!");
        if ($test === false)
            apologize("We are going into problems wih the database. Please try again.");
       
        // else send information for the template
        $js_function=<<<FUNCTION

FUNCTION;
        simpler_render("resumeSheet.php", ["title" => "Character sheet for ".$test[0]['char_name'],
                                           "char_info" => $test[0],
                                           "conditions" => $conditions]);

    }
    else
    {
        redirect("/");
    }
?>
