<?php

use App\Model\db\StaffData;

require_once __DIR__ . '/../../vendor/autoload.php';

$staffMembers = StaffData::getAllStaff();
$successMessage = match ($_GET['success'] ?? '') {
    'created' => 'Le membre du staff a bien été ajouté.',
    'updated' => 'Les informations du membre du staff ont bien été modifiées.',
    default => null,
};
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notre staff - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-7xl px-4 py-10">
    <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-primary">FootClub</p>
            <h1 class="mt-1 text-3xl font-bold">Notre staff</h1>
            <p class="mt-2 text-base-content/70">Retrouvez les membres de l'équipe encadrante du club.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="badge badge-primary badge-lg">
                <?= count($staffMembers) ?> membre<?= count($staffMembers) > 1 ? 's' : '' ?>
            </span>
            <a href="addStaff.php" class="btn btn-primary">
                Ajouter un membre
            </a>
        </div>
    </header>

    <?php if ($successMessage !== null): ?>
        <div class="alert alert-success mb-6" role="status">
            <span><?= $escape($successMessage) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($staffMembers === []): ?>
        <div class="alert">
            <span>Aucun membre du staff n'est enregistré pour le moment.</span>
        </div>
    <?php else: ?>
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($staffMembers as $staff): ?>
                <?php
                $fullName = $staff->getFirstname() . ' ' . $staff->getLastname();
                $escapedName = $escape($fullName);
                ?>
                <li class="card overflow-hidden border border-base-200 bg-base-100 shadow-md transition-shadow hover:shadow-xl">
                    <figure class="h-56 bg-base-200">
                        <?php if ($staff->getPicture() !== ''): ?>
                            <img
                                src="<?= $escape($staff->getPicture()) ?>"
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
                            <?= $escape($staff->getRole()) ?>
                        </p>
                        <div class="card-actions mt-3 justify-end">
                            <a
                                href="editStaff.php?id=<?= $staff->getId() ?>"
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
