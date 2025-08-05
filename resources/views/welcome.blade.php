@extends('layouts.app')

@push('styles')
<style>
:root {
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --primary-lighter: #eff6ff;
    --dark: #1f2937;
    --light: #f8fafc;
    --gray: #6b7280;
    --gray-light: #e5e7eb;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: var(--dark);
    line-height: 1.6;
    background-color: #ffffff;
}

h1, h2, h3, h4, h5, h6 {
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--dark);
}

a {
    text-decoration: none;
    transition: all 0.3s ease;
}

img {
    max-width: 100%;
    height: auto;
}

/* Boutons */
.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: var(--primary);
    border-color: var(--primary);
}

.btn-primary:hover {
    background-color: #1d4ed8;
    border-color: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
}

.btn-outline-dark {
    border: 2px solid var(--dark);
    color: var(--dark);
}

.btn-outline-dark:hover {
    background-color: var(--dark);
    color: white;
    transform: translateY(-2px);
}

/* Section Hero */
.hero-section {
    background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
    padding: 6rem 0;
    position: relative;
    overflow: hidden;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-badge {
    background-color: var(--primary-lighter);
    color: var(--primary);
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.hero-badge i {
    margin-right: 0.5rem;
}

.hero-image {
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hero-image:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
}

/* Section Produits */
.products-section {
    padding: 6rem 0;
    background-color: white;
}

.product-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product-info {
    padding: 1.5rem;
}

.product-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--dark);
}

.product-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 1rem;
}

