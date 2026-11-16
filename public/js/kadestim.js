document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.main-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const isOpen = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', String(isOpen));
            toggle.setAttribute('aria-label', isOpen ? 'Tutup navigasi' : 'Buka navigasi');
        });
    }

    const audio = document.querySelector('#kadestim-theme');
    const playButton = document.querySelector('[data-audio-toggle]');
    const muteButton = document.querySelector('[data-audio-mute]');
    const musicEnabled = document.body.dataset.musicEnabled === 'true';
    const savedTimeKey = 'kadestim-theme-time';

    if (!musicEnabled || !audio) {
        try { sessionStorage.removeItem(savedTimeKey); } catch (_) {}
        return;
    }

    const updatePlayButton = () => {
        if (!playButton) return;
        const playing = !audio.paused;
        playButton.querySelector('span').textContent = playing ? 'Jeda lagu' : 'Putar lagu';
        playButton.setAttribute('aria-label', playing ? 'Jeda lagu' : 'Putar lagu');
    };

    try {
        const savedTime = Number(sessionStorage.getItem(savedTimeKey));
        if (Number.isFinite(savedTime) && savedTime > 0) {
            audio.addEventListener('loadedmetadata', () => {
                if (Number.isFinite(audio.duration) && savedTime < audio.duration) {
                    audio.currentTime = savedTime;
                }
            }, { once: true });
        }
    } catch (_) {}

    audio.volume = 0.55;
    audio.play().then(updatePlayButton).catch(updatePlayButton);
    audio.addEventListener('play', updatePlayButton);
    audio.addEventListener('pause', updatePlayButton);
    audio.addEventListener('ended', updatePlayButton);

    playButton?.addEventListener('click', () => {
        if (audio.paused) audio.play().then(updatePlayButton).catch(updatePlayButton);
        else audio.pause();
    });

    muteButton?.addEventListener('click', () => {
        audio.muted = !audio.muted;
        muteButton.textContent = audio.muted ? 'Aktifkan suara' : 'Bisukan';
        muteButton.setAttribute('aria-pressed', String(audio.muted));
    });

    window.addEventListener('pagehide', () => {
        try {
            if (Number.isFinite(audio.currentTime)) {
                sessionStorage.setItem(savedTimeKey, String(audio.currentTime));
            }
        } catch (_) {}
    });
});