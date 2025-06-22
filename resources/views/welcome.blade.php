<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bridge Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/ui@latest/dist/tailwind-ui.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md text-center">
            <h1 class="text-3xl font-bold mb-4">🔗 Bridge API</h1>
            <p class="text-gray-700 mb-6">Nova verzija bridge aplikacije je uspešno pokrenuta.</p>

            <div class="space-y-2">
                <div>
                    <span class="font-semibold">Verzija:</span> <span class="text-blue-600">v2.0</span>
                </div>
                <div>
                    <span class="font-semibold">Status veze sa ERP:</span>
                    <span class="text-green-600">Povezano</span>
                </div>
                <div>
                    <span class="font-semibold">Poslednja sinhronizacija:</span>
                    <span class="text-gray-700">{{ now()->format('d.m.Y H:i') }}</span>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('status') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Pogledaj detaljan status
                </a>
            </div>
        </div>

        <footer class="text-xs text-gray-500 mt-6">
            &copy; {{ date('Y') }} BridgeApp.rs · Laravel 12
        </footer>
    </div>
</body>
</html>
