<?php
require_once 'db.php';

$upcoming_matches_query = "SELECT um.match_id, t1.team_name AS home_team, t2.team_name AS visit_team 
                           FROM upcoming_match um
                           JOIN team t1 ON um.home_team_id = t1.team_id
                           JOIN team t2 ON um.visit_team_id = t2.team_id";
$upcoming_matches_result = mysqli_query($conn, $upcoming_matches_query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Match Setup & Live Scoring</title>
    <link rel="stylesheet" href="/Cricket-League-Website/CSS_File/manage-live-score.css">
</head>
<body>
    <div class="container">
        <div class="card" id="match-select-step">
            <h2>Step 1: Select Match</h2>
            <label>Select Match to Begin Scoring:</label>
            <select id="match_id" name="match_id">
                <option value="">-- Select an Upcoming Match --</option>
                <?php while ($match = mysqli_fetch_assoc($upcoming_matches_result)) { ?>
                    <option value="<?php echo $match['match_id']; ?>">
                        <?php echo htmlspecialchars($match['home_team'] . " vs " . $match['visit_team']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        
        <div class="card" id="match-setup" style="display: none;">
            <h2>Step 2: Match Setup</h2>
            <form id="setup-form">
                <label>Who is Batting First?</label>
                <select id="batting_team_id" name="batting_team_id">
                    </select>

                <input type="hidden" id="bowling_team_id" name="bowling_team_id">

                <label>Striker:</label>
                <select id="striker_id" name="striker_id" class="player-select"></select>

                <label>Non-Striker:</label>
                <select id="non_striker_id" name="non_striker_id" class="player-select"></select>

                <label>Opening Bowler:</label>
                <select id="bowler_id" name="bowler_id" class="player-select"></select>

                <button type="button" onclick="startScoring()">Start Scoring</button>
            </form>
        </div>

        <div class="card scoring-area" id="scoring-area" style="display: none;">
             <h2>Live Scoring</h2>
            <div class="live-score-display">Live Score: <span id="score-display">0/0 (0.0)</span></div>
            
            <div class="current-ball-info">
                <div class="player-role">
                    <span>Striker</span>
                    <strong id="striker-name"></strong>
                </div>
                 <div class="player-role">
                    <span>Non-Striker</span>
                    <strong id="non-striker-name"></strong>
                </div>
            </div>
             <div class="bowler-info">
                <span>Bowler:</span>
                <strong id="bowler-name"></strong>
            </div>

            <form id="scoring-form">
                <div class="runs-scored">
                    <span>Runs Scored</span>
                    <div class="run-buttons">
                        <button type="button" onclick="selectRuns(0)">0</button>
                        <button type="button" onclick="selectRuns(1)">1</button>
                        <button type="button" onclick="selectRuns(2)">2</button>
                        <button type="button" onclick="selectRuns(3)">3</button>
                        <button type="button" onclick="selectRuns(4)">4</button>
                        <button type="button" onclick="selectRuns(6)">6</button>
                    </div>
                </div>

                <div class="extras">
                    <label>Extras:</label>
                    <select id="extras_type" name="extras_type">
                        <option value="none">None</option>
                        <option value="wide">Wide</option>
                        <option value="noball">No Ball</option>
                        <option value="byes">Byes</option>
                        <option value="legbyes">Leg Byes</option>
                    </select>
                </div>

                <div class="wicket">
                    <label>Is it a Wicket?</label>
                    <input type="checkbox" id="is_wicket" name="is_wicket">
                </div>
                
                <input type="hidden" id="runs_scored" name="runs_scored" value="0">
                <button type="button" class="submit-ball" onclick="submitBall()">Submit Ball</button>
            </form>
             <button type="button" class="end-match" onclick="endMatch()">End Match Session</button>
        </div>
    </div>

    <div id="wicket-modal" class="modal">
        <div class="modal-content">
            <h3>Wicket Details</h3>
            <p>Who is out?</p>
            <div id="batsman-out-options">
                <label><input type="radio" name="batsman_out" id="out-striker" value=""> <span id="out-striker-name"></span></label>
                <label><input type="radio" name="batsman_out" id="out-non-striker" value=""> <span id="out-non-striker-name"></span></label>
            </div>
            
            <label for="new_batsman_id">New Batsman:</label>
            <select id="new_batsman_id"></select>

            <button type="button" onclick="confirmWicket()">Confirm Wicket</button>
            <button type="button" class="cancel-btn" onclick="closeModal('wicket-modal')">Cancel</button>
        </div>
    </div>
    
    <div id="new-bowler-modal" class="modal">
        <div class="modal-content">
            <h3>End of Over</h3>
            <p>Select the next bowler.</p>
            <label for="new_bowler_id">New Bowler:</label>
            <select id="new_bowler_id"></select>
            <button type="button" onclick="confirmNewBowler()">Continue</button>
        </div>
    </div>


<script>
    let currentScore = { runs: 0, wickets: 0, balls: 0 };
    let teamPlayers = { batting: [], bowling: [] };
    let matchTeams = {};

    document.getElementById('match_id').addEventListener('change', (event) => {
        const matchId = event.target.value;
        if (!matchId) {
            document.getElementById('match-setup').style.display = 'none';
            return;
        }

        fetch(`/Cricket-League-Website/get-match-teams.php?match_id=${matchId}`)
            .then(response => response.json())
            .then(data => {
                matchTeams = data;
                const battingSelect = document.getElementById('batting_team_id');
                battingSelect.innerHTML = `
                    <option value="${data.home_team_id}">${data.home_team_name}</option>
                    <option value="${data.visit_team_id}">${data.visit_team_name}</option>
                `;
                battingSelect.dispatchEvent(new Event('change')); 
                document.getElementById('match-setup').style.display = 'block';
            });
    });

    document.getElementById('batting_team_id').addEventListener('change', (event) => {
        const battingTeamId = event.target.value;
        const bowlingTeamId = (battingTeamId === matchTeams.home_team_id) ? matchTeams.visit_team_id : matchTeams.home_team_id;
        document.getElementById('bowling_team_id').value = bowlingTeamId;

        fetchPlayers(battingTeamId, ['striker_id', 'non_striker_id'], 'batting');
        fetchPlayers(bowlingTeamId, ['bowler_id'], 'bowling');
    });
    
    function fetchPlayers(teamId, playerElementIds, teamType) {
        if (!teamId) return;

        fetch(`/Cricket-League-Website/get-players.php?team_id=${teamId}`)
            .then(response => response.json())
            .then(players => {
                teamPlayers[teamType] = players;
                playerElementIds.forEach(elementId => {
                    const select = document.getElementById(elementId);
                    select.innerHTML = '<option value="">-- Select Player --</option>';
                    players.forEach(player => {
                        select.innerHTML += `<option value="${player.player_id}">${player.first_name} ${player.last_name}</option>`;
                    });
                });
            });
    }

    function startScoring() {
        if (!document.getElementById('striker_id').value || !document.getElementById('non_striker_id').value || !document.getElementById('bowler_id').value) {
            alert('Please select a striker, non-striker, and opening bowler.');
            return;
        }
        
        document.getElementById('match-select-step').style.display = 'none';
        document.getElementById('match-setup').style.display = 'none';
        document.getElementById('scoring-area').style.display = 'block';

        document.getElementById('striker-name').innerText = document.getElementById('striker_id').options[document.getElementById('striker_id').selectedIndex].text;
        document.getElementById('non-striker-name').innerText = document.getElementById('non_striker_id').options[document.getElementById('non_striker_id').selectedIndex].text;
        document.getElementById('bowler-name').innerText = document.getElementById('bowler_id').options[document.getElementById('bowler_id').selectedIndex].text;
        
        if (!document.querySelector('#setup-form input[name="match_id"]')) {
            document.getElementById('setup-form').insertAdjacentHTML('beforeend', `<input type="hidden" name="match_id" value="${document.getElementById('match_id').value}">`);
        }
        sendDataToServer(true);
    }

    function selectRuns(runs) {
        document.getElementById('runs_scored').value = runs;
        document.querySelectorAll('.run-buttons button').forEach(btn => btn.classList.remove('selected'));
        event.target.classList.add('selected');
    }

    function submitBall() {
        if (document.getElementById('is_wicket').checked) {
            openWicketModal();
        } else {
            sendDataToServer();
        }
    }
    
    function sendDataToServer(isSetup = false, wicketData = null) {
        const data = new FormData(document.getElementById('setup-form'));
        if (!isSetup) {
            new FormData(document.getElementById('scoring-form')).forEach((val, key) => data.append(key, val));
        }
        data.append('is_setup', isSetup);

        if (wicketData) {
            const field = wicketData.out_id === data.get('striker_id') ? 'striker_id' : 'non_striker_id';
            data.set(field, wicketData.new_id);
        }

        fetch('/Cricket-League-Website/update-score.php', {
            method: 'POST',
            body: data
        })
        .then(response => {
            if (!response.ok) { throw new Error(`HTTP error! status: ${response.status}`); }
            return response.json();
        })
        .then(score => {
            currentScore = score;
            const overs = Math.floor(score.balls / 6);
            const ball_in_over = score.balls % 6;
            document.getElementById('score-display').innerText = `${score.runs}/${score.wickets} (${overs}.${ball_in_over})`;
            
            const isLegalDelivery = !['wide', 'noball'].includes(document.getElementById('extras_type').value);

            if (!isSetup) {
                if ([1, 3, 5].includes(parseInt(document.getElementById('runs_scored').value, 10))) {
                    swapStrikers();
                }
                if (isLegalDelivery && score.balls > 0 && ball_in_over === 0) {
                    openNewBowlerModal();
                }
            }
            resetBallInputs();
        })
        .catch(error => {
            console.error('Error fetching score:', error);
            alert("An error occurred. Check the console for details.");
        });
    }

    function openWicketModal() {
        const strikerId = document.getElementById('striker_id').value;
        const nonStrikerId = document.getElementById('non_striker_id').value;
        document.getElementById('out-striker').value = strikerId;
        document.getElementById('out-striker-name').innerText = document.getElementById('striker-name').innerText;
        document.getElementById('out-non-striker').value = nonStrikerId;
        document.getElementById('out-non-striker-name').innerText = document.getElementById('non-striker-name').innerText;

        const newBatsmanSelect = document.getElementById('new_batsman_id');
        newBatsmanSelect.innerHTML = '<option value="">-- Select New Batsman --</option>';
        teamPlayers.batting.forEach(player => {
            if (player.player_id !== strikerId && player.player_id !== nonStrikerId) {
                newBatsmanSelect.innerHTML += `<option value="${player.player_id}">${player.first_name} ${player.last_name}</option>`;
            }
        });
        document.getElementById('wicket-modal').style.display = 'flex';
    }

    function confirmWicket() {
        const newBatsmanId = document.getElementById('new_batsman_id').value;
        const outBatsmanRadio = document.querySelector('input[name="batsman_out"]:checked');
        if (!newBatsmanId || !outBatsmanRadio) {
            alert('Please select the batsman who is out and the new batsman.');
            return;
        }
        const outBatsmanId = outBatsmanRadio.value;
        const wicketData = { out_id: outBatsmanId, new_id: newBatsmanId };
        
        const newBatsmanName = document.getElementById('new_batsman_id').options[document.getElementById('new_batsman_id').selectedIndex].text;
        if (outBatsmanId === document.getElementById('striker_id').value) {
            document.getElementById('striker_id').value = newBatsmanId;
            document.getElementById('striker-name').innerText = newBatsmanName;
        } else {
            document.getElementById('non_striker_id').value = newBatsmanId;
            document.getElementById('non-striker-name').innerText = newBatsmanName;
        }
        sendDataToServer(false, wicketData);
        closeModal('wicket-modal');
    }
    
    function openNewBowlerModal() {
        const currentBowlerId = document.getElementById('bowler_id').value;
        const newBowlerSelect = document.getElementById('new_bowler_id');
        newBowlerSelect.innerHTML = '<option value="">-- Select New Bowler --</option>';
        teamPlayers.bowling.forEach(player => {
            if (player.player_id !== currentBowlerId) {
                newBowlerSelect.innerHTML += `<option value="${player.player_id}">${player.first_name} ${player.last_name}</option>`;
            }
        });
        swapStrikers();
        document.getElementById('new-bowler-modal').style.display = 'flex';
    }

    function confirmNewBowler() {
        const newBowlerId = document.getElementById('new_bowler_id').value;
        if (!newBowlerId) {
            alert('Please select a new bowler.');
            return;
        }
        document.getElementById('bowler_id').value = newBowlerId;
        document.getElementById('bowler-name').innerText = document.getElementById('new_bowler_id').options[document.getElementById('new_bowler_id').selectedIndex].text;
        closeModal('new-bowler-modal');
    }

    function closeModal(modalId) { 
        document.getElementById(modalId).style.display = 'none';
        resetBallInputs();
    }
    
    function swapStrikers() {
        const strikerSelect = document.getElementById('striker_id');
        const nonStrikerSelect = document.getElementById('non_striker_id');
        const tempId = strikerSelect.value;
        const tempName = document.getElementById('striker-name').innerText;
        strikerSelect.value = nonStrikerSelect.value;
        document.getElementById('striker-name').innerText = document.getElementById('non-striker-name').innerText;
        nonStrikerSelect.value = tempId;
        document.getElementById('non-striker-name').innerText = tempName;
    }

    function resetBallInputs() {
        document.querySelectorAll('.run-buttons button').forEach(btn => btn.classList.remove('selected'));
        document.getElementById('runs_scored').value = 0;
        document.getElementById('is_wicket').checked = false;
        document.getElementById('extras_type').value = 'none';
    }

    function endMatch() {
        if (!confirm("Are you sure you want to end this scoring session? This cannot be undone.")) return;
        document.getElementById('scoring-area').style.display = 'none';
        document.getElementById('match-setup').style.display = 'none';
        document.getElementById('match-select-step').style.display = 'block';

        document.getElementById('match_id').value = '';
        document.getElementById('setup-form').reset();
        const hiddenMatchId = document.querySelector('#setup-form input[name="match_id"]');
        if (hiddenMatchId) hiddenMatchId.remove();
        
        document.getElementById('scoring-form').reset();
        document.getElementById('score-display').innerText = '0/0 (0.0)';
    }

</script>

</body>
</html>