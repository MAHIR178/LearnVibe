document.addEventListener('DOMContentLoaded', function() {
    // 1. Password validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', validatePasswords);
    }
    
    // 2. Auto-hide messages
    setTimeout(hideMessages, 5000);
});

function validatePasswords(e) {
    const password = document.querySelector('input[name="password"]').value;
    const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
    
    if (password !== '' && password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
    }
}

function hideMessages() {
    document.querySelectorAll('.message').forEach(msg => {
        msg.style.display = 'none';
    });
}