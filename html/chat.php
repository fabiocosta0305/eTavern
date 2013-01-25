<?php

    // configuration
    require("../includes/config.php"); 

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

function dataSent($data, $user)
{
    $query="";
    $myData=$data;
    $command="";
        
    $dataQuery=query("select UNIX_TIMESTAMP(CURRENT_TIMESTAMP) as last");

    if ($dataQuery === false)
        return (false);

    $lastTimestamp=$dataQuery[0]['last'];

    // Now some parsing...

    if ($data[0]==="/")
    {
        $info=explode(" ",$myData);

        $command=$info[0];

        $info[0]=" ";
        $myData=implode(" ",$info);
        
        switch($command)
        {
            case '/off':
                $query="insert into offChatLog (userid,text) VALUES (?,?);";
                $info[0]="";
                $myData=implode(" ",$info);
                break;
            case '/me':
                $query="insert into onChatLog (userid,text,command) VALUES (?,?,'$command');";
                $info[0]="";
                $myData=implode(" ",$info);               
                break;
        }
    }
    else
        $query="insert into onChatLog (userid,text) VALUES (?,?);";

    error_log($query);
    
    $data=query($query, $user, $myData); 
   
    return($lastTimestamp);
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
    $dataQuery=query("select username,text,unix_timestamp(postedOn) as postedOn,command from user,onChatLog where user.id=onChatLog.userid and unix_timestamp(postedOn) > ? order by postedOn",$lastTimestamp);

    if ($dataQuery === false)
        return (false);
      
    foreach($dataQuery as $line)
    {
        switch($line['command'])
        {
            case '/me':
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
