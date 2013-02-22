<form action="newTable.php" method="post">
    <fieldset>
        <div class="control-group">
            <input autofocus name="name" placeholder="Adventure name" size="80" type="text"/>
            <input name="system" placeholder="Adventure System" size="50" type="text"/>
            <input name="defaultDice" placeholder="Default Dice Roll" size="10" type="text"/>
        </div>
        <div class="charSheetHeader">Description</div>
        <div>
          <textarea style="width:97%" name="history" cols="60"                     rows="5">
Description(optional)
          </textarea>
        </div>
        <div class="control-group">
            <button type="submit" class="btn">Create table</button>
        </div>
    </fieldset>
</form>
