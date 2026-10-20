(function () {
    const loader = document.getElementById('site-loader');
    if (!loader) {
        return;
    }

    let visible = false;
    const showTimer = window.setTimeout(function () {
        if (document.readyState === 'complete') {
            return;
        }

        visible = true;
        loader.classList.add('is-visible');
        loader.setAttribute('aria-hidden', 'false');
    }, 250);

    function hideLoader() {
        window.clearTimeout(showTimer);
        loader.classList.remove('is-visible');
        loader.setAttribute('aria-hidden', 'true');
        visible = false;
    }

    window.addEventListener('load', hideLoader, { once: true });
    window.addEventListener('pageshow', hideLoader);

    window.setTimeout(function () {
        if (visible) {
            hideLoader();
        }
    }, 15000);
})();
