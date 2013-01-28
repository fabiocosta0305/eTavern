<?php

// configuration
require("../includes/config.php"); 
require_once("../includes/diceRoller.php");
require_once("../includes/dataSent.php");

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        switch ($_POST['command'])
        {
            case 'dataSent':
                echo dataSent($_POST['info'], $_SESSION['id'], $_SESSION['advid']);
                break;
            case 'offChatGet':
                $data = offChatGet($_POST['lastTimestamp'], $_SESSION['id'], $_SESSION['advid']);
                echo $data;
                break;
            case 'onChatGet':
                $data = onChatGet($_POST['lastTimestamp'], $_SESSION['id'], $_SESSION['advid']);
                echo $data;
                break;
            case 'firstTimestamp':
                $data=query("select unix_timestamp(CURRENT_TIMESTAMP) as timestamp");
                $row=$data[0];
                echo json_encode(["lastTimestamp"=>$row['timestamp']]);

        }
    }
    else
    {
        $js=<<<FUNCTION
$(document).ready(function() {
        takeFirstTimestamp();
        $("#chatForm").submit(sendChat);
        setInterval(offChatGet, 2000);
        setInterval(onChatGet, 2000);
    });
FUNCTION;
render ("chatWindow.php", ["title" => "Table",
                           "jquery" => $js,
                           "extraJS"=> ["js/chat.js"]]);
    }



function offChatGet($lastTimestamp, $user, $advid)
{
    $chatData="";
    $timestamp=$lastTimestamp;
    $dataQuery=query("
select username,text,unix_timestamp(postedOn) as postedOn
  from user,offChatLog,adventure
 where user.id=offChatLog.userid
  and adventure.advid=offChatLog.advid
  and unix_timestamp(postedOn) > ?
  and adventure.advid=?
order by postedOn",$lastTimestamp,$advid);

    if ($dataQuery === false)
        return (false);

    error_log("Aqui!");
    
    foreach($dataQuery as $data)
    {
        $chatData.="<div class=chatText><span class=chatUser>".$data['username'].":</span> ".$data['text']."</div>";
        if ($data['postedOn'] > $timestamp) $timestamp=$data['postedOn'];
    }

    $data2send=["chatData"=>$chatData,"lastTimestamp"=>$timestamp];
        
    return(json_encode($data2send));
}

function onChatGet($lastTimestamp, $user, $advid)
{
    $chatData="";
    $timestamp=$lastTimestamp;
    $dataQuery=query("
select  userid,username,text,unix_timestamp(postedOn) as postedOn,command,parm
  from user,onChatLog,adventure
 where user.id=onChatLog.userid
   and unix_timestamp(postedOn) > ?
   and adventure.advid=onChatLog.advid
   and adventure.advid=?
 order by postedOn",$lastTimestamp,$advid);

    if ($dataQuery === false)
        return (false);
      
    foreach($dataQuery as $line)
    {
        switch(strtoupper($line['command']))
        {
            case '/OFF':
                break;
                
            case '/SECRETDICE':
                if ($line['userid']===$_SESSION['id'])
                    $chatData.="<div class=chatInfo>".$line['username']." rolled ".$line['parm']." with a ".$line['text']."</div>";
                else
                    $chatData.="<div class=chatInfo>".$line['username']." rolled ".$line['parm']."</div>";
                break;

            case '/FAKEDICE':
                error_log("Aqui!");
                $rolled=($line['userid']===$_SESSION['id'])?'fake-rolled':'rolled';
                $chatData.="<div class=chatInfo>".$line['username']." $rolled ".$line['parm']."</div>";
                break;
                
            case '/DICE':
                $chatData.="<div class=chatInfo>".$line['username']." rolled ".$line['parm']." with a ".$line['text']."</div>";
                break;

            case '/SECRET':
                if ($line['userid']===$_SESSION['id'])
                {
                    $data2=query("select username from user where id=?",$line['parm']);

                    $row=$data2[0];
                    
                    $chatData.="<div class=secretChatText><span class=secretChatUser>".$line['username']." (secret to {$row['username']}):</span> ".$line['text']."</div>";
                }
                else if ($line['parm']==$_SESSION['id'])
                {
                    $chatData.="<div class=secretChatText><span class=secretChatUser>".$line['username']." (secret to you):</span> ".$line['text']."</div>";
                }
                break;
                
            case '/DEFAULTDICE':
            case '/ME':
            case '/ERROR':
                $chatData.="<div class=chatText><span class=chatUser>".$line['username'].":</span> ".$line['text']."</div>";
                break;
            default:
                $chatData.="<div class=chatDesc>".$line['text']."</div>";
                break;
                
        }
        if ($line['postedOn'] > $timestamp) $timestamp=$line['postedOn'];
    }

    $data2send=["chatData"=>$chatData,"lastTimestamp"=>$timestamp];

    return(json_encode($data2send));
}

?>
