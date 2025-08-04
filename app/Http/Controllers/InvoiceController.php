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
            $directory = 'invoices';
            $relativePath = $directory . '/' . $filename;
            $storagePath = 'public/' . $relativePath;
            $absolutePath = storage_path('app/' . $storagePath);
            
            // Créer le répertoire s'il n'existe pas
            if (!file_exists(dirname($absolutePath))) {
                mkdir(dirname($absolutePath), 0755, true);
            }
            
            // Sauvegarder le PDF dans le stockage
            file_put_contents($absolutePath, $pdf->output());
            
            // Vérifier que le fichier a bien été créé
            if (!file_exists($absolutePath)) {
                throw new \Exception('Le fichier PDF n\'a pas pu être créé à l\'emplacement : ' . $absolutePath);
            }
            
            // Mettre à jour le chemin du PDF dans la base de données
            $invoice->update(['pdf_path' => $relativePath]);
            
            // Journaliser la création du fichier
            \Log::info('Fichier PDF généré avec succès', [
                'order_id' => $order->id,
                'invoice_id' => $invoice->id,
                'path' => $absolutePath,
                'size' => filesize($absolutePath) . ' bytes'
            ]);

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
            // Journalisation pour le débogage
            \Log::info('Tentative de téléchargement de la facture', [
                'invoice_id' => $invoice->id,
                'order_id' => $invoice->order_id,
                'user_id' => auth()->id(),
                'is_admin' => auth()->user()->is_admin,
                'invoice_status' => $invoice->status,
                'pdf_path' => $invoice->pdf_path
            ]);

            // Vérifier que la facture est signée
            if ($invoice->status !== 'signed') {
                if (auth()->user()->is_admin) {
                    return redirect()->route('admin.orders.show', $invoice->order)
                        ->with('warning', 'Cette facture n\'a pas encore été signée.');
                }
                abort(403, 'Cette facture n\'est pas encore disponible. Elle est en attente de signature par l\'administrateur.');
            }

            // Vérifier que l'utilisateur a le droit de voir cette facture
            if (!auth()->user()->is_admin && $invoice->order->user_id !== auth()->id()) {
                abort(403, 'Accès non autorisé à cette facture.');
            }

            // Nettoyer le chemin du fichier
            $pdfPath = ltrim($invoice->pdf_path, '/');
            $storagePath = 'public/' . $pdfPath;
            $absolutePath = storage_path('app/' . $storagePath);
            
            // Journalisation du chemin du fichier
            \Log::info('Chemin du fichier de facture', [
                'pdf_path' => $pdfPath,
                'storage_path' => $storagePath,
                'absolute_path' => $absolutePath,
                'file_exists' => file_exists($absolutePath),
                'is_readable' => is_readable($absolutePath)
            ]);
            
            // Si le fichier n'existe pas ou n'est pas lisible, on essaie de le régénérer
            if (!file_exists($absolutePath) || !is_readable($absolutePath)) {
                $order = $invoice->order;
                \Log::info('Tentative de régénération du PDF pour la commande', [
                    'order_id' => $order->id,
                    'invoice_id' => $invoice->id,
                    'user_is_admin' => auth()->user()->is_admin
                ]);
                
                // Créer le PDF
                $pdf = PDF::loadView('invoices.pdf', compact('order'));
                $pdfContent = $pdf->output();
                
                // Créer le répertoire s'il n'existe pas
                $directory = dirname($absolutePath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Sauvegarder le fichier
                file_put_contents($absolutePath, $pdfContent);
                
                // Vérifier que le fichier a bien été créé
                if (!file_exists($absolutePath) || !is_readable($absolutePath)) {
                    throw new \Exception('Le fichier n\'a pas pu être généré ou n\'est pas accessible.');
                }
                
                // Mettre à jour le chemin dans la base de données
                $invoice->update(['pdf_path' => $pdfPath]);
                
                \Log::info('PDF régénéré avec succès', [
                    'path' => $absolutePath,
                    'size' => filesize($absolutePath) . ' bytes'
                ]);
            }

            // Marquer la notification comme lue si l'utilisateur est le propriétaire
            if ($invoice->order->user_id === auth()->id()) {
                $invoice->order->user->notifications()
                    ->where('order_id', $invoice->order_id)
                    ->where('type', 'invoice_signed')
                    ->update(['read_at' => now()]);
            }

            // Nom du fichier de téléchargement
            $filename = 'facture-' . $invoice->order_id . '-' . $invoice->signed_at->format('Y-m-d') . '.pdf';
            
            // Vérifier que le fichier existe et est lisible
            if (!file_exists($absolutePath) || !is_readable($absolutePath)) {
                throw new \Exception('Le fichier de facture est introuvable ou inaccessible.');
            }
            
            // Vérifier la taille du fichier
            $fileSize = filesize($absolutePath);
            if ($fileSize === 0) {
                throw new \Exception('Le fichier de facture est vide.');
            }
            
            // Journalisation avant le téléchargement
            \Log::info('Préparation du téléchargement', [
                'filename' => $filename,
                'path' => $absolutePath,
                'size' => $fileSize . ' bytes',
                'mime_type' => mime_content_type($absolutePath)
            ]);
            
            // En-têtes de réponse
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => $fileSize,
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Pragma' => 'public',
                'Expires' => '0'
            ];
            
            // Retourner la réponse de téléchargement
            return response()->file($absolutePath, $headers);

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