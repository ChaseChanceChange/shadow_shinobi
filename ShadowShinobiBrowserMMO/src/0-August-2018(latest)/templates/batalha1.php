<?php
$nomedochar = isset($_GET['nomedochar']) ? (string) $_GET['nomedochar'] : '';
$template = <<<THEVERYENDOFYOU
<section class="ss-card ss-action-card">
  <div class="ss-action-card__header">
    <span class="ss-eyebrow">OPERATIVE CHALLENGE</span>
    <h2>Challenge an Operative</h2>
    <p>Enter the operative name to begin a Challenge using the existing combat system.</p>
  </div>
  <form action="users.php?do=batalha1" method="post" class="ss-action-card__form">
    <label for="duel-player">Opponent name</label>
    <input id="duel-player" type="text" name="jogador" size="30" maxlength="30" value="$nomedochar" autocomplete="off" required />
    <div class="buttons ss-action-card__actions">
      <button type="submit" name="submit">Challenge</button>
      <button type="reset" name="reset">Clear</button>
    </div>
  </form>
</section>
THEVERYENDOFYOU;
?>
