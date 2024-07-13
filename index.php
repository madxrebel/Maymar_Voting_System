<?php 

	// conneting to database
	include('config/db_connect.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .leaderboard-table {
            width: 100%;
            border-collapse: collapse;
        }
        .leaderboard-table th, .leaderboard-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .leaderboard-table th {
            background-color: #333;
            color: white;
        }
        .leaderboard-table img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .leaderboard-table tr:hover {
            background-color: #f5f5f5;
        }
        .vote-button {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
        .highlight {
            animation: highlightRow 0.5s ease-in-out, moveRow 1s ease-in-out;
        }
        @keyframes highlightRow {
            0% {
                transform: scale(1);
                background-color: #ffff99;
            }
            50% {
                transform: scale(1.05);
                background-color: #ffff99;
            }
            100% {
                transform: scale(1);
                background-color: white;
            }
        }
        @keyframes moveRow {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Leaderboard</h2>
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>RANK</th>
                    <th>TEAM</th>
                    <th>VOTES</th>
                    <th>WIN %</th>
                    <th>VOTE</th>
                </tr>
            </thead>
            <tbody id="leaderboard-body">
                <tr>
                    <td>1</td>
                    <td>
                        <img src="https://example.com/image1.jpg" alt="Senor Buscador">
                        Senor Buscador
                    </td>
                    <td class="votes">67</td>
                    <td>97%</td>
                    <td><span class="vote-button" onclick="vote(this, 'Senor Buscador')">Vote</span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        <img src="https://example.com/image2.jpg" alt="Mystik Dan">
                        Mystik Dan
                    </td>
                    <td class="votes">50</td>
                    <td>60%</td>
                    <td><span class="vote-button" onclick="vote(this, 'Mystik Dan')">Vote</span></td>
                </tr>
                <!-- Repeat the above <tr> block for each row -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const votes = {
            'Senor Buscador': 67,
            'Mystik Dan': 50,
        };

        function vote(button, team) {
            votes[team]++;
            updateLeaderboard(team);
        }

        function updateLeaderboard(votedTeam) {
            const tbody = document.getElementById('leaderboard-body');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            let highestVotesRow = null;

            rows.forEach(row => {
                const teamName = row.querySelector('td:nth-child(2)').innerText.trim();
                row.querySelector('.votes').innerText = votes[teamName];
                if (teamName === votedTeam) {
                    highestVotesRow = row;
                }
            });

            // Apply highlight effect to the voted row
            if (highestVotesRow) {
                highestVotesRow.classList.add('highlight');
                setTimeout(() => {
                    // Remove highlight after animation
                    highestVotesRow.classList.remove('highlight');

                    // Rearrange rows after the animation
                    rows.sort((a, b) => {
                        const votesA = parseInt(a.querySelector('.votes').innerText);
                        const votesB = parseInt(b.querySelector('.votes').innerText);
                        return votesB - votesA;
                    });

                    tbody.innerHTML = '';
                    rows.forEach((row, index) => {
                        row.querySelector('td:first-child').innerText = index + 1;
                        tbody.appendChild(row);
                    });
                }, 1500); // Match this duration with the animation duration
            }
        }
    </script>
</body>
</html>