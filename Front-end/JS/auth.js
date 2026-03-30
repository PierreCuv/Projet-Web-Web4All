document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.tab-btn');
    const forms = document.querySelectorAll('.auth-form');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-target');

            // Switch tabs
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // Switch forms
            forms.forEach(f => f.classList.remove('active'));
            document.getElementById(target).classList.add('active');
        });
    });

    // Gestion de la soumission (Simulation)
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const type = form.id === 'login-form' ? 'Connexion' : 'Inscription';
            alert(`🚀 Simulation : ${type} réussie !`);
            window.location.href = 'index.html';
        });
    });
});
// Dans ton fichier auth.js, lors de la soumission du formulaire :
const loginForm = document.getElementById('login-form');
if(loginForm) {
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Simulation d'une réponse positive du serveur PHP
        localStorage.setItem('isLoggedIn', 'true');
        localStorage.setItem('userName', 'Ted'); // Optionnel
        
        alert("Connexion réussie !");
        window.location.href = 'index.html';
    });
}
// Dans auth.js, dans la partie soumission du formulaire d'inscription :
const registerForm = document.getElementById('register-form');
if (registerForm) {
    registerForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const role = document.getElementById('reg-role').value;
        const nom = document.getElementById('reg-nom').value;

        // On stocke le rôle en plus de l'état de connexion
        localStorage.setItem('isLoggedIn', 'true');
        localStorage.setItem('userRole', role);
        localStorage.setItem('userName', nom);

        let message = "";
        if(role === 'entreprise') message = `Bienvenue, l'entreprise ${nom} !`;
        else if(role === 'pilote') message = `Prêt pour le décollage, Pilote ${nom} ?`;
        else message = `Salut ${nom}, bonne recherche de stage !`;

        alert(`✅ ${message}`);
        window.location.href = 'index.html';
    });
}