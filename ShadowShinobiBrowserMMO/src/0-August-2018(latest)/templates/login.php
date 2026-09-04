<?php
global $conteudouser;
$template = <<<THEVERYENDOFYOU
<section class="ss-login" aria-labelledby="ss-login-title">
  <div class="ss-login__brand">
    <img src="images/login.gif" alt="Shadow Shinobi" />
    <span>Shadow Shinobi · Browser MMO</span>
  </div>

  $conteudouser

  <div class="ss-login__intro">
    <span class="ss-eyebrow">SHADOW SHINOBI</span>
    <h1 id="ss-login-title">Enter the world</h1>
    <p>Build your character, train your abilities, master your gear, and take on the next mission.</p>
  </div>

  <form action="login.php?do=login" method="post" id="formback" class="ss-login__form">
    <fieldset id="field2" class="ss-panel">
      <legend>Sign in</legend>

      <label for="nomeform">Account name</label>
      <div class="ss-field">
        <input type="text" size="30" name="username" id="nomeform" autocomplete="username" required />
      </div>

      <label for="senhaform">Password</label>
      <div class="ss-field">
        <input type="password" size="30" name="password" id="senhaform" autocomplete="current-password" required />
        <a href="javascript: mostrarpass('senhaform');" aria-label="Show or hide password"><img src="layoutnovo/dropmenu/b4.gif" title="Mostrar/Ocultar Senha" border="0" alt="Show or hide password"></a>
      </div>

      <label class="ss-check">
        <input type="checkbox" name="rememberme" value="yes" />
        <span>Remember me</span>
      </label>

      <div class="buttons ss-login__actions">
        <button type="submit" class="positive" name="submit"><img src="layoutnovo/dropmenu/b1.gif" alt=""> Enter</button>
        <button type="reset" class="negative" name="reset"><img src="layoutnovo/dropmenu/b3.gif" alt=""> Reset</button>
      </div>

      <p class="ss-login__hint">Your session stays compatible with the original game account system.</p>
    </fieldset>
  </form>

  <div class="ss-login__links">
    <p>New to Shadow Shinobi? <a href="users.php?do=register">Create your character.</a></p>
    <p><a href="users.php?do=changepassword">Change your password</a> · <a href="users.php?do=lostpassword">Recover a lost password</a></p>
    <p>Registered but missing your confirmation email? <a href="ativarconta.php">Activate your account</a>.</p>
  </div>
</section>
THEVERYENDOFYOU;
?>
