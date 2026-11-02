<?php

use App\Controller\OpposingClubController;

require_once __DIR__ . '/../../vendor/autoload.php';

$formValues = ['street_number' => '', 'street' => '', 'postal_code' => '', 'city' => ''];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = OpposingClubController::process($_POST);
    if ($result['success']) {
        header('Location: opposingClub.php?success=created');
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
    <title>Ajouter un club adverse - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-3xl px-4 py-10">
    <div class="mb-5"><a href="opposingClub.php" class="btn btn-ghost btn-sm">&larr; Retour à la liste</a></div>
    <section class="card border border-base-200 bg-base-100 shadow-xl">
        <div class="card-body gap-6">
            <header>
                <p class="text-sm font-semibold uppercase tracking-widest text-primary">FootClub</p>
                <h1 class="card-title mt-1 text-3xl">Ajouter un club adverse</h1>
                <p class="mt-2 text-base-content/70">Renseignez l'adresse du club adverse.</p>
            </header>
            <?php if (isset($errors['_form'])): ?><div class="alert alert-error" role="alert"><span><?= $escape($errors['_form']) ?></span></div><?php endif; ?>
            <form method="post" class="grid gap-5">
                <?php foreach (['street_number' => ['Numéro de rue', 50], 'street' => ['Rue', 255], 'postal_code' => ['Code postal', 20], 'city' => ['Ville', 255]] as $field => [$label, $maxLength]): ?>
                    <label class="form-control w-full">
                        <span class="label"><span class="label-text font-medium"><?= $escape($label) ?></span></span>
                        <input type="text" name="<?= $field ?>" class="input input-bordered w-full" maxlength="<?= $maxLength ?>" required value="<?= $escape($formValues[$field]) ?>" aria-invalid="<?= isset($errors[$field]) ? 'true' : 'false' ?>">
                        <?php if (isset($errors[$field])): ?><span class="mt-1 text-sm text-error"><?= $escape($errors[$field]) ?></span><?php endif; ?>
                    </label>
                <?php endforeach; ?>
                <div class="flex flex-wrap justify-end gap-3">
                    <a href="opposingClub.php" class="btn btn-ghost btn-sm">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer le club adverse</button>
                </div>
            </form>
        </div>
    </section>
</main>
</body>
</html>
