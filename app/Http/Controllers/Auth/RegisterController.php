<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Exception;

class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegistrationForm()
    {
        return view('auth.login');
    }

    /**
     * Traite la demande d'inscription
     */
    public function register(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'required' => 'Le champ :attribute est obligatoire.',
                'email' => 'L\'adresse email doit être valide.',
                'unique' => 'Cette adresse email est déjà utilisée.',
                'min' => 'Le mot de passe doit contenir au moins :min caractères.',
                'confirmed' => 'La confirmation du mot de passe ne correspond pas.'
            ]);

            // Création de l'utilisateur
            $user = User::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'name' => $validated['nom'] . ' ' . $validated['prenom'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            if (!$user) {
                throw new Exception('Erreur lors de la création du compte.');
            }

            // Connexion automatique
            Auth::login($user);

            // Redirection avec message de succès
            return redirect()
                ->route('catalogue.index')
                ->with('success', 'Inscription réussie ! Bienvenue sur notre plateforme.');

        } catch (ValidationException $e) {
            // En cas d'erreur de validation
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (Exception $e) {
            // Pour les autres types d'erreurs
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage())
                ->withInput();
        }
    }
}