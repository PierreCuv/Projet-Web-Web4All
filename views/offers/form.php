<section class="form-section">
    <div class="container">
        <h2 class="section-title">
            <?= $offer ? 'Modifier l\'offre' : 'Publier une nouvelle offre' ?>
        </h2>
        <p class="section-subtitle">
            <?= $offer ? 'Mettez à jour les informations' : 'Recrutez vos futurs talents dès maintenant' ?>
        </p>

        <form class="application-form" method="POST" action="<?= $action ?>">
            <?= \Core\CSRF::field() ?>

            <div class="form-group">
                <label for="titre">Titre de l'offre *</label>
                <input type="text" id="titre" name="titre" required
                       placeholder="Ex: Développeur PHP Junior"
                       value="<?= \Core\View::e($offer['titre'] ?? '') ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="id_entreprise">Entreprise *</label>
                    <select id="id_entreprise" name="id_entreprise" required
                            style="width:100%;padding:1rem;border:2px solid var(--light-gray);border-radius:8px;">
                        <option value="">— Sélectionner —</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= $company['id_entreprise'] ?>"
                                <?= ($offer['id_entreprise'] ?? '') == $company['id_entreprise'] ? 'selected' : '' ?>>
                                <?= \Core\View::e($company['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lieu">Lieu</label>
                    <input type="text" id="lieu" name="lieu"
                           placeholder="Ex: Paris, France"
                           value="<?= \Core\View::e($offer['lieu'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date_debut">Date de début</label>
                    <input type="date" id="date_debut" name="date_debut"
                           value="<?= \Core\View::e($offer['date_debut'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="date_fin">Date de fin</label>
                    <input type="date" id="date_fin" name="date_fin"
                           value="<?= \Core\View::e($offer['date_fin'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="remuneration">Rémunération (€/mois)</label>
                    <input type="number" id="remuneration" name="remuneration"
                           placeholder="Ex: 800" step="0.01" min="0"
                           value="<?= \Core\View::e($offer['remuneration'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Compétences requises</label>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                    <?php foreach ($competences as $comp): ?>
                        <label style="display:flex;align-items:center;gap:.4rem;
                                      background:var(--light-gray);padding:.3rem .75rem;
                                      border-radius:20px;cursor:pointer;font-size:.9rem;">
                            <input type="checkbox" name="competences[]"
                                   value="<?= $comp['id_competence'] ?>"
                                   <?= in_array($comp['id_competence'], $selected_comp_ids ?? []) ? 'checked' : '' ?>>
                            <?= \Core\View::e($comp['nom']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description du poste *</label>
                <textarea id="description" name="description" rows="8" required
                          placeholder="Décrivez les missions et le profil recherché..."><?= \Core\View::e($offer['description'] ?? '') ?></textarea>
            </div>

            <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary btn-large" style="flex:1;">
                    <?= $offer ? 'Enregistrer les modifications' : 'Publier l\'offre' ?>
                </button>
                <a href="<?= APP_URL ?>/offres" class="btn btn-secondary btn-large">Annuler</a>
            </div>
        </form>
    </div>
</section>
