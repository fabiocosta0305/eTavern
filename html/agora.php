<?php

// configuration
require("../includes/config.php"); 

// old people will get out (>3 minutes of inactivity);

cleanAgora();

// if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
       
        switch ($_POST['command'])
        {
            case 'dataSent':

                $myData=$_POST['info'];

                // break the line into parts (first one, if preceded by a slash, is a command)

                if ($_POST['info'][0]==="/")
                {
                    $info=explode(" ",$_POST['info']);

                    error_log($info[0]);
                    
                    switch (strtoupper($info[0]))
                    {
                        case '/PART':

                            error_log("Aqui!");
                            
                            $data=query("DELETE FROM onAgora WHERE id=?",$_SESSION['id']);
                            
                            echo "kicked out";

                            $data=query("SELECT * FROM user WHERE id=?",$_SESSION['id']);
                            
                            $myData="{$data[0]['realname']}({$data[0]['username']}) had parted from the Agora";
                            
                            break;
                    }

                    $myData="Information: ".$myData;
                }

                $data=query("insert into agoraChatLog (userid,text) values (?,?)",$_SESSION['id'],$myData);

                if ($data===false)
                    return;

                $data=query("replace into onAgora values (?,now())");

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
                    $myData=explode(" ", $info['text']);
                    if (trim($myData[0])==="Information:")
                    {
                        $chatData.="<div class='agoraChat'>{$info['text']}</div>\n";
                    }
                    else
                    {
                        $chatData.="<div class='agoraChat'><span class='agoraChatUser'>{$info['username']} :</span> {$info['text']}</div>\n";
                    }
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
