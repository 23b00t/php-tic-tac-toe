function showAlertFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const errorParam = urlParams.get('error');
    
    if (errorParam) {
        alert(errorParam);
    }
}

// Beim Laden der Seite die Funktion ausführen
window.onload = showAlertFromUrl;
