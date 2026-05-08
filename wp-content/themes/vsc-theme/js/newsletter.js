/**
 * Harissa Newsletter — AJAX Form Handler
 */
document.addEventListener('DOMContentLoaded', function () {

  var form = document.getElementById('harissa-nl-form');
  if (!form) return;

  var emailInput = document.getElementById('harissa-nl-email');
  var btnText    = form.querySelector('.harissa-newsletter__btn-text');
  var btnLoading = form.querySelector('.harissa-newsletter__btn-loading');
  var messageDiv = document.getElementById('harissa-nl-message');
  var submitBtn  = document.getElementById('harissa-nl-btn');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    var email = emailInput.value.trim();

    // Validation côté client
    if (!email || !isValidEmail(email)) {
      showMessage('Veuillez entrer une adresse email valide.', 'error');
      emailInput.focus();
      return;
    }

    // État loading
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline-flex';
    submitBtn.disabled = true;
    emailInput.disabled = true;
    hideMessage();

    // Préparer les données
    var formData = new FormData();
    formData.append('action', 'harissa_newsletter');
    formData.append('email', email);
    formData.append('nonce', form.querySelector('#harissa_nl_nonce').value);
    formData.append('list_id', form.querySelector('input[name="list_id"]').value);

    // Envoyer via AJAX
    fetch(harissaNL.ajaxurl, {
      method: 'POST',
      body: formData,
    })
    .then(function (response) { return response.json(); })
    .then(function (data) {
      if (data.success) {
        showMessage(data.data.message, 'success');
        emailInput.value = '';
        // Remplacer le formulaire par un message de succès
        form.innerHTML = '<div class="harissa-newsletter__success">' +
          '<span style="font-size:24px;">✓</span> ' +
          data.data.message +
          '</div>';
      } else {
        showMessage(data.data.message || 'Une erreur est survenue.', 'error');
      }
    })
    .catch(function () {
      showMessage('Erreur de connexion. Veuillez réessayer.', 'error');
    })
    .finally(function () {
      btnText.style.display = 'inline';
      btnLoading.style.display = 'none';
      submitBtn.disabled = false;
      emailInput.disabled = false;
    });
  });

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function showMessage(text, type) {
    messageDiv.textContent = text;
    messageDiv.className = 'harissa-newsletter__message harissa-newsletter__message--' + type;
    messageDiv.style.display = 'block';
  }

  function hideMessage() {
    messageDiv.style.display = 'none';
  }

});
