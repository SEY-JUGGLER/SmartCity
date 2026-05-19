<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — SignalApp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">

<div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">SignalApp</h1>
        <p class="text-sm text-gray-500 mt-1">Connectez-vous à votre espace</p>
    </div>

    @if ($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input type="password" name="password" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded">
                Se souvenir de moi
            </label>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                Mot de passe oublié ?
            </a>
            @endif
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2.5 rounded-lg text-sm font-medium
                       hover:bg-blue-700 transition-colors">
            Se connecter
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">S'inscrire</a>
    </p>
</div>

</body>
</html>