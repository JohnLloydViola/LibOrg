const menuButton = document.getElementById("menu-button");
const sidebar = document.getElementById("sidebar");
const menuIcon = document.getElementById("menu-icon");

sidebar.classList.remove("collapsed");
menuButton.classList.remove("collapsed");
menuIcon.src = "../../assets/images/MenuClosed.svg";

menuButton.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
    menuButton.classList.toggle("collapsed");

    if (sidebar.classList.contains("collapsed")) {
        menuIcon.src = "../../assets/images/MenuOpen.svg";
    } else {
        menuIcon.src = "../../assets/images/MenuClosed.svg";
    }
});