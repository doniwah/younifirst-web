<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouNiFirst - Login</title>
    <link rel="stylesheet" href="/css/login.css">
    <style>

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                <div class="brand-name">CampusHub</div>
            </div>
            <p class="subtitle">Selamat datang di platform komunitas kampus</p>
        </div>
        <div class="login-card">
            <h2 class="card-title">Login ke Akun</h2>
            <p class="card-subtitle">Masukkan email dan password untuk melanjutkan</p>

            <form>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="nim@student.polije.ac.id" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="********" required>
                </div>

                <button type="submit" class="login-button">Login</button>
            </form>
        </div>
    </div>
</body>

</html>