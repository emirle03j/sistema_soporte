function eliminar(id, url, mensaje, returnUrl = '') {
    if (confirm(mensaje)) {
        let finalUrl = url + "?id=" + id;
        if (returnUrl) {
            finalUrl += "&returnUrl=" + encodeURIComponent(returnUrl);
        }
        window.location.href = finalUrl;
    }
}
