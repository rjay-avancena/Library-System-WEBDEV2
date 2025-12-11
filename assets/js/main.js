// Minimal JS for UI (show/hide, alerts, etc.)
function showAlert(msg, type = 'alert') {
    const alertDiv = document.createElement('div');
    alertDiv.className = type;
    alertDiv.innerText = msg;
    document.body.prepend(alertDiv);
    setTimeout(() => alertDiv.remove(), 3000);
}
