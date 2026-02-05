
function updateDateTime() {
    const now = new Date();

    // Format date: January 27, 2026
    const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('current-date').textContent =
        now.toLocaleDateString('en-US', dateOptions);

    // Format time: 5:01:03 AM
    const timeOptions = { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true };
    document.getElementById('current-time').textContent =
        now.toLocaleTimeString('en-US', timeOptions);
}

// Update immediately
updateDateTime();

// Update every second
setInterval(updateDateTime, 1000);
