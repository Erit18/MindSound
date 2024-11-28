document.addEventListener('DOMContentLoaded', function() {
    const audioElement = document.getElementById('audio');
    const playBtn = document.querySelector('.playBtn');
    const backwardBtn = document.querySelector('.backwardBtn');
    const forwardBtn = document.querySelector('.forwardBtn');
    const progressBar = document.querySelector('.bar');
    const timeNow = document.querySelector('.timeNow');
    const audioDuration = document.querySelector('.audioDuration');
    const disk = document.querySelector('.disk');

    let isPlaying = false;

    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    function updateProgress() {
        const currentTime = audioElement.currentTime;
        const duration = audioElement.duration;
        progressBar.value = (currentTime / duration) * 100;
        timeNow.textContent = formatTime(currentTime);
        
        if (isPlaying) {
            requestAnimationFrame(updateProgress);
        }
    }

    function togglePlay() {
        if (audioElement.paused) {
            audioElement.play();
            isPlaying = true;
            disk.classList.add('play');
            playBtn.innerHTML = '<i class="fas fa-pause"></i>';
            requestAnimationFrame(updateProgress);
        } else {
            audioElement.pause();
            isPlaying = false;
            disk.classList.remove('play');
            playBtn.innerHTML = '<i class="fas fa-play"></i>';
        }
    }

    function seek(e) {
        const percent = e.target.value;
        const time = (percent * audioElement.duration) / 100;
        audioElement.currentTime = time;
        if (!isPlaying) {
            updateProgress();
        }
    }

    function skip(direction) {
        audioElement.currentTime += direction * 10;
        if (!isPlaying) {
            updateProgress();
        }
    }

    // Event Listeners
    playBtn.addEventListener('click', togglePlay);
    backwardBtn.addEventListener('click', () => skip(-1));
    forwardBtn.addEventListener('click', () => skip(1));
    progressBar.addEventListener('input', seek);

    audioElement.addEventListener('loadedmetadata', function() {
        audioDuration.textContent = formatTime(audioElement.duration);
        timeNow.textContent = formatTime(0);
        progressBar.value = 0;
    });

    audioElement.addEventListener('ended', function() {
        isPlaying = false;
        disk.classList.remove('play');
        playBtn.innerHTML = '<i class="fas fa-play"></i>';
        progressBar.value = 0;
        timeNow.textContent = formatTime(0);
    });
});
