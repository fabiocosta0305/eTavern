<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    }
    else if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // get some info about the user and its binder (character sheet list)

        $userinfo=query("select * from user where id=?",$_GET['id']);

        $binder=query("select * from characters where userid=?",$_GET['id']);
        $img=get_gravatar($userinfo[0]['email'],80,
                          "https://raw.github.com/hufflepuffbr/eTavern/master/html/img/d20_80.jpg");

        if ( ($userinfo === false) || ($binder === false) )
            apologize("We are having some problems with the database, please try again later.");
        
        // else render form
        simpler_render("userinfo.php", ["title" => "Information for {$userinfo[0]['realname']}({$userinfo[0]['username']})",
                                        "userinfo" => $userinfo[0],
                                        "avatar" => $img,
                                        "binder" => $binder ]);
    }
    else
    {
        redirect('/');
    }

?>
