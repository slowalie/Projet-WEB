document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("popUpLoginPage");
    if (!modal) {
        return;
    }

    const backdrop = modal.querySelector("[data-auth-close='true']");
    const loginOpeners = document.querySelectorAll(".navbar .button-connexion");
    const signUpOpeners = document.querySelectorAll(".button-inscription");
    const roleSelector = document.getElementById("auth-role-selector");
    const formContainer = document.getElementById("auth-form-container");
    const roleCards = modal.querySelectorAll(".auth-role-card");
    const backToRoleButtons = modal.querySelectorAll(".auth-back-btn, .auth-back-to-role");
    const closeButton = modal.querySelector(".auth-modal-close");
    const authTabs = modal.querySelectorAll(".auth-tab");
    const loginForm = modal.querySelector(".auth-form-connexion");
    const signupForm = modal.querySelector(".auth-form-inscription");

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
        // Show role selector, hide form container
        if (roleSelector) roleSelector.style.display = "flex";
        if (formContainer) formContainer.style.display = "none";
        
        modal.classList.add("is-open");
        modal.setAttribute("aria-hidden", "false");
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        modal.classList.remove("is-open");
        modal.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";
    }

    // Show form based on selected role
    function showFormForRole(role) {
        if (roleSelector) roleSelector.style.display = "none";
        if (formContainer) formContainer.style.display = "flex";

        // Update title
        const titleMap = {
            admin: "Connexion Admin",
            pilot: "Connexion Pilot",
            etudiant: "Connexion Étudiant"
        };
        const title = document.getElementById("auth-role-title");
        if (title) title.textContent = titleMap[role] || "Connexion";

        // Store selected role
        document.getElementById("roleInput").value = role;
        document.getElementById("roleInputInscription").value = role;

        // Show login form by default
        if (loginForm) loginForm.style.display = "block";
        if (signupForm) signupForm.style.display = "none";
        
        // Gérer la visibilité de l'onglet inscription basée sur le rôle
        const connexionTab = modal.querySelector(".auth-tab[data-tab='connexion']");
        const inscriptionTab = modal.querySelector(".auth-tab[data-tab='inscription']");
        
        if (connexionTab) {
            connexionTab.classList.add("visible");
        }
        
        if (inscriptionTab) {
            // Masquer inscription pour tout le monde initialement
            // Elle ne devient visible qu'après connexion
            inscriptionTab.classList.remove("visible");
        }

        // Reset tabs
        authTabs.forEach(tab => {
            if (tab.classList.contains("visible")) {
                if (tab.dataset.tab === "connexion") {
                    tab.classList.add("active");
                } else {
                    tab.classList.remove("active");
                }
            }
        });
    }

    function goBackToRoleSelector() {
        if (roleSelector) roleSelector.style.display = "flex";
        if (formContainer) formContainer.style.display = "none";
    }

    // Handle role card clicks
    roleCards.forEach(card => {
        card.addEventListener("click", function() {
            const role = this.dataset.role;
            showFormForRole(role);
        });
    });

    // Handle back to role selector button clicks
    modal.querySelectorAll(".auth-back-to-role").forEach(button => {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            goBackToRoleSelector();
        });
    });

    // Handle back button in role selector (close modal)
    modal.querySelectorAll(".auth-back-btn").forEach(button => {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeModal();
        });
    });

    // Handle tab clicks
    authTabs.forEach(tab => {
        tab.addEventListener("click", function() {
            // Skip if tab is hidden (for students)
            if (!this.classList.contains("visible")) {
                return;
            }

            const tabName = this.dataset.tab;
            
            // Update active tab (only visible ones)
            authTabs.forEach(t => {
                if (t.classList.contains("visible")) {
                    t.classList.remove("active");
                }
            });
            this.classList.add("active");

            // Update title based on tab
            const title = document.getElementById("auth-role-title");
            const role = document.getElementById("roleInputInscription").value;
            
            const roleMap = {
                admin: "Admin",
                pilot: "Pilot",
                etudiant: "Étudiant"
            };
            
            if (title) {
                const roleLabel = roleMap[role] || "";
                if (tabName === "connexion") {
                    title.textContent = `Connexion ${roleLabel}`;
                } else {
                    title.textContent = `Inscription ${roleLabel}`;
                }
            }

            // Show/hide forms
            if (tabName === "connexion") {
                if (loginForm) loginForm.style.display = "block";
                if (signupForm) signupForm.style.display = "none";
            } else if (tabName === "inscription") {
                if (loginForm) loginForm.style.display = "none";
                if (signupForm) signupForm.style.display = "block";
            }
        });
    });

    // Handle close button
    if (closeButton) {
        closeButton.addEventListener("click", closeModal);
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
            
            // Vérifier si l'utilisateur est connecté (en cherchant le bouton de déconnexion)
            const logoutButton = document.querySelector(".button-logout");
            const isAuthenticated = logoutButton !== null;
            
            if (isAuthenticated) {
                // L'utilisateur est connecté, montrer directement le formulaire d'inscription
                // Afficher le conteneur de formulaire et cacher le sélecteur de rôle
                if (roleSelector) roleSelector.style.display = "none";
                if (formContainer) formContainer.style.display = "flex";
                
                // Afficher l'onglet inscription
                const inscriptionTab = modal.querySelector(".auth-tab[data-tab='inscription']");
                const connexionTab = modal.querySelector(".auth-tab[data-tab='connexion']");
                
                if (inscriptionTab) {
                    inscriptionTab.classList.add("visible");
                    inscriptionTab.classList.add("active");
                }
                if (connexionTab) {
                    connexionTab.classList.remove("visible");
                    connexionTab.classList.remove("active");
                }
                
                // Afficher le formulaire d'inscription
                if (signupForm) signupForm.style.display = "block";
                if (loginForm) loginForm.style.display = "none";
                
                // Ouvrir la modal
                modal.classList.add("is-open");
                modal.setAttribute("aria-hidden", "false");
                document.body.style.overflow = "hidden";
            } else {
                // L'utilisateur n'est pas connecté, afficher le formulaire de connexion
                window.alert("Veuillez vous connecter d'abord pour accéder au formulaire d'inscription.");
                openModal("login");
            }
        });
    });

    if (backdrop) {
        backdrop.addEventListener("click", closeModal);
    }

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && modal.classList.contains("is-open")) {
            closeModal();
        }
    });

    // Handle auth status messages
    const params = new URLSearchParams(window.location.search);
    const authStatus = params.get("auth_status");
    const authMode = params.get("auth_mode");
    const alertParam = params.get("alert");

    if (alertParam === "please_login") {
        window.alert("Veuillez vous connecter pour accéder à cette page.");
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, "", cleanUrl);
    }

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
