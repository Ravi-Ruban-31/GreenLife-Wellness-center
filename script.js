function showForm(formId) {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

   
    loginForm.classList.remove('active');
    registerForm.classList.remove('active');

    
    document.getElementById(formId).classList.add('active');
}
