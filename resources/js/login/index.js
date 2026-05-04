document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
        const response = await fetch('http://127.0.0.1:8000/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                email: email,
                password: password,
            }),
        });

        const result = await response.json();

        if (!response.ok) {
            alert(result.message || 'Login gagal');
            return;
        }

        // ✅ Simpan token (opsional tapi penting)
        localStorage.setItem('access_token', result.data.access_token);
        localStorage.setItem('role', result.data.role);

        // ✅ Redirect sesuai role
        window.location.href = result.data.redirect_page;

    } catch (error) {
        console.error(error);
        alert('Terjadi kesalahan server');
    }
});
