<?php

use App\Controller\StaffController;

require_once __DIR__ . '/../../vendor/autoload.php';

$formValues = [
    'firstname' => '',
    'lastname' => '',
    'role' => '',
    'picture' => '',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = StaffController::process($_POST);
    if ($result['success']) {
        header('Location: staff.php?success=created');
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
    <title>Ajouter un membre du staff - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-3xl px-4 py-10">
    <div class="mb-6">
        <a href="staff.php" class="link link-primary text-sm">&larr; Retour à la liste</a>
    </div>

    <section class="card border border-base-200 bg-base-100 shadow-xl">
        <div class="card-body gap-6">
            <header>
                <p class="text-sm font-semibold uppercase tracking-widest text-primary">FootClub</p>
                <h1 class="card-title mt-1 text-3xl">Ajouter un membre du staff</h1>
                <p class="mt-2 text-base-content/70">Renseignez les informations du nouveau membre du staff.</p>
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
                            placeholder="Ex. Kylian"
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
                        <span class="label"><span class="label-text font-medium">Nom</span></span>
                        <input
                            type="text"
                            name="lastname"
                            class="input input-bordered w-full"
                            placeholder="Ex. Mbappé"
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
                        <span class="label"><span class="label-text font-medium">Rôle</span></span>
                        <input
                            type="text"
                            name="role"
                            class="input input-bordered w-full"
                            placeholder="Ex. Entraîneur"
                            maxlength="255"
                            required
                            value="<?= $escape($formValues['role']) ?>"
                            aria-invalid="<?= isset($errors['role']) ? 'true' : 'false' ?>"
                        >
                        <?php if (isset($errors['role'])): ?>
                            <span class="mt-1 text-sm text-error"><?= $escape($errors['role']) ?></span>
                        <?php endif; ?>
                    </label>

                    <label class="form-control w-full">
                        <span class="label"><span class="label-text font-medium">Photo (URL)</span></span>
                        <input
                            type="text"
                            name="picture"
                            class="input input-bordered w-full"
                            placeholder="Ex. assets/staff/membre.jpg"
                            maxlength="255"
                            value="<?= $escape($formValues['picture']) ?>"
                            aria-invalid="<?= isset($errors['picture']) ? 'true' : 'false' ?>"
                        >
                        <?php if (isset($errors['picture'])): ?>
                            <span class="mt-1 text-sm text-error"><?= $escape($errors['picture']) ?></span>
                        <?php endif; ?>
                    </label>

                <div class="flex flex-wrap justify-end gap-3">
                    <a href="staff.php" class="btn btn-ghost">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer le membre du staff</button>
                </div>
            </form>
        </div>
    </section>
</main>
</body>
</html>
