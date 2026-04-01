<?php

// ── Accueil ───────────────────────────────────────────────────────────────
$router->get('/',        'HomeController', 'index');

// ── Authentification ──────────────────────────────────────────────────────
$router->get( '/login',  'AuthController', 'showLogin');
$router->post('/login',  'AuthController', 'login');
$router->get( '/logout', 'AuthController', 'logout');

// ── Entreprises ───────────────────────────────────────────────────────────
$router->get( '/entreprises',               'CompanyController', 'index');
$router->get( '/entreprises/creer',         'CompanyController', 'create',  ['administrateur','pilote']);
$router->post('/entreprises/creer',         'CompanyController', 'store',   ['administrateur','pilote']);
$router->get( '/entreprises/:id',           'CompanyController', 'show');
$router->get( '/entreprises/:id/modifier',  'CompanyController', 'edit',    ['administrateur','pilote']);
$router->post('/entreprises/:id/modifier',  'CompanyController', 'update',  ['administrateur','pilote']);
$router->post('/entreprises/:id/evaluer',   'CompanyController', 'evaluate',['administrateur','pilote']);
$router->post('/entreprises/:id/supprimer', 'CompanyController', 'destroy', ['administrateur','pilote']);

// ── Offres ────────────────────────────────────────────────────────────────
$router->get( '/offres',                 'OfferController', 'index');
$router->get( '/offres/statistiques',    'OfferController', 'statistics');
$router->get( '/offres/creer',           'OfferController', 'create',  ['administrateur','pilote']);
$router->post('/offres/creer',           'OfferController', 'store',   ['administrateur','pilote']);
$router->get( '/offres/:id',             'OfferController', 'show');
$router->get( '/offres/:id/modifier',    'OfferController', 'edit',    ['administrateur','pilote']);
$router->post('/offres/:id/modifier',    'OfferController', 'update',  ['administrateur','pilote']);
$router->post('/offres/:id/supprimer',   'OfferController', 'destroy', ['administrateur','pilote']);

// ── Pilotes ───────────────────────────────────────────────────────────────
$router->get( '/pilotes',               'PilotController', 'index',  ['administrateur']);
$router->get( '/pilotes/creer',         'PilotController', 'create', ['administrateur']);
$router->post('/pilotes/creer',         'PilotController', 'store',  ['administrateur']);
$router->get( '/pilotes/:id',           'PilotController', 'show',   ['administrateur']);
$router->get( '/pilotes/:id/modifier',  'PilotController', 'edit',   ['administrateur']);
$router->post('/pilotes/:id/modifier',  'PilotController', 'update', ['administrateur']);
$router->post('/pilotes/:id/supprimer', 'PilotController', 'destroy',['administrateur']);

// ── Etudiants ─────────────────────────────────────────────────────────────
$router->get( '/etudiants',               'StudentController', 'index',  ['administrateur','pilote']);
$router->get( '/etudiants/creer',         'StudentController', 'create', ['administrateur','pilote']);
$router->post('/etudiants/creer',         'StudentController', 'store',  ['administrateur','pilote']);
$router->get( '/etudiants/:id',           'StudentController', 'show',   ['administrateur','pilote']);
$router->get( '/etudiants/:id/modifier',  'StudentController', 'edit',   ['administrateur','pilote']);
$router->post('/etudiants/:id/modifier',  'StudentController', 'update', ['administrateur','pilote']);
$router->post('/etudiants/:id/supprimer', 'StudentController', 'destroy',['administrateur','pilote']);

// ── Candidatures ──────────────────────────────────────────────────────────
$router->post('/offres/:id/postuler',  'ApplicationController', 'store',          ['etudiant']);
$router->get( '/mes-candidatures',     'ApplicationController', 'myApplications', ['etudiant']);
$router->get( '/pilote/candidatures',  'ApplicationController', 'pilotView',      ['pilote']);

// ── Wishlist ──────────────────────────────────────────────────────────────
$router->get( '/ma-wishlist',           'WishlistController', 'index',  ['etudiant']);
$router->post('/offres/:id/wishlist',   'WishlistController', 'add',    ['etudiant']);
$router->post('/offres/:id/unwishlist', 'WishlistController', 'remove', ['etudiant']);

// ── Pages statiques ───────────────────────────────────────────────────────
$router->get('/mentions-legales', 'StaticController', 'mentions');
