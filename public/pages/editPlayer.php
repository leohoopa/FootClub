<?php

use App\Controller\PlayerController;
use App\enum\Roles;
use App\Model\db\PlayerData;
use App\Model\db\PlayerHasTeamData;
use App\Model\db\TeamData;

require_once __DIR__ . '/../../vendor/autoload.php';

$playerId = isset($_GET['id']) && is_scalar($_GET['id']) && ctype_digit((string) $_GET['id'])
    ? (int) $_GET['id']
    : 0;
$player = $playerId > 0 ? PlayerData::getPlayer($playerId) : null;
$teams = TeamData::getAllTeams();
$teamAssociation = $player === null ? null : PlayerHasTeamData::getPlayerTeamAssociation($playerId);
$errors = [];
$formValues = $player === null
    ? ['firstname' => '', 'lastname' => '', 'birthdate' => '', 'picture' => '', 'team_id' => '', 'role' => '']
    : [
        'firstname' => $player->getFirstname(),
        'lastname' => $player->getLastname(),
        'birthdate' => $player->getBirthdate()->format('Y-m-d'),
        'picture' => $player->getPicture(),
        'team_id' => $teamAssociation === null ? '' : (string) $teamAssociation['team_id'],
        'role' => $teamAssociation === null ? '' : (string) $teamAssociation['role'],
    ];

if ($player !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = PlayerController::process($_POST, $playerId);
    if ($result['success']) {
        header('Location: player.php?success=updated');
        exit;
    }

    $formValues = $result['values'];
    $errors = $result['errors'];
}

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un joueur - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-3xl px-4 py-10">
    <div class="mb-5">
        <a href="player.php" class="btn btn-ghost btn-sm">&larr; Retour à la liste</a>
    </div>

    <?php if ($player === null): ?>
        <div class="alert alert-warning">
            <span>Joueur introuvable. Vérifiez la sélection depuis la liste des joueurs.</span>
        </div>
    <?php else: ?>
        <section class="card border border-base-200 bg-base-100 shadow-xl">
            <div class="card-body gap-6">
                <header>
                    <p class="text-sm font-semibold uppercase tracking-widest text-primary">FootClub</p>
                    <h1 class="card-title mt-1 text-3xl">Modifier le joueur</h1>
                    <p class="mt-2 text-base-content/70">Modifiez les informations de ce joueur.</p>
                </header>

                <?php if (isset($errors['_form'])): ?>
                <div class="alert alert-error" role="alert">
                    <span><?= $escape($errors['_form']) ?></span>
                </div>
                <?php endif; ?>

                <form method="post" class="grid gap-5">
                    <label class="form-control w-full">
                        <span class="label"><span class="label-text font-medium">Prénom</span></span>
                        <input
                                type="text"
                                name="firstname"
                                class="input input-bordered w-full"
                                maxlength="255"
                                required
                                value="<?= $escape($formValues['firstname']) ?>"
                                aria-invalid="<?= isset($errors['firstname']) ? 'true' : 'false' ?>"
                            >
                            <?php if (isset($errors['firstname'])): ?>
                                <span class="mt-1 text-sm text-error"><?= $escape($errors['firstname']) ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="form-control w-full">
                            <span class="label"><span class="label-text font-medium">Équipe (facultatif)</span></span>
                            <select name="team_id" class="select select-bordered w-full" aria-invalid="<?= isset($errors['team_id']) ? 'true' : 'false' ?>">
                                <option value="">Aucune équipe</option>
                                <?php foreach ($teams as $team): ?>
                                    <option value="<?= (int) $team['id'] ?>" <?= $formValues['team_id'] === (string) $team['id'] ? 'selected' : '' ?>>
                                        <?= $escape((string) $team['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['team_id'])): ?>
                                <span class="mt-1 text-sm text-error"><?= $escape($errors['team_id']) ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="form-control w-full">
                            <span class="label"><span class="label-text font-medium">Poste dans l'équipe</span></span>
                            <select name="role" class="select select-bordered w-full" aria-invalid="<?= isset($errors['role']) ? 'true' : 'false' ?>">
                                <option value="">Choisir un poste</option>
                                <?php foreach (Roles::cases() as $role): ?>
                                    <option value="<?= $escape($role->value) ?>" <?= $formValues['role'] === $role->value ? 'selected' : '' ?>>
                                        <?= $escape($role->value) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['role'])): ?>
                                <span class="mt-1 text-sm text-error"><?= $escape($errors['role']) ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="form-control w-full">
                            <span class="label"><span class="label-text font-medium">Nom</span></span>
                            <input
                                type="text"
                                name="lastname"
                                class="input input-bordered w-full"
                                maxlength="255"
                                required
                                value="<?= $escape($formValues['lastname']) ?>"
                                aria-invalid="<?= isset($errors['lastname']) ? 'true' : 'false' ?>"
                            >
                            <?php if (isset($errors['lastname'])): ?>
                                <span class="mt-1 text-sm text-error"><?= $escape($errors['lastname']) ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="form-control w-full">
                            <span class="label"><span class="label-text font-medium">Date de naissance</span></span>
                            <input
                                type="date"
                                name="birthdate"
                                class="input input-bordered w-full"
                                required
                                value="<?= $escape($formValues['birthdate']) ?>"
                                aria-invalid="<?= isset($errors['birthdate']) ? 'true' : 'false' ?>"
                            >
                            <?php if (isset($errors['birthdate'])): ?>
                                <span class="mt-1 text-sm text-error"><?= $escape($errors['birthdate']) ?></span>
                            <?php endif; ?>
                        </label>

                        <label class="form-control w-full">
                            <span class="label"><span class="label-text font-medium">URL de la photo</span></span>
                            <input
                                type="text"
                                name="picture"
                                class="input input-bordered w-full"
                                placeholder="Ex. https://exemple.com/photo-joueur.jpg"
                                maxlength="255"
                                value="<?= $escape($formValues['picture']) ?>"
                                aria-invalid="<?= isset($errors['picture']) ? 'true' : 'false' ?>"
                            >
                            <?php if (isset($errors['picture'])): ?>
                                <span class="mt-1 text-sm text-error"><?= $escape($errors['picture']) ?></span>
                            <?php endif; ?>
                        </label>

                    <div class="flex flex-wrap justify-end gap-3">
                        <a href="player.php" class="btn btn-ghost btn-sm">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </section>
    <?php endif; ?>
</main>
</body>
</html>
