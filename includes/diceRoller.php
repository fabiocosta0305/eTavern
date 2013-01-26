<?php
function rollDice($die)
{
   
    $data=[];
    $dicePool=[];
    $result=0;
    $roll="";
    $diceMin=1;
    
    $numberOfMatches=preg_match("/^([0-9]+)?d([0-9fF]+)([+-][0-9]+)?$/",$die,$data);
   
    if ($numberOfMatches == 0)
        return ("Error: entry $die not valid");

    if(!isset($data[1]) || empty($data[1]) || $data[1]==="")
        $data[1]=1;    

    $dices=$data[1];
    $diceFace=$data[2];
    $diceMax=$data[2];

    if (strtoupper($diceFace)==="F")
    {
         $diceMin=-1;
         $diceMax=1;
    }

    for ($i=0; $i < $dices; $i++)
    {
        $rolledDice=mt_rand($diceMin,$diceMax);

        if(strtoupper($diceFace)==="F")
        {
            $signal="";
            if ($rolledDice<0)
                $signal="-";
            else if($rolledDice>0)
                $signal="+";
            
            $dicePool[]=sprintf("%s%d",$signal,abs($rolledDice));
        }
        else
            $dicePool[]=$rolledDice;
        
        $result+=$rolledDice;
    }

    $roll=implode(", ",$dicePool);
    
    if(strtoupper($diceFace)==="F")
    {
        $signal="";
        if ($result<0)
            $signal="-";
        else if($result>0)
            $signal="+";
        
        $result=sprintf("%s%d",$signal,abs($result));
    }

    $roll.=" = $result";

    if (isset($data[3]))
    {
        $result+=$data[3];
        if ($data[3]<0)
            $signal="-";
        else
            $signal="+";
            
        $roll=$roll . sprintf(" %s %d = %d",$signal, abs($data[3]),$result);
    }

    return $roll;
}
?>