<?php
global $conteudouser;
$template = <<<THEVERYENDOFYOU
<section class="ss-card ss-account-form">
  <div class="ss-action-card__header">
    <span class="ss-eyebrow">ACCOUNT RECOVERY</span>
    <h2>Recover your password</h2>
    $conteudouser
    <p>Enter the email address associated with your account to begin recovery.</p>
  </div>
  <form action="users.php?do=lostpassword" method="post" class="ss-account-form__fields">
    <div class="ss-form-field">
      <label for="recovery-email">Email address</label>
      <input id="recovery-email" type="email" name="email" size="30" maxlength="100" autocomplete="email" required />
    </div>
    <div class="buttons ss-action-card__actions">
      <button type="submit" class="positive" name="submit"><img src="layoutnovo/dropmenu/b1.gif" alt=""> Send recovery</button>
      <button type="reset" class="negative" name="reset"><img src="layoutnovo/dropmenu/b3.gif" alt=""> Reset</button>
    </div>
  </form>
</section>
THEVERYENDOFYOU;
?>
