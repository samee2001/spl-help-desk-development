 // Global toast helper
 function showToast(message, isSuccess) {
    var container = document.getElementById('toastContainer');
    if (!container) return;

    var wrapper = document.createElement('div');
    wrapper.className = 'toast align-items-center text-bg-' + (isSuccess ? 'success' : 'danger') + ' border-0';
    wrapper.setAttribute('role', 'alert');
    wrapper.setAttribute('aria-live', 'assertive');
    wrapper.setAttribute('aria-atomic', 'true');

    var body = document.createElement('div');
    body.className = 'd-flex';
    body.innerHTML = '<div class="toast-body">' + (message || '') + '</div>' +
        '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>';
    wrapper.appendChild(body);

    container.appendChild(wrapper);
    var toast = new bootstrap.Toast(wrapper, {
        delay: 5000
    });
    toast.show();
    wrapper.addEventListener('hidden.bs.toast', function() {
        wrapper.remove();
    });
}