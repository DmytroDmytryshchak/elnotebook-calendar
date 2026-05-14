<?php $pageTitle = 'Register — Calendar Notebook'; ?>
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
        <h1 class="auth-title">Create account</h1>
        <p class="auth-subtitle">Start organizing your schedule</p>

        <form action="/final_project/Public/register" method="POST" class="auth-form" novalidate>
            <input type="hidden" name="_csrf_token"
                   value="<?php echo htmlspecialchars(isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''); ?>">

            <div class="form-group <?php echo isset($errors['name']) ? 'form-group--error' : ''; ?>">
                <label for="name" class="form-label">Full name</label>
                <input type="text" id="name" name="name" class="form-input"
                       value="<?php echo htmlspecialchars(isset($old['name']) ? $old['name'] : ''); ?>"
                       autocomplete="name" required>
                <?php if (isset($errors['name'])): ?>
                    <span class="form-error"><?php echo htmlspecialchars($errors['name']); ?></span>
                <?php endif; ?>
            </div>

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
                       autocomplete="new-password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="form-error"><?php echo htmlspecialchars($errors['password']); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo isset($errors['password_confirm']) ? 'form-group--error' : ''; ?>">
                <label for="password_confirm" class="form-label">Confirm password</label>
                <input type="password" id="password_confirm" name="password_confirm" class="form-input"
                       autocomplete="new-password" required>
                <?php if (isset($errors['password_confirm'])): ?>
                    <span class="form-error"><?php echo htmlspecialchars($errors['password_confirm']); ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Create account</button>
        </form>

        <p class="auth-footer">
            Already have an account?
            <a href="/final_project/Public/login" class="auth-link">Sign in</a>
        </p>
    </div>
</div>
</body>
</html>