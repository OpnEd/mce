<x-filament-panels::page>
    <head>
        <title>Sale to {{ $sale->customer?->name }}</title>
    </head>
    <div
        style="background-color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; max-width: 800px; margin: 0 auto;">
        <!-- Header -->
        <table style="width: 100%; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px;">
            <tr>
                <td>
                    <h1 style="font-size: 24px; font-weight: bold; color: #4a5568;">{{ __('Invoice')}}</h1>
                    <p style="color: #a0aec0;">Invoice #{{ $sale->factura?->code ?? $sale->id }}</p>
                    <p style="color: #a0aec0;">Date: {{ $sale->created_at->format('Y-m-d') }}</p>
                </td>
                <td style="text-align: right;">
                    <img src="/path-to-your-logo.png" alt="Company Logo" style="height: 64px;">
                </td>
            </tr>
        </table>

        <!-- Billing Information -->
        <table style="width: 100%; margin-top: 24px;">
            <tr>
                <td>
                    <h2 style="font-weight: bold; color: #4a5568;">{{ __('Billed To') }}</h2>
                    <p style="color: #718096;">{{ $sale->customer?->name }}</p>
                    <p style="color: #718096;">{{ $sale->customer?->address }}</p>
                    <p style="color: #718096;">Email: {{ $sale->customer?->email }}</p>
                </td>
                <td style="text-align: right;">
                    <h2 style="font-weight: bold; color: #4a5568;">{{ __('Company')}}</h2>
                    <p style="color: #718096;">{{ $settings['Team Name'] }}</p>
                    <p style="color: #718096;">{{ $settings['Address'] }}</p>
                    <p style="color: #718096;">Email: {{ $settings['E-mail'] }}</p>
                </td>
            </tr>
        </table>

        <!-- Invoice Items -->
        <table style="width: 100%; margin-top: 24px; border-collapse: collapse; border: 1px solid #e2e8f0;">
            <thead>
                <tr style="background-color: #f7fafc;">
                    <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: left; color: #4a5568;">Ítem</th>
                    <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: left; color: #4a5568;">Description</th>
                    <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; color: #4a5568;">Quantity</th>
                    <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; color: #4a5568;">Unit Price</th>
                    <th style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; color: #4a5568;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $index => $item)
                    <tr>
                        <td style="border: 1px solid #e2e8f0; padding: 8px; color: #718096;">
                            {{ $index + 1 }}
                        </td>
                        <td style="border: 1px solid #e2e8f0; padding: 8px; color: #718096;">
                            {{ $item->inventory?->product?->name }}
                        </td>
                        <td style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; color: #718096;">
                            {{ $item->quantity }}
                        </td>
                        <td style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; color: #718096;">
                            ${{ $item->sale_price }}
                        </td>
                        <td style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; color: #718096;">
                            ${{ $item->quantity * $item->sale_price }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f7fafc;">
                    <td colspan="4"
                        style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; font-weight: bold; color: #4a5568;">
                        Subtotal</td>
                    <td style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; color: #4a5568;">
                        ${{ $sale->total }}</td>
                </tr>
                <tr style="background-color: #f7fafc;">
                    <td colspan="4"
                        style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; font-weight: bold; color: #4a5568;">
                        IVA</td>
                    <td style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; color: #4a5568;">
                        ${{ $sale->iva ?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="4"
                        style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; font-weight: bold; color: #4a5568;">
                        Discount</td>
                    <td style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; color: #4a5568;">
                        ${{ $sale->discount ?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="4"
                        style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; font-weight: bold; color: #4a5568;">
                        Total</td>
                    <td style="border: 1px solid #e2e8f0; padding: 8px; text-align: right; color: #4a5568;">
                        ${{ $sale->total - $sale->discount + $sale->iva }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div style="margin-top: 24px; text-align: center; color: #a0aec0;">
            <p>{{ __('Thank you for your business!') }}</p>
            <p>{{ __('If you have any questions about this invoice, please contact us at :email', ['email' => $sale->team?->email]) }}</p>
        </div>
    </div>
</x-filament-panels::page>
