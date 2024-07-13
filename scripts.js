document.addEventListener('DOMContentLoaded', () => {
    const leaderboard = document.getElementById('leaderboard');

    function fetchLeaderboard() {
        fetch('/leaderboard')
            .then(response => response.json())
            .then(data => {
                leaderboard.innerHTML = '';
                data.forEach((team, index) => {
                    const listItem = document.createElement('li');
                    listItem.textContent = `${team.team_name}: ${team.votes} votes`;
                    if (index === 0) listItem.classList.add('top');
                    leaderboard.appendChild(listItem);
                });
            });
    }

    fetchLeaderboard();
    setInterval(fetchLeaderboard, 5000); // Refresh every 5 seconds
});


const countdown = document.getElementById('countdown');
const endTime = new Date();
endTime.setHours(14, 0, 0); // 2:00 PM

function updateCountdown() {
    const now = new Date();
    const remaining = endTime - now;

    if (remaining <= 0) {
        countdown.textContent = 'Competition has ended';
        clearInterval(countdownInterval);
    } else {
        const hours = Math.floor(remaining / (1000 * 60 * 60));
        const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
        countdown.textContent = `Competition ends in ${hours}h ${minutes}m ${seconds}s`;
    }
}

updateCountdown();
const countdownInterval = setInterval(updateCountdown, 1000);
