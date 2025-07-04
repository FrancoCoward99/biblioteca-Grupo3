document.querySelector("form").addEventListener("submit", function(e) {
  const email = document.querySelector("input[name='correo']").value.trim();
  const password = document.querySelector("input[name='contrasena']").value.trim();

  if (!email || !password) {
    e.preventDefault();
    alert("Debe ingresar usuario y contraseña.");
  }
});