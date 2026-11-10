const logoutForm = document.getElementById("logout-form-member");

logoutForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    try {
        await api.post("../../api/auth/member/Logout.php");
        sessionStorage.removeItem("csrf_token");

        window.location.href = "../LoginPage.php";
    } catch (error) {
        console.error("Logout failed:", error);
    }
});
