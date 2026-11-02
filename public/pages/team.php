<?php

use App\Model\db\TeamData;

require_once __DIR__ . '/../../vendor/autoload.php';

$teams = TeamData::getAllTeams();
$successMessage = match ($_GET['success'] ?? '') {
    'created' => "L'équipe a bien été ajoutée.",
    'updated' => "Les informations de l'équipe ont bien été modifiées.",
    default => null,
};
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les équipes - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-7xl px-4 py-10">
    <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="../index.php" class="btn btn-ghost btn-sm">&larr; Accueil</a>
            <p class="mt-4 text-sm font-semibold uppercase tracking-widest text-primary">FootClub</p>
            <h1 class="mt-1 text-3xl font-bold">Les équipes</h1>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="badge badge-primary badge-lg"><?= count($teams) ?> équipe<?= count($teams) > 1 ? 's' : '' ?></span>
            <a href="addTeam.php" class="btn btn-primary">Ajouter une équipe</a>
        </div>
    </header>

    <?php if ($successMessage !== null): ?>
        <div class="alert alert-success mb-6" role="status"><span><?= $escape($successMessage) ?></span></div>
    <?php endif; ?>

    <?php if ($teams === []): ?>
        <div class="alert"><span>Aucune équipe n'est enregistrée pour le moment.</span></div>
    <?php else: ?>
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($teams as $team): ?>
                <li class="card border border-base-200 bg-base-100 shadow-md">
                    <div class="card-body">
                        <h2 class="card-title"><?= $escape((string) $team['name']) ?></h2>
                        <div class="card-actions justify-between">
                            <a href="teamPlayers.php?id=<?= (int) $team['id'] ?>" class="btn btn-primary btn-sm">Voir les joueurs</a>
                            <a href="editTeam.php?id=<?= (int) $team['id'] ?>" class="btn btn-outline btn-primary btn-sm">Modifier</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>
</body>
</html>
