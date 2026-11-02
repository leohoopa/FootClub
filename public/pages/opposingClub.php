<?php

use App\Model\db\OpposingClubData;

require_once __DIR__ . '/../../vendor/autoload.php';

$clubs = OpposingClubData::getAllOpposingClubs();
$successMessage = match ($_GET['success'] ?? '') {
    'created' => 'Le club adverse a bien été ajouté.',
    'updated' => 'Les informations du club adverse ont bien été modifiées.',
    default => null,
};
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clubs adverses - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-7xl px-4 py-10">
    <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="../index.php" class="btn btn-ghost btn-sm">&larr; Accueil</a>
            <p class="mt-4 text-sm font-semibold uppercase tracking-widest text-primary">FootClub</p>
            <h1 class="mt-1 text-3xl font-bold">Clubs adverses</h1>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="badge badge-primary badge-lg"><?= count($clubs) ?> club<?= count($clubs) > 1 ? 's' : '' ?></span>
            <a href="addOpposingClub.php" class="btn btn-primary">Ajouter un club adverse</a>
        </div>
    </header>

    <?php if ($successMessage !== null): ?>
        <div class="alert alert-success mb-6" role="status"><span><?= $escape($successMessage) ?></span></div>
    <?php endif; ?>

    <?php if ($clubs === []): ?>
        <div class="alert"><span>Aucun club adverse n'est enregistré pour le moment.</span></div>
    <?php else: ?>
        <div class="overflow-x-auto rounded-box border border-base-200 bg-base-100 shadow-md">
            <table class="table">
                <thead><tr><th>Ville</th><th>Adresse</th><th>Code postal</th><th><span class="sr-only">Actions</span></th></tr></thead>
                <tbody>
                <?php foreach ($clubs as $club): ?>
                    <tr>
                        <td><?= $escape((string) $club['city']) ?></td>
                        <td><?= $escape((string) $club['street_number'] . ' ' . (string) $club['street']) ?></td>
                        <td><?= $escape((string) $club['postal_code']) ?></td>
                        <td class="text-right"><a href="editOpposingClub.php?id=<?= (int) $club['id'] ?>" class="btn btn-outline btn-primary btn-sm">Modifier</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
