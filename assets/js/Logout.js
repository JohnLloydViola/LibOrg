const logoutForm = document.getElementById("logout-form");

logoutForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    try {
        await axios.post("../api/auth/Logout.php");

        window.location.href = "./LoginPage.php";
    } catch (error) {
        console.error("Logout failed:", error);
    }
});
