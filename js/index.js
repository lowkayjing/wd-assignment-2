function delegate(el, evt, sel, handler) {
    el.addEventListener(evt, function(event) {
        var t = event.target;
        while (t && t !== this) {
            if (t.matches(sel)) {
                handler.call(t, event);
            }
            t = t.parentNode;
        }
    });
}

delegate(document, 'click', '.delete', function(e) {
    var t = e.target;

    t.closest('td').querySelector('.confirm').classList.remove('d-none');
    t.closest('.buttons').classList.add('d-none');
});

delegate(document, 'click', '.yes', function(e) {
    var t = e.target;

    t.closest('td').querySelector('form').submit();
});

delegate(document, 'click', '.no', function(e) {
    var t = e.target;

    t.closest('.confirm').classList.add('d-none');
    t.closest('td').querySelector('.buttons').classList.remove('d-none');
});