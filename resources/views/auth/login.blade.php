<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion / Créer un compte</title>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .login-container {
            background: #fff;
            padding: 2.5rem 2rem 2rem 2rem;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .tab-switcher {
            display: flex;
            width: 100%;
            margin-bottom: 2rem;
        }
        .tab-btn {
            flex: 1;
            padding: 0.8rem 0;
            background: none;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3a4b;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: color 0.2s, border-bottom 0.2s;
        }
        .tab-btn.active {
            color: #4f8cff;
            border-bottom: 3px solid #4f8cff;
        }
        .form-slider {
            width: 100%;
            overflow: hidden;
            position: relative;
            height: 340px;
        }
        .forms-wrapper {
            display: flex;
            width: 200%;
            transition: transform 0.5s cubic-bezier(.77,0,.18,1);
        }
        .form-section {
            width: 50%;
            padding-right: 1rem;
            padding-left: 1rem;
            box-sizing: border-box;
        }
        .form-group {
            width: 100%;
            margin-bottom: 1.1rem;
        }
        label {
            display: block;
            margin-bottom: 0.4rem;
            color: #2d3a4b;
            font-weight: 500;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1px solid #bfc9d9;
            border-radius: 8px;
            font-size: 1rem;
            background: #f7fafd;
            transition: border 0.2s;
        }
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            border: 1.5px solid #4f8cff;
            outline: none;
            background: #fff;
        }
        .error {
            color: #e74c3c;
            font-size: 0.95rem;
            margin-top: 0.2rem;
        }
        button[type="submit"] {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(90deg, #4f8cff 0%, #38b6ff 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(79, 140, 255, 0.08);
            transition: background 0.2s, transform 0.1s;
        }
        button[type="submit"]:hover {
            background: linear-gradient(90deg, #38b6ff 0%, #4f8cff 100%);
            transform: translateY(-2px) scale(1.03);
        }
        @media (max-width: 500px) {
            .login-container {
                padding: 1.5rem 0.7rem 1.2rem 0.7rem;
                max-width: 98vw;
            }
            .form-slider {
                height: 420px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="tab-switcher">
            <button class="tab-btn active" id="loginTab" type="button">Connexion</button>
            <button class="tab-btn" id="registerTab" type="button">Créer un compte</button>
        </div>
        <div class="form-slider">
            <div class="forms-wrapper" id="formsWrapper">
                <!-- Connexion -->
                <div class="form-section">
                    <h1 style="text-align:center; margin-bottom:1.2rem;">Connexion</h1>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="nom">Nom :</label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required autofocus>
                            @error('nom')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom :</label>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required>
                            @error('prenom')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe :</label>
                            <input type="password" name="password" id="password" required>
                            @error('password')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit">Se connecter</button>
                    </form>
                </div>
                <!-- Inscription -->
                <div class="form-section">
                    <h1 style="text-align:center; margin-bottom:1.2rem;">Créer un compte</h1>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label for="register_nom">Nom :</label>
                            <input type="text" name="nom" id="register_nom" value="{{ old('nom') }}" required>
                            @error('nom')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="register_prenom">Prénom :</label>
                            <input type="text" name="prenom" id="register_prenom" value="{{ old('prenom') }}" required>
                            @error('prenom')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="register_password">Mot de passe :</label>
                            <input type="password" name="password" id="register_password" required>
                            @error('password')<div class="error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="register_password_confirmation">Confirmer le mot de passe :</label>
                            <input type="password" name="password_confirmation" id="register_password_confirmation" required>
                        </div>
                        <button type="submit">Créer mon compte</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const formsWrapper = document.getElementById('formsWrapper');

        loginTab.addEventListener('click', function() {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            formsWrapper.style.transform = 'translateX(0%)';
        });
        registerTab.addEventListener('click', function() {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            formsWrapper.style.transform = 'translateX(-50%)';
        });
    </script>
</body>
</html> 