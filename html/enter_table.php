<?php

// configuration
require("../includes/config.php"); 

if (isset($_SESSION['advid']))
{
    // this is a way to warrant that people can enter back on an adventure if the table was not ended
    
    $query=query("select * from adventure where advid=? and not ended",$_SESSION['advid']);
    
    if ($query === false)
        apologize("We had some problem on the database! Please wait or contact the administrator!");
    
    if ($query === 0)
    {
        
        part_table();
        
        redirect("/");
    }
    else
        redirect("/chat.php");
    
    
}// if came from a link
elseif ($_SERVER["REQUEST_METHOD"] == "GET")
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

    $data=query("select char_name, system, id from characters where userid=? and id not in (select charid from used_chars where userid=? and advid=?)",$_SESSION['id'],$_SESSION['id'],$_GET['adventure']);
    /* $data=query("select char_name, system, id from characters, adv_table where characters.userid=? and adv_table.userid=? and adv_table.advid=? and characters.charid<>adv_table.charid",$_SESSION['id']); */

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

    // registering the character on the table
    
    $sql=query("insert into adv_table values (?,?,?,DEFAULT)", $_POST['advid'],$_SESSION['id'],$_POST['characters']);

    error_log($_POST['characters']);
    
    $_SESSION['advid']=$_POST['advid'];
    $_SESSION['defaultDice']=$_POST['defaultDice'];
    $_SESSION['charid']=$_POST['characters'];
    
    redirect("/chat.php");
    
}
else
    redirect("/");
?>