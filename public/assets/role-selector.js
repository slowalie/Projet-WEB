document.addEventListener("DOMContentLoaded", function () {
    const roleSelector = document.getElementById("auth-role-selector");
    const formContainer = document.getElementById("auth-form-container");
    const roleCards = document.querySelectorAll(".auth-role-card");
    const roleTitle = document.getElementById("auth-role-title");
    
    // All role input fields to update
    const roleInputs = [
        document.getElementById("roleInput"),
        document.getElementById("roleInputInscription"),
        document.getElementById("roleInputLogin"),
        document.getElementById("roleInputSignUp")
    ].filter(el => el !== null); // Filter out null if some don't exist
    
    const backBtn = document.querySelector(".auth-back-btn");
    const backToRoleBtn = document.querySelector(".auth-back-to-role");
    const tabButtons = document.querySelectorAll(".auth-tab");
    const formConnexion = document.querySelector(".auth-form-connexion");
    const formInscription = document.querySelector(".auth-form-inscription");

    const roleTexts = {
        admin: "Connexion Admin",
        pilot: "Connexion Pilote",
        etudiant: "Connexion Étudiant"
    };

    // Sélection du rôle
    roleCards.forEach(card => {
        card.addEventListener("click", function (e) {
            e.preventDefault();
            const role = this.dataset.role;
            
            // Mise à jour des champs cachés
            roleInputs.forEach(input => input.value = role);
            
            // Mise à jour du titre
            roleTitle.textContent = roleTexts[role];
            
            // Affichage du formulaire
            roleSelector.style.display = "none";
            formContainer.style.display = "block";
            formConnexion.style.display = "block";
            formInscription.style.display = "none";
            
            // Masquer complètement l'onglet d'inscription pour admin/pilot
            // Il ne devient visible qu'après connexion
            tabButtons.forEach(tab => {
                tab.classList.remove('visible');
                tab.classList.remove('active');
            });
            
            // Rendre uniquement l'onglet "Connexion" visible
            const connexionTab = document.querySelector('.auth-tab[data-tab="connexion"]');
            if (connexionTab) {
                connexionTab.classList.add('visible');
                connexionTab.classList.add('active');
            }
        });
    });

    // Retour à la sélection des rôles
    [backBtn, backToRoleBtn].forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            roleSelector.style.display = "block";
            formContainer.style.display = "none";
        });
    });

    // Onglets Connexion/Inscription
    tabButtons.forEach(tab => {
        tab.addEventListener("click", function (e) {
            e.preventDefault();
            const targetTab = this.dataset.tab;
            
            // Vérifier si on essaie d'accéder à l'onglet inscription sans autorisation
            if (targetTab === "inscription" && !this.classList.contains("visible")) {
                alert("Veuillez vous connecter d'abord pour accéder au formulaire d'inscription.");
                return;
            }
            
            // Retire la classe active de tous les onglets visibles
            tabButtons.forEach(t => {
                if (t.classList.contains("visible")) {
                    t.classList.remove("active");
                }
            });
            this.classList.add("active");
            
            // Affiche/masque les formulaires
            if (targetTab === "connexion") {
                formConnexion.style.display = "block";
                formInscription.style.display = "none";
            } else {
                formConnexion.style.display = "none";
                formInscription.style.display = "block";
            }
        });
    });

    // Fermeture de la modal
    const closeButton = document.querySelector(".auth-modal-close");
    if (closeButton) {
        closeButton.addEventListener("click", function (e) {
            e.preventDefault();
            roleSelector.style.display = "block";
            formContainer.style.display = "none";
        });
    }
});
