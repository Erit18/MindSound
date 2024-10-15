document.addEventListener('DOMContentLoaded', function() {
    const audioElement = document.getElementById('audio');
    const playBtn = document.querySelector('.playBtn');
    const backwardBtn = document.querySelector('.backwardBtn');
    const forwardBtn = document.querySelector('.forwardBtn');
    const progressBar = document.querySelector('.bar');
    const timeNow = document.querySelector('.timeNow');
    const audioDuration = document.querySelector('.audioDuration');

    let audioContext, source, analyser;
    let isPlaying = false;
    let startedAt = 0;
    let pausedAt = 0;
    let duration = 0;

    function initAudio() {
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
        source = audioContext.createMediaElementSource(audioElement);
        analyser = audioContext.createAnalyser();
        source.connect(analyser);
        analyser.connect(audioContext.destination);
    }

    function togglePlay() {
        if (!audioContext) initAudio();
        if (isPlaying) {
            audioContext.suspend();
            pausedAt = audioContext.currentTime - startedAt;
            isPlaying = false;
            playBtn.classList.add('pause');
        } else {
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }
            startedAt = audioContext.currentTime - pausedAt;
            audioElement.play();
            isPlaying = true;
            playBtn.classList.remove('pause');
            requestAnimationFrame(updateAudioTime);
        }
    }

    function formatTime(time) {
        const minutes = Math.floor(time / 60);
        const seconds = Math.floor(time % 60);
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    function updateAudioTime() {
        if (isPlaying) {
            const currentTime = audioContext.currentTime - startedAt;
            progressBar.value = (currentTime / duration) * 100;
            timeNow.textContent = formatTime(currentTime);
            requestAnimationFrame(updateAudioTime);
        }
    }

    function seek(e) {
        const seekTime = (e.target.value / 100) * duration;
        pausedAt = seekTime;
        if (isPlaying) {
            audioElement.currentTime = seekTime;
            startedAt = audioContext.currentTime - seekTime;
        }
        timeNow.textContent = formatTime(seekTime);
    }

    function skip(direction) {
        const currentTime = isPlaying ? audioContext.currentTime - startedAt : pausedAt;
        const newTime = Math.max(0, Math.min(duration, currentTime + direction * 10));
        if (isPlaying) {
            audioElement.currentTime = newTime;
            startedAt = audioContext.currentTime - newTime;
        } else {
            pausedAt = newTime;
        }
        progressBar.value = (newTime / duration) * 100;
        timeNow.textContent = formatTime(newTime);
    }

    playBtn.addEventListener('click', togglePlay);
    backwardBtn.addEventListener('click', () => skip(-1));
    forwardBtn.addEventListener('click', () => skip(1));
    progressBar.addEventListener('input', seek);

    audioElement.addEventListener('loadedmetadata', function() {
        duration = audioElement.duration;
        audioDuration.textContent = formatTime(duration);
        progressBar.max = 100;
    });

    audioElement.addEventListener('error', function(e) {
        console.error('Error loading audio:', e);
    });
});
