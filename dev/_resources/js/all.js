window.onload = init;
function init() {
    document.getElementById('imprint-trigger').onclick = showImprint;
}
function showImprint() {
    document.getElementById('imprint').removeAttribute('class');
}