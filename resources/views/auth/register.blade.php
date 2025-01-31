<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nom -->
        <div>
            <x-input-label for="name" :value="__('Nom')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Adresse Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mot de passe -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmer le mot de passe -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- CGU et Politique de confidentialité -->
        <div class="mt-4">
            <label class="inline-flex items-center">
                <input type="checkbox" id="terms" name="terms" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" required>
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                    J'ai lu et j'accepte les 
                    <a href="/terms" class="text-white underline hover:text-gray-100">Conditions Générales d'Utilisation</a>
                    et la Politique de Confidentialité.
                </span>
            </label>
            <div id="terms-error" class="hidden text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                Veuillez accepter les conditions générales d'utilisation pour continuer
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Déjà inscrit?') }}
            </a>

            <x-primary-button class="ms-4" id="register-button" disabled>
                {{ __('S\'inscrire') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        const termsCheckbox = document.getElementById('terms');
        const registerButton = document.getElementById('register-button');
        const termsError = document.getElementById('terms-error');

        termsCheckbox.addEventListener('change', function() {
            registerButton.disabled = !this.checked;
            termsError.classList.toggle('hidden', this.checked);
        });

        // Afficher le message d'erreur au chargement si la case n'est pas cochée
        if (!termsCheckbox.checked) {
            termsError.classList.remove('hidden');
        }
    </script>
</x-guest-layout>
