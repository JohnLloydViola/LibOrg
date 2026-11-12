const loginForm = document.getElementById("login-form");

const loginSuccess = document.getElementById("login-success");
const loginFailed = document.getElementById("login-failed");

const loginErrors = {
  email: document.getElementById("login-email-error"),
  password: document.getElementById("login-password-error")
};

loginForm.addEventListener("submit", async (event) => {
  event.preventDefault();

  clearMessages();

  const formData = {
    email: document.getElementById("email").value,
    password: document.getElementById("password").value
  };

  try {
    const response = await api.post("../api/auth/Login.php", formData);

    // Save csrf token to session storage
    if (response.data.csrf_token) {
      sessionStorage.setItem("csrf_token", response.data.csrf_token);
    }

    // Save laravel api token to session storage
    if (response.data.token) {
      sessionStorage.setItem("token", response.data.token);
    }

    loginSuccess.textContent = response.data.message;
    loginSuccess.hidden = false;

    window.location.href = response.data.redirect;
  } catch (error) {
    if (error.response && error.response.data) {
      const responseData = error.response.data;

      if (responseData.errors) {
        const errors = responseData.errors;

        loginErrors.email.textContent =
          errors.email ? `*${errors.email}` : "";

        loginErrors.password.textContent =
          errors.password ? `*${errors.password}` : "";
      } else if (responseData.message) {
        loginFailed.textContent = responseData.message;
        loginFailed.hidden = false;
      } else {
        loginFailed.textContent = "Login failed. Please try again.";
        loginFailed.hidden = false;
      }
    } else {
      loginFailed.textContent = "Login failed. Please try again.";
      loginFailed.hidden = false;
    }
  }
});

function clearMessages() {
  loginSuccess.textContent = "";
  loginSuccess.hidden = true;

  loginFailed.textContent = "";
  loginFailed.hidden = true;

  Object.values(loginErrors).forEach((error) => {
    error.textContent = "";
  });
}