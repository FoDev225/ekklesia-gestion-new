(function () {
    const TIMEOUT_MINUTES = 15; // ← Ajuste ici selon ta décision
    const WARNING_BEFORE_SECONDS = 60; // avertit 60s avant la déconnexion

    const TIMEOUT_MS = TIMEOUT_MINUTES * 60 * 1000;
    const WARNING_MS = TIMEOUT_MS - (WARNING_BEFORE_SECONDS * 1000);

    let warningTimer, logoutTimer, countdownInterval;

    function resetTimers() {
        clearTimeout(warningTimer);
        clearTimeout(logoutTimer);
        clearInterval(countdownInterval);
        hideWarning();

        warningTimer = setTimeout(showWarning, WARNING_MS);
        logoutTimer = setTimeout(forceLogout, TIMEOUT_MS);
    }

    function showWarning() {
        const modal = document.getElementById('idle-warning-modal');
        if (!modal) return;
        modal.classList.remove('hidden');

        let remaining = WARNING_BEFORE_SECONDS;
        const counterEl = document.getElementById('idle-countdown');
        countdownInterval = setInterval(() => {
            remaining--;
            if (counterEl) counterEl.textContent = remaining;
            if (remaining <= 0) clearInterval(countdownInterval);
        }, 1000);
    }

    function hideWarning() {
        const modal = document.getElementById('idle-warning-modal');
        if (modal) modal.classList.add('hidden');
    }

    function forceLogout() {
        document.getElementById('idle-logout-form')?.submit();
    }

    // Activité utilisateur = réinitialise le minuteur
    ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart'].forEach(evt => {
        document.addEventListener(evt, resetTimers, { passive: true });
    });

    document.getElementById('idle-stay-connected')?.addEventListener('click', resetTimers);

    resetTimers();
})();