/* Section CTA */
.cta-section {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    padding: 6rem 0;
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.cta-content {
    position: relative;
    z-index: 2;
}

.cta-title {
    color: white;
    margin-bottom: 1.5rem;
}

.cta-text {
    font-size: 1.25rem;
    margin-bottom: 2.5rem;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    opacity: 0.9;
}

/* Responsive */
@media (max-width: 991.98px) {
    .hero-section {
        padding: 4rem 0;
        text-align: center;
    }
    
    .hero-content {
        margin-bottom: 3rem;
    }
    
    .hero-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .hero-buttons .btn:last-child {
        margin-bottom: 0;
    }
}

@media (max-width: 767.98px) {
    h1 {
        font-size: 2.25rem !important;
    }
    
    .lead {
        font-size: 1.125rem;
    }
    
    .hero-section, 
    .products-section,
    .cta-section {
        padding: 3rem 0;
    }
}
/* Reset et normalisation pour Chrome */
html {
    -webkit-text-size-adjust: 100%;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

body {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    line-height: 1.6;
    color: var(--dark);
    background-color: #ffffff;
}

/* Correction des problèmes de rendu sous Chrome */
*, *::before, *::after {
    box-sizing: border-box;
    -webkit-tap-highlight-color: transparent;
}

/* Amélioration du rendu des images */
img {
    max-width: 100%;
    height: auto;
    display: block;
}

/* Correction des boutons sous Chrome */
button, .btn {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border: none;
    background: none;
    cursor: pointer;
    padding: 0;
    margin: 0;
}

/* Amélioration de la typographie */
h1, h2, h3, h4, h5, h6 {
    margin-top: 0;
    margin-bottom: 1rem;
    line-height: 1.2;
    font-weight: 700;
    color: var(--dark);
}

/* Correction des liens */
a {
    color: var(--primary);
    text-decoration: none;
    transition: color 0.2s ease;
}

a:hover {
    color: var(--primary-dark);
    text-decoration: none;
}

/* Correction des champs de formulaire */
input, textarea, select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: var(--border-radius);
    padding: 0.5rem 1rem;
    border: 1px solid var(--light-gray);
    width: 100%;
    font-size: 1rem;
    line-height: 1.5;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

input:focus, textarea:focus, select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Correction des listes */
ul, ol {
    padding-left: 1.5rem;
    margin: 1rem 0;
}

/* Correction des tableaux */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

th, td {
    padding: 0.75rem 1rem;
    text-align: left;
    border-bottom: 1px solid var(--light-gray);
}

th {
    font-weight: 600;
    background-color: var(--light);
}

/* Correction des citations */
blockquote {
    margin: 1.5rem 0;
    padding: 1rem 1.5rem;
    border-left: 4px solid var(--primary);
    background-color: var(--light);
    font-style: italic;
}

/* Correction des codes */
code, pre {
    font-family: 'Fira Code', 'Courier New', monospace;
    background-color: var(--light);
    padding: 0.2rem 0.4rem;
    border-radius: var(--border-radius-sm);
    font-size: 0.9em;
}

pre {
    padding: 1rem;
    overflow-x: auto;
    margin: 1rem 0;
}

/* Correction des images responsives */
.img-fluid {
    max-width: 100%;
    height: auto;
}

/* Correction des vidéos responsives */
.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    height: 0;
    overflow: hidden;
}

.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

/* Correction des animations */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* Correction des problèmes de flexbox sous Chrome */
.flex-container {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}

/* Correction des problèmes de grille sous Chrome */
.grid-container {
    display: -ms-grid;
    display: grid;
}

/* Correction des transitions sous Chrome */
.transition-all {
    -webkit-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

/* Correction des transformations sous Chrome */
.transform {
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
}

/* Correction des ombres sous Chrome */
.shadow {
    -webkit-box-shadow: var(--shadow);
    box-shadow: var(--shadow);
}

/* Correction des bordures arrondies sous Chrome */
.rounded {
    -webkit-border-radius: var(--border-radius);
    border-radius: var(--border-radius);
}

/* Correction des dégradés sous Chrome */
.gradient-bg {
    background: -webkit-linear-gradient(135deg, var(--primary), var(--secondary));
    background: linear-gradient(135deg, var(--primary), var(--secondary));
}

/* Correction des animations de chargement */
@-webkit-keyframes spin {
    to {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@keyframes spin {
    to {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

.loading {
    -webkit-animation: spin 1s linear infinite;
    animation: spin 1s linear infinite;
}

/* Correction des problèmes de hauteur */
.min-h-screen {
    min-height: 100vh;
    min-height: -webkit-fill-available;
}

/* Correction des problèmes de position sticky sous Chrome */
.sticky {
    position: -webkit-sticky;
    position: sticky;
}

/* Correction des problèmes de débordement sous Chrome */
.overflow-hidden {
    -webkit-mask-image: -webkit-radial-gradient(white, black);
}

/* Correction des problèmes de sélection de texte sous Chrome */
::selection {
    background: var(--primary);
    color: white;
}

/* Correction des problèmes de placeholder sous Chrome */
::-webkit-input-placeholder {
    color: var(--gray);
    opacity: 1;
}

:-ms-input-placeholder {
    color: var(--gray);
}

::-ms-input-placeholder {
    color: var(--gray);
}

::placeholder {
    color: var(--gray);
}

/* Correction des problèmes de scrollbar sous Chrome */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--light);
}

::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
}

/* Correction des problèmes de print */
@media print {
    *,
    *::before,
    *::after {
        text-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
    }
    
    a:not(.btn) {
        text-decoration: underline;
    }
    
    img {
        page-break-inside: avoid;
    }
    
    h2, h3 {
        page-break-after: avoid;
    }
}
/* Reset et variables globales */
:root {
    /* Couleurs principales */
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #818cf8;
    --secondary: #8b5cf6;
    --accent: #f472b6;
    
    /* Nuances de gris */
    --light: #f8fafc;
    --light-gray: #e2e8f0;
    --gray: #94a3b8;
    --dark-gray: #475569;
    --dark: #1e293b;
    
    /* Couleurs de statut */
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    
    /* Espacements */
    --spacing-xs: 0.5rem;
    --spacing-sm: 1rem;
    --spacing-md: 1.5rem;
    --spacing-lg: 2rem;
    --spacing-xl: 3rem;
    
    /* Bordures */
    --border-radius-sm: 0.375rem;
    --border-radius: 0.5rem;
    --border-radius-lg: 0.75rem;
    --border-radius-xl: 1rem;
    
    /* Ombres */
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

/* Reset et styles de base */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: var(--dark);
    line-height: 1.6;
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}

/* Typographie */
h1, h2, h3, h4, h5, h6 {
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1rem;
    color: var(--dark);
}

h1 { font-size: 2.5rem; }
h2 { font-size: 2rem; }
h3 { font-size: 1.75rem; }
h4 { font-size: 1.5rem; }
h5 { font-size: 1.25rem; }
h6 { font-size: 1rem; }

p {
    margin-bottom: 1rem;
}

a {
    color: var(--primary);
    text-decoration: none;
    transition: color 0.2s ease;
}

a:hover {
    color: var(--primary-dark);
    text-decoration: none;
}

img {
    max-width: 100%;
    height: auto;
    display: block;
}

/* Boutons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.625rem 1.25rem;
    font-weight: 600;
    line-height: 1.5;
    text-align: center;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    user-select: none;
    border: 1px solid transparent;
    border-radius: var(--border-radius);
    transition: all 0.2s ease;
}

.btn-primary {
    background-color: var(--primary);
    color: white;
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow);
}

.btn-outline {
    background-color: transparent;
    border: 1px solid var(--primary);
    color: var(--primary);
}

.btn-outline:hover {
    background-color: rgba(99, 102, 241, 0.1);
    color: var(--primary-dark);
}

/* Conteneurs */
.container {
    width: 100%;
    padding-right: var(--spacing-md);
    padding-left: var(--spacing-md);
    margin-right: auto;
    margin-left: auto;
}

@media (min-width: 640px) {
    .container {
        max-width: 640px;
    }
}

@media (min-width: 768px) {
    .container {
        max-width: 768px;
    }
}

@media (min-width: 1024px) {
    .container {
        max-width: 1024px;
    }
}

@media (min-width: 1280px) {
    .container {
        max-width: 1280px;
    }
}

/* Sections */
section {
    padding: var(--spacing-xl) 0;
    position: relative;
}

/* Utilitaires */
.text-center { text-align: center; }
.text-primary { color: var(--primary); }
.bg-light { background-color: var(--light); }
.rounded { border-radius: var(--border-radius); }
.shadow { box-shadow: var(--shadow); }
.mb-0 { margin-bottom: 0 !important; }
.mt-0 { margin-top: 0 !important; }
.mb-4 { margin-bottom: 1rem !important; }
.mt-4 { margin-top: 1rem !important; }
.mb-6 { margin-bottom: 1.5rem !important; }
.mt-6 { margin-top: 1.5rem !important; }

/* Section Hero */
.hero-section {
    position: relative;
    min-height: 90vh;
    overflow: hidden;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    padding: 0;
}

.hero-slider {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}

.hero-slide {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    display: flex;
    align-items: center;
    min-height: 90vh;
    padding: var(--spacing-xl) 0;
}

.hero-slide::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
    text-align: center;
    color: white;
}

.hero-content h1 {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.1;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.hero-content p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-hero {
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 1rem;
    transition: all 0.3s ease;
    min-width: 200px;
}

.btn-hero-primary {
    background: white;
    color: var(--primary);
    border: 2px solid white;
}

.btn-hero-outline {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-hero-primary:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.btn-hero-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

/* Navigation du slider */
.hero-slider .swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 1;
    transition: all 0.3s ease;
}

.hero-slider .swiper-pagination-bullet-active {
    background: white;
    transform: scale(1.3);
}

.hero-slider .swiper-button-next,
.hero-slider .swiper-button-prev {
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    transition: all 0.3s ease;
}

.hero-slider .swiper-button-next:hover,
.hero-slider .swiper-button-prev:hover {
    background: rgba(255, 255, 255, 0.2);
}

.hero-slider .swiper-button-next::after,
.hero-slider .swiper-button-prev::after {
    font-size: 1.25rem;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .hero-content p {
        font-size: 1.1rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-hero {
        width: 100%;
        max-width: 300px;
    }
    
    .hero-slider .swiper-button-next,
    .hero-slider .swiper-button-prev {
        display: none;
    }
}
.hero-section {
    position: relative;
    min-height: 90vh;
    overflow: hidden;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.hero-slider {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}

.hero-slide {
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: center;
    min-height: 90vh;
}

.hero-slide::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    color: white;
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    text-align: center;
}

.hero-content h1 {
    font-size: 4rem;
    font-weight: 900;
    margin-bottom: 1.5rem;
    line-height: 1.1;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    animation: fadeInUp 1s ease-out;
}

.hero-content p {
    font-size: 1.5rem;
    margin-bottom: 2.5rem;
    opacity: 0.95;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    animation: fadeInUp 1s ease-out 0.4s both;
}

.btn-hero {
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    font-size: 1.1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: #fff;
    color: #6366f1;
    border: 2px solid #fff;
}

.btn-outline-light {
    background: transparent;
    color: #fff;
    border: 2px solid #fff;
}

.btn-hero:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.btn-primary:hover {
    background: #f0f0f0;
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .hero-content p {
        font-size: 1.2rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-hero {
        width: 100%;
        max-width: 250px;
    }
}

.testimonials-section {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fc 0%, #e9ecef 100%);
    padding: 6rem 0;
}

.testimonial-card {
    background: white;
    border-radius: 1.2rem;
    padding: 2.5rem;
    height: 100%;
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(99, 102, 241, 0.1);
}

.testimonial-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(99, 102, 241, 0.15);
}

.testimonial-text {
    color: #4b5563;
    line-height: 1.7;
    font-style: italic;
    position: relative;
    padding-left: 1.5rem;
}

.testimonial-text::before {
    content: '\201C';
    font-size: 4rem;
    color: rgba(99, 102, 241, 0.2);
    position: absolute;
    left: -1rem;
    top: -1.5rem;
    font-family: serif;
    line-height: 1;
}

.testimonial-rating {
    font-size: 1.2rem;
    letter-spacing: 2px;
}

.testimonial-author h6 {
    color: #1f2937;
    margin-bottom: 0.2rem;
}

.testimonial-author small {
    font-size: 0.85rem;
}

/* Navigation personnalisée */
.testimonial-swiper {
    padding: 1rem 0 3rem 0;
}

.swiper-button-next,
.swiper-button-prev {
    color: #6366f1;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    opacity: 0.8;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
    background: #6366f1;
    color: white;
    opacity: 1;
}

.swiper-button-next::after,
.swiper-button-prev::after {
    font-size: 1rem;
    font-weight: bold;
}

.swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: #d1d5db;
    opacity: 1;
    margin: 0 5px !important;
}

.swiper-pagination-bullet-active {
    background: #6366f1;
    transform: scale(1.3);
}

/* Responsive */
@media (max-width: 768px) {
    .testimonials-section {
        padding: 4rem 0;
    }
    
    .testimonial-card {
        padding: 1.8rem;
    }
    
    .testimonial-text {
        font-size: 1rem !important;
    }
    
    .swiper-button-next,
    .swiper-button-prev {
        display: none;
    }
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="hero-badge">
                    <i class="fas fa-sparkles"></i> Nouvelle Collection 2024
                </span>
                <h1 class="display-4 fw-bold mb-4">Mode élégante pour votre style unique</h1>
                <p class="lead mb-5">Découvrez nos pièces soigneusement sélectionnées, conçues pour allier confort et élégance. Livraison rapide et retours gratuits.</p>
                
                <div class="hero-buttons">
                    <a href="{{ route('catalogue.index') }}" class="btn btn-primary btn-lg me-3">
                        Découvrir la boutique
                    </a>
                    <a href="#produits" class="btn btn-outline-dark btn-lg">
                        Voir les produits
                    </a>
                </div>
            </div>
            
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                     alt="Collection Printemps-Été 2024" 
                     class="hero-image img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Produits en vedette -->
<section id="produits" class="products-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Nos produits phares</h2>
            <p class="lead text-muted">Découvrez nos meilleures ventes</p>
        </div>
        
        <div class="row g-4">
            @forelse($featured_products as $product)
            <div class="col-md-4">
                <div class="product-card">
                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                             alt="{{ $product->name }}" 
                             class="product-image">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-box-open fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <p class="text-muted mb-3">{{ Str::limit($product->description, 80) }}</p>
                        <p class="product-price">{{ format_price(convert_euro_to_fcfa($product->price)) }}</p>
                        <a href="{{ route('catalogue.fiche', $product) }}" class="btn btn-outline-dark w-100">
                            Voir le produit
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    Aucun produit n'est disponible pour le moment.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Prêt à trouver votre style ?</h2>
            <p class="cta-text">Découvrez notre collection complète et trouvez les pièces parfaites pour votre garde-robe.</p>
            <a href="{{ route('catalogue.index') }}" class="btn btn-light btn-lg">
                Parcourir la boutique
            </a>
        </div>
    </div>
</section>

<style>
/* ====================
   HERO SECTION STYLES
   ==================== */
.hero-section {
    position: relative;
    padding: 6rem 0;
    background-color: #ffffff;
    overflow: hidden;
}

/* Badge de collection */
.bg-light-primary {
    background-color: rgba(79, 70, 229, 0.1);
}

/* Typographie */
.hero-section h1 {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.2;
    color: #1f2937;
    margin-bottom: 1.5rem;
}

.hero-section .lead {
    font-size: 1.25rem;
    color: #4b5563;
    margin-bottom: 2.5rem;
}

/* Boutons */
.hero-section .btn {
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.hero-section .btn-primary {
    background-color: #4f46e5;
    border-color: #4f46e5;
}

.hero-section .btn-primary:hover {
    background-color: #4338ca;
    border-color: #4338ca;
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.hero-section .btn-outline-dark {
    border: 2px solid #1f2937;
    color: #1f2937;
}

.hero-section .btn-outline-dark:hover {
    background-color: #1f2937;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Groupe d'avatars */
.avatar-group {
    display: flex;
    margin-top: 2rem;
}

.avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    margin-left: -10px;
    transition: transform 0.3s ease;
}

.avatar:first-child {
    margin-left: 0;
}

.avatar:hover {
    transform: translateY(-5px);
    z-index: 2;
}

/* Badges d'information */
.position-absolute.bg-white {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}

.position-absolute.bg-white:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Icônes dans les badges */
.bg-light-primary {
    background-color: rgba(79, 70, 229, 0.1);
}

.bg-light-success {
    background-color: rgba(16, 185, 129, 0.1);
}

/* Image principale */
.hero-section img {
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hero-section img:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
}

/* ====================
   RESPONSIVE STYLES
   ==================== */
@media (max-width: 991.98px) {
    .hero-section {
        padding: 4rem 0;
        text-align: center;
    }
    
    .hero-section h1 {
        font-size: 2.5rem !important;
    }
    
    .hero-section .lead {
        font-size: 1.1rem;
    }
    
    .hero-section .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .avatar-group {
        justify-content: center;
    }
    
    .position-absolute.bg-white {
        transform: scale(0.9);
    }
}

@media (max-width: 767.98px) {
    .hero-section h1 {
        font-size: 2rem !important;
    }
    
    .hero-section .lead {
        font-size: 1rem;
    }
    
    .position-absolute.bg-white {
        transform: scale(0.8);
    }
}
</style>
</style>

<!-- VALEURS EN ZIG-ZAG GLASSMORPHISM -->
<section class="container py-5">
    <div class="section-title mb-5" data-aos="fade-up">
        <h2>Nos valeurs, votre expérience</h2>
        <p>Ce qui rend Ma Boutique unique et inoubliable.</p>
    </div>
    <div class="valeurs-zigzag">
        <div class="valeur-card" data-aos="fade-up" data-aos-delay="100">
            <div class="valeur-icon"><i class="fas fa-bolt"></i></div>
            <div class="valeur-title">Ultra rapide</div>
            <div class="valeur-text">Commandez, recevez, profitez. Livraison express et process instantané.</div>
        </div>
        <div class="valeur-card" data-aos="fade-up" data-aos-delay="200">
            <div class="valeur-icon"><i class="fas fa-heart"></i></div>
            <div class="valeur-title">Communauté</div>
            <div class="valeur-text">Des clients ambassadeurs, des avis authentiques, une vraie famille.</div>
        </div>
        <div class="valeur-card" data-aos="fade-up" data-aos-delay="300">
            <div class="valeur-icon"><i class="fas fa-magic"></i></div>
            <div class="valeur-title">Expérience magique</div>
            <div class="valeur-text">Interface intuitive, animations, surprises à chaque étape.</div>
        </div>
        <div class="valeur-card" data-aos="fade-up" data-aos-delay="400">
            <div class="valeur-icon"><i class="fas fa-lock"></i></div>
            <div class="valeur-title">Sécurité totale</div>
            <div class="valeur-text">Paiement, données, livraison : tout est protégé et transparent.</div>
        </div>
    </div>
</section>

@push('scripts')
<script>
// Attendre que tout le contenu soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Délai pour s'assurer que tout est bien chargé
    setTimeout(initializeSliders, 100);
});

function initializeSliders() {
    // Vérifier que Swiper est disponible
    if (typeof Swiper === 'undefined') {
        console.error('Swiper is not loaded');
        return;
    }

    // Initialize Hero Slider
    const heroSlider = document.querySelector('.hero-slider');
    if (heroSlider && !heroSlider.swiper) {
        new Swiper(heroSlider, {
            loop: true,
            effect: 'fade',
            speed: 1000,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: heroSlider.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: heroSlider.querySelector('.swiper-button-next'),
                prevEl: heroSlider.querySelector('.swiper-button-prev'),
            },
            on: {
                init: function() {
                    console.log('Hero Slider initialized');
                },
                error: function(e) {
                    console.error('Hero Slider error:', e);
                }
            }
        });
    }

    // Initialize Testimonial Swiper
    const testimonialSwiper = document.querySelector('.testimonial-swiper');
    if (testimonialSwiper && !testimonialSwiper.swiper) {
        new Swiper(testimonialSwiper, {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: testimonialSwiper.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: testimonialSwiper.parentElement.querySelector('.swiper-button-next'),
                prevEl: testimonialSwiper.parentElement.querySelector('.swiper-button-prev'),
            },
            breakpoints: {
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 }
            },
            on: {
                init: function() {
                    console.log('Testimonial Slider initialized');
                },
                error: function(e) {
                    console.error('Testimonial Slider error:', e);
                }
            }
        });
    }

    // Initialize AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false,
            startEvent: 'load',
            initClassName: 'aos-init',
            animatedClassName: 'aos-animate',
            useClassNames: true,
            disableMutationObserver: false,
            debounceDelay: 50,
            throttleDelay: 99,
            offset: 100,
            delay: 0,
            once: true,
            mirror: false
        });
        console.log('AOS initialized');
        
        // Initialisation du slider de témoignages
        if (typeof Swiper !== 'undefined') {
            const testimonialSwiper = new Swiper('.testimonial-swiper', {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 30,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    992: {
                        slidesPerView: 3,
                    }
                },
                on: {
                    init: function() {
                        this.update();
                        AOS.refresh();
                    }
                }
            });
            
            // Réinitialisation des sliders au redimensionnement
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    const allSliders = document.querySelectorAll('.swiper');
                    allSliders.forEach(function(slider) {
                        if (slider.swiper) {
                            slider.swiper.update();
                        }
                    });
                    AOS.refresh();
                }, 250);
            });
        } else {
            console.error('Swiper is not loaded');
        }
    } else {
        console.error('AOS is not loaded');
    }
    
    // Rafraîchir AOS après le chargement des images
    window.addEventListener('load', function() {
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    });
}
</script>
@endpush
@endsection
