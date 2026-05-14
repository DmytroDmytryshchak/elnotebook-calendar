<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''); ?>">
    <title><?php echo htmlspecialchars(isset($pageTitle) ? $pageTitle : 'Calendar Notebook'); ?></title>
    <link rel="stylesheet" href="/final_project/Public/assets/css/style.css">
</head>
<body class="app-body">

<div class="app-layout">

    <header class="topbar">
        <div class="topbar-brand">
            <span>📅</span>
            <span>Calendar Notebook</span>
        </div>

        <div class="topbar-actions">
            <?php require BASE_PATH . '/Views/part/notification-bell.php'; ?>

            <span class="topbar-user">
                👤 <?php echo htmlspecialchars(isset($userName) ? $userName : ''); ?>
            </span>

            <form action="/final_project/Public/logout" method="POST" style="display:inline;">
                <input type="hidden" name="_csrf_token"
                       value="<?php echo htmlspecialchars(isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''); ?>">
                <button type="submit" class="btn btn-outline btn-sm">Logout</button>
            </form>
        </div>
    </header>

    <main class="main-content">
        <?php require BASE_PATH . '/Views/' . $contentView . '.php'; ?>
    </main>

</div>

<?php require BASE_PATH . '/Views/part/event-modal.php'; ?>


<script src="/final_project/Public/assets/js/ajax.js"></script>
<script src="/final_project/Public/assets/js/calendar.js"></script>
<script src="/final_project/Public/assets/js/event-modal.js"></script>
<script src="/final_project/Public/assets/js/notification.js"></script>
</body>
</html>
