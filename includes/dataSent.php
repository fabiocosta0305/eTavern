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
                    $info[0]=$info[1]="";
                    
                    $myData=implode(" ",$info);
                    
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