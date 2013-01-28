<?php

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
                $info[0]="";
                $myData=implode(" ",$info);
                $data=query("insert into offChatLog (userid,text) VALUES (?,?)",
                            $user, $myData); 
                break;

            case '/me':
                $info[0]="";
                $myData=implode(" ",$info);               
                break;
                
            case '/defaultDice':
                $info[0]="";
                $myData=implode(" ",$info);

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

            case '/secret':
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

            case '/fakeDice':
            case '/secretDice':
            case '/dice':                        
                if (!isset($parm) || empty($parm))
                {
                    if (isset($_SESSION['defaultDice'])) $parm=$_SESSION['defaultDice'];
                }
                else
                {
                    $parm=$info[1];
                    $info[0]=$info[1]="";
                }
               
                $myData=rollDice($parm);
                break;

            default:
                $myData="had tried a invalid command:".$command;
                $command="/error";
        }
    }
    
    /* $data=query($query, $user, $myData); */
    $data=query("insert into onChatLog (userid,text,command,parm) VALUES (?,?,?,?)",
                $user, $myData, $command, $parm); 
   
    return($lastTimestamp);
}

?>