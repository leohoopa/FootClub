<?php

use App\Model\db\PlayerData;

require_once __DIR__ . '/../../autoloader.php';

$players = PlayerData::getAllPlayers();
$successMessage = match ($_GET['success'] ?? '') {
    'created' => 'Le joueur a bien été ajouté.',
    'updated' => 'Les informations du joueur ont bien été modifiées.',
    default => null,
};
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos joueurs - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-7xl px-4 py-10">
    <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-primary">FootClub</p>
            <h1 class="mt-1 text-3xl font-bold">Nos joueurs</h1>
            <p class="mt-2 text-base-content/70">Retrouvez tous les joueurs du club.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="badge badge-primary badge-lg">
                <?= count($players) ?> joueur<?= count($players) > 1 ? 's' : '' ?>
            </span>
            <a href="addPlayer.php" class="btn btn-primary">
                Ajouter un joueur
            </a>
        </div>
    </header>

    <?php if ($successMessage !== null): ?>
        <div class="alert alert-success mb-6" role="status">
            <span><?= $escape($successMessage) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($players === []): ?>
        <div class="alert">
            <span>Aucun joueur n'est enregistré pour le moment.</span>
        </div>
    <?php else: ?>
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($players as $player): ?>
                <?php
                $fullName = $player->getFirstname() . ' ' . $player->getLastname();
                $escapedName = $escape($fullName);
                ?>
                <li class="card overflow-hidden border border-base-200 bg-base-100 shadow-md transition-shadow hover:shadow-xl">
                    <figure class="h-56 bg-base-200">
                        <?php if ($player->getPicture() !== ''): ?>
                            <img
                                src="<?= $escape($player->getPicture()) ?>"
                                alt="Photo de <?= $escapedName ?>"
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
                        <h2 class="card-title"><?= $escapedName ?></h2>
                        <p class="text-sm text-base-content/70">
                            Né(e) le
                            <time datetime="<?= $escape($player->getBirthdate()->format('Y-m-d')) ?>">
                                <?= $escape($player->getBirthdate()->format('d/m/Y')) ?>
                            </time>
                        </p>
                        <div class="card-actions mt-3 justify-end">
                            <a
                                href="editPlayer.php?id=<?= $player->getId() ?>"
                                class="btn btn-outline btn-primary btn-sm"
                            >
                                Modifier
                            </a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>
</body>
</html>
