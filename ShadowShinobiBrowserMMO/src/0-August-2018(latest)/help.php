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
    <p>Welcome to Shadow Shinobi. This guide explains the systems available to your operative.</p>
  </div>

  <nav class="ss-codex-nav" aria-label="Codex sections">
    <a href="#overview">Overview</a>
    <a href="#disciplines">Disciplines</a>
    <a href="#settlements">Settlements</a>
    <a href="#exploration">Exploration</a>
    <a href="#combat">Combat</a>
    <a href="#gear">Gear & Recovery</a>
  </nav>

  <article id="overview" class="ss-codex-section">
    <h3>Overview</h3>
    <p>Shadow Shinobi is a browser MMO built around exploration, combat, progression, contracts, gear and strategic resource management.</p>
    <p>Choose a specialization, develop your attributes, acquire equipment and push farther from the safety of a settlement.</p>
  </article>

  <article id="disciplines" class="ss-codex-section">
    <h3>Disciplines</h3>
    <p>Your specialization determines how quickly you develop and which Arts and bonuses become available.</p>
    <ul>
      <li><strong>Veilblade</strong> — resilient and resource-rich.</li>
      <li><strong>Ironhand</strong> — focused on raw offensive power.</li>
      <li><strong>Threadseer</strong> — agile and precise.</li>
    </ul>
    <p>Use Discipline sites to improve your capabilities and unlock stronger options.</p>
  </article>

  <article id="settlements" class="ss-codex-section">
    <h3>Settlements</h3>
    <p>Settlements are your safe hubs. Rest to restore vital resources, purchase available Gear, acquire route access and manage your Vault.</p>
    <p>Different settlements stock different equipment and connect to different parts of the world.</p>
  </article>

  <article id="exploration" class="ss-codex-section">
    <h3>Exploration</h3>
    <p>Travel across the world grid using the directional controls and map. Distance from established settlements increases the danger of the encounters you can find.</p>
    <p>Watch your travel resources and use known destinations intelligently. Exploration is where Contracts, threats and recoverable Gear intersect.</p>
  </article>

  <article id="combat" class="ss-codex-section">
    <h3>Combat</h3>
    <p>Combat is turn-based. Choose between direct attacks, learned Arts and retreating when the opposition is too strong.</p>
    <p>Damage, defense, accuracy, recovery and special effects are determined by your operative's attributes, Gear and available abilities.</p>
    <p>Defeating a threat awards Insight and Coin, and may produce Recovery that can improve your loadout.</p>
  </article>

  <article id="gear" class="ss-codex-section">
    <h3>Gear & Recovery</h3>
    <p>Gear occupies defined equipment slots and can modify your combat profile. Recovery from defeated threats can provide additional resources or equipment components.</p>
    <p>Use your Pack for carried items and your Vault for stored resources and Gear. Transfers are intended for settlement use.</p>
  </article>

  <footer class="ss-codex-footer">
    <strong>Shadow Shinobi</strong>
    <span>Field Guide · Build from the shadows.</span>
  </footer>
</section>
HTML;

display($page, "Shadow Codex");
?>
