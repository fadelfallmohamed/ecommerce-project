<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Affiche la liste des messages de contact.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $messages = Contact::latest()->paginate(15);
            $unreadCount = Contact::unread()->count();
            
            return view('admin.contacts.index', [
                'messages' => $messages,
                'unreadCount' => $unreadCount
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des messages de contact: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'Une erreur est survenue lors de la récupération des messages.');
        }
    }

    /**
     * Affiche les détails d'un message de contact.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\View\View
     */
    public function show(Contact $contact)
    {
        try {
            // Marquer le message comme lu
            if ($contact->unread()) {
                $contact->markAsRead();
            }
            
            return view('admin.contacts.show', compact('contact'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage du message: ' . $e->getMessage());
            return redirect()->route('admin.contacts.index')
                ->with('error', 'Une erreur est survenue lors de l\'affichage du message.');
        }
    }

    /**
     * Marque un message comme non lu.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsUnread(Contact $contact)
    {
        try {
            $contact->markAsUnread();
            return back()->with('success', 'Le message a été marqué comme non lu.');
        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage du message comme non lu: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors du marquage du message comme non lu.');
        }
    }

    /**
     * Supprime un message de contact.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Contact $contact)
    {
        try {
            $contact->delete();
            return redirect()->route('admin.contacts.index')
                ->with('success', 'Le message a été supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du message: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression du message.');
        }
    }

    /**
     * Met à jour le statut de lecture d'un message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Contact $contact)
    {
        try {
            $action = $request->input('action');
            
            if ($action === 'mark_as_read') {
                $contact->markAsRead();
                $message = 'Le message a été marqué comme lu.';
            } elseif ($action === 'mark_as_unread') {
                $contact->markAsUnread();
                $message = 'Le message a été marqué comme non lu.';
            } else {
                return back()->with('error', 'Action non valide.');
            }
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du message: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du message.');
        }
    }
}
