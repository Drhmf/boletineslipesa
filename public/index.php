<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Boletín Estudiantil</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script type="module" src="assets/recaptcha.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="w-full max-w-sm bg-white shadow-lg rounded-2xl p-6">
    <h1 class="text-2xl font-bold text-center mb-6">Consulta de Calificaciones</h1>

    <form id="loginForm" class="space-y-4">
      <div>
        <label for="sigerd_id" class="block text-sm font-medium">ID SIGERD</label>
        <input id="sigerd_id" name="sigerd_id" type="text" required
               class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm
                      focus:border-indigo-500 focus:ring-indigo-500">
      </div>
      <button type="submit"
              class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition">
        Ingresar
      </button>
    </form>

    <p id="error" class="text-red-500 text-sm mt-4 hidden"></p>
  </div>

  <script type="module">
    import { getToken } from './assets/recaptcha.js';
    const siteKey = '<?php echo htmlspecialchars(App\Config\Env::get("CAPTCHA_SITEKEY")); ?>';

    document.getElementById('loginForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const sigerd = document.getElementById('sigerd_id').value.trim();
      const captchaToken = await getToken(siteKey, 'student_login');

      const res  = await fetch('/api/student_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sigerd_id: sigerd, captcha_token: captchaToken })
      });
      const data = await res.json();

      if (res.ok) {
        localStorage.setItem('jwt', data.token);
        window.location.href = '/student/dashboard.php';
      } else {
        const errorEl = document.getElementById('error');
        errorEl.textContent = data.error || 'Error desconocido';
        errorEl.classList.remove('hidden');
      }
    });
  </script>
</body>
</html>
