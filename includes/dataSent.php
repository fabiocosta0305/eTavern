<?php

function dataSent($data, $user, $advid)
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

        $command=strtoupper($info[0]);

        $info[0]=" ";
        $myData=implode(" ",$info);
        
        switch($command)
        {
            case '/OFF':
                $info[0]="";
                $myData=implode(" ",$info);
                $data=query("insert into offChatLog (userid,text,advid) VALUES (?,?,?)",
                            $user, $myData,$advid); 
                break;

            case '/ME':
                $info[0]="";
                $myData=implode(" ",$info);               
                break;
                
            case '/DEFAULTDICE':
                $info[0]="";
                $myData=implode(" ",$info);

                if(!(count($info)>1))
                {
                    unset($_SESSION['defaultDice']);
                    $myData="unset the default dice";
                    break;
                }
                
                $parm=$info[1];

                $numberOfMatches=preg_match("/^([0-9]+)?d([0-9fF]+)([+-][0-9]+)?$/",$info[1]);
                
                if ($numberOfMatches == 0)
                    $myData="Error: entry $die not valid";
                else
                {
                    $_SESSION['defaultDice']=$info[1];
                    $myData="set default dice to ".$info[1];
                }
                break;

            case '/KICK':
                if (isset($_SESSION['charid']))
                {
                    $myData="master-only command!";
                    $command="/error";
                    break;
                }
                else if (count($info)<2)
                {
                    $command="/error";
                    $myData="not enought parameters";
                }
                else
                {
                    $parm=$info[1];

                    $info[1]="";
                   
                    $data=query("select userid from parties where username=? and advid=?",
                                $parm,$_SESSION['advid']);
                    
                    if ($data === false)
                    {
                        $command="/error";
                        $myData="user $parm not found or not on this table";
                    }
                    else
                    {
                        $row=$data[0];
                        $parm=$row['userid'];
                        $myData=trim(implode(" ",$info));
                    }
                }               
                break;

            case '/SECRET':
                if (count($info)<3)
                {
                    $command="/error";
                    $myData="not enought parameters";
                }
                else
                {
                    $parm=$info[1];

                    $info[1]="";
                   
                    $data=query("select id from user where username=?",
                                $parm);
                    
                    if ($data === false)
                    {
                        $command="/error";
                        $myData="user $parm not found";
                    }
                    else
                    {
                        $row=$data[0];
                        $parm=$row['id'];
                    }
                }               
                break;

            case '/FAKEDICE':
            case '/SECRETDICE':
                if (isset($_SESSION['charid']))
                {
                    $myData="master-only command!";
                    $command="/error";
                    break;
                }
            case '/DICE':
                
                if (count($info)>1)
                {
                    $parm=$info[1];
                    $info[0]=$info[1]="";
                }
                else if (isset($_SESSION['defaultDice'])) $parm=$_SESSION['defaultDice'];
                else
                {
                    $myData="tried a dice roll without a /defaultDice or giving a dice:";
                    $command="/error";
                    break;
                }
            
                error_log($parm);
               
                $myData=rollDice($parm);
                break;

            case '/WHOIS':
                if (count($info)>1)
                {
                    $parm=$info[1];
                    $info[0]=$info[1]="";

                    $data=query("
SELECT char_name, username
FROM parties
WHERE advid =  ?
AND lower(username) =  lower(?)",$_SESSION['advid'],$parm);

                    if ($data===false)
                        return false;
                    elseif (count($data)!=1)
                    {
                        error_log(count($data));
                        $myData="invalid query";
                        $command="/error";
                        break;
                    }
                    else
                    {
                        $row=$data[0];
                        $myData="$parm is {$row['char_name']}";
                        break;
                    }

                    
                }
                else
                {
                    $myData="no user given";
                    $command="/error";
                    break;
                }

                break;

            case '/WHOPLAYS':
                if (count($info)>1)
                {
                    $parm=trim($myData);

                    error_log($parm."\n".$myData);

                    $data=query("
SELECT char_name, username, realname
FROM parties
WHERE advid =  ?
AND lower(char_name) =  lower(?)",$_SESSION['advid'],$parm);

                    if ($data===false)
                        return false;
                    elseif (count($data)!=1)
                    {
                        error_log(count($data));
                        $myData="invalid query";
                        $command="/error";
                        break;
                    }
                    else
                    {
                        $row=$data[0];
                        $myData="$parm is {$row['username']} ({$row['realname']})";
                        break;
                    }
                }
                else
                {
                    $myData="no character given";
                    $command="/error";
                    break;
                }
                break;

            case '/END':
            case '/KILL':
            case '/PART':
                $data=query("insert into onChatLog (userid,text,command,parm,advid) VALUES (?,?,?,?,?)",
                            $user, "$user parted from the adventure!", $command, $parm, $advid);

                part_table();
                
                return json_encode(["end"=>true,"lastTimestamp"=>$lastTimestamp]);
                break;

            case '/CONDITION':
                if (isset($_SESSION['charid']))
                {
                    $myData="master-only command!";
                    $command="/error";
                    break;
                }
                if (count($info)>=2)
                {

                    $condition_user=$info[1];
                    $condition_type=$info[2];

                    $info[1]=$info[2]="";

                    $condition_desc=implode(" ",$info);

                    $data=query("
SELECT char_name, charid
FROM parties
WHERE stillOn
AND advid =  ?
AND lower(username) =  lower(?)
AND charid > 0",$_SESSION['advid'],$condition_user);

                    if ($data===false)
                    {
                        $myData="no valid user";
                        $command="/error";
                        break;
                    }
                    
                    $condition_charid=$data[0]['charid'];

                    $register=query("insert into conditions values (default,?,?,?,default)",
                                    $condition_charid,$condition_type,$condition_desc);

                    if ($register===false)
                        return false;

                    $myData="{$data[0]['char_name']} had received the condition $condition_type";
                    
                }
                else
                {
                    $myData="no user or condition given";
                    $command="/error";
                    break;
                }

                break;

                
            case '/CHANGECONDITION':
                if (isset($_SESSION['charid']))
                {
                    $myData="master-only command!";
                    $command="/error";
                    break;
                }
                if (count($info)>=3)
                {

                    $condition_user=$info[1];
                    $condition_type=$info[2];
                    $condition_newtype=$info[3];

                    $info[1]=$info[2]=$info[3]="";

                    $condition_desc=implode(" ",$info);

                    $data=query("
UPDATE char_conditions
   SET description=?
 WHERE advid =  ?
   AND not goneAway
   AND lower(username) =  lower(?)
   AND lower(description) = lower(?)
   AND charid > 0",$condition_newtype,$_SESSION['advid'],$condition_user,$condition_type);

                    if ($data===false)
                    {
                        $myData="no valid user";
                        $command="/error";
                        break;
                    }
                    
                    $myInfo=query("
SELECT char_name, charid
FROM parties
WHERE stillOn
AND advid =  ?
AND lower(username) =  lower(?)
AND charid > 0",$_SESSION['advid'],$condition_user);

                    $myData="{$myInfo[0]['char_name']} had the condition $condition_type changed to $condition_newtype";
                    
                }
                else
                {
                    $myData="no user or condition given";
                    $command="/error";
                    break;
                }

                break;

            case '/CHANGECONDITIONINFO':
                if (isset($_SESSION['charid']))
                {
                    $myData="master-only command!";
                    $command="/error";
                    break;
                }
                if (count($info)>=3)
                {

                    $condition_user=$info[1];
                    $condition_type=$info[2];

                    $info[1]=$info[2]="";

                    $condition_desc=implode(" ",$info);

                    $data=query("
UPDATE char_conditions
   SET value=?
 WHERE advid =  ?
   AND not goneAway
   AND lower(username) =  lower(?)
   AND lower(description) = lower(?)
   AND charid > 0",$condition_desc,$_SESSION['advid'],$condition_user,$condition_type);

                    if ($data===false)
                    {
                        $myData="no valid user";
                        $command="/error";
                        break;
                    }
                    
                    $myInfo=query("
SELECT char_name, charid
FROM parties
WHERE stillOn
AND advid =  ?
AND lower(username) =  lower(?)
AND charid > 0",$_SESSION['advid'],$condition_user);

                    $myData="{$myInfo[0]['char_name']} had the condition $condition_type changed to $condition_desc";
                    
                }
                else
                {
                    $myData="no user or condition given";
                    $command="/error";
                    break;
                }

                break;
                
            case '/CONDITIONS':

                error_log(count($info));
                $hit_user=(count($info)===2)?$info[1]:"";

                if (count($info)==2 && isset($_SESSION['charid']))
                    {
                        $myData="master-only command";
                        $command="/error";
                        break;
                    }
                
                if (count($info)!=2)
                {
                    error_log("Aqui!");
                    if (!isset($_SESSION['charid']))
                    {
                        $myData="no user";
                        $command="/error";
                        break;
                    }
                    else
                    $data=query("
SELECT *
FROM parties
WHERE stillOn
AND advid =  ?
AND lower(charid) =  lower(?)",$_SESSION['advid'],$_SESSION['charid']);
                    if ($data===false)
                        return false;

                    $hit_user=$data[0]['username'];
                        
                }
                
                $data=query("
SELECT *
FROM char_conditions
WHERE stillOn
AND advid =  ?
AND lower(username) =  lower(?)",$_SESSION['advid'],$hit_user);

                if ($data===false)
                    return false;
                elseif (count($data)===0)
                {
                    $myData="no conditions for this character";                        
                }
                else
                {
                    $conditions=[];
                    foreach($data as $condition)
                    {
                        $charname=$condition['char_name'];
                        $conditions[]=$condition['description'];
                    }
                    $myData="$charname is under the conditions ".implode(",",$conditions);
                }
                
                break;

            case '/ABOUTCONDITION':
                if (count($info)!=3)
                {
                    $myData="no user";
                    $command="/error";
                    break;
                }
                else
                {
                    $hit_user=$info[1];
                    $condition=$info[2];

                    $data=query("
SELECT *
FROM char_conditions
WHERE stillOn
and not goneAway
AND advid =  ?
AND lower(username) =  lower(?)
AND lower(description) = lower(?) ",$_SESSION['advid'],$hit_user,$condition);

                    if ($data===false)
                        return false;
                    elseif (count($data)===0)
                    {
                        $myData="no conditions for this character or condition $condition invalid!";
                    }
                    else
                    {
                        $myData="Information about the condition $condition for {$data[0]['char_name']}:{$data[0]['value']}";
                    }
                   
                }
                break;

            case '/REMOVECONDITION':
            case '/REVOKECONDITION':
                if (count($info)!=3)
                {
                    $myData="no user";
                    $command="/error";
                    break;
                }
                else
                {
                    $hit_user=$info[1];
                    $condition=$info[2];

                    $here_info=query("
SELECT *
FROM char_conditions
WHERE stillOn
and not goneAway
AND advid =  ?
AND lower(username) =  lower(?)
AND lower(description) = lower(?) ",$_SESSION['advid'],$hit_user,$condition);
                    
                    $this_data=query("
UPDATE char_conditions
   SET goneAway=true
 WHERE advid =  ?
   AND lower(username) =  lower(?)
   AND lower(description) = lower(?) ",$_SESSION['advid'],$hit_user,$condition);

                    
                    if (count($here_info)===0)
                    {
                        $myData="no conditions for this character or condition $condition invalid!";
                    }
                    else
                    {
                        $myData="Condition $condition for {$here_info[0]['char_name']} revoked";
                    }
                   
                }
                break;

            case '/PARTY':
                error_log("SELECT char_name, username FROM parties WHERE advid = '{$_SESSION['advid']}'");

                $data=query("SELECT char_name, username
FROM parties
WHERE advid = ? order by char_name='MASTER' desc, char_name asc",$_SESSION['advid']);

                $myData="<table width=100% style='charlist'><tr><th>User</th><th>Character</th></tr>";                    

                foreach($data as $char)
                    $myData.="<tr><td>{$char['username']}</td><td>{$char['char_name']}</td></tr>";

                $myData.="</table>";

                break;
                
            default:
                $myData="had tried a invalid command:".$command;
                $command="/error";

        }
    }
    
    /* $data=query($query, $user, $myData); */
    $data=query("insert into onChatLog (userid,text,command,parm,advid) VALUES (?,?,?,?,?)",
                $user, $myData, $command, $parm, $advid); 
   
    return json_encode(["end"=>false,"lastTimestamp"=>$lastTimestamp]);
}

?>