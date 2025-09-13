<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Struk Pesanan {{ $order->order_code }}</title>
    <style>
        /* Menggunakan font monospace agar terlihat seperti struk printer */
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            /* Lebar kertas struk umum (misal 80mm) */
            margin: 0 auto;
            padding: 10mm;
            /* Sedikit padding di tepi */
            color: #000;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 1.2em;
            /* Ukuran judul lebih besar */
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            font-size: 0.8em;
        }

        .info-block,
        .item-list,
        .summary {
            font-size: 0.9em;
            margin-bottom: 8px;
        }

        .info-line {
            display: flex;
            justify-content: space-between;
        }

        .info-line span:first-child {
            text-align: left;
        }

        .info-line span:last-child {
            text-align: right;
        }

        .item-list table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .item-list th,
        .item-list td {
            padding: 2px 0;
            vertical-align: top;
        }

        .item-list .qty-item {
            text-align: left;
            width: 70%;
        }

        .item-list .price {
            text-align: right;
            width: 30%;
        }

        .summary .total-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }

        .summary .total-row strong {
            font-size: 1.1em;
            /* Total lebih menonjol */
        }

        .summary .discount {
            color: #d9534f;
        }

        /* Warna merah untuk diskon */
        .summary .paid {
            padding-top: 5px;
            border-top: 1px dashed #000;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .footer p {
            margin: 5px 0;
            font-size: 0.8em;
        }

        /* Print-specific styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Nama Kafe Anda</h1>
        <p>Alamat Kafe Anda, Kota</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <div class="divider"></div>

    <div class="info-block">
        <div class="info-line"><span>Kasir</span><span>{{ auth()->user()->name ?? 'Kasir' }}</span></div>
        <div class="info-line"><span>No. Pesanan</span><span>{{ $order->order_code }}</span></div>
        <div class="info-line"><span>Tanggal</span><span>{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
        <div class="info-line"><span>Pelanggan</span><span>{{ $order->customer_name }}</span></div>
        <div class="info-line"><span>Meja</span><span>{{ $order->table->table_number }}</span></div>
    </div>

    <div class="divider"></div>

    <div class="item-list">
        <table>
            @foreach ($order->orderDetails as $detail)
                <tr>
                    <td class="qty-item">{{ $detail->quantity }}x {{ $detail->menu->name }}</td>
                    <td class="price">Rp{{ number_format($detail->price_at_order) }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="divider"></div>

    <div class="summary">
        <div class="total-row"><span>Subtotal</span><span>Rp{{ number_format($order->total_amount) }}</span></div>
        @if ($order->discount_amount > 0)
            <div class="total-row discount">
                <span>Diskon</span><span>-Rp{{ number_format($order->discount_amount) }}</span>
            </div>
        @endif
        <div class="divider"></div> {{-- Divider sebelum total akhir --}}

        <div class="total-row">
            <strong><span>TOTAL BAYAR</span></strong>
            <strong><span>Rp{{ number_format($order->final_amount) }}</span></strong>
        </div>

        <div class="paid total-row">
            <span>Uang Dibayar</span>
            {{-- Memanggil relasi 'payment' (singular) --}}
            <span>Rp{{ number_format($order->payment->amount_paid ?? 0) }}</span>
        </div>
        <div class="total-row">
            <strong><span>Kembalian</span></strong>
            <strong><span>Rp{{ number_format(($order->payment->amount_paid ?? 0) - $order->final_amount) }}</span></strong>
        </div>
    </div>

    <div class="divider"></div>

    <div class="footer">
        <p>Terima Kasih Atas Kunjungan Anda!</p>
        <p>Selamat Menikmati!</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
