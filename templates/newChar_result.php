<div class="charSheet">
<div class="charHeader">
<div class="charSheetHeader">Basic Information</div>
     <div class="dataHeader">Name:</div>
     <div class="dataValue"><?= $data["char_name"];?></div>
     <div class="dataHeader">System:</div>
     <div class="dataValue"><?= $data["system"];?></div>
     <div class="dataHeader">Basic Description:</div>
     <div class="dataValue"><?= $data["base_desc"];?></div>
<?
     if ($permissions):
?>
<div class="charButtons">
     <div class="charButton">
     <form action="changeChar.php" method="post">
     <input type="hidden" name="userid" value="<?= $id;?>"/>
     <input type="hidden" name="sheetid" value="<?= $data['id'];?>"/>
     <button type="submit" class="btn">Change it!</button>
     </form>
     </div>
     <div class="charButton">
     <form action="deleteChar.php" method="post">
     <input type="hidden" name="userid" value="<?= $id;?>"/>
     <input type="hidden" name="sheetid" value="<?= $data['id'];?>"/>
     <button type="submit" class="btn">Delete it!</button>
     </form>
</div>
</div>
<?
     endif;
?>
</div>
<div class="charSheetData">
<div class="charSheetHeader">Character Sheet</div>
<div class="charSheetArea">
<?= $data['sheet'];?>
     </div>
<div class="charSheetHeader">History</div>
<div class="charSheetArea">
<div class="historyArea">
<?= $data['history'];?>
</div>
</div>
</div>
</div>
     <div style="width:100%; display:table; float:bottom">
     <a href="/">Return to the tavern</a>
     </div>