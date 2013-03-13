<?php

// configuration
require("../includes/config.php"); 

// if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
       
        switch ($_POST['command'])
        {
            case 'dataSent':

                $data=query("insert into agoraChatLog (userid,text) values (?,?)",$_SESSION['id'],$_POST['info']);

                if ($data===false)
                    return;

                break;

            case 'agoraGet':
                $chatData="";

                $data=query("select username, text from agoraChatLog, user where user.id=agoraChatLog.userid and unix_timestamp(postedOn)>?",$_POST['lastTimestamp']);

                if ($data===false)
                    return;

                $timedata=query("select unix_timestamp(CURRENT_TIMESTAMP) as timestamp");
                if ($timedata===false)
                    return;

                $timestamp=$timedata[0]['timestamp'];

                foreach($data as $info)
                {
                    $chatData.="<div class='agoraChat'><span class='agoraChatUser'>{$info['username']} :</span> {$info['text']}</div>\n";
                }
                
                $data2send=["agoraData"=>$chatData,"lastTimestamp"=>$timestamp];
                echo json_encode($data2send);
                break;
                
            case 'firstTimestamp':
                $data=query("select unix_timestamp(CURRENT_TIMESTAMP) as timestamp");
                $row=$data[0];
                echo json_encode(["lastTimestamp"=>$row['timestamp']]);
                break;

        }
    }
    else
    {

        $data=query("insert into onAgora (id) values (?)",$_SESSION['id']);
        
        $js=<<<FUNCTION
$(document).ready(function() {
        whoIsOnAgora();

        $("#agoraForm").submit(sendAgora);
        takeAgoraTimestamp();
        setInterval(agoraGet, 5000);
        setInterval(whoIsOnAgora, 5000);        
    });
FUNCTION;
render ("agoraWindow.php", ["title" => "Agora - Open Chat",
                           "jquery" => $js,
                           "extraJS"=> ["js/agora.js"]]);
    }



?>
