<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Facture #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page { margin: 15px; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .invoice-number {
            font-size: 16px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .invoice-date {
            font-size: 14px;
            color: #6c757d;
        }
        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }
        .info-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            font-size: 14px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            color: #6c757d;
            border-bottom: 2px solid #dee2e6;
        }
        .table td {
            vertical-align: middle;
        }
        .total-row {
            font-weight: 600;
            background-color: #f8f9fa;
        }
        .grand-total {
            font-size: 16px;
            background-color: #f1f8ff !important;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #333;
            margin: 10px 0;
        }
        .terms {
            margin-top: 30px;
            font-size: 11px;
            color: #6c757d;
            line-height: 1.5;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="invoice-container">
            <div class="header mb-4">
                <div class="invoice-number">FACTURE N°{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                <h1 class="mt-2 mb-2">COMMANDE N°{{ $order->id }}</h1>
                <div class="invoice-date">
                    <i class="far fa-calendar-alt me-1"></i> Date d'émission: {{ now()->format('d/m/Y') }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-title">
                            <i class="fas fa-user-circle me-2"></i>Client
                        </div>
                        <p class="mb-1">
                            <strong>{{ $order->user->name }}</strong>
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                            {{ $order->address->address_line1 }}
                        </p>
                        @if($order->address->address_line2)
                            <p class="mb-1 ms-4">{{ $order->address->address_line2 }}</p>
                        @endif
                        <p class="mb-1">
                            {{ $order->address->postal_code }} {{ $order->address->city }}
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-flag me-2 text-muted"></i>
                            {{ $order->address->country }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-phone me-2 text-muted"></i>
                            {{ $order->address->phone }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-title">
                            <i class="fas fa-store me-2"></i>Émetteur
                        </div>
                        <p class="mb-1">
                            <strong>Ma Boutique</strong>
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                            123 Rue du Commerce
                        </p>
                        <p class="mb-1 ms-4">75001 Paris, France</p>
                        <p class="mb-1">
                            <i class="fas fa-phone me-2 text-muted"></i>
                            +33 1 23 45 67 89
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-envelope me-2 text-muted"></i>
                            contact@maboutique.fr
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-id-card me-2 text-muted"></i>
                            SIRET: 123 456 789 00000
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-file-invoice me-2 text-muted"></i>
                            TVA: FR12345678901
                        </p>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Désignation</th>
                            <th class="text-end">Prix unitaire</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-end">Total HT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item->product->name }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-hashtag me-1"></i>PROD-{{ str_pad($item->product_id, 4, '0', STR_PAD_LEFT) }}
                                </small>
                            </td>
                            <td class="text-end">{{ number_format($item->price, 2, ',', ' ') }} €</td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill">{{ $item->quantity }}</span>
                            </td>
                            <td class="text-end fw-semibold">{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr class="total-row">
                            <td colspan="3" class="text-end">Total HT :</td>
                            <td class="text-end">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="3" class="text-end">TVA (20,00%) :</td>
                            <td class="text-end">{{ number_format($order->total * 0.2, 2, ',', ' ') }} €</td>
                        </tr>
                        <tr class="grand-total">
                            <td colspan="3" class="text-end fw-bold">TOTAL TTC :</td>
                            <td class="text-end fw-bold">{{ number_format($order->total * 1.2, 2, ',', ' ') }} €</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end text-muted small pt-3">
                                <i class="fas fa-file-invoice me-1"></i>
                                Montant en lettres : 
                                <span class="fst-italic">
                                    {{ \App\Helpers\NumberToWordsHelper::convert(round($order->total * 1.2, 2)) }} euros
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <div class="info-card">
                        <div class="info-title">
                            <i class="fas fa-credit-card me-2"></i>Conditions de règlement
                        </div>
                        <p class="mb-1">
                            <i class="fas {{ $order->payment_method === 'a_la_livraison' ? 'fa-truck' : 'fa-credit-card' }} me-2"></i>
                            {{ $order->payment_method === 'a_la_livraison' ? 'Paiement à la livraison' : 'Paiement en ligne effectué' }}
                        </p>
                        @if($order->payment_method === 'en_ligne')
                            <p class="mb-0">
                                <i class="far fa-calendar-check me-2"></i>
                                Date de paiement : {{ $order->created_at->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="terms">
                        <p class="mb-2">
                            <i class="fas fa-info-circle me-1"></i>
                            En cas de retard de paiement, seront exigibles, conformément à l'article L. 441-6 du code de commerce, une indemnité calculée sur la base de trois fois le taux d'intérêt légal en vigueur ainsi qu'une indemnité forfaitaire pour frais de recouvrement de 40 euros.
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-building me-1"></i>
                            Ma Boutique - SAS au capital de 50 000 € - RCS Paris 123 456 789 - N° TVA intracommunautaire FR12345678901
                        </p>
                    </div>
                </div>
            </div>

            <div class="signature mt-5">
                <div class="signature-line"></div>
                <div class="text-muted small mt-2">Signature et cachet</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
