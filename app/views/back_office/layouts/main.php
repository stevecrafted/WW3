<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($title ?? 'Back Office - Gestion des sections') ?></title>
    <link rel="stylesheet" href="/assets/css/back_office.css" />
</head>

<body>
    <?= $content ?? '' ?>
</body>

</html>
