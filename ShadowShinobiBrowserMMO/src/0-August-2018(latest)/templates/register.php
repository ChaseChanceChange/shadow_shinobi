<?php
$template = <<<THEVERYENDOFYOU
<section class="ss-card ss-account-form ss-register" aria-labelledby="ss-register-title">
  <div class="ss-action-card__header">
    <span class="ss-eyebrow">CHARACTER CREATION</span>
    <h2 id="ss-register-title">Create your account</h2>
    $conteudo
    <p>Set up your account, character identity, specialization, and starting difficulty.</p>
  </div>
  <form action="users.php?do=register" method="post" class="ss-account-form__fields">
    <div class="ss-form-section">
      <h3>Account</h3>
      <div class="ss-form-field">
        <label for="nomedaconta1">Account name</label>
        <div class="ss-field"><input id="nomedaconta1" type="text" name="username" size="30" maxlength="30" autocomplete="username" required /></div>
        <small>Use 30 characters or fewer. Avoid spaces.</small>
      </div>
      <div class="ss-form-field"><label for="p1senha">Password</label><div class="ss-field"><input id="p1senha" type="password" name="password1" size="30" maxlength="10" autocomplete="new-password" required /><a href="javascript: mostrarpass('p1senha');" aria-label="Show or hide password"><img src="layoutnovo/dropmenu/b4.gif" title="Mostrar/Ocultar Senha" border="0" alt="Show or hide password"></a></div></div>
      <div class="ss-form-field"><label for="p2senha">Confirm password</label><div class="ss-field"><input id="p2senha" type="password" name="password2" size="30" maxlength="10" autocomplete="new-password" required /><a href="javascript: mostrarpass('p2senha');" aria-label="Show or hide password"><img src="layoutnovo/dropmenu/b4.gif" title="Mostrar/Ocultar Senha" border="0" alt="Show or hide password"></a></div></div>
      <div class="ss-form-field"><label for="email1">Email address</label><input id="email1" type="email" name="email1" size="30" maxlength="100" autocomplete="email" required /></div>
      <div class="ss-form-field"><label for="email2">Confirm email</label><input id="email2" type="email" name="email2" size="30" maxlength="100" autocomplete="email" required />{{verifytext}}</div>
    </div>
    <div class="ss-form-section">
      <h3>Character</h3>
      <div class="ss-form-field"><label for="charname">Character name</label><input id="charname" type="text" name="charname" size="30" maxlength="30" autocomplete="off" required /></div>
      <div class="ss-form-field"><label for="charclass">Specialization</label><select id="charclass" name="charclass"><option value="1">{{class1name}}</option><option value="2">{{class2name}}</option><option value="3">{{class3name}}</option></select></div>
      <div class="ss-form-field"><label for="difficulty">Difficulty</label><select id="difficulty" name="difficulty"><option value="1">{{diff1name}}</option><option value="2">{{diff2name}}</option><option value="3">{{diff3name}}</option></select></div>
      <p class="ss-form-note">Need more detail? Read the <a href="help.php">game help</a> for specialization and difficulty information.</p>
    </div>
    <div class="buttons ss-action-card__actions">
      <button type="submit" class="positive" name="submit"><img src="layoutnovo/dropmenu/b1.gif" alt=""> Create account</button>
      <button type="reset" class="negative" name="reset"><img src="layoutnovo/dropmenu/b3.gif" alt=""> Reset</button>
    </div>
  </form>
</section>
THEVERYENDOFYOU;
?>
