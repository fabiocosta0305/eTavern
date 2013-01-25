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
    });
FUNCTION;
render ("chatWindow.php", ["title" => "Table",
                           "jquery" => $js,
                           "extraJS"=> ["js/chat.js"]]);
    }

function dataSent($data, $user)
{
    $dataQuery=query("select UNIX_TIMESTAMP(CURRENT_TIMESTAMP) as last");

    if ($dataQuery === false)
        return (false);

    $lastTimestamp=$dataQuery[0]['last'];
    
    $data=query("insert into offChatLog (userid,text) VALUES (?,?);", $user, $data); 
   
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
        $chatData.="<div class=chatText>".$data['username'].":".$data['text']."</div>";
        //        error_log($chatData);
        if ($data['postedOn'] > $timestamp) $timestamp=$data['postedOn'];
    }

    $data2send=["chatData"=>$chatData,"lastTimestamp"=>$timestamp];
        
    return(json_encode($data2send));
}

?>
