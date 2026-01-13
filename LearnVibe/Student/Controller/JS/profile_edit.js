// Profile Edit Form Validation and Data Loading

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            
            // Only check if password is not empty
            if (password !== '' && password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        });
    }
    
    // fetch current user data and populate form fields
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resp = this.responseText;
            // Guard: ensure response looks like JSON before parsing
            if (typeof resp !== 'string' || resp.trim().charAt(0) !== '{') {
                console.error('Unexpected non-JSON response:', resp);
                return;
            }
            try {
                var data = JSON.parse(resp);
            } catch (e) {
                console.error('Invalid JSON response', e, resp);
                return;
            }

            if (data && data.success && data.user) {
                var u = data.user;
                var fullName = document.querySelector('input[name="full_name"]');
                if (fullName) fullName.value = u.full_name || '';

                var emailInput = document.querySelector('input[type="email"].readonly-input');
                if (emailInput) emailInput.value = u.email || '';

                var contact = document.querySelector('input[name="contact_number"]');
                if (contact) contact.value = u.contact_number || '';

                var uni = document.querySelector('input[name="university_name"]');
                if (uni) uni.value = u.university_name || '';

                var dept = document.querySelector('input[name="department"]');
                if (dept) dept.value = u.department || '';

                var yearSelect = document.querySelector('select[name="year"]');
                if (yearSelect && u.year) yearSelect.value = u.year;

                var expertiseInput = document.querySelector('input[name="expertise"]');
                if (expertiseInput) expertiseInput.value = u.expertise || '';
            }
        }
    };

    xhttp.open("GET", "../Controller/get_current_user.php", true);
    xhttp.send();

    // Auto-hide messages after 5 seconds
    setTimeout(function() {
        const messages = document.querySelectorAll('.message');
        messages.forEach(function(msg) {
            msg.style.display = 'none';
        });
    }, 5000);
});
