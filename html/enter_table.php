<?php

// configuration
require("../includes/config.php"); 

// if came from a link
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $binder=[];
    // If no adventure id sent, apologize
    
    if (!isset($_GET["adventure"]))
        apologize("No adventure chosen!");

    // Double check if the adventure exists

    $adventure=htmlspecialchars($_GET["adventure"]);

  $data=query("
select adventure.*, user.username as masterUserName, user.realname as masterRealName
  from user, adventure
 where user.id=adventure.masterid
   and advid=?",$adventure);

    if ($data===false)
        apologize("We had some problem on the database! Please wait or contact the administrator!");

    if (count($data)===0)
        apologize("The adventure provided is invalid or had ended!");

    $advdata=$data[0];

    // check the character binder for the player

    $data=query("select char_name, system, id from characters where userid=?",$_SESSION['id']);

    if ($data===false)
        apologize("We had some problem on the database! Please wait or contact the administrator!");

    if (count($data)===0)
        apologize("You need to insert some characters on the binder before enter on a table!");

    foreach ($data as $char)
        $binder[]=["name"=> "{$char['char_name']}({$char['system']})", "id"=>$char['id']];

    render("choose_char.php", ["title" => "choose you character!",
                                  "table"=>$advdata,
                                  "binder"=>$binder]);

    
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if ($_POST['characters']===0)
        apologize("You need to choose a character!");

    $_SESSION['advid']=$_POST['advid'];
    $_SESSION['defaultDice']=$_POST['defaultDice'];
    $_SESSION['charid']=$_POST['characters'];

    redirect("/chat.php");

}
else
    // otherwise, redirects back to the tavern
    redirect("/");
?>