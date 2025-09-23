<?php include 'header.php'; ?>
<?php
// --- Database connection ---
require_once 'includes/db.php';

// --- Fetch all players ---
$players = [];
$result = $conn->query("SELECT player_id, first_name, last_name FROM player ORDER BY first_name, last_name");
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

$player1 = $player2 = null;
$error = "";

// --- Function to get player stats ---
function getPlayer($conn, $id) {
    $stmt = $conn->prepare("
        SELECT p.first_name, p.last_name, p.type, perf.number_of_match, perf.runs, perf.wickets
        FROM player p
        LEFT JOIN player_performance perf ON p.player_id = perf.player_id
        WHERE p.player_id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $player = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $player;
}

// --- Handle form submit ---
if (isset($_POST['compare'])) {
    $id1 = $_POST['player1_id'] ?? "";
    $id2 = $_POST['player2_id'] ?? "";

    if ($id1 && $id2 && $id1 !== $id2) {
        $player1 = getPlayer($conn, $id1);
        $player2 = getPlayer($conn, $id2);

        if (!$player1 || !$player2) {
            $error = "Invalid player selection.";
        }
    } else {
        $error = "Please select two different players.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="CSS_File/playerCompare.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div style="height: 2.5rem;"></div>
    <form id="compareForm" method="POST" autocomplete="off" class="card p-4 shadow-sm">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Player 1</label>
                <div class="autocomplete-wrapper">
                    <input type="text" id="player1_input" class="form-control" placeholder="Type player name...">
                    <input type="hidden" name="player1_id" id="player1_id">
                    <div id="player1_list" class="autocomplete-list" style="display:none"></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Player 2</label>
                <div class="autocomplete-wrapper">
                    <input type="text" id="player2_input" class="form-control" placeholder="Type player name...">
                    <input type="hidden" name="player2_id" id="player2_id">
                    <div id="player2_list" class="autocomplete-list" style="display:none"></div>
                </div>
            </div>
        </div>

        <button type="submit" name="compare" class="btn btn-primary mt-3 w-100">Compare</button>
    </form>

    <!-- Error message -->
    <?php if ($error): ?>
        <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Comparison result -->
    <?php if ($player1 && $player2): ?>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card p-3 shadow text-center">
                <h4><?= htmlspecialchars($player1['first_name']." ".$player1['last_name']) ?></h4>
                <p><b>Type:</b> <?= htmlspecialchars($player1['type']) ?></p>
                <p><b>Matches:</b> <?= $player1['number_of_match'] ?? 0 ?></p>
                <p><b>Runs:</b> <?= $player1['runs'] ?? 0 ?></p>
                <p><b>Wickets:</b> <?= $player1['wickets'] ?? 0 ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 shadow text-center">
                <h4><?= htmlspecialchars($player2['first_name']." ".$player2['last_name']) ?></h4>
                <p><b>Type:</b> <?= htmlspecialchars($player2['type']) ?></p>
                <p><b>Matches:</b> <?= $player2['number_of_match'] ?? 0 ?></p>
                <p><b>Runs:</b> <?= $player2['runs'] ?? 0 ?></p>
                <p><b>Wickets:</b> <?= $player2['wickets'] ?? 0 ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// ---- Players data ----
const players = <?= json_encode(array_map(fn($p)=>['id'=>$p['player_id'],'name'=>trim($p['first_name'].' '.$p['last_name'])], $players), JSON_UNESCAPED_UNICODE) ?>;

function attachAutocomplete(inputId, listId) {
    const input = document.getElementById(inputId);
    const hiddenInput = document.getElementById(inputId.replace('_input','_id'));
    const list = document.getElementById(listId);
    let currentFocus = -1;

    input.addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        closeAllLists();
        if (!val) return;
        const matches = players.filter(p => p.name.toLowerCase().includes(val)).slice(0,50);
        if (!matches.length) return;
        list.style.display = 'block';
        list.innerHTML = '';
        matches.forEach(p => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            item.textContent = p.name;
            item.dataset.id = p.id;
            item.addEventListener('click', function(){
                input.value = p.name;          // display name
                hiddenInput.value = p.id;      // store id
                closeAllLists();
            });
            list.appendChild(item);
        });
    });

    input.addEventListener('keydown', function(e) {
        const items = list.querySelectorAll('.autocomplete-item');
        if (!items.length) return;
        if (e.key === 'ArrowDown') { currentFocus++; addActive(items); e.preventDefault(); }
        else if (e.key === 'ArrowUp') { currentFocus--; addActive(items); e.preventDefault(); }
        else if (e.key === 'Enter') { e.preventDefault(); if(currentFocus>-1) items[currentFocus].click(); }
        else if (e.key === 'Escape') { closeAllLists(); }
    });

    function addActive(items) { removeActive(items); if(currentFocus>=items.length) currentFocus=0; if(currentFocus<0) currentFocus=items.length-1; items[currentFocus].classList.add('autocomplete-active'); items[currentFocus].scrollIntoView({block:'nearest'}); }
    function removeActive(items) { items.forEach(i=>i.classList.remove('autocomplete-active')); }
    function closeAllLists(){ list.style.display='none'; currentFocus=-1; }

    document.addEventListener('click',function(e){ if(e.target!==input && e.target.parentNode!==list) closeAllLists(); });
}

attachAutocomplete('player1_input','player1_list');
attachAutocomplete('player2_input','player2_list');
</script>

</body>
</html>
<?php include 'footer.php'; ?>
