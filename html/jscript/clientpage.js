
document.addEventListener("DOMContentLoaded", function () {
    fetch("fetch-profile.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
            } else {
                document.querySelector(".profile-pic").src = data.profile_picture || "profile-placeholder.jpg";
                document.querySelector(".profile-card h2").textContent = data.company_name || "No Company Name";
                document.querySelector(".location").textContent = "📍 " + (data.industry || "Industry Not Specified");
                document.querySelector(".bio").textContent = data.preferred_communication || "No Preferred Communication";
            }
        })
        .catch(error => console.error("Error fetching profile:", error));
});

