<?php

require_once __DIR__ . '/../vendor/autoload.php';

$sections = [
    [
        'title' => 'Joueurs',
        'description' => 'Consultez les joueurs du club, ajoutez-en ou modifiez leurs informations.',
        'href' => 'pages/player.php',
        'action' => 'Accéder aux joueurs',
    ],
    [
        'title' => 'Staff',
        'description' => 'Retrouvez les membres de l’équipe encadrante et gérez leurs informations.',
        'href' => 'pages/staff.php',
        'action' => 'Accéder au staff',
    ],
    [
        'title' => 'Matchs',
        'description' => 'Consultez les rencontres et leurs résultats, puis ajoutez ou modifiez un match.',
        'href' => 'pages/match.php',
        'action' => 'Accéder aux matchs',
    ],
];
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootClub - Accueil</title>
    <link rel="stylesheet" href="assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-7xl px-4 py-10">
    <header class="mb-10">
        <p class="text-sm font-semibold uppercase tracking-widest text-primary">Bienvenue sur</p>
        <h1 class="mt-2 text-4xl font-bold">FootClub</h1>
        <p class="mt-3 text-base-content/70">Gérez les membres et les rencontres du club depuis un seul endroit.</p>
    </header>

    <nav aria-label="Accès aux rubriques" class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($sections as $section): ?>
            <section class="card border border-base-200 bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="card-title text-2xl"><?= htmlspecialchars($section['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h2>
                    <p class="text-base-content/70"><?= htmlspecialchars($section['description'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                    <div class="card-actions mt-4">
                        <a href="<?= htmlspecialchars($section['href'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="btn btn-primary">
                            <?= htmlspecialchars($section['action'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                        </a>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>
        <section class="card border border-base-200 bg-base-100 shadow-md">
            <div class="card-body">
                <h2 class="card-title text-2xl">Équipes</h2>
                <p class="text-base-content/70">Consultez et gérez les équipes du club.</p>
                <div class="card-actions mt-4">
                    <a href="pages/team.php" class="btn btn-primary">Accéder aux équipes</a>
                </div>
            </div>
        </section>
        <section class="card border border-base-200 bg-base-100 shadow-md">
            <div class="card-body">
                <h2 class="card-title text-2xl">Clubs adverses</h2>
                <p class="text-base-content/70">Consultez et gérez les clubs rencontrés.</p>
                <div class="card-actions mt-4">
                    <a href="pages/opposingClub.php" class="btn btn-primary">Accéder aux clubs adverses</a>
                </div>
            </div>
        </section>
    </nav>
</main>
</body>
</html>