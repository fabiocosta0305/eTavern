<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (!isset($_POST['realname']) || empty($_POST['realname']))
            apologize("You need to provide your real name.");

        $test=query("update user set realname=?, aboutYou=?, site=?, facebook=?, twitter=?, googleplus=? where id=?",
                    $_POST['realname'], $_POST['aboutYou'], $_POST['site'], $_POST['facebook'], $_POST['twitter'],
                    $_POST['googleplus'], $_SESSION["id"]);

        if ($test === false)
            apologize("We are going into problems wih the database. Please try again.");

        redirect("/");
    }
    else
    {
        $test=query("select * from user where id=?", $_SESSION["id"]);

        if ($test === false)
            apologize("We are going into problems wih the database. Please try again.");

        
        // else render form
        $js_function=<<<FUNCTION

            $(document).ready(aboutYouFormValidation);

FUNCTION;
        render("aboutYou_edit.php", ["title" => "Information About You",
                                     "extraJS" => ["js/aboutYouVal.js","js/jquery.validate.js"],
                                     "jquery" => $js_function,
                                     "data" => $test[0],
                                     "controller" => "aboutYou.php"]);

    }

?>
