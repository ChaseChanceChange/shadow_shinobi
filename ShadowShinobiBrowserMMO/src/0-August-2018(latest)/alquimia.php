<?php
// alchemy.php :: Shadow Shinobi item fusion.
// Legacy route preserved as ?do=fundir; recipe matching uses stable item IDs.

include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();

$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='" . $userrow["latitude"] . "' AND longitude='" . $userrow["longitude"] . "' LIMIT 1", "towns");
if (mysqli_num_rows($townquery) == 0) {
    display("Your settlement data could not be located. Please try again.", "Error");
    die();
}
$townrow = mysqli_fetch_array($townquery);

if (($townrow['id'] != 2) && ($townrow['id'] != 5)) {
    header('Location: index.php?conteudo=Alchemy is only available at the designated Discipline sites.');
    die();
}

if (isset($_GET["do"]) && $_GET["do"] === "fundir") {
    fundir();
}

function ssAlchemySelectedItems(array $post, array $userrow): array {
    $selected = [];
    for ($slot = 1; $slot <= 4; $slot++) {
        $key = 'bpnome' . $slot;
        $value = isset($post[$key]) && is_string($post[$key]) ? $post[$key] : '';
        if ($value === '' || $value === 'None') {
            continue;
        }
        if (!isset($userrow['bp' . $slot]) || $userrow['bp' . $slot] !== $value) {
            return [];
        }
        $parts = explode(',', $value);
        if (count($parts) < 3 || !ctype_digit((string)$parts[1])) {
            return [];
        }
        $selected[] = [
            'slot' => $slot,
            'name' => $parts[0],
            'id' => (int)$parts[1],
            'type' => (int)$parts[2],
            'value' => $value,
        ];
    }
    return $selected;
}

function ssAlchemyResultString(int $id): string {
    $recipes = [
        34 => ['Twin Storm Blades', 1],
        36 => ['Dune Guard', 3],
        37 => ['Mistveil Guard', 3],
        38 => ['Echo Guard', 3],
        41 => ['Hunter Mask', 3],
        42 => ['Mist Spirit Garb', 2],
        43 => ['Blackleaf Honor Guard', 3],
    ];
    if (!isset($recipes[$id])) {
        return '';
    }
    return $recipes[$id][0] . ',' . $id . ',' . $recipes[$id][1] . ',X';
}

