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
    <link rel="stylesheet" href="/Cricket-League-Website/CSS_File/managelivescoreStyle.css">
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
            <h2 id="setup-title">Step 2: Match Setup</h2>
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
            
            <label for="new_batsman_id" id="new_batsman_label">New Batsman:</label>
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

    <div id="innings-break-modal" class="modal">
        <div class="modal-content">
            <h3>Innings Over!</h3>
            <p>Target to win: <strong id="target-display"></strong></p>
            <button type="button" onclick="transitionToSecondInnings()">Start Second Innings</button>
        </div>
    </div>


<script>
    let currentScore = { runs: 0, wickets: 0, balls: 0 };
    let teamPlayers = { batting: [], bowling: [] };
    let matchTeams = {};
    let availableBatsmen = [];
    let isSecondInnings = false;

    document.getElementById('match_id').addEventListener('change', (event) => {
        const matchId = event.target.value;
        availableBatsmen = []; 
        currentScore = { runs: 0, wickets: 0, balls: 0 };
        isSecondInnings = false;
        document.getElementById('setup-title').innerText = 'Step 2: Match Setup';
        if (!matchId) {
            document.getElementById('match-setup').style.display = 'none';
            return;
        }

        fetch(`/Cricket-League-Website/getmatchTeams.php?match_id=${matchId}`)
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

        fetch(`/Cricket-League-Website/getPlayers.php?team_id=${teamId}`)
            .then(response => response.json())
            .then(players => {
                teamPlayers[teamType] = players.map(p => ({...p, player_id: parseInt(p.player_id, 10)}));
                
                if (teamType === 'batting') {
                    availableBatsmen = [...teamPlayers.batting];
                }

                playerElementIds.forEach(elementId => {
                    const select = document.getElementById(elementId);
                    select.innerHTML = '<option value="">-- Select Player --</option>';
                    teamPlayers[teamType].forEach(player => {
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
            data.set('is_wicket', document.getElementById('is_wicket').checked);
        }
        data.append('is_setup', isSetup);

        if (wicketData) {
            const outBatsmanIsStriker = wicketData.out_id === parseInt(data.get('striker_id'), 10);
            const field = outBatsmanIsStriker ? 'striker_id' : 'non_striker_id';
            data.set(field, wicketData.new_id);
        }

        fetch('/Cricket-League-Website/updateScore.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(score => {
            if (score.error) {
                alert(score.error);
                return;
            }

            currentScore = score;
            const overs = Math.floor(score.balls / 6);
            const ball_in_over = score.balls % 6;
            document.getElementById('score-display').innerText = `${score.runs}/${score.wickets} (${overs}.${ball_in_over})`;
            
            document.getElementById('striker_id').value = score.striker_id;
            document.getElementById('non_striker_id').value = score.non_striker_id;
            
            const newStriker = teamPlayers.batting.find(p => p.player_id === score.striker_id);
            const newNonStriker = teamPlayers.batting.find(p => p.player_id === score.non_striker_id);
            
            if(newStriker) document.getElementById('striker-name').innerText = newStriker.first_name + ' ' + newStriker.last_name;
            if(newNonStriker) document.getElementById('non-striker-name').innerText = newNonStriker.first_name + ' ' + newNonStriker.last_name;
            
            if (score.innings_over && !isSecondInnings) {
                document.getElementById('target-display').innerText = score.target;
                document.getElementById('innings-break-modal').style.display = 'flex';
                document.getElementById('scoring-form').style.display = 'none';
                return;
            }

            const isLegalDelivery = !['wide', 'noball'].includes(document.getElementById('extras_type').value);
            
            if (!isSetup && isLegalDelivery && score.balls > 0 && ball_in_over === 0 && !score.innings_over) {
                 openNewBowlerModal();
            }

             if (!isSetup) resetBallInputs();
        })
        .catch(error => console.error('Error fetching score:', error));
    }

    function openWicketModal() {
        const strikerId = parseInt(document.getElementById('striker_id').value, 10);
        const nonStrikerId = parseInt(document.getElementById('non_striker_id').value, 10);

        document.getElementById('out-striker').value = strikerId;
        document.getElementById('out-striker-name').innerText = document.getElementById('striker-name').innerText;
        document.getElementById('out-non-striker').value = nonStrikerId;
        document.getElementById('out-non-striker-name').innerText = document.getElementById('non-striker-name').innerText;

        const newBatsmanSelect = document.getElementById('new_batsman_id');
        const newBatsmanLabel = document.getElementById('new_batsman_label');

        if (currentScore.wickets >= 9) {
            newBatsmanSelect.style.display = 'none';
            newBatsmanLabel.style.display = 'none';
            newBatsmanSelect.innerHTML = '';
        } else {
            newBatsmanSelect.style.display = 'block';
            newBatsmanLabel.style.display = 'block';
            newBatsmanSelect.innerHTML = '<option value="">-- Select New Batsman --</option>';
            const playersForDropdown = availableBatsmen.filter(player => 
                player.player_id !== strikerId && player.player_id !== nonStrikerId
            );
            playersForDropdown.forEach(player => {
                newBatsmanSelect.innerHTML += `<option value="${player.player_id}">${player.first_name} ${player.last_name}</option>`;
            });
        }
        
        document.getElementById('wicket-modal').style.display = 'flex';
    }

    function confirmWicket() {
        const outBatsmanRadio = document.querySelector('input[name="batsman_out"]:checked');
        if (!outBatsmanRadio) {
            alert('Please select the batsman who is out.');
            return;
        }

        let newBatsmanId = null;
        if (currentScore.wickets < 9) {
            newBatsmanId = document.getElementById('new_batsman_id').value;
            if (!newBatsmanId) {
                alert('Please select the new batsman.');
                return;
            }
        }

        const outBatsmanId = parseInt(outBatsmanRadio.value, 10);
        availableBatsmen = availableBatsmen.filter(player => player.player_id !== outBatsmanId);

        const wicketData = { out_id: outBatsmanId, new_id: newBatsmanId ? parseInt(newBatsmanId, 10) : null };
        
        if (newBatsmanId) {
            const newBatsmanName = document.getElementById('new_batsman_id').options[document.getElementById('new_batsman_id').selectedIndex].text;
            if (outBatsmanId === parseInt(document.getElementById('striker_id').value, 10)) {
                document.getElementById('striker_id').value = newBatsmanId;
                document.getElementById('striker-name').innerText = newBatsmanName;
            } else {
                document.getElementById('non_striker_id').value = newBatsmanId;
                document.getElementById('non-striker-name').innerText = newBatsmanName;
            }
        }
        
        sendDataToServer(false, wicketData);
        closeModal('wicket-modal');
    }

    function transitionToSecondInnings() {
        closeModal('innings-break-modal');
        
        document.getElementById('scoring-area').style.display = 'none';
        document.getElementById('scoring-form').style.display = 'block';
        document.getElementById('match-setup').style.display = 'block';
        document.getElementById('setup-title').innerText = 'Setup Second Innings';

        document.getElementById('score-display').innerText = '0/0 (0.0)';
        currentScore = { runs: 0, wickets: 0, balls: 0 };
        isSecondInnings = true;
        
        const oldBowlingTeamId = document.getElementById('bowling_team_id').value;
        const battingSelect = document.getElementById('batting_team_id');
        
        battingSelect.value = oldBowlingTeamId;
        battingSelect.dispatchEvent(new Event('change'));
    }
    
    function openNewBowlerModal() {
        const currentBowlerId = parseInt(document.getElementById('bowler_id').value, 10);
        const newBowlerSelect = document.getElementById('new_bowler_id');
        newBowlerSelect.innerHTML = '<option value="">-- Select New Bowler --</option>';
        teamPlayers.bowling.forEach(player => {
            if (player.player_id !== currentBowlerId) {
                newBowlerSelect.innerHTML += `<option value="${player.player_id}">${player.first_name} ${player.last_name}</option>`;
            }
        });
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
        if (modalId === 'wicket-modal' || modalId === 'new-bowler-modal') {
             resetBallInputs();
        }
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
        if (confirm("Are you sure you want to end this scoring session? This will reset everything.")) {
            window.location.reload();
        }
    }

</script>

</body>
</html>