window.onload = init;

function init() {
    document.getElementById('imprint-trigger').onclick = triggerImprint;
}

function triggerImprint() {
    let imprint = document.getElementById('imprint');

    if (imprint.classList.contains('hidden')) {
        imprint.classList.remove('hidden');
    } else {
        imprint.classList.add('hidden');
    }
}