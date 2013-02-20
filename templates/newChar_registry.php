<div class="charSheet">
<div class="charHeader">
<div class="charSheetHeader">Basic Information</div>
<form action="<?= $controller;?>" method="post">
     <fieldset>
     <div class="charForm">
     <div class="control-group">
<? if (isset($data)): ?>
     <input name="id" type="hidden" value="<?= $data["id"];?>">
<? endif?>
     <input autofocus name="char_name" placeholder="Character Name" type="text" width="40" value="<? if (isset($data)) echo $data["char_name"];?>"/>
     </input>
     </div>
     <div class="control-group">
     <input name="system" placeholder="Which system?" type="text" width="40" value="<? if (isset($data)) echo $data["system"];?>"/>
     </div>
     <div class="control-group">
     <input name="base_desc" placeholder="Basic description/information (optional)" type="text" width="60" value="<? if (isset($data)) echo $data["base_desc"];?>"/>
     </div>
     </div>
     </div>
     <div class="charSheetData">
     <div class="charSheetHeader">Character Sheet <a href class="charLink" onClick="return popitup('/help/wikiHelp.html');">(Wiki Formatted)</a></div>
     <textarea style="width:97%" name="sheet" cols="60" rows="5">
<?= isset($data)?$data["sheet"]:"Character Sheet(optional)";?>
     </textarea>
     <div class="charSheetHeader">History <a href class="charLink" onClick="return popitup('/help/wikiHelp.html');">(Wiki Formatted)</a></div>
     <textarea style="width:97%" name="history" cols="60" rows="5">
     <?= isset($data)?$data["history"]:"History(optional)";?>
     </textarea>
     </div>
     </div>
     <div class="control-group">
     <button type="submit" class="btn">Record it!</button>
     </div>
     <fieldset>
</form>
     </div>