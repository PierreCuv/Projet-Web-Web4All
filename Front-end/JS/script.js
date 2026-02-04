// ==========================================
// SYSTÈME DE CARTES EXPANDABLES
// ==========================================

// Sélectionner tous les éléments nécessaires
const cards = document.querySelectorAll('.offre-card');
const overlay = document.getElementById('overlay');
let expandedCard = null;
let originalCardState = null;

// Fonction pour expand une carte progressivement depuis sa position d'origine
function expandCard(card) {
    if (expandedCard) return; // Empêcher plusieurs cartes ouvertes
    
    // Sauvegarder l'état original de la carte
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
    
    // Désactiver temporairement les transitions pour positionner la carte
    card.style.transition = 'none';
    
    // Positionner la carte à sa position actuelle en fixed
    card.style.position = 'fixed';
    card.style.top = rect.top + 'px';
    card.style.left = rect.left + 'px';
    card.style.width = rect.width + 'px';
    card.style.height = rect.height + 'px';
    card.style.margin = '0';
    card.style.transform = 'none';
    card.style.zIndex = '1001';
    
    // Force reflow pour que le navigateur applique ces styles
    void card.offsetHeight;
    
    // Réactiver les transitions
    card.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
    
    // Activer l'overlay
    overlay.classList.add('active');
    
    expandedCard = card;
    
    // Petit délai pour assurer que la transition est prête
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            // Ajouter la classe expanded qui déclenche l'animation vers le centre
            card.classList.add('expanded');
        });
    });
}

// Fonction pour fermer la carte et la ramener à sa position d'origine
function closeCard() {
    if (!expandedCard || !originalCardState) return;
    
    const card = expandedCard;
    
    // Retirer la classe expanded
    card.classList.remove('expanded');
    
    // Désactiver l'overlay
    overlay.classList.remove('active');
    
    // Attendre la fin de la transition avant de restaurer les styles originaux
    setTimeout(() => {
        if (originalCardState && originalCardState.element === card) {
            // Restaurer tous les styles originaux
            card.style.position = originalCardState.position;
            card.style.top = originalCardState.top;
            card.style.left = originalCardState.left;
            card.style.width = originalCardState.width;
            card.style.height = originalCardState.height;
            card.style.transform = originalCardState.transform;
            card.style.zIndex = originalCardState.zIndex;
            card.style.transition = originalCardState.transition;
            card.style.margin = '';
            
            expandedCard = null;
            originalCardState = null;
        }
    }, 600); // Correspond à la durée de la transition CSS (0.6s)
}

// Ajouter les event listeners sur chaque carte
cards.forEach(card => {
    card.addEventListener('click', (e) => {
        // Ne pas expand si on clique sur un bouton
        if (e.target.classList.contains('btn-postuler') || 
            e.target.classList.contains('btn-apply-quick')) {
            e.stopPropagation();
            
            if (e.target.classList.contains('btn-apply-quick')) {
                // Rediriger vers le formulaire
                window.location.href = '#postuler';
            } else {
                // Ouvrir la carte pour voir plus d'infos
                return;
            }
            return;
        }
        
        // Si la carte est déjà expanded, ne rien faire
        if (card.classList.contains('expanded')) {
            return;
        }
        
        expandCard(card);
    });
});

// Fermer au clic sur l'overlay (partie floue)
overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
        closeCard();
    }
});

// Fermer avec la touche Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && expandedCard) {
        closeCard();
    }
});

// ==========================================
// GESTION DU FORMULAIRE DE CANDIDATURE
// ==========================================

const applicationForm = document.getElementById('applicationForm');

if (applicationForm) {
    applicationForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Récupérer les données du formulaire
        const formData = new FormData(applicationForm);
        
        // Simuler l'envoi (à remplacer par un vrai envoi)
        alert('✅ Candidature envoyée avec succès!\n\nNous reviendrons vers vous très prochainement.');
        
        // Réinitialiser le formulaire
        applicationForm.reset();
    });
}

// ==========================================
// GESTION DU FICHIER CV
// ==========================================

const cvInput = document.getElementById('cv');
const fileLabel = document.querySelector('.file-label .file-text');

if (cvInput && fileLabel) {
    cvInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            const fileName = e.target.files[0].name;
            fileLabel.textContent = fileName;
        } else {
            fileLabel.textContent = 'Choisir un fichier';
        }
    });
}

// ==========================================
// SMOOTH SCROLL POUR LA NAVIGATION
// ==========================================

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        
        // Ne pas empêcher le comportement par défaut pour les liens de mentions légales
        if (href === '#' || href.startsWith('#politique') || href.startsWith('#cookies')) {
            return;
        }
        
        e.preventDefault();
        
        const target = document.querySelector(href);
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// GESTION DU CHANGEMENT DE CATÉGORIE DANS LE MEGA MENU
document.querySelectorAll('.sidebar-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
        // Enlever l'état actif partout
        document.querySelectorAll('.sidebar-item').forEach(si => si.classList.remove('active'));
        document.querySelectorAll('.mega-grid').forEach(mg => mg.classList.remove('active'));

        // Activer l'élément actuel
        this.classList.add('active');
        const target = this.getAttribute('data-target');
        const grid = document.getElementById(target);
        if(grid) grid.classList.add('active');
    });
});