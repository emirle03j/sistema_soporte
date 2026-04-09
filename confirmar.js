function eliminar(id, url, mensaje) {
    if (confirm(mensaje)) {
        window.location.href = url + "?id=" + id;
    }
}
