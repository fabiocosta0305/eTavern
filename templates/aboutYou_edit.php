<form action="<?= $controller;?>" method="post">
  <fieldset>
    <input name="id" type="hidden" value="<?= $data["id"];?>"/>
    <div class="personalInfo">
    <div class="control-group"><span>Your name:</span>
        <input autofocus id="realname" name="realname" placeholder="Real Name" type="text" width="40" value="<? if (isset($data)) echo $data["realname"];?>"/>
    </div>
    <div class="control-group">
        <span>Email:</span>
        <input id="system" name="email" placeholder="Email" type="text" width="40" value="<? if (isset($data)) echo $data["email"];?>"/>
    </div>
</div>
    <div class="extraInfo">
    <div class="control-group"><span>About You:</span>
      <textarea name="aboutYou" id="aboutYou" cols="60" rows="5">
        <?= isset($data)?$data["aboutYou"]:"About you";?>
      </textarea>
    </div>
    <div class="control-group"><span>Facebook:</span>
        <input autofocus id="facebook" name="facebook" placeholder="Facebook" type="text" width="40" value="<? if (isset($data)) echo $data["facebook"];?>"/>
    </div>
        <div class="control-group"><span>Twitter:</span>
        <input autofocus id="twitter" name="twitter" placeholder="Twitter" type="text" width="40" value="<? if (isset($data)) echo $data["twitter"];?>"/>
    </div>
        <div class="control-group"><span>Google+:</span>
        <input autofocus id="googleplus" name="googleplus" placeholder="Google+" type="text" width="40" value="<? if (isset($data)) echo $data["googleplus"];?>"/>
    </div>
    </div>
</div>
    <div class="control-group">
      <button type="submit" class="btn">Record it!</button>
    </div>
    <fieldset>
</form>
