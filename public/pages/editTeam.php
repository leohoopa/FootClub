<?php

use App\Controller\TeamController;
use App\Model\db\TeamData;

require_once __DIR__ . '/../../vendor/autoload.php';

$teamId = isset($_GET['id']) && is_scalar($_GET['id']) && ctype_digit((string) $_GET['id'])
    ? (int) $_GET['id']
    : 0;
$team = $teamId > 0 ? TeamData::getTeam($teamId) : null;
$formValues = ['name' => $team === null ? '' : (string) $team['name']];
$errors = [];
if ($team !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = TeamController::process($_POST, $teamId);
    if ($result['success']) {
        header('Location: team.php?success=updated');
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
    <title>Modifier une équipe - FootClub</title>
    <link rel="stylesheet" href="../assets/output.css">
</head>
<body>
<main class="container mx-auto max-w-3xl px-4 py-10">
    <div class="mb-5"><a href="team.php" class="btn btn-ghost btn-sm">&larr; Retour à la liste</a></div>
    <?php if ($team === null): ?>
        <div class="alert alert-warning"><span>Équipe introuvable. Vérifiez la sélection depuis la liste des équipes.</span></div>
    <?php else: ?>
        <section class="card border border-base-200 bg-base-100 shadow-xl">
            <div class="card-body gap-6">
                <header>
                    <p class="text-sm font-semibold uppercase tracking-widest text-primary">FootClub</p>
                    <h1 class="card-title mt-1 text-3xl">Modifier l'équipe</h1>
                </header>
                <?php if (isset($errors['_form'])): ?><div class="alert alert-error" role="alert"><span><?= $escape($errors['_form']) ?></span></div><?php endif; ?>
                <form method="post" class="grid gap-5">
                    <label class="form-control w-full">
                        <span class="label"><span class="label-text font-medium">Nom de l'équipe</span></span>
                        <input type="text" name="name" class="input input-bordered w-full" maxlength="255" required value="<?= $escape($formValues['name']) ?>" aria-invalid="<?= isset($errors['name']) ? 'true' : 'false' ?>">
                        <?php if (isset($errors['name'])): ?><span class="mt-1 text-sm text-error"><?= $escape($errors['name']) ?></span><?php endif; ?>
                    </label>
                    <div class="flex flex-wrap justify-end gap-3">
                        <a href="team.php" class="btn btn-ghost btn-sm">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </section>
    <?php endif; ?>
</main>
</body>
</html>
