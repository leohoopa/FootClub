<?php

use App\Model\db\MatchDataFoot;

require_once __DIR__ . '/../../vendor/autoload.php';

$matches = MatchDataFoot::getAllMatches();
$successMessage = match ($_GET['success'] ?? '') {
    'created' => 'Le match a bien été ajouté.',
    'updated' => 'Les informations du match ont bien été modifiées.',
    default => null,
};
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les matchs - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-7xl px-4 py-10">
    <header class="mb-10 flex flex-wrap items-end justify-between gap-5">
        <div>
            <a href="../index.php" class="btn btn-ghost btn-sm">&larr; Accueil</a>
            <p class="mt-5 text-sm font-semibold uppercase tracking-widest text-primary">FootClub · Calendrier</p>
            <h1 class="mt-1 text-4xl font-bold">Matchs</h1>
            <p class="mt-2 text-base-content/70">Retrouvez les rencontres et leurs résultats.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="badge badge-neutral badge-lg">
                <?= count($matches) ?> match<?= count($matches) > 1 ? 's' : '' ?>
            </span>
            <a href="addMatch.php" class="btn btn-primary">Ajouter un match</a>
        </div>
    </header>

    <?php if ($successMessage !== null): ?>
        <div class="alert alert-success mb-6" role="status"><span><?= $escape($successMessage) ?></span></div>
    <?php endif; ?>

    <?php if ($matches === []): ?>
        <section class="card border border-base-200 bg-base-100 shadow-sm">
                <div class="card-body items-center py-16 text-center">
                    <span class="text-4xl" aria-hidden="true">⚽</span>
                    <h2 class="card-title mt-2">Aucun match pour le moment</h2>
                    <p class="text-base-content/70">Ajoutez une rencontre pour la faire apparaître dans cette liste.</p>
                    <a href="addMatch.php" class="btn btn-primary mt-3">Ajouter un match</a>
                </div>
        </section>
    <?php else: ?>
        <ul class="grid gap-5 lg:grid-cols-2">
                <?php foreach ($matches as $match): ?>
                    <?php
                    $teamScore = (int) $match['team_score'];
                    $opponentScore = (int) $match['opponent_score'];
                    $result = $teamScore > $opponentScore
                        ? ['label' => 'Victoire', 'class' => 'badge-success']
                        : ($teamScore === $opponentScore
                            ? ['label' => 'Match nul', 'class' => 'badge-warning']
                            : ['label' => 'Défaite', 'class' => 'badge-error']);
                    $matchDate = new DateTimeImmutable((string) $match['match_date']);
                    ?>
                    <li class="card overflow-hidden border border-base-200 bg-base-100 shadow-sm transition-shadow hover:shadow-lg">
                        <div class="card-body gap-5 p-5 sm:p-6">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex flex-wrap items-center gap-2 text-sm text-base-content/70">
                                    <time datetime="<?= $escape($matchDate->format(DateTimeInterface::ATOM)) ?>" class="font-medium text-base-content">
                                        <?= $escape($matchDate->format('d/m/Y')) ?>
                                    </time>
                                    <span aria-hidden="true">·</span>
                                    <span><?= $escape($matchDate->format('H:i')) ?></span>
                                </div>
                                <span class="badge <?= $result['class'] ?>"><?= $result['label'] ?></span>
                            </div>

                            <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-3 rounded-box bg-base-200/60 px-4 py-6 sm:gap-5 sm:px-6">
                                <div class="min-w-0 text-center">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-base-content/60">Notre équipe</p>
                                    <h2 class="mt-2 break-words text-base font-bold sm:text-lg">
                                        <?= $escape((string) $match['team_name']) ?>
                                    </h2>
                                </div>
                                <p class="whitespace-nowrap text-3xl font-black tabular-nums sm:text-4xl" aria-label="Score <?= $teamScore ?> à <?= $opponentScore ?>">
                                    <?= $teamScore ?><span class="mx-1 text-base-content/40">–</span><?= $opponentScore ?>
                                </p>
                                <div class="min-w-0 text-center">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-base-content/60">Adversaire</p>
                                    <h2 class="mt-2 break-words text-base font-bold sm:text-lg">
                                        <?= $escape((string) $match['opponent_city']) ?>
                                    </h2>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-base-200 pt-4">
                                <p class="flex items-center gap-2 text-sm text-base-content/70">
                                    <span aria-hidden="true">📍</span>
                                    <span><?= $escape((string) $match['city']) ?></span>
                                </p>
                                <a
                                    href="editMatch.php?id=<?= (int) $match['id'] ?>"
                                    class="btn btn-outline btn-primary btn-sm"
                                    aria-label="Modifier le match contre <?= $escape((string) $match['opponent_city']) ?> du <?= $escape($matchDate->format('d/m/Y')) ?>"
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
