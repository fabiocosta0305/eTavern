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
                echo dataSent($_POST['info'], $_SESSION['id']);
                break;
            case 'offChatGet':
                $data = offChatGet($_POST['lastTimestamp'], $_SESSION['id']);
                echo $data;
                break;
            case 'onChatGet':
                $data = onChatGet($_POST['lastTimestamp'], $_SESSION['id']);
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

function offChatGet($lastTimestamp, $user)
{
    $chatData="";
    $timestamp=$lastTimestamp;
    $dataQuery=query("select username,text,unix_timestamp(postedOn) as postedOn from user,offChatLog where user.id=offChatLog.userid and unix_timestamp(postedOn) > ? order by postedOn ",$lastTimestamp);

    if ($dataQuery === false)
        return (false);
      
    foreach($dataQuery as $data)
    {
        $chatData.="<div class=chatText><span class=chatUser>".$data['username'].":</span> ".$data['text']."</div>";
        if ($data['postedOn'] > $timestamp) $timestamp=$data['postedOn'];
    }

    $data2send=["chatData"=>$chatData,"lastTimestamp"=>$timestamp];
        
    return(json_encode($data2send));
}

function onChatGet($lastTimestamp, $user)
{
    $chatData="";
    $timestamp=$lastTimestamp;
    $dataQuery=query("
select  userid,username,text,unix_timestamp(postedOn) as postedOn,command,parm
  from user,onChatLog
 where user.id=onChatLog.userid
   and unix_timestamp(postedOn) > ? order by postedOn",$lastTimestamp);

    if ($dataQuery === false)
        return (false);
      
    foreach($dataQuery as $line)
    {
        switch($line['command'])
        {
            case '/off':
                break;
                
            case '/secretDice':
                if ($line['userid']===$_SESSION['id'])
                    $chatData.="<div class=chatInfo>".$line['username']." rolled ".$line['parm']." with a ".$line['text']."</div>";
                else
                    $chatData.="<div class=chatInfo>".$line['username']." rolled ".$line['parm']."</div>";
                break;

            case '/fakeDice':
                error_log("Aqui!");
                $rolled=($line['userid']===$_SESSION['id'])?'fake-rolled':'rolled';
                $chatData.="<div class=chatInfo>".$line['username']." $rolled ".$line['parm']."</div>";
                break;
                
            case '/dice':
                $chatData.="<div class=chatInfo>".$line['username']." rolled ".$line['parm']." with a ".$line['text']."</div>";
                break;

            case '/secret':
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
                
            case '/defaultDice':
            case '/me':
            case '/error':
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
