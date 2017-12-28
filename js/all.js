window.onload = init;

function init() {
    document.getElementById('imprint-trigger').onclick = triggerImprint;
}

function triggerImprint() {
    var imprint = document.getElementById('imprint');

    if (imprint.classList.contains('hidden')) {
        imprint.classList.remove('hidden');
    } else {
        imprint.classList.add('hidden');
    }
}

/* piwik */
var _paq = _paq || [];
/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
_paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
_paq.push(["setCookieDomain", "*.uli.io"]);
_paq.push(['trackPageView']);
_paq.push(['enableLinkTracking']);
(function () {
    var u = "https://piwik.ehlertainment.de/";
    _paq.push(['setTrackerUrl', u + 'piwik.php']);
    _paq.push(['setSiteId', '3']);
    var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
    g.type = 'text/javascript';
    g.async = true;
    g.defer = true;
    g.src = u + 'piwik.js';
    s.parentNode.insertBefore(g, s);
})();
/* end piwik */