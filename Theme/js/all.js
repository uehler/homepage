window.onload = init;

function init() {
    document.getElementById('imprint-trigger').onclick = triggerImprint;

    var collapse = document.querySelectorAll('.collapse');
    for (var i = 0; i < collapse.length; i++) {
        collapse[i].addEventListener('click', triggerCollapse);
    }
}

function triggerImprint() {
    var imprint = document.getElementById('imprint');

    if (imprint.classList.contains('hidden')) {
        imprint.classList.remove('hidden');
    } else {
        imprint.classList.add('hidden');
    }
}

function triggerCollapse() {
    var me = this;

    // hide all collapse content
    var collapse = document.querySelectorAll('.collapse');
    for (var i = 0; i < collapse.length; i++) {
        if (collapse[i].dataset.target != me.dataset.target) {
            document.getElementById(collapse[i].dataset.target).classList.add('hidden');
        }
    }

    // show the triggered content
    var targetCollapse = document.getElementById(this.dataset.target);
    if (targetCollapse.classList.contains('hidden')) {
        targetCollapse.classList.remove('hidden');
    } else {
        targetCollapse.classList.add('hidden');
    }

    // show all "show more" and hide the clicked one
    var collapseHide = document.querySelectorAll('.collapse-hide');
    for (i = 0; i < collapseHide.length; i++) {
        collapseHide[i].classList.remove('hidden');
    }
    if (me.classList.contains('collapse-hide')) {
        me.classList.add('hidden');
    } else if (!targetCollapse.classList.contains('hidden')) {
        // hide if .collapse was clicked
        me.nextSibling.classList.add('hidden');
    }
}