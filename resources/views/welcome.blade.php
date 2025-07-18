@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --primary: #1e40af;
        --primary-light: #3b82f6;
        --primary-lighter: #60a5fa;
        --light-bg: #f8fafc;
        --card-bg: #ffffff;
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --transition: all 0.3s ease;
    }
    
    body {
        background: var(--light-bg);
        color: var(--text-dark);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
        margin-bottom: 4rem;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.95;
        margin-bottom: 2rem;
        max-width: 600px;
        line-height: 1.6;
    }
    
    .btn-hero {
        background: white;
        color: var(--primary);
        font-weight: 600;
        padding: 0.9rem 2.5rem;
        border-radius: 50px;
        font-size: 1.1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-hero:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        color: var(--primary);
    }
    
    .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: transparent;
        color: white;
        font-weight: 600;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        transition: var(--transition);
    }
    
    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: white;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Features Section */
    .features-section {
        padding: 5rem 0;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 3.5rem;
    }
    
    .section-title h2 {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }
    
    .section-title p {
        color: var(--text-light);
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .feature-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        height: 100%;
        transition: var(--transition);
        border: 1px solid rgba(0, 0, 0, 0.03);
        box-shadow: var(--shadow-sm);
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .feature-icon {
        width: 70px;
        height: 70px;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: var(--primary);
        font-size: 1.75rem;
        transition: var(--transition);
    }
    
    .feature-card:hover .feature-icon {
        background: var(--primary);
        color: white;
    }
    
    .feature-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-dark);
    }
    
    .feature-text {
        color: var(--text-light);
        line-height: 1.7;
    }
    
    /* How It Works Section */
    .how-it-works {
        background: #f8fafc;
        padding: 5rem 0;
        position: relative;
    }
    
    .step-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        height: 100%;
        text-align: center;
        transition: var(--transition);
        border: 1px solid rgba(0, 0, 0, 0.03);
        box-shadow: var(--shadow-sm);
    }
    
    .step-number {
        width: 50px;
        height: 50px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        margin: 0 auto 1.5rem;
        position: relative;
        z-index: 1;
    }
    
    .step-number:after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(30, 64, 175, 0.1);
        border-radius: 50%;
        z-index: -1;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.7; }
        70% { transform: scale(1.5); opacity: 0; }
        100% { transform: scale(1); opacity: 0; }
    }
    
    .step-icon {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 1.5rem;
    }
    
    .step-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--text-dark);
    }
    
    .step-text {
        color: var(--text-light);
        line-height: 1.7;
    }
    
    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        padding: 5rem 0;
        text-align: center;
        border-radius: 20px;
        margin: 4rem 0;
    }
    
    .cta-title {
        font-size: 2.25rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
    }
    
    .cta-text {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto 2.5rem;
        line-height: 1.7;
    }
    
    /* Responsive */
    @media (max-width: 991.98px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .section-title h2 {
            font-size: 2rem;
        }
        
        .cta-title {
            font-size: 2rem;
        }
    }
    
    /* Product Cards */
    .product-card {
        background: var(--card-bg);
        border-radius: 12px;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .product-img-container {
        position: relative;
        padding-top: 100%;
        background: #f8fafc;
        overflow: hidden;
    }
    
    .product-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-img {
        transform: scale(1.05);
    }
    
    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
    }
    
    .product-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .product-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
        line-height: 1.4;
        min-height: 2.8rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-description {
        color: var(--text-light);
        font-size: 0.875rem;
        margin-bottom: 1rem;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    @media (max-width: 767.98px) {
        .hero-section {
            padding: 4rem 0;
            text-align: center;
        }
        
        .hero-title {
            font-size: 2.25rem;
        }
        
        .hero-buttons {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-hero, .btn-outline-light {
            width: 100%;
            justify-content: center;
        }
        
        .section-title h2 {
            font-size: 1.75rem;
        }
        
        .cta-title {
            font-size: 1.75rem;
        }
        
        .feature-card, .step-card {
            margin-bottom: 1rem;
        }
        
        .product-card {
            margin-bottom: 1.5rem;
        }
    }
</style>
@endpush
@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-content">
                    <h1 class="hero-title">Bienvenue sur <span class="text-warning">Ma Boutique</span></h1>
                    <p class="hero-subtitle">Découvrez une nouvelle façon de faire du shopping en ligne. Des produits de qualité, un service client à votre écoute, et une expérience simple et rapide.</p>
                    <div class="hero-buttons d-flex gap-3">
                        <a href="{{ route('catalogue.index') }}" class="btn btn-hero">
                            <i class="fas fa-shopping-bag me-2"></i> Voir le catalogue
                        </a>
                        <a href="#how-it-works" class="btn btn-outline-light">
                            <i class="fas fa-play-circle me-2"></i> Comment ça marche
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <img src="{{ asset('images/hero-illustration.svg') }}" alt="Shopping en ligne" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-title">
            <h2>Pourquoi choisir notre boutique ?</h2>
            <p>Nous nous engageons à vous offrir la meilleure expérience d'achat en ligne avec des produits de qualité et un service client exceptionnel.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Paiement sécurisé</h3>
                    <p class="feature-text">Transactions 100% sécurisées avec les dernières technologies de cryptage pour protéger vos informations.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="feature-title">Livraison rapide</h3>
                    <p class="feature-text">Expédition sous 24h et livraison express disponible pour une réception rapide de vos articles.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h3 class="feature-title">Retour facile</h3>
                    <p class="feature-text">Satisfait ou remboursé sous 14 jours si l'article ne correspond pas à vos attentes.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="feature-title">Support 24/7</h3>
                    <p class="feature-text">Notre équipe est disponible pour vous aider à tout moment, 7j/7.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="how-it-works">
    <div class="container">
        <div class="section-title">
            <h2>Comment ça marche ?</h2>
            <p>Commandez vos articles préférés en seulement quelques clics avec notre processus simplifié.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="step-title">Parcourez</h3>
                    <p class="step-text">Explorez nos catégories et trouvez les produits qui vous intéressent.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="fas fa-cart-plus"></i>
                    </div>
                    <h3 class="step-title">Ajoutez</h3>
                    <p class="step-text">Ajoutez vos articles préférés à votre panier en un seul clic.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="step-title">Payez</h3>
                    <p class="step-text">Paiement sécurisé avec plusieurs méthodes de paiement disponibles.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="step-title">Recevez</h3>
                    <p class="step-text">Livraison rapide à votre porte, partout au Sénégal.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2 class="cta-title">Prêt à faire du shopping ?</h2>
        <p class="cta-text">Rejoignez des milliers de clients satisfaits et découvrez notre sélection exclusive de produits de qualité.</p>
        <a href="{{ route('catalogue.index') }}" class="btn btn-light btn-lg px-5">
            <i class="fas fa-shopping-cart me-2"></i> Commencer mes achats
        </a>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Nos produits populaires</h2>
            <p>Découvrez nos articles les plus appréciés par nos clients.</p>
        </div>
        
        <div class="row g-4">
            @forelse($featured_products as $product)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="product-card">
                        <div class="product-img-container">
                            <img src="{{ $product->main_image_url }}" class="product-img" alt="{{ $product->name }}">
                            @if($product->stock > 0)
                                <span class="product-badge bg-success">En stock</span>
                            @else
                                <span class="product-badge bg-danger">Rupture</span>
                            @endif
                        </div>
                        <div class="product-body">
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <p class="product-description">{{ Str::limit($product->description, 70) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="product-price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</div>
                                <a href="{{ route('catalogue.fiche', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Aucun produit en vedette pour le moment.</p>
                </div>
            @endforelse
        </div>
        
        @if(isset($featured_products) && $featured_products->count() > 0)
            <div class="text-center mt-5">
                <a href="{{ route('catalogue.index') }}" class="btn btn-outline-primary">
                    Voir tous les produits <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
