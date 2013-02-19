<form id="registerForm" action="register.php" method="post">
    <fieldset>
        <div class="control-group">
          <input autofocus name="username" id="username" placeholder="Username" type="text"/>
        </div>
        <div class="control-group">
          <input name="email" id="email" placeholder="Email" type="text"/>
        </div>
        <div class="control-group">
          <input name="realname" id="realname" placeholder="Real Name (optional)" type="text"/>
        </div>
        <div class="control-group">
          <input name="password" id="password" placeholder="Password" type="password"/>
        </div>
        <div class="control-group">
          <input name="confirmation" id="confirmation" placeholder="Confirmation" type="password"/>
        </div>
        <div class="control-group">
          <button type="submit" id="btnSubmit" class="btn">Register</button>
        </div>
    </fieldset>
</form>
<div>
    or <a href="login.php">login</a>
</div>
