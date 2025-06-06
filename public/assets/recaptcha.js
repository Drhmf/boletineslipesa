export function loadCaptcha(siteKey) {
  return new Promise((resolve) => {
    const script = document.createElement('script');
    script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
    script.onload = () => resolve(window.grecaptcha);
    document.head.appendChild(script);
  });
}

export function getToken(siteKey, action = 'submit') {
  return loadCaptcha(siteKey).then(grecaptcha => grecaptcha.execute(siteKey, { action }));
}
