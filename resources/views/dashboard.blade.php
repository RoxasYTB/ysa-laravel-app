<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-10" style="filter: blur(10px);background: #000000AA;">
        <!-- Le contenu peut être ajouté ici -->
    </div>

    <div class="py-12">
    <!-- Bandeau cookies -->
        <div class="fixed bottom-0 left-0 w-full bg-gray-900/50 flex justify-center items-center">
            <div id="cookie-banner" class="max-w-[70rem] w-full mx-auto bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100 text-center mb-2 flex justify-center items-center" style="display: none;width: 50%;">
                <div class="flex flex-col items-center justify-center">
                    <div class="flex flex-col items-center mb-4">
                        <svg class="w-8 h-8 mb-3 text-amber-500" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="250" cy="250" r="240" fill="#C87F3C"/>
                            <circle cx="250" cy="250" r="220" fill="#E6A756"/>
                            <!-- Pépites aléatoires -->
                            <circle cx="150" cy="130" r="20" fill="#8B4513"/>
                            <circle cx="320" cy="180" r="25" fill="#8B4513"/>
                            <circle cx="200" cy="300" r="22" fill="#8B4513"/>
                            <circle cx="380" cy="280" r="18" fill="#8B4513"/>
                            <circle cx="120" cy="250" r="15" fill="#8B4513"/>
                            <circle cx="280" cy="120" r="17" fill="#8B4513"/>
                            <circle cx="350" cy="350" r="20" fill="#8B4513"/>
                            <circle cx="180" cy="220" r="16" fill="#8B4513"/>
                            <circle cx="250" cy="400" r="19" fill="#8B4513"/>
                            <circle cx="400" cy="200" r="21" fill="#8B4513"/>
                            <circle cx="100" cy="350" r="23" fill="#8B4513"/>
                            <circle cx="300" cy="250" r="18" fill="#8B4513"/>
                        </svg>
                        <p class="text-center">Nous utilisons des cookies pour améliorer votre expérience. En continuant à naviguer, vous acceptez notre utilisation des cookies.</p>
                    </div>
                    <div class="flex gap-4 justify-center">
                        <button id="accept-cookies" class="bg-gray-500 hover:bg-amber-600 text-white px-4 py-2 rounded transition duration-200">
                            Accepter
                        </button>
                        <button id="reject-cookies" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition duration-200">
                            Refuser
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cookieBanner = document.getElementById('cookie-banner');
            const acceptButton = document.getElementById('accept-cookies');
            const rejectButton = document.getElementById('reject-cookies');

            if (!localStorage.getItem('cookiesAccepted')) {
                cookieBanner.style.display = 'flex';
                document.querySelector('.fixed').style.display = 'flex';
            } else {
                document.querySelector('.fixed').style.display = 'none';
            }

            acceptButton.addEventListener('click', function() {
                localStorage.setItem('cookiesAccepted', 'true');
                cookieBanner.style.display = 'none';
                document.querySelector('.fixed').style.display = 'none';
            });

            rejectButton.addEventListener('click', function() {
                localStorage.setItem('cookiesAccepted', 'false');
                cookieBanner.style.display = 'none';
                document.querySelector('.fixed').style.display = 'none';
            });
        });
    </script>
</x-app-layout>
