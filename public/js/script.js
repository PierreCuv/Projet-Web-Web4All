/* ==========================================
   SYSTÈME DE CARTES EXPANDABLES
   ========================================== */

const cards = document.querySelectorAll('.offre-card');
const overlay = document.getElementById('overlay');
let expandedCard = null;
let originalCardState = null;

function expandCard(card) {
    // Sécurité : ne pas animer si déjà ouvert ou si c'est la vue "Détail" (statique)
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

    // Préparation de l'animation
    card.style.transition = 'none';
    card.style.position = 'fixed';
    card.style.top = rect.top + 'px';
    card.style.left = rect.left + 'px';
    card.style.width = rect.width + 'px';
    card.style.height = rect.height + 'px';
    card.style.margin = '0';
    card.style.zIndex = '1001';
    
    void card.offsetHeight; // Force le reflow
    
    card.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
    if(overlay) overlay.classList.add('active');
    expandedCard = card;

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            card.classList.add('expanded');
        });
    });
}

function closeCard() {
    if (!expandedCard || !originalCardState) return;
    
    const card = expandedCard;
    card.classList.remove('expanded');
    if(overlay) overlay.classList.remove('active');

    // On attend la fin de l'animation CSS pour reset les styles inline
    setTimeout(() => {
        if (originalCardState && originalCardState.element === card) {
            card.style.position = originalCardState.position || '';
            card.style.top = originalCardState.top || '';
            card.style.left = originalCardState.left || '';
            card.style.width = originalCardState.width || '';
            card.style.height = originalCardState.height || '';
            card.style.transform = originalCardState.transform || '';
            card.style.zIndex = originalCardState.zIndex || '';
            card.style.transition = originalCardState.transition || '';
            card.style.margin = '';
            
            expandedCard = null;
            originalCardState = null;
        }
    }, 600);
}

// Clic sur une carte pour l'ouvrir
cards.forEach(card => {
    card.addEventListener('click', (e) => {
        if (card.classList.contains('static-card')) return;
        
        // Empêcher l'ouverture si on clique sur un bouton ou le coeur
        if (e.target.closest('.btn-postuler') || 
            e.target.closest('.btn-apply-quick') || 
            e.target.closest('.add-wishlist') ||
            e.target.closest('.btn')) {
            return; 
        }
        
        if (card.classList.contains('expanded')) return;
        expandCard(card);
    });
});

// Fermeture au clic sur l'overlay OU n'importe où ailleurs que la carte
document.addEventListener('click', (e) => {
    if (expandedCard) {
        // Si le clic n'est PAS sur la carte ouverte ET n'est PAS un bouton qui l'a ouverte
        if (!expandedCard.contains(e.target) && !e.target.closest('.offre-card')) {
            closeCard();
        }
    }
});

// Fermeture avec la touche Échap
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && expandedCard) closeCard();
});

/* ==========================================
   GESTION DES FICHIERS (Multi-champs)
   ========================================== */

document.addEventListener('change', (e) => {
    if (e.target.type === 'file') {
        const input = e.target;
        const labelText = input.parentElement.querySelector('.file-text');
        if (labelText) {
            labelText.textContent = input.files.length > 0 ? input.files[0].name : 'Choisir un fichier PDF';
        }
    }
});

/* ==========================================
   AUTHENTIFICATION
   ========================================== */

function checkAuth() {
    return localStorage.getItem('isLoggedIn') === 'true';
}

document.addEventListener('DOMContentLoaded', () => {
    const isLoggedIn = checkAuth();
    const role = localStorage.getItem('userRole');
    const navMenu = document.querySelector('.nav-menu');
    const authNavItem = document.querySelector('.nav-auth');

    if (isLoggedIn) {
        if (authNavItem) {
            authNavItem.innerHTML = `<a href="#" id="logout-btn" class="btn btn-secondary btn-small">Déconnexion</a>`;
            document.getElementById('logout-btn').addEventListener('click', (e) => {
                e.preventDefault();
                localStorage.clear();
                window.location.href = 'index.html';
            });
        }

        if (role === 'entreprise' || role === 'pilote') {
            const createLi = document.createElement('li');
            createLi.innerHTML = `<a href="creer-offre.html" class="nav-link create-link" style="color: #FF6B35; font-weight: bold;">+ Publier</a>`;
            if(navMenu) navMenu.insertBefore(createLi, authNavItem);
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
            return;
        }

        const offerData = {
            id: btn.getAttribute('data-id'),
            title: btn.getAttribute('data-title'),
            company: btn.getAttribute('data-company')
        };

        let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        const index = wishlist.findIndex(item => item.id === offerData.id);

        if (index === -1) {
            wishlist.push(offerData);
            btn.querySelector('.heart-icon').textContent = '❤️';
            alert("Annonce ajoutée !");
        } else {
            wishlist.splice(index, 1);
            btn.querySelector('.heart-icon').textContent = '🤍';
            alert("Offre retirée.");
        }
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
    }
});

/* ==========================================
   MEGA MENU & FORMULAIRE
   ========================================== */

const applicationForm = document.getElementById('applicationForm');
if (applicationForm) {
    applicationForm.addEventListener('submit', (e) => {
        if (!checkAuth()) {
            e.preventDefault();
            alert("🔒 Tu dois être connecté pour postuler !");
        }
    });
}

document.querySelectorAll('.sidebar-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
        document.querySelectorAll('.sidebar-item').forEach(si => si.classList.remove('active'));
        document.querySelectorAll('.mega-grid').forEach(mg => mg.classList.remove('active'));
        this.classList.add('active');
        const grid = document.getElementById(this.getAttribute('data-target'));
        if(grid) grid.classList.add('active');
    });
});