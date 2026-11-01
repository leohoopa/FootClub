<?php

use App\Model\db\PlayerHasTeamData;
use App\Model\db\TeamData;

require_once __DIR__ . '/../../vendor/autoload.php';

$teamId = isset($_GET['id']) && is_scalar($_GET['id']) && ctype_digit((string) $_GET['id'])
    ? (int) $_GET['id']
    : 0;
$team = $teamId > 0 ? TeamData::getTeam($teamId) : null;
$players = $team === null ? [] : PlayerHasTeamData::getPlayersByTeam($teamId);
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $team === null ? 'Équipe introuvable' : 'Joueurs - ' . $escape((string) $team['name']) ?> - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-7xl px-4 py-10">
    <header class="mb-8">
        <a href="team.php" class="btn btn-ghost btn-sm">&larr; Retour aux équipes</a>
        <?php if ($team === null): ?>
            <h1 class="mt-5 text-3xl font-bold">Équipe introuvable</h1>
            <p class="mt-2 text-base-content/70">Cette équipe n'existe pas ou a été supprimée.</p>
        <?php else: ?>
            <p class="mt-5 text-sm font-semibold uppercase tracking-widest text-primary">Effectif</p>
            <div class="mt-1 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold"><?= $escape((string) $team['name']) ?></h1>
                    <p class="mt-2 text-base-content/70">Joueurs associés à cette équipe.</p>
                </div>
                <span class="badge badge-primary badge-lg"><?= count($players) ?> joueur<?= count($players) > 1 ? 's' : '' ?></span>
            </div>
        <?php endif; ?>
    </header>

    <?php if ($team !== null && $players === []): ?>
        <section class="card border border-base-200 bg-base-100 shadow-sm">
            <div class="card-body items-center py-16 text-center">
                <h2 class="card-title">Aucun joueur dans cette équipe</h2>
                <p class="text-base-content/70">Vous pouvez associer des joueurs depuis leur formulaire de création ou de modification.</p>
                <a href="addPlayer.php" class="btn btn-primary mt-3">Ajouter un joueur</a>
            </div>
        </section>
    <?php elseif ($team !== null): ?>
        <ul class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($players as $player): ?>
                <?php $fullName = (string) $player['firstname'] . ' ' . (string) $player['lastname']; ?>
                <li class="card overflow-hidden border border-base-200 bg-base-100 shadow-md">
                    <figure class="h-52 bg-base-200">
                        <?php if ((string) $player['picture'] !== ''): ?>
                            <img
                                src="<?= $escape((string) $player['picture']) ?>"
                                alt="Photo de <?= $escape($fullName) ?>"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <div class="avatar placeholder">
                                <div class="w-24 rounded-full bg-neutral text-neutral-content">
                                    <span class="text-3xl">FC</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </figure>
                    <div class="card-body">
                        <h2 class="card-title"><?= $escape($fullName) ?></h2>
                        <p class="text-sm text-base-content/70"><?= $escape((string) $player['role']) ?></p>
                        <p class="text-sm text-base-content/70">
                            Né(e) le <?= $escape(date('d/m/Y', strtotime((string) $player['birthdate']))) ?>
                        </p>
                        <div class="card-actions mt-3 justify-end">
                            <a href="editPlayer.php?id=<?= (int) $player['id'] ?>" class="btn btn-outline btn-primary btn-sm">Modifier</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>
</body>
</html>
