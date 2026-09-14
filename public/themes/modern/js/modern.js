(function () {
    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            var input = document.getElementById(button.dataset.passwordToggle);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            button.setAttribute('aria-label', input.type === 'password' ? 'Show password' : 'Hide password');
        });
    });
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            var submit = form.querySelector('button[type="submit"]');
            if (submit) { submit.classList.add('is-loading'); submit.setAttribute('aria-busy', 'true'); }
        });
    });
})();
