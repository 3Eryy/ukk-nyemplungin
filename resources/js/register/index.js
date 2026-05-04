// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
    }
}

// Form validation and submission
document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const password = document.getElementById('password').value;

    // Basic validation
    if (name.length < 3) {
        alert('Nama harus minimal 3 karakter');
        return;
    }

    if (!email.includes('@')) {
        alert('Email tidak valid');
        return;
    }

    if (phone.length < 10) {
        alert('Nomor telepon tidak valid');
        return;
    }

    if (password.length < 8) {
        alert('Password harus minimal 8 karakter');
        return;
    }

    // Success message
    alert('Pendaftaran berhasil! Selamat datang di Nyemplungin.In');

    // Reset form
    this.reset();
});

// Phone number formatting
document.getElementById('phone').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    e.target.value = value;
});

// Add floating label effect
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('focus', function () {
        this.parentElement.parentElement.querySelector('label').classList.add('text-blue-600');
    });

    input.addEventListener('blur', function () {
        this.parentElement.parentElement.querySelector('label').classList.remove('text-blue-600');
    });
});