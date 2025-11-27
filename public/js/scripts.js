// Asegúrate de que el script se ejecute solo cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('contrasena');
    const showPasswordCheckbox = document.getElementById('showPassword');
    const togglePasswordIcon = document.getElementById('togglePassword'); // Para la opción de icono

    // Opción con Checkbox:
    if (showPasswordCheckbox) {
        showPasswordCheckbox.addEventListener('change', function() {
            if (this.checked) {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        });
    }


});