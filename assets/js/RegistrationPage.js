const registerForm = document.getElementById("register-form");

const registerSuccess = document.getElementById("register-success");
const registerFailed = document.getElementById("register-failed");

const registerErrors = {
  full_name: document.getElementById("register-full_name-error"),
  email: document.getElementById("register-email-error"),
  password: document.getElementById("register-password-error"),
  phone_number: document.getElementById("register-phone_number-error"),
  address: document.getElementById("register-address-error")
};

registerForm.addEventListener("submit", async (event) => {
  event.preventDefault();

  clearMessages();

  const formData = {
    full_name: document.getElementById("full_name").value,
    email: document.getElementById("email").value,
    password: document.getElementById("password").value,
    phone_number: document.getElementById("phone_number").value,
    address: document.getElementById("address").value
  };

  try {
    const response = await axios.post("../api/auth/Register.php", formData);

    registerSuccess.textContent = response.data.message;
    registerSuccess.hidden = false;

    registerForm.reset();
  } catch (error) {
    if (error.response && error.response.status === 409) {
      registerErrors.email.textContent = "*Email is already registered";
    } else if (error.response && error.response.data) {
      const responseData = error.response.data;

      if (responseData.errors) {
        const errors = responseData.errors;

        registerErrors.full_name.textContent =
          errors.full_name ? `*${errors.full_name}` : '';

        registerErrors.email.textContent =
          errors.email ? `*${errors.email}` : '';

        registerErrors.phone_number.textContent =
          errors.phone_number ? `*${errors.phone_number}` : '';

        registerErrors.address.textContent =
          errors.address ? `*${errors.address}` : '';

        registerErrors.password.textContent =
          errors.password ? `*${errors.password}` : '';
      } else if (responseData.message) {
        registerFailed.textContent = responseData.message;
        registerFailed.hidden = false;
      } else {
        registerFailed.textContent = "Registration failed. Please try again.";
        registerFailed.hidden = false;
      }
    } else {
      registerFailed.textContent = "Registration failed. Please try again.";
      registerFailed.hidden = false;
    }
  }
});

function clearMessages() {
  registerSuccess.textContent = "";
  registerSuccess.hidden = true;

  registerFailed.textContent = "";
  registerFailed.hidden = true;

  Object.values(registerErrors).forEach((error) => {
    error.textContent = "";
  });
}