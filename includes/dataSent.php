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

        $command=$info[0];

        $info[0]=" ";
        $myData=implode(" ",$info);
        
        switch(strtoupper($command))
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

            case '/SECRET':
                if (count($info)!==3)
                {
                    $command="/error";
                    $myData="not enought parameters";
                }
                else
                {
                    $parm=$info[1];
                                       
                   
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
SELECT characters.char_name, user.username
FROM adventure, user, characters, adv_table
WHERE adventure.advid = adv_table.advid
AND adv_table.stillOn
AND adv_table.userid = user.id
AND characters.id = adv_table.charid
AND adventure.advid =  ?
AND lower(user.username) =  lower(?)",$_SESSION['advid'],$parm);

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
FROM user, characters, adv_table
WHERE adv_table.stillOn
AND adv_table.userid = user.id
AND characters.id = adv_table.charid
AND adv_table.advid =  ?
AND lower(user.username) =  lower(?)",$_SESSION['advid'],$condition_user);

                    if ($data===false)
                    {
                        $myData="no valid user";
                        $command="/error";
                        break;
                    }
                    
                    $condition_charid=$data[0]['charid'];

                    $register=query("insert into conditions values (?,?,?,default)",
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
                
            case '/CONDITIONS':
                if (count($info)!=2)
                {
                    $myData="no user";
                    $command="/error";
                    break;
                }
                else
                {
                    $user=$info[1];

                    $data=query("
SELECT char_name, characters.id as charid, conditions.*
FROM user, characters, adv_table, conditions
WHERE adv_table.stillOn
and not conditions.goneAway
AND adv_table.userid = user.id
AND characters.id = adv_table.charid
AND conditions.charid=adv_table.charid
AND adv_table.advid =  ?
AND lower(user.username) =  lower(?)",$_SESSION['advid'],$user);

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
                        $myData="$charname received the conditions ".implode(",",$conditions);
                    }
                    
                }
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