function fundir() {
    global $userrow;

    if ($userrow === false) {
        display("Please log in before using Alchemy.", "Error", false, false, false);
        die();
    }

    if ($userrow["currentaction"] !== "In Town") {
        if ($userrow["currentaction"] === "Fighting") {
            header('Location: ./index.php?do=fight&conteudo=Alchemy can only be used inside a settlement.');
        } else {
            header('Location: ./index.php?conteudo=Alchemy can only be used inside a settlement.');
        }
        die();
    }

    if ($userrow["batalha_timer2"] == 5) {
        header('Location: ./index.php?conteudo=You cannot change equipment while a duel is active.');
        die();
    }

    $message = isset($_GET['frase']) && is_string($_GET['frase']) ? $_GET['frase'] : '';
    $result = '';
    $components = ['', '', '', ''];

    if (isset($_POST["submit"])) {
        $selected = ssAlchemySelectedItems($_POST, $userrow);

        if (count($selected) !== 2) {
            $message = "Select exactly two Gear or Recovery items.";
        } else {
            $ids = [(int)$selected[0]['id'], (int)$selected[1]['id']];
            sort($ids);
            $recipeKey = $ids[0] . ':' . $ids[1];

            // Stable recipe definitions. Names are presentation data only.
            $recipes = [
                '12:31' => 34,
                '29:36' => 36,
                '29:37' => 38,
                '29:38' => 37,
                '21:31' => 41,
                '17:39' => 42,
                '30:46' => 43,
            ];

            if (!isset($recipes[$recipeKey])) {
                $message = "Those two components do not form a known Shadow fusion.";
            } else {
                $outputId = $recipes[$recipeKey];
                $result = ssAlchemyResultString($outputId);

                $keepSlot = $selected[0]['slot'];
                $consumeSlot = $selected[1]['slot'];

                $updatequery = doquery("UPDATE {{table}} SET bp$consumeSlot='None' WHERE charname='" . $userrow["charname"] . "' LIMIT 1", "users");
                $updatequery = doquery("UPDATE {{table}} SET bp$keepSlot='" . $result . "' WHERE charname='" . $userrow["charname"] . "' LIMIT 1", "users");

                $message = "Fusion complete. The two components were reforged into a new Shadow artifact.";
            }
        }
    }

    include('funcoesinclusas.php');

    if ($message !== '') {
        $message = '<div class="ss-message"><strong>Alchemy</strong><br>' . ui_en(strip_tags($message)) . '</div>';
    }

    if ($result === '') {
        $resultImage = 'images/alquimiagif.gif';
    } else {
        $arrayresult = explode(',', $result);
        $resultImage = ($arrayresult[2] <= 3)
            ? 'layoutnovo/equipamentos/' . (int)$arrayresult[1] . '.gif'
            : 'layoutnovo/equipamentos/drops/' . (int)$arrayresult[1] . '.gif';
    }

    for ($slot = 1; $slot <= 4; $slot++) {
        $raw = $userrow['bp' . $slot] ?? 'None';
        if ($raw === '' || $raw === 'None') {
            $components[$slot - 1] = 'images/bpalquimia.jpg';
            continue;
        }
        $parts = explode(',', $raw);
        $img = '';
        $dur = '';
        iconeitemmochila($parts, $img, $dur);
        $components[$slot - 1] = 'images/' . $img . '.gif';
    }

    $table = '<form action="alquimia.php?do=fundir" method="post">'
        . '<table class="ss-data-table">'
        . '<thead><tr><th>Slot</th><th>Gear</th><th>Durability</th><th>Select</th></tr></thead><tbody>';

    for ($slot = 1; $slot <= 4; $slot++) {
        $raw = $userrow['bp' . $slot] ?? 'None';
        if ($raw === '' || $raw === 'None') {
            $name = 'Empty';
            $dur = '—';
        } else {
            $parts = explode(',', $raw);
            $name = htmlspecialchars($parts[0] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
            $dur = htmlspecialchars($parts[3] ?? '—', ENT_QUOTES, 'UTF-8');
        }
        $table .= '<tr><td>' . $slot . '</td><td>' . $name . '</td><td>' . $dur . '</td>'
            . '<td><input type="checkbox" name="bpnome' . $slot . '" value="' . htmlspecialchars($raw, ENT_QUOTES, 'UTF-8') . '"></td></tr>';
    }

    $table .= '</tbody><tfoot><tr><td colspan="4"><button type="submit" name="submit">Fuse Components</button></td></tr></tfoot></table></form>';

    $page = '<section class="ss-card ss-codex">'
        . '<div class="ss-action-card__header"><span class="ss-eyebrow">DISCIPLINE WORKSHOP</span>'
        . '<h2>Alchemy</h2><p>Combine compatible components to create stronger Shadow artifacts.</p></div>'
        . $message
        . '<div class="ss-alchemy-preview"><div><span class="ss-eyebrow">RESULT</span><img src="' . htmlspecialchars($resultImage, ENT_QUOTES, 'UTF-8') . '" alt="Fusion result"></div>'
        . '<div><span class="ss-eyebrow">COMPONENTS</span><div class="ss-alchemy-components">'
        . '<img src="' . htmlspecialchars($components[0], ENT_QUOTES, 'UTF-8') . '" alt="Component slot 1">'
        . '<img src="' . htmlspecialchars($components[1], ENT_QUOTES, 'UTF-8') . '" alt="Component slot 2">'
        . '<img src="' . htmlspecialchars($components[2], ENT_QUOTES, 'UTF-8') . '" alt="Component slot 3">'
        . '<img src="' . htmlspecialchars($components[3], ENT_QUOTES, 'UTF-8') . '" alt="Component slot 4">'
        . '</div></div></div>'
        . $table
        . '</section>';

    display($page, "Alchemy", false, false, false);
}
?>
