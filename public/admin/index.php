<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin • Boletines</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script type="module" src="assets/recaptcha.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">
  <div class="w-full max-w-sm bg-white shadow-lg rounded-2xl p-6">
    <h1 class="text-2xl font-bold text-center mb-6">Panel Administrativo</h1>

    <form id="adminLoginForm" class="space-y-4">
      <div>
        <label for="username" class="block text-sm font-medium">Usuario</label>
        <input id="username" name="username" type="text" required
               class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm
                      focus:border-indigo-500 focus:ring-indigo-500">
      </div>

      <div>
        <label for="password" class="block text-sm font-medium">Contraseña</label>
        <input id="password" name="password" type="password" required
               class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm
                      focus:border-indigo-500 focus:ring-indigo-500">
      </div>

      <button type="submit"
              class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition">
        Entrar
      </button>
    </form>

    <p id="error" class="text-red-500 text-sm mt-4 hidden"></p>
  </div>

  <script type="module">
    import { getToken } from './assets/recaptcha.js';
    const siteKey = '<?php echo htmlspecialchars(App\Config\Env::get("CAPTCHA_SITEKEY")); ?>';

    document.getElementById('adminLoginForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value.trim();
      const captchaToken = await getToken(siteKey, 'admin_login');

      const res  = await fetch('/api/admin_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password, captcha_token: captchaToken })
      });
      const data = await res.json();

      if (res.ok) {
        localStorage.setItem('jwt', data.token);
        window.location.href = '/admin/dashboard.php';
      } else {
        const errorEl = document.getElementById('error');
        errorEl.textContent = data.error || 'Error desconocido';
        errorEl.classList.remove('hidden');
      }
    });
  </script>
</body>
</html>
