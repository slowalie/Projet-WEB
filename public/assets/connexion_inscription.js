
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("popUpLoginPage");
    if (!modal) {
        return;
    }

    const loginPart = document.getElementById("loginPart");
    const signUpPart = document.getElementById("signUpPart");
    const closedLoginPart = document.getElementById("closedLoginPart");
    const closedSignUpPart = document.getElementById("closedSignUpPart");
    const closeButton = document.getElementById("authCloseButton");
    const backdrop = modal.querySelector("[data-auth-close='true']");

    const loginOpeners = document.querySelectorAll(".navbar .button-connexion");
    const signUpOpeners = document.querySelectorAll(".button-inscription");

    function setMode(mode) {
        const isLogin = mode === "login";

        loginPart.style.display = isLogin ? "flex" : "none";
        signUpPart.style.display = isLogin ? "none" : "flex";

        closedLoginPart.classList.toggle("is-hidden", isLogin);
        closedSignUpPart.classList.toggle("is-hidden", !isLogin);
    }

    function getAuthMessage(status) {
        const messages = {
            register_success: "Inscription reussie. Vous pouvez maintenant vous connecter.",
            login_success: "Connexion reussie.",
            invalid_credentials: "Email ou mot de passe incorrect.",
            email_exists: "Cet email est deja utilise.",
            missing_fields: "Veuillez remplir tous les champs.",
            invalid_email: "Adresse email invalide.",
            password_mismatch: "Les mots de passe ne correspondent pas.",
            weak_password: "Le mot de passe doit contenir au moins 8 caracteres.",
        };

        return messages[status] || "";
    }

    function openModal(mode) {
        setMode(mode);
        modal.classList.add("is-open");
        modal.setAttribute("aria-hidden", "false");
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        modal.classList.remove("is-open");
        modal.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";
    }

    function togglePassword(inputId, triggerId) {
        const input = document.getElementById(inputId);
        const trigger = document.getElementById(triggerId);
        if (!input || !trigger) {
            return;
        }

        if (input.type === "password") {
            input.type = "text";
            trigger.textContent = "🙈";
        } else {
            input.type = "password";
            trigger.textContent = "👁";
        }
    }

    loginOpeners.forEach(function (button) {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            openModal("login");
        });
    });

    signUpOpeners.forEach(function (button) {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            openModal("signup");
        });
    });

    closedLoginPart.addEventListener("click", function () {
        setMode("login");
    });

    closedSignUpPart.addEventListener("click", function () {
        setMode("signup");
    });

    closeButton.addEventListener("click", closeModal);
    backdrop.addEventListener("click", closeModal);

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && modal.classList.contains("is-open")) {
            closeModal();
        }
    });

    document.getElementById("showLoginPswd").addEventListener("click", function () {
        togglePassword("mdpLin", "showLoginPswd");
    });

    document.getElementById("showSignUpPswd").addEventListener("click", function () {
        togglePassword("mdpSup", "showSignUpPswd");
    });

    document.getElementById("showSignUpConfPswd").addEventListener("click", function () {
        togglePassword("confirmMdpSup", "showSignUpConfPswd");
    });

    const params = new URLSearchParams(window.location.search);
    const authStatus = params.get("auth_status");
    const authMode = params.get("auth_mode");

    if (authStatus) {
        const message = getAuthMessage(authStatus);
        if (message !== "") {
            openModal(authMode === "signup" ? "signup" : "login");
            window.setTimeout(function () {
                window.alert(message);
            }, 100);
        }

        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, "", cleanUrl);
    }
});