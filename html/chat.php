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
    $parm="";
        
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
            case '/dice':
                $parm=$info[1];
                $info[0]=$info[1]="";
                $myData=rollDice($parm);
                break;
        }
    }
    else
        $query="insert into onChatLog (userid,text) VALUES (?,?);";

    
    /* $data=query($query, $user, $myData); */
    $data=query("insert into onChatLog (userid,text,command,parm) VALUES (?,?,?,?)",
                $user, $myData, $command, $parm); 
   
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
    $dataQuery=query("
select username,text,unix_timestamp(postedOn) as postedOn,command,parm
  from user,onChatLog
 where user.id=onChatLog.userid
   and unix_timestamp(postedOn) > ? order by postedOn",$lastTimestamp);

    if ($dataQuery === false)
        return (false);
      
    foreach($dataQuery as $line)
    {
        switch($line['command'])
        {
            case '/me':
                $chatData.="<div class=chatText><span class=chatUser>".$line['username'].":</span> ".$line['text']."</div>";
                break;
            case '/dice':
                $chatData.="<div class=chatInfo>".$line['username']." rolled ".$line['parm']." with a ".$line['text']."</div>";
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

function rollDice($die)
{
   
    $data=[];
    $dicePool=[];
    $result=0;
    $roll="";
    
    $numberOfMatches=preg_match("/^([0-9]+)?d([0-9fF]+)([+-][0-9]+)?$/",$die,$data);

    error_log(print_r($data));
    
    if ($numberOfMatches == 0)
        return ("Error: entry $die not valid");

    $numberOfMatches=count($data);

    if(!isset($data[1]) || empty($data[1]))
        $data[0]=1;

    error_log(implode("|", $data));
    
    if (gettype($data[1])!="integer")
    {
        $dices=1;
        $diceFace=$data[1];
    }
    else
    {
        $dices=$data[1];
        $diceFace=$data[3];
    }

    for ($i=0; $i < $dices; $i++)
    {
        srand();

        if (gettype($diceFace)=="integer")
            $rolledDice=mt_rand(1,$diceFace);
        else
            $rolledDice=mt_rand(-1,1);

        $dicePool[]=$rolledDice;
        
        $result+=$rolledDice;
    }

    $roll=implode(", ",$dicePool);

    $roll.=" = $result";

    if ($numberOfMatches==4)
    {
        $result+=$data[3];
        $roll=$roll . sprintf(" +%d = %d",$data[3],$result);
    }

    return $roll;
}

?>
