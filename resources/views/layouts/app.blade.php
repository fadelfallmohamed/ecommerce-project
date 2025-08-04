<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ma Boutique')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #1e40af;
            --primary-light: #3b82f6;
            --primary-lighter: #60a5fa;
            --light-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #1f2937;
            --text-light: #6b7280;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
            min-height: 100vh;
            color: var(--text-dark);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow-x: hidden;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            letter-spacing: -1px;
            display: flex;
            align-items: center;
            gap: 0.5em;
        }
        
        .navbar {
            box-shadow: 0 2px 12px rgba(30,64,175,0.07);
            background: #fff;
        }
        
        .nav-link.active, .nav-link:focus, .nav-link:hover {
            color: #2563eb !important;
            font-weight: 600;
        }
        
        .nav-link {
            font-size: 1.08rem;
            margin-right: 1.2em;
        }
        
        .container {
            max-width: 1200px;
            padding: 0 15px;
        }
        
        /* Hero Slider */
        .hero-slider {
            width: 100%;
            height: 80vh;
            min-height: 500px;
            position: relative;
            overflow: hidden;
            margin-bottom: 4rem;
        }
        
        .swiper {
            width: 100%;
            height: 100%;
        }
        
        .swiper-slide {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 2rem;
        }
        
        .slide-content {
            max-width: 800px;
            padding: 2rem;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            backdrop-filter: blur(5px);
        }
        
        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: white;
            opacity: 0.5;
            transition: all 0.3s ease;
        }
        
        .swiper-pagination-bullet-active {
            opacity: 1;
            transform: scale(1.3);
            background: #3b82f6;
        }
        
        .swiper-button-next, .swiper-button-prev {
            color: white;
            transition: all 0.3s ease;
        }
        
        .swiper-button-next:hover, .swiper-button-prev:hover {
            color: #3b82f6;
            transform: scale(1.2);
        }
        
        /* Product Cards */
        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .product-image {
            height: 200px;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.05);
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .product-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.3rem;
            margin: 0.5rem 0;
        }
        
        .product-rating {
            color: #ffc107;
            margin-bottom: 1rem;
        }
        
        .btn-add-to-cart {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.7rem;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-add-to-cart:hover {
            background: #1e40af;
            transform: translateY(-2px);
        }
        
        /* Section Title */
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 700px;
            margin: 0 auto;
        }
    </style>
    
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ route('catalogue.index') }}">
            <i class="fa-solid fa-bag-shopping text-primary"></i> Ma Boutique
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalogue.index') ? 'active' : '' }}" href="{{ route('catalogue.index') }}">Produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">À propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                        <i class="fa-solid fa-cart-shopping"></i> Panier
                    </a>
                </li>
                @auth
                <li class="nav-item position-relative">
                    <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                        <i class="fa-solid fa-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ auth()->user()->unreadNotifications->count() }}
                                <span class="visually-hidden">notifications non lues</span>
                            </span>
                        @endif
                    </a>
                </li>
                @endauth
            @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i> Mon compte
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2"></i> Mon profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                                <i class="fas fa-shopping-bag me-2"></i> Mes commandes
                            </a>
                        </li>
                        @if(auth()->user()->is_admin)
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
                            </a>
                        </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" style="cursor: pointer;">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @if(auth()->user()->is_admin)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog me-1"></i> Administration
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                        <li>
                            <a class="dropdown-item {{ request()->is('admin/produits*') ? 'active' : '' }}" href="{{ url('/admin/produits') }}">
                                <i class="fas fa-box me-2"></i> Gérer les produits
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->is('admin/utilisateurs*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users me-2"></i> Gérer les utilisateurs
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            @endauth
            </ul>
        </div>
            </div>
</nav>
<main class="py-4">
        @yield('content')
    </main>
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<!-- AOS Animation JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Custom Scripts -->
<script>
    // Initialize AOS (Animate On Scroll)
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS only if not already initialized in child view
        if (typeof AOS !== 'undefined') {
            const aosElement = document.querySelector('[data-aos]');
            if (aosElement && !aosElement.hasAttribute('data-aos-init')) {
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true,
                    mirror: false
                });
                aosElement.setAttribute('data-aos-init', 'true');
            }
        }
        
        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Add to cart functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-add-to-cart')) {
                const button = e.target.closest('.btn-add-to-cart');
                const productCard = button.closest('.product-card');
                const productName = productCard.querySelector('.product-title').textContent;
                const productPrice = productCard.querySelector('.product-price').textContent;
                
                // Add animation to button
                button.innerHTML = '<i class="fas fa-check me-2"></i>Ajouté !';
                button.style.backgroundColor = '#28a745';
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Ajouter';
                    button.style.backgroundColor = '';
                }, 2000);
                
                // Here you would typically add the item to the cart via AJAX
                console.log(`Added to cart: ${productName} - ${productPrice}`);
            }
        });
    });
</script>

@stack('scripts')
</body>
</html> 