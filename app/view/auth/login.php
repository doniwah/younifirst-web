<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouNiFirst - Login</title>
    <link rel="stylesheet" href="/css/login.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                <div class="brand-name">YouniFirst</div>
            </div>
            <p class="subtitle">Selamat datang di platform komunitas kampus</p>
        </div>

        <div class="login-card">
            <h2 class="card-title">Login ke Akun</h2>
            <p class="card-subtitle">Masukkan email dan password untuk melanjutkan</p>

            <?php if (isset($error)): ?>
            <p style="color:red; text-align:center; margin-bottom:10px;">
                <?= htmlspecialchars($error) ?>
            </p>
            <?php endif; ?>

            <form method="post" action="/users/login">
                <div class="form-group">
                    <label for="username">Email</label>
                    <input type="text" id="email" name="email" placeholder="nim@student.polije.ac.id" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="********" required>
                </div>

                <?php if (isset($model['error'])): ?>
                <p style="color:red;"><?= htmlspecialchars($model['error']) ?></p>
                <?php endif; ?>

                <button type="submit" class="login-button">Login</button>
            </form>
        </div>
    </div>
</body>

</html>