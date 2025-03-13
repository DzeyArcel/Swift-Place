function toggleNotifications() {
    var dropdown = document.getElementById("notificationDropdown");
    dropdown.classList.toggle("show");
}

// Close dropdown when clicking outside
document.addEventListener("click", function(event) {
    var dropdown = document.getElementById("notificationDropdown");
    var icon = document.querySelector(".notification-icon");

    if (!icon.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove("show");
    }
});
