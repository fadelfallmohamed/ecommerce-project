<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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
            max-width: 370px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .login-container h1 {
            margin-bottom: 1.5rem;
            font-size: 2.1rem;
            font-weight: 700;
            color: #2d3a4b;
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
        input[type="password"] {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1px solid #bfc9d9;
            border-radius: 8px;
            font-size: 1rem;
            background: #f7fafd;
            transition: border 0.2s;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
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
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Connexion</h1>
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
</body>
</html> 