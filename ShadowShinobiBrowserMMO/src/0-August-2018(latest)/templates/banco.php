<?php
$template = <<<THEVERYENDOFYOU
<section class="ss-card ss-bank" aria-labelledby="ss-bank-title">
  <div class="ss-action-card__header">
    <span class="ss-eyebrow">TREASURY</span>
    <h2 id="ss-bank-title">Bank</h2>
    <p>Move Ryou between your carried funds and the bank.</p>
  </div>
  <form action="funcaoitens.php?do=banco" method="post" class="ss-bank__grid">
    <div class="ss-bank__operation">
      <label for="bank-deposit">Deposit Ryou</label>
      <input id="bank-deposit" type="number" name="deposito" min="0" step="1" inputmode="numeric" />
      <button type="submit" name="submit" value="OK" class="ss-bank__button">Deposit</button>
    </div>
    <div class="ss-bank__operation">
      <label for="bank-withdraw">Withdraw Ryou</label>
      <input id="bank-withdraw" type="number" name="retirar" min="0" step="1" inputmode="numeric" />
      <button type="submit" name="submit" value="OK" class="ss-bank__button">Withdraw</button>
    </div>
  </form>
</section>
THEVERYENDOFYOU;
?>
