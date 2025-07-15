@extends('layouts.app')

@section('content')
    <div style="max-width:600px;margin:60px auto 0 auto;padding:40px 24px;background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(30,64,175,0.07);text-align:center;">
        <h2 style="font-size:2.2rem;font-weight:bold;margin-bottom:18px;color:#2563eb;">Bienvenue sur votre tableau de bord</h2>
        <p style="font-size:1.2rem;">Bonjour <span style="color:#fde047;">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span> !<br>Gérez vos informations, commandes et bien plus ici.</p>
    </div>
@endsection 