function addClassNone(element) {
    element.classList.add('d-none');
}

function removeClassNone(element) {
    element.classList.remove('d-none');
}

function removeAllElementChild(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}
