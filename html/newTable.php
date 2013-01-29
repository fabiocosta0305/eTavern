<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // sanity check for variables
        
        if (empty($_POST['name']))
            apologize("You need to provide an adventure name.");

        if (empty($_POST['system']))
            apologize("You need to provide an adventure system.");

        if (isset($_POST['defaultDice']) && !empty($_POST['defaultDice']))
        {
            $numberOfMatches=preg_match("/^([0-9]+)?d([0-9]+|f)([+-][0-9]+)?$/i",$_POST['defaultDice']);
            if ($numberOfMatches == 0)
                apologize ("dice format not correct");
        }

        // generates a unique id

        do
        {
            $advid=uniqid("etavern_",TRUE);
        } while (count(query("select * from adventure where advid=?",$advid))!==0);

        // the query as a condition makes double sure of a unique id will be produced

        // create info for the table and registering it into the system

        $sql=query("insert into adventure values (?,?,?,?,?,?,DEFAULT,DEFAULT)",
                   $advid,$_SESSION['id'],$_POST['name'],$_POST['system'],$_POST['history'],$_POST['defaultDice']);

        if ($sql===false)
            apologize("We had a problem with the database. Please try again later!");

        // everything okay, define some variables redirects to the chat php

        $_SESSION['advid']=$advid;
        $_SESSION['defaultDice']=$_POST['defaultDice'];

        redirect("/chat.php");

    }
    else
    {
        render("table_create.php", ["title" => "Create new Table"]);
    }

?>
