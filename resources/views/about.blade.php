@extends('layouts.app')

@push('styles')
<style>
    :root {
        --primary: #4f46e5;
        --secondary: #10b981;
        --dark: #1f2937;
        --light: #f9fafb;
        --gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }
    
    .hero-about {
        background: var(--gradient);
        color: white;
        padding: 6rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .hero-about::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29-22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-0.895 2-2s-0.895-2-2-2-2 0.895-2 2 0.895 2 2 2zM60 91c1.105 0 2-0.895 2-2s-0.895-2-2-2-2 0.895-2 2 0.895 2 2 2zM35 41c1.105 0 2-0.895 2-2s-0.895-2-2-2-2 0.895-2 2 0.895 2 2 2zM12 60c1.105 0 2-0.895 2-2s-0.895-2-2-2-2 0.895-2 2 0.895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.5;
        z-index: 0;
    }
    
    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 3rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: var(--primary);
        border-radius: 2px;
    }
    
    /* Team Cards */
    .team-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }
    
    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .team-img {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .initials-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 4rem;
        font-weight: 700;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    
    .team-card:hover .team-img img {
        transform: scale(1.05);
    }
    
    .team-info {
        padding: 2rem;
        text-align: center;
    }
    
    .team-role {
        color: var(--primary);
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .team-name {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--dark);
    }
    
    .team-desc {
        color: #6b7280;
        margin-bottom: 1.5rem;
        line-height: 1.7;
    }
    
    .social-links {
        display: flex;
        justify-content: center;
        gap: 0.8rem;
        margin-top: 1.5rem;
    }
    
    .social-link {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        background: var(--primary);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .social-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-about text-center">
    <div class="container position-relative">
        <h1 class="display-4 fw-bold mb-4">L'Équipe Créative</h1>
        <p class="lead mb-0">Découvrez les esprits créatifs derrière notre succès</p>
    </div>
</section>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="section-title">Nos Fondateurs</h2>
        <p class="lead text-muted">Une équipe passionnée qui repousse les limites de l'innovation</p>
    </div>
    <div class="row justify-content-center g-4">
        <!-- Fadel - Développeur Web -->
        <div class="col-lg-5 col-md-10">
            <div class="team-card h-100">
                <div class="team-img">
                    <div class="initials-avatar">FF</div>
                </div>
                <div class="team-info">
                    <span class="team-role">Développeur Web Full-Stack</span>
                    <h3 class="team-name">Fadel Fall</h3>
                    <p class="team-desc">
                        Expert en développement web avec plus de 8 ans d'expérience, Fadel excelle dans la création d'applications web performantes et évolutives. Passionné par les nouvelles technologies, il transforme des idées complexes en solutions logicielles élégantes.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" title="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="#" class="social-link" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" title="Portfolio">
                            <i class="fas fa-globe"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fofana - Designer -->
        <div class="col-lg-5 col-md-10">
            <div class="team-card h-100">
                <div class="team-img">
                    <div class="initials-avatar">FD</div>
                </div>
                <div class="team-info">
                    <span class="team-role">Designer UX/UI Créatif</span>
                    <h3 class="team-name">Fofana Diallo</h3>
                    <p class="team-desc">
                        Designer talentueux avec un œil pour le détail, Fofana crée des expériences utilisateur exceptionnelles. Son approche centrée sur l'utilisateur et son sens aigu de l'esthétique permettent de concevoir des interfaces à la fois belles et intuitives.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" title="Dribbble">
                            <i class="fab fa-dribbble"></i>
                        </a>
                        <a href="#" class="social-link" title="Behance">
                            <i class="fab fa-behance"></i>
                        </a>
                        <a href="#" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" title="Portfolio">
                            <i class="fas fa-globe"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notre Histoire -->
    <div class="row justify-content-center mt-5 pt-5">
        <div class="col-lg-10 text-center">
            <h2 class="section-title">Notre Histoire</h2>
            <p class="lead text-muted mb-5">Comment tout a commencé et où nous allons</p>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-3 h-100">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-lightbulb fa-2x"></i>
                        </div>
                        <h4 class="h5 mb-3">Notre Vision</h4>
                        <p class="mb-0">Révolutionner l'expérience e-commerce en alliant technologie de pointe et design exceptionnel pour offrir des solutions uniques à nos clients.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-3 h-100">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-rocket fa-2x"></i>
                        </div>
                        <h4 class="h5 mb-3">Notre Mission</h4>
                        <p class="mb-0">Créer des plateformes e-commerce intuitives, performantes et esthétiques qui transforment les visiteurs en clients fidèles.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-3 h-100">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                        <h4 class="h5 mb-3">Nos Valeurs</h4>
                        <ul class="list-unstyled mb-0 text-start">
                            <li class="mb-2">✓ Innovation constante</li>
                            <li class="mb-2">✓ Excellence technique</li>
                            <li class="mb-2">✓ Design réfléchi</li>
                            <li>✓ Satisfaction client</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact -->
    <div class="row justify-content-center mt-5 pt-5">
        <div class="col-lg-8 text-center">
            <h2 class="section-title">Travaillons ensemble</h2>
            <p class="lead text-muted mb-5">Vous avez un projet en tête ? Parlons-en !</p>
            
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="mailto:contact@votresite.com" class="btn btn-primary btn-lg px-4">
                    <i class="fas fa-envelope me-2"></i>Nous contacter
                </a>
                <a href="tel:+221706608566" class="btn btn-outline-primary btn-lg px-4">
                    <i class="fas fa-phone me-2"></i>+221 70 660 85 66
                </a>
            </div>
            
            <div class="social-links mt-5">
                <a href="#" class="social-link" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-link" title="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-link" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-link" title="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .team-member {
        transition: transform 0.3s ease;
    }
    .team-member:hover {
        transform: translateY(-5px);
    }
    .social-links a {
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }
    .social-links a:hover {
        color: #0056b3 !important;
    }
</style>

@endsection
