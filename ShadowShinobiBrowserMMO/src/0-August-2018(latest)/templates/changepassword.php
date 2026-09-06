<?php
global $conteudouser;
$template = <<<THEVERYENDOFYOU
<section class="ss-card ss-account-form">
  <div class="ss-action-card__header">
    <span class="ss-eyebrow">ACCOUNT SECURITY</span>
    <h2>Change password</h2>
    $conteudouser
    <p>Use your current password to set a new account password.</p>
  </div>
  <form action="users.php?do=changepassword" method="post" class="ss-account-form__fields">
    <div class="ss-form-field">
      <label for="account-username">Username</label>
      <input id="account-username" type="text" name="username" size="30" maxlength="30" autocomplete="username" required />
    </div>
    <div class="ss-form-field">
      <label for="account-old-password">Current password</label>
      <input id="account-old-password" type="password" name="oldpass" size="20" autocomplete="current-password" required />
    </div>
    <div class="ss-form-field">
      <label for="account-new-password">New password</label>
      <input id="account-new-password" type="password" name="newpass1" size="20" maxlength="10" autocomplete="new-password" required />
    </div>
    <div class="ss-form-field">
      <label for="account-new-password-confirm">Confirm new password</label>
      <input id="account-new-password-confirm" type="password" name="newpass2" size="20" maxlength="10" autocomplete="new-password" required />
    </div>
    <div class="buttons ss-action-card__actions">
      <button type="submit" class="positive" name="submit"><img src="layoutnovo/dropmenu/b1.gif" alt=""> Change password</button>
      <button type="reset" class="negative" name="reset"><img src="layoutnovo/dropmenu/b3.gif" alt=""> Reset</button>
    </div>
  </form>
</section>
THEVERYENDOFYOU;
?>
