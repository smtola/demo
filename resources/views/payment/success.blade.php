<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POS</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&family=Noto+Sans+Khmer:wght@100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', 'Noto Sans Khmer', sans-serif;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem;
        }
        
        /* Enhanced glass morphism effects */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        /* Light text colors for better readability */
        .text-gray-800 {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .text-gray-700 {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .text-gray-600 {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .text-gray-500 {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        
        .text-gray-400 {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        
        /* Smooth animations */
        * {
            transition: all 0.2s ease;
        }
        
        /* Custom scrollbar for webkit browsers */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.5);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.7);
        }
        
        /* Focus styles */
        input:focus, button:focus {
            outline: none;
        }
        
        /* Loading animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Enhanced input and button styling */
        input, button {
            border-radius: 16px !important;
        }
        
        .rounded-2xl {
            border-radius: 20px !important;
        }
        
        .rounded-xl {
            border-radius: 16px !important;
        }
        
        .rounded-lg {
            border-radius: 12px !important;
        }
        
        /* Better contrast for inputs */
        input {
            background: rgba(255, 255, 255, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        input::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        
        input:focus {
            background: rgba(255, 255, 255, 0.3) !important;
            border-color: rgba(59, 130, 246, 0.8) !important;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="app-name" content="Book SMS" />
    <meta name="locale" content="{{ app()->getLocale() }}" />
    <link rel="icon" href="/favicon.ico">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#0ea5e9">
</head>
<body class="text-gray-900">
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded shadow">
        @if(isset($sale))
            <h1 class="text-xl font-bold mb-4">Payment Successful ✅</h1>
            <p><strong>Reference:</strong> {{ $sale->reference }}</p>
            <p><strong>Customer:</strong> {{ $sale->customer_info }}</p>
            <p><strong>Total:</strong> ${{ number_format($sale->total_amount, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($sale->status) }}</p>
        @else
            <h1 class="text-xl font-bold mb-4">No Sale Found ⚠️</h1>
            <p>{{ $message ?? 'Payment details not available.' }}</p>
        @endif
    </div>
</body>
</html>