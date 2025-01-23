export function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed bottom-0 start-0 end-0 m-3 z-3`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

    const container = document.querySelector('main.container');
    const existingAlert = container.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    container.insertBefore(alertDiv, container.firstChild.nextSibling);

    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
