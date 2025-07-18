@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0">À propos de nous</h1>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid mb-3" style="max-height: 120px;">
                        <h2 class="h3 text-primary">Notre Histoire</h2>
                    </div>
                    
                    <div class="mb-4">
                        <p class="lead">
                            Fondée en 2025, notre entreprise s'engage à fournir des produits de qualité supérieure à des prix compétitifs.
                        </p>
                        <p>
                            Notre mission est de simplifier votre expérience d'achat en ligne tout en vous offrant un service client exceptionnel.
                        </p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h5 text-primary">Notre Vision</h3>
                                    <p>Devenir le leader du e-commerce en offrant une expérience d'achat unique et personnalisée.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h5 text-primary">Nos Valeurs</h3>
                                    <ul class="list-unstyled">
                                        <li>✓ Qualité exceptionnelle</li>
                                        <li>✓ Service client hors pair</li>
                                        <li>✓ Innovation constante</li>
                                        <li>✓ Respect de l'environnement</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="team-section mt-5">
                        <h2 class="h4 text-center mb-4">Notre Équipe</h2>
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="team-member">
                                    <div class="rounded-circle overflow-hidden mx-auto mb-3" style="width: 150px; height: 150px; background-color: #f8f9fa;">
                                        <i class="fas fa-user fa-5x text-muted d-flex align-items-center justify-content-center h-100"></i>
                                    </div>
                                    <h4>Nom du Membre</h4>
                                    <p class="text-muted">Fondateur & CEO</p>
                                </div>
                            </div>
                            <!-- Add more team members as needed -->
                        </div>
                    </div>

                    <div class="contact-info text-center mt-5 pt-4 border-top">
                        <h3 class="h5 mb-3">Contactez-nous</h3>
                        <p>Email: contact@votresite.com<br>
                        Téléphone: +33 1 23 45 67 89</p>
                        <div class="social-links">
                            <a href="#" class="text-primary mx-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-primary mx-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-primary mx-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-primary mx-2"><i class="fab fa-linkedin-in"></i></a>
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
