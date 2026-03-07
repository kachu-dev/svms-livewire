<x-toaster-hub />

@fluxScripts
@livewireScripts

<script>
    function updateDateTime() {
        const now = new Date();

        const dateOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        };
        document.getElementById('current-date').textContent = now.toLocaleDateString(
            'en-US',
            dateOptions,
        );

        const timeOptions = {
            hour: 'numeric',
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
        };
        document.getElementById('current-time').textContent = now.toLocaleTimeString(
            'en-US',
            timeOptions,
        );
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
