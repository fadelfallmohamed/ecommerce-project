<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Boutique</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&display=swap" rel="stylesheet">
    <style>
        body { margin:0; font-family: 'Montserrat', Arial, sans-serif; background: #f5f6fa; }
        header { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.03); padding: 0 40px; display: flex; align-items: center; height: 64px; }
        .logo { font-weight: bold; font-size: 1.3rem; color: #222; display: flex; align-items: center; }
        .logo i { color: #2563eb; font-size: 1.5rem; margin-right: 8px; }
        nav { margin-left: 32px; }
        nav a { color: #222; text-decoration: none; margin: 0 16px; font-weight: 500; }
        nav a:hover { color: #2563eb; }
        .search-bar { flex: 1; display: flex; justify-content: center; }
        .search-bar input { width: 340px; padding: 8px 16px; border-radius: 20px; border: 1px solid #ddd; font-size: 1rem; }
        .header-actions { display: flex; align-items: center; gap: 24px; margin-left: 32px; }
        .cart { position: relative; font-size: 1.3rem; }
        .cart-badge { position: absolute; top: -8px; right: -10px; background: #ef4444; color: #fff; border-radius: 50%; font-size: 0.8rem; padding: 2px 6px; }
        .login { font-weight: 600; color: #222; text-decoration: none; }
        .login:hover { color: #2563eb; }
        main { min-height: 60vh; }
        footer { background: #181e2a; color: #e5e7eb; padding: 48px 0 16px 0; }
        .footer-content { max-width: 1200px; margin: auto; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 32px; }
        .footer-col { flex: 1; min-width: 220px; }
        .footer-col h3 { color: #fff; margin-bottom: 16px; }
        .footer-col ul { list-style: none; padding: 0; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col ul li a { color: #e5e7eb; text-decoration: none; }
        .footer-col ul li a:hover { color: #2563eb; }
        .footer-logo { font-weight: bold; font-size: 1.2rem; color: #fff; display: flex; align-items: center; margin-bottom: 12px; }
        .footer-logo i { color: #2563eb; font-size: 1.5rem; margin-right: 8px; }
        .footer-social { margin-top: 16px; }
        .footer-social a { color: #e5e7eb; margin-right: 16px; font-size: 1.3rem; text-decoration: none; }
        .footer-social a:hover { color: #2563eb; }
        .footer-contact { margin-top: 8px; }
        .footer-contact i { color: #2563eb; margin-right: 8px; }
        .footer-bottom { text-align: center; color: #a1a1aa; margin-top: 32px; font-size: 0.95rem; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo"><i class="fa-solid fa-bag-shopping"></i> Ma Boutique</div>
        <nav>
            <a href="/">Accueil</a>
            <a href="#">Produits</a>
            <a href="#">À propos</a>
            <a href="#">Contact</a>
            @auth
                <a href="/home">Tableau de bord</a>
            @endauth
        </nav>
        <div class="search-bar">
            <input type="text" placeholder="Rechercher un produit...">
        </div>
        <div class="header-actions">
            <div class="cart">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-badge">0</span>
            </div>
            @auth
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="login" style="background:none;border:none;cursor:pointer;">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="login">Connexion</a>
            @endauth
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <div class="footer-logo"><i class="fa-solid fa-bag-shopping"></i> Ma Boutique</div>
                <div>Votre boutique en ligne de confiance pour tous vos besoins. Nous proposons une large gamme de produits de qualité à des prix compétitifs.</div>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h3>Liens rapides</h3>
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Produits</a></li>
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Contact</h3>
                <div class="footer-contact"><i class="fa-solid fa-phone"></i> 781979264 / 708489388</div>
                <div class="footer-contact"><i class="fa-solid fa-envelope"></i> contact@maboutique.com</div>
                <div class="footer-contact"><i class="fa-solid fa-location-dot"></i> 123 Rue Gueule Tapé, Dakar</div>
            </div>
        </div>
        <div class="footer-bottom">
            © 2025 Ma Boutique. Tous droits réservés.
        </div>
    </footer>
</body>
</html> 