const logoutForm = document.getElementById("logout-form-member");

logoutForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    try {
        await axios.post("../../api/auth/member/Logout.php");

        window.location.href = "../LoginPage.php";
    } catch (error) {
        console.error("Logout failed:", error);
    }
});
