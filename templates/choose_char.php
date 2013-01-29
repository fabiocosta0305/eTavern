<div id="advInfo">
        <div class="tables-info">
          <div class="advTable">
            <div class="advTitle"><?= $table['name'];?></a>
            <div class="advInfo"> 
                <div class="advMasterName"><b>Master:</b><? echo "{$table['masterRealName']}({$table['masterUserName']})"; ?></div>
                <div class="advSystem"><b>System:</b><?= $table['system'];?></div>
                <div class="advDescription"><b>Description:</div><div class="advDescriptionInfo"></b><?= $table['description'];?></div>
            </div>
          </div>
        </div>

</div>
<form action="/enter_table.php" method="post">
<input type="hidden" name="defaultDice" value="<?= $table['defaultDice'];?>">
<input type="hidden" name="advid" value="<?= $_GET['adventure'];?>">
     <fieldset>
       Character to use: <select name="characters">
<option value="0"></option>
<?php
     foreach($binder as $sheet):
     error_log(print_r($sheet));
?>
     <option value="<?= $sheet['id']; ?>"><?= $sheet['name']; ?></option>
<?php
     endforeach;
?>
</select>
     <button type="submit" class="btn">Choose and enjoy!</button>
     </div>
     <fieldset>
</form>
     </div>