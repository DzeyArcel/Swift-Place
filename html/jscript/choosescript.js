document.addEventListener("DOMContentLoaded", function () {
    const userTypeRadios = document.querySelectorAll('input[name="user-type"]');
    const createAccountButton = document.querySelector('.create-account');

    let selectedRole = "";

    userTypeRadios.forEach(radio => {
        radio.addEventListener("change", function () {
            createAccountButton.removeAttribute("disabled");
            createAccountButton.classList.add("active");

            // Store the selected role
            selectedRole = this.value;

            // Change button text dynamically
            if (selectedRole === "client") {
                createAccountButton.textContent = "Join as a Client";
            } else {
                createAccountButton.textContent = "Apply as a Freelancer";
            }
        });
    });

    createAccountButton.addEventListener("click", function () {
        if (selectedRole === "client") {
            window.location.href = "client.html"; // Link for Clients
        } else if (selectedRole === "freelancer") {
            window.location.href = "freelancer-signup.html"; // Link for Freelancers
        }
    });
});
