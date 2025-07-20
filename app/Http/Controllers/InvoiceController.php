<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'pdf_path' => 'required|string',
            'invoice_date' => 'required|date',
        ]);
        Invoice::create($validated);
        return redirect()->route('invoices.index');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'pdf_path' => 'required|string',
            'invoice_date' => 'required|date',
        ]);
        $invoice->update($validated);
        return redirect()->route('invoices.index');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index');
    }

    /**
     * Génère une facture pour une commande (accès admin uniquement)
     *
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function generate(Order $order)
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user()->is_admin) {
                abort(403, 'Action non autorisée. Seuls les administrateurs peuvent générer des factures.');
            }

            // Vérifier si une facture existe déjà
            if ($order->invoice) {
                // Si la facture est déjà signée, on la télécharge directement
                if ($order->invoice->status === 'signed') {
                    return redirect()->back()
                        ->with('info', 'Cette commande a déjà une facture signée.');
                }
                
                // Si la facture est en attente, on la régénère
                $invoice = $order->invoice;
            } else {
                // Créer une nouvelle facture
                $invoice = $order->invoice()->create([
                    'invoice_date' => now(),
                    'status' => 'pending', // En attente de signature
                ]);
            }

            // Créer le PDF
            $pdf = PDF::loadView('invoices.pdf', compact('order'));
            
            // Générer un nom de fichier unique
            $filename = 'facture-' . $order->id . '-' . now()->format('Y-m-d') . '.pdf';
            $path = 'invoices/' . $filename;
            
            // Sauvegarder le PDF dans le stockage
            Storage::put('public/' . $path, $pdf->output());

            // Mettre à jour le chemin du PDF
            $invoice->update(['pdf_path' => $path]);

            // Rediriger avec un message de succès
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Facture générée avec succès. Vous pouvez maintenant la signer.');

        } catch (\Exception $e) {
            // Journaliser l'erreur
            \Log::error('Erreur lors de la génération de la facture : ' . $e->getMessage());
            
            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la génération de la facture. Veuillez réessayer.');
        }
    }

    public function sign(Invoice $invoice)
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user()->is_admin) {
                abort(403, 'Action non autorisée. Seuls les administrateurs peuvent signer les factures.');
            }

            // Vérifier que la facture n'est pas déjà signée
            if ($invoice->status === 'signed') {
                return redirect()->back()
                    ->with('warning', 'Cette facture a déjà été signée.');
            }

            // Mettre à jour le statut et ajouter la signature
            $invoice->update([
                'status' => 'signed',
                'signed_by' => auth()->id(),
                'signed_at' => now(),
            ]);

            // Envoyer une notification à l'utilisateur
            $invoice->order->user->notifications()->create([
                'order_id' => $invoice->order_id,
                'type' => 'invoice_signed',
                'message' => 'Votre facture #' . $invoice->id . ' a été signée et est prête à être téléchargée.',
                'read_at' => null,
            ]);

            // Si l'API de notification est disponible, on peut aussi envoyer une notification push
            if (config('services.push_notification.enabled')) {
                // Code pour envoyer une notification push
            }

            return redirect()->back()
                ->with('success', 'Facture signée avec succès. Le client a été notifié.');

        } catch (\Exception $e) {
            // Journaliser l'erreur
            \Log::error('Erreur lors de la signature de la facture : ' . $e->getMessage());
            
            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la signature de la facture. Veuillez réessayer.');
        }
    }

    /**
     * Télécharge une facture (accès admin ou propriétaire de la commande)
     *
     * @param Invoice $invoice
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function download(Invoice $invoice)
    {
        try {
            // Vérifier que la facture est signée
            if ($invoice->status !== 'signed') {
                // Si l'utilisateur est admin, on le redirige vers la page de la commande
                if (auth()->user()->is_admin) {
                    return redirect()->route('admin.orders.show', $invoice->order)
                        ->with('warning', 'Cette facture n\'a pas encore été signée.');
                }
                
                // Sinon, on renvoie une erreur 403
                abort(403, 'Cette facture n\'est pas encore disponible. Elle est en attente de signature par l\'administrateur.');
            }

            // Vérifier que l'utilisateur a le droit de voir cette facture
            if (!auth()->user()->is_admin && $invoice->order->user_id !== auth()->id()) {
                abort(403, 'Accès non autorisé à cette facture.');
            }

            $path = Storage::path('public/' . $invoice->pdf_path);
            
            if (!file_exists($path)) {
                // Si le fichier n'existe pas, on essaie de le régénérer (admin uniquement)
                if (auth()->user()->is_admin) {
                    $order = $invoice->order;
                    $pdf = PDF::loadView('invoices.pdf', compact('order'));
                    Storage::put('public/' . $invoice->pdf_path, $pdf->output());
                    
                    if (!file_exists($path)) {
                        throw new \Exception('Impossible de régénérer la facture.');
                    }
                } else {
                    // Pour les utilisateurs normaux, on renvoie une erreur
                    abort(404, 'Le fichier de facture est introuvable. Veuillez contacter le support.');
                }
            }

            // Marquer la notification comme lue si l'utilisateur est le propriétaire
            if ($invoice->order->user_id === auth()->id()) {
                $invoice->order->user->notifications()
                    ->where('order_id', $invoice->order_id)
                    ->where('type', 'invoice_signed')
                    ->update(['read_at' => now()]);
            }

            // Télécharger le fichier avec un nom personnalisé
            $filename = 'facture-' . $invoice->order_id . '-' . $invoice->signed_at->format('Y-m-d') . '.pdf';
            return response()->download($path, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);

        } catch (\Exception $e) {
            // Journaliser l'erreur
            \Log::error('Erreur lors du téléchargement de la facture : ' . $e->getMessage());
            
            // Rediriger avec un message d'erreur
            if (auth()->user()->is_admin) {
                return redirect()->back()
                    ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
            }
            
            return redirect()->route('orders.index')
                ->with('error', 'Impossible de télécharger la facture. Veuillez contacter le support.');
        }
    }
}