<?php

use App\Controller\MatchController;
use App\Model\db\MatchDataFoot;
use App\Model\db\OpposingClubData;
use App\Model\db\TeamData;

require_once __DIR__ . '/../../vendor/autoload.php';

$matchId = isset($_GET['id']) && is_scalar($_GET['id']) && ctype_digit((string) $_GET['id'])
    ? (int) $_GET['id']
    : 0;
$match = $matchId > 0 ? MatchDataFoot::getMatch($matchId) : null;
$teams = TeamData::getAllTeams();
$opposingClubs = OpposingClubData::getAllOpposingClubs();
$errors = [];
$formValues = $match === null
    ? ['team_score' => '', 'opponent_score' => '', 'match_date' => '', 'city' => '', 'team_id' => '', 'opposing_club_id' => '']
    : [
        'team_score' => (string) $match['team_score'],
        'opponent_score' => (string) $match['opponent_score'],
        'match_date' => date('Y-m-d\TH:i', strtotime((string) $match['match_date'])),
        'city' => (string) $match['city'],
        'team_id' => (string) $match['team_id'],
        'opposing_club_id' => (string) $match['opposing_club_id'],
    ];

if ($match !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = MatchController::process($_POST, $matchId);
    if ($result['success']) {
        header('Location: match.php?success=updated');
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
    <title>Modifier un match - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-3xl px-4 py-10">
    <div class="mb-5">
        <a href="match.php" class="btn btn-ghost btn-sm">&larr; Retour à la liste</a>
    </div>

    <?php if ($match === null): ?>
        <div class="alert alert-warning"><span>Match introuvable. Vérifiez la sélection depuis la liste des matchs.</span></div>
    <?php else: ?>
        <section class="card border border-base-200 bg-base-100 shadow-xl">
            <div class="card-body gap-6">
                <header>
                    <p class="text-sm font-semibold uppercase tracking-widest text-primary">FootClub</p>
                    <h1 class="card-title mt-1 text-3xl">Modifier le match</h1>
                    <p class="mt-2 text-base-content/70">Modifiez le résultat et les informations de la rencontre.</p>
                </header>
                <?php if (isset($errors['_form'])): ?>
                    <div class="alert alert-error" role="alert"><span><?= $escape($errors['_form']) ?></span></div>
                <?php endif; ?>
                <?php if ($teams === [] || $opposingClubs === []): ?>
                    <div class="alert alert-warning" role="alert">
                        <span>
                            Une équipe et un club adverse doivent être disponibles pour enregistrer le match.
                            <a href="team.php" class="btn btn-outline btn-sm">Gérer les équipes</a>
                            ·
                            <a href="opposingClub.php" class="btn btn-outline btn-sm">Gérer les clubs adverses</a>
                        </span>
                    </div>
                <?php endif; ?>

                <form method="post" class="grid gap-5">
                    <label class="form-control w-full">
                        <span class="label"><span class="label-text font-medium">Équipe</span></span>
                        <select name="team_id" class="select select-bordered w-full" required>
                            <option value="">Choisir une équipe</option>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?= (int) $team['id'] ?>" <?= $formValues['team_id'] === (string) $team['id'] ? 'selected' : '' ?>><?= $escape((string) $team['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['team_id'])): ?><span class="mt-1 text-sm text-error"><?= $escape($errors['team_id']) ?></span><?php endif; ?>
                    </label>
                    <label class="form-control w-full">
                        <span class="label"><span class="label-text font-medium">Club adverse</span></span>
                        <select name="opposing_club_id" class="select select-bordered w-full" required>
                            <option value="">Choisir un club adverse</option>
                            <?php foreach ($opposingClubs as $club): ?>
                                <option value="<?= (int) $club['id'] ?>" <?= $formValues['opposing_club_id'] === (string) $club['id'] ? 'selected' : '' ?>>
                                    <?= $escape((string) $club['city'] . ' - ' . $club['street_number'] . ' ' . $club['street']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['opposing_club_id'])): ?><span class="mt-1 text-sm text-error"><?= $escape($errors['opposing_club_id']) ?></span><?php endif; ?>
                    </label>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <?php foreach (['team_score' => 'Score de notre équipe', 'opponent_score' => 'Score adverse'] as $field => $label): ?>
                            <label class="form-control w-full">
                                <span class="label"><span class="label-text font-medium"><?= $escape($label) ?></span></span>
                                <input type="number" name="<?= $field ?>" class="input input-bordered w-full" min="0" max="2147483647" step="1" required value="<?= $escape($formValues[$field]) ?>">
                                <?php if (isset($errors[$field])): ?><span class="mt-1 text-sm text-error"><?= $escape($errors[$field]) ?></span><?php endif; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <label class="form-control w-full">
                        <span class="label"><span class="label-text font-medium">Date et heure</span></span>
                        <input type="datetime-local" name="match_date" class="input input-bordered w-full" required value="<?= $escape($formValues['match_date']) ?>">
                        <?php if (isset($errors['match_date'])): ?><span class="mt-1 text-sm text-error"><?= $escape($errors['match_date']) ?></span><?php endif; ?>
                    </label>
                    <label class="form-control w-full">
                        <span class="label"><span class="label-text font-medium">Ville du match</span></span>
                        <input type="text" name="city" class="input input-bordered w-full" maxlength="255" required value="<?= $escape($formValues['city']) ?>">
                        <?php if (isset($errors['city'])): ?><span class="mt-1 text-sm text-error"><?= $escape($errors['city']) ?></span><?php endif; ?>
                    </label>
                    <div class="flex flex-wrap justify-end gap-3">
                        <a href="match.php" class="btn btn-ghost btn-sm">Annuler</a>
                        <button type="submit" class="btn btn-primary" <?= $teams === [] || $opposingClubs === [] ? 'disabled' : '' ?>>Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </section>
    <?php endif; ?>
</main>
</body>
</html>
