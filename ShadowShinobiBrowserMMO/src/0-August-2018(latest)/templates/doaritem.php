<?php
$template = <<<THEVERYENDOFYOU
<section class="ss-card ss-action-card">
  <div class="ss-action-card__header">
    <span class="ss-eyebrow">GEAR TRANSFER</span>
    <h2>Transfer Gear</h2>
    <p>Send stored Gear to another operative. This transfer costs 40 Coin and cannot be reversed.</p>
  </div>
  <form action="users.php?do=doaritem" method="post" class="ss-action-card__form">
    <label for="gear-recipient">Recipient operative</label>
    <input id="gear-recipient" type="text" name="username" size="30" maxlength="30" autocomplete="off" required />
    <label for="gear-selection">Gear to transfer</label>
    <select name="Combobox1" size="1" id="gear-selection">
      <option selected value="0">Choose Gear</option>
      <option value="1">Weapon in my Trade Vault</option>
      <option value="2">Vest in my Trade Vault</option>
      <option value="3">Headband in my Trade Vault</option>
      <option value="4">Slot 1 in my Trade Vault</option>
      <option value="5">Slot 2 in my Trade Vault</option>
      <option value="6">Slot 3 in my Trade Vault</option>
    </select>
    <div class="buttons ss-action-card__actions">
      <button type="submit" name="submit">Transfer</button>
      <button type="reset" name="reset">Clear</button>
    </div>
  </form>
</section>
THEVERYENDOFYOU;
?>
