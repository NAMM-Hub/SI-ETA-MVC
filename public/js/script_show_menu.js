function mostrarOcultar(id) {
  var elemento = document.getElementById(id);
  if (elemento.classList.contains('oculto')) {
    elemento.classList.remove('oculto');
  } else {
    elemento.classList.add('oculto');
  }
}