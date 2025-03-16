function selectRole(role) {
  document.getElementById("role").value = role;
  document.getElementById("userRole").textContent =
    role.charAt(0).toUpperCase() + role.slice(1);
  document.getElementById("loginForm").classList.remove("hidden");
}

function togglePassword() {
  const passwordField = document.getElementById("password");
  if (passwordField.type === "password") {
    passwordField.type = "text";
  } else {
    passwordField.type = "password";
  }
}
