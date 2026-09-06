<?php
$template = <<<THEVERYENDOFYOU
<section class="ss-card ss-action-card">
  <div class="ss-action-card__header">
    <span class="ss-eyebrow">TRANSFER</span>
    <h2>Transfer Coin</h2>
    <p>Send Coin to another operative.</p>
  </div>
  <form action="users.php?do=doardinheiro" method="post" class="ss-action-card__form">
    <label for="coin-recipient">Recipient operative</label>
    <input id="coin-recipient" type="text" name="username" size="30" maxlength="30" autocomplete="off" required />
    <label for="coin-amount">Coin amount</label>
    <input id="coin-amount" type="number" name="oldpass" min="0" step="1" inputmode="numeric" required />
    <div class="buttons ss-action-card__actions">
      <button type="submit" name="submit">Transfer</button>
      <button type="reset" name="reset">Clear</button>
    </div>
  </form>
</section>
THEVERYENDOFYOU;
?>
