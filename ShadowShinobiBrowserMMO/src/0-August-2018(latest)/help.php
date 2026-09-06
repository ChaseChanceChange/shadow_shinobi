<?php
include('lib.php');
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysqli_fetch_array($controlquery);
include('cookies.php');
$userrow = checkcookies();

$page = <<<HTML
<section class="ss-card ss-codex">
  <div class="ss-action-card__header">
    <span class="ss-eyebrow">SHADOW CODEX</span>
    <h2>Field Guide</h2>
    <p>Welcome to Shadow Shinobi. Use this guide to understand the systems available to your operative.</p>
  </div>

  <nav class="ss-codex-nav" aria-label="Codex sections">
    <a href="#overview">Overview</a>
    <a href="#disciplines">Disciplines</a>
    <a href="#settlements">Settlements</a>
    <a href="#field">Field Operations</a>
    <a href="#combat">Combat</a>
    <a href="#status">Operative Status</a>
    <a href="#gear">Gear & Recovery</a>
    <a href="#standing">Standing & Insight</a>
  </nav>

  <article id="overview" class="ss-codex-section">
    <h3>Overview</h3>
    <p>Shadow Shinobi is a browser-based squad RPG built around exploration, combat, Gear, Contracts, and Disciplines. Your operative grows through Insight, advances Standing, acquires equipment, and travels between settlements.</p>
  </article>

  <article id="disciplines" class="ss-codex-section">
    <h3>Disciplines</h3>
    <p>Your starting Discipline defines how your operative develops. Each available path has its own balance of durability, offensive power, speed, and access to Arts.</p>
    <ul>
      <li><strong>".$controlrow["class1name"]."</strong> — the first development path.</li>
      <li><strong>".$controlrow["class2name"]."</strong> — the second development path.</li>
      <li><strong>".$controlrow["class3name"]."</strong> — the third development path.</li>
    </ul>
    <p>Difficulty affects the danger and rewards of field encounters. Higher difficulty means tougher Threats and greater returns.</p>
  </article>

  <article id="settlements" class="ss-codex-section">
    <h3>Settlements</h3>
    <p>Settlements are safe zones where an operative can recover, resupply, manage Gear, access the Vault, review Contracts, develop Disciplines, and interact with other operatives.</p>
    <p>Discovered destinations can be reached through the world navigation system. Travel consumes available movement resources.</p>
  </article>

  <article id="field" class="ss-codex-section">
    <h3>Field Operations</h3>
    <p>Outside a settlement, move by coordinate using the navigation controls. Field travel can trigger contact with a Threat, making exploration both a movement system and a source of encounters.</p>
    <p>The local channel, operative encounters, recovery points, and discovered destinations all belong to the field layer.</p>
  </article>

  <article id="combat" class="ss-codex-section">
    <h3>Combat</h3>
    <p>Combat is turn-based. Attack Power, defensive Gear, Attributes, Arts, and encounter difficulty all influence the result.</p>
    <p>When your command phase begins, choose from the available combat actions. Victory grants Insight and Coin and can produce Recovery. Defeat carries a financial penalty but does not end your operative's progression.</p>
  </article>

  <article id="status" class="ss-codex-section">
    <h3>Operative Status</h3>
    <p>Your status panel tracks Health, Essence, movement resources, Standing, Insight, Attributes, current location, and current activity.</p>
    <p>The Operative Record provides a fuller view of your build, while the Attribute panel lets you inspect the values that shape combat performance.</p>
  </article>

  <article id="gear" class="ss-codex-section">
    <h3>Gear & Recovery</h3>
    <p>Gear occupies weapon, armor, shield, and relic slots. Recovered items can provide additional bonuses. Your Pack stores carried items, while the Vault provides settlement storage.</p>
    <p>Gear may be purchased, transferred, refined, equipped, or recovered through the systems available in the field and at settlements.</p>
  </article>

  <article id="standing" class="ss-codex-section">
    <h3>Standing & Insight</h3>
    <p>Insight represents your progression toward the next Standing. Standing reflects the long-term development of your operative and unlocks stronger opportunities.</p>
    <p>Contracts, Discipline progress, and field encounters are the main ways to build Insight. Attribute development lets you specialize your operative further.</p>
  </article>

  <footer class="ss-codex-footer">
    <a href="index.php">Return to the field</a>
  </footer>
</section>
HTML;

display($page, "Codex", false, false, false);
?>
