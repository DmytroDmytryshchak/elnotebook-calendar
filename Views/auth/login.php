<?php $pageTitle = 'Login — Calendar Notebook'; ?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="/final_project/Public/assets/css/style.css">
</head>
<body class="auth-body">
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <span class="auth-logo-icon">📅</span>
            <span class="auth-logo-text">Calendar Notebook</span>
        </div>
        <h1 class="auth-title">Welcome back</h1>
        <p class="auth-subtitle">Sign in to your calendar</p>

        <?php if (!empty($errors['credentials'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($errors['credentials']); ?>
            </div>
        <?php endif; ?>

        <form action="/final_project/Public/login" method="POST" class="auth-form" novalidate>
            <input type="hidden" name="_csrf_token"
                   value="<?php echo htmlspecialchars(isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''); ?>">

            <div class="form-group <?php echo isset($errors['email']) ? 'form-group--error' : ''; ?>">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input"
                       value="<?php echo htmlspecialchars(isset($old['email']) ? $old['email'] : ''); ?>"
                       autocomplete="email" required>
                <?php if (isset($errors['email'])): ?>
                    <span class="form-error"><?php echo htmlspecialchars($errors['email']); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo isset($errors['password']) ? 'form-group--error' : ''; ?>">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input"
                       autocomplete="current-password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="form-error"><?php echo htmlspecialchars($errors['password']); ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Sign in</button>
        </form>

        <p class="auth-footer">
            Don't have an account?
            <a href="/final_project/Public/register" class="auth-link">Create one</a>
        </p>
    </div>
</div>
</body>
</html>