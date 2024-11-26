// Funcionalidad común entre páginas
const initializeCommonFeatures = () => {
    const modeToggle = document.getElementById('mode-toggle');
    if (modeToggle) {
        modeToggle.addEventListener('click', () => {
            document.body.classList.toggle('white-mode');
        });
    }
};

document.addEventListener('DOMContentLoaded', initializeCommonFeatures); 