document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.nav-candidat nav a');
    const sections = document.querySelectorAll('section[id]');

    const setActiveSection = (hash) => {
        if (!hash) {
            return;
        }

        const targetExists = Array.from(sections).some((section) => section.id === hash);
        const safeHash = targetExists ? hash : 'apercu';

        sections.forEach((section) => {
            section.style.display = section.id === safeHash ? 'block' : 'none';
        });

        navLinks.forEach((link) => {
            link.classList.toggle('active', link.getAttribute('href') === `#${safeHash}`);
        });
    };

    navLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            const target = link.getAttribute('href');
            if (!target || !target.startsWith('#')) {
                return;
            }

            event.preventDefault();
            const sectionId = target.substring(1);
            setActiveSection(sectionId);
            window.location.hash = sectionId;
        });
    });

    const currentHash = window.location.hash ? window.location.hash.substring(1) : 'apercu';
    setActiveSection(currentHash);

    const profileForm = document.getElementById('profile-form');
    const toggleButton = document.getElementById('toggle-profile-form');
    const editButton = document.getElementById('open-profile-form');
    const docsButton = document.getElementById('open-profile-form-doc');

    const openProfileForm = () => {
        if (!profileForm) {
            return;
        }

        profileForm.hidden = false;
        const firstInput = profileForm.querySelector('input');
        if (firstInput) {
            firstInput.focus();
        }
    };

    if (toggleButton) {
        toggleButton.addEventListener('click', openProfileForm);
    }

    if (editButton) {
        editButton.addEventListener('click', openProfileForm);
    }

    if (docsButton) {
        docsButton.addEventListener('click', openProfileForm);
    }
});