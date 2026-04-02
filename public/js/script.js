/* ==========================================
   SYSTÈME DE CARTES EXPANDABLES
   ========================================== */

const cards = document.querySelectorAll('.offre-card');
const overlay = document.getElementById('overlay');
let expandedCard = null;
let originalCardState = null;

function expandCard(card) {
    // Sécurité : ne pas animer si c'est une vue "Détail" (statique)
    if (expandedCard || card.classList.contains('static-card')) return;
    
    const rect = card.getBoundingClientRect();
    originalCardState = {
        element: card,
        position: card.style.position,
        top: card.style.top,
        left: card.style.left,
        width: card.style.width,
        height: card.style.height,
        transform: card.style.transform,
        zIndex: card.style.zIndex,
        transition: card.style.transition
    };

    card.style.transition = 'none';
    card.style.position = 'fixed';
    card.style.top = rect.top + 'px';
    card.style.left = rect.left + 'px';
    card.style.width = rect.width + 'px';
    card.style.height = rect.height + 'px';
    card.style.margin = '0';
    card.style.zIndex = '1001';
    
    void card.offsetHeight;
    
    card.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
    if(overlay) overlay.classList.add('active');
    expandedCard = card;

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            card.classList.add('expanded');
        });
    });
}

// ... ta fonction closeCard() reste identique ...

cards.forEach(card => {
    card.addEventListener('click', (e) => {
        // Si c'est une carte statique (page show.php), on ne fait RIEN
        if (card.classList.contains('static-card')) return;

        // Gestion des exceptions (boutons, liens, formulaires)
        if (e.target.closest('.btn-postuler') || 
            e.target.closest('.btn-apply-quick') || 
            e.target.closest('.add-wishlist') ||
            e.target.closest('form')) { // Ajout de form pour éviter les bugs
            return; 
        }
        
        if (card.classList.contains('expanded')) return;
        expandCard(card);
    });
});

/* ==========================================
   GESTION DES FICHIERS (Multi-champs)
   ========================================== */

// On écoute tous les changements sur les inputs de type file
document.addEventListener('change', (e) => {
    if (e.target.type === 'file') {
        const input = e.target;
        // On cherche le span .file-text qui est dans le label juste à côté
        const label = input.parentElement.querySelector('.file-text');
        if (label) {
            label.textContent = input.files.length > 0 ? input.files[0].name : 'Choisir un fichier';
        }
    }
});

cards.forEach(card => {
    card.addEventListener('click', (e) => {
        // Empêcher l'ouverture si on clique sur un bouton ou le coeur
        if (e.target.closest('.btn-postuler') || 
            e.target.closest('.btn-apply-quick') || 
            e.target.closest('.add-wishlist')) {
            return; 
        }
        if (card.classList.contains('expanded')) return;
        expandCard(card);
    });
});

overlay.addEventListener('click', (e) => { if (e.target === overlay) closeCard(); });
document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && expandedCard) closeCard(); });


function checkAuth() {
    return localStorage.getItem('isLoggedIn') === 'true';
}

document.addEventListener('DOMContentLoaded', () => {
    const isLoggedIn = checkAuth();
    const role = localStorage.getItem('userRole');
    const navMenu = document.querySelector('.nav-menu');
    const authNavItem = document.querySelector('.nav-auth');

    if (isLoggedIn) {
        // 1. Changer le bouton Connexion en Déconnexion
        if (authNavItem) {
            authNavItem.innerHTML = `<a href="#" id="logout-btn" class="btn btn-secondary btn-small">Déconnexion</a>`;
            document.getElementById('logout-btn').addEventListener('click', (e) => {
                e.preventDefault();
                localStorage.removeItem('isLoggedIn');
                localStorage.removeItem('userRole');
                localStorage.removeItem('userName');
                window.location.href = 'index.html';
            });
        }

        // 2. Ajouter le bouton "Publier" pour Entreprises et Pilotes
        if (role === 'entreprise' || role === 'pilote') {
            const createLi = document.createElement('li');
            createLi.innerHTML = `<a href="creer-offre.html" class="nav-link create-link" style="color: #FF6B35; font-weight: bold;">+ Publier</a>`;
            navMenu.insertBefore(createLi, authNavItem);
        }
    }
});

/* ==========================================
   SYSTÈME DE WISHLIST
   ========================================== */

document.addEventListener('click', (e) => {
    const btn = e.target.closest('.add-wishlist');
    if (btn) {
        e.stopPropagation();
        if (!checkAuth()) {
            alert("Connecte-toi pour sauvegarder tes offres préférées !");
            window.location.href = 'connexion.html';
            return;
        }

        const offerData = {
            id: btn.getAttribute('data-id'),
            title: btn.getAttribute('data-title'),
            company: btn.getAttribute('data-company'),
            type: btn.getAttribute('data-type'),
            location: btn.getAttribute('data-location')
        };

        let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        const isAlreadyIn = wishlist.some(item => item.id === offerData.id);

        if (!isAlreadyIn) {
            wishlist.push(offerData);
            btn.querySelector('.heart-icon').textContent = '❤️';
            btn.style.borderColor = '#FF6B35';
            alert("Annonce ajoutée !");
        } else {
            wishlist = wishlist.filter(item => item.id !== offerData.id);
            btn.querySelector('.heart-icon').textContent = '🤍';
            btn.style.borderColor = '#eee';
            alert("Offre retirée.");
        }
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
    }
});

/* ==========================================
   FORMULAIRES ET DIVERS
   ========================================== */

const applicationForm = document.getElementById('applicationForm');
if (applicationForm) {
    applicationForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!checkAuth()) {
            alert("🔒 Tu dois être connecté pour postuler !");
            window.location.href = 'connexion.html';
            return;
        }
        alert('✅ Candidature envoyée avec succès !');
        applicationForm.reset();
    });
}

// Gestion label fichier CV
const cvInput = document.getElementById('cv');
if (cvInput) {
    cvInput.addEventListener('change', (e) => {
        const txt = e.target.files.length > 0 ? e.target.files[0].name : 'Choisir un fichier';
        document.querySelector('.file-label .file-text').textContent = txt;
    });
}

// Mega menu
document.querySelectorAll('.sidebar-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
        document.querySelectorAll('.sidebar-item').forEach(si => si.classList.remove('active'));
        document.querySelectorAll('.mega-grid').forEach(mg => mg.classList.remove('active'));
        this.classList.add('active');
        const grid = document.getElementById(this.getAttribute('data-target'));
        if(grid) grid.classList.add('active');
    });
}); 