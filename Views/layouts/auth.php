<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Calendar Notebook') ?></title>
    <link rel="stylesheet" href="/final_project/Public/assets/css/style.css">
</head>
<body class="auth-body">

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-logo">
            <span class="auth-logo-icon">📅</span>
            <span class="auth-logo-text">Calendar Notebook</span>
        </div>

        <?php require BASE_PATH . '/views/' . $view . '.php'; ?>

    </div>
</div>

</body>
</html>
