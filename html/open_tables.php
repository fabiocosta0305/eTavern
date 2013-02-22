<?php

  // configuration
  require("../includes/config.php");

  // cleaning old tables (>3 hours of inactivity);

  cleanOldTables();

  //taking the users

  $data=query("
select adventure.*, user.username as masterUserName, user.realname as masterRealName
  from user, adventure
 where user.id=adventure.masterid
   and not ended
 order by adventure.opened, adventure.name");

  if ($data===false)
      return false;

  if (count($data)===0)
      echo"
<div class=\"tables-info\">
There's no open tables
</div>";
  foreach($data as $table):
?>
        <div class="tables-info">
          <div class="advTable">
            <a class="advTitle" href="/enter_table.php?masterid=<?= $table['masterid'];?>&adventure=<?= $table['advid']; ?>"><?= $table['name'];?></a>
            <div class="advInfo"> 
                <div class="advMasterName"><b>Master:</b><? echo "{$table['masterRealName']}({$table['masterUserName']})"; ?></div>
                <div class="advSystem"><b>System:</b><?= $table['system'];?></div>
                <div class="advDescription"><b>Description:</div><div class="advDescriptionInfo"></b><?= $table['description'];?></div>
            </div>
          </div>
        </div>
<?php
        endforeach;
?>
