<x-app-layout>
    <!--Bloc principal-->
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <!--Creer des idea-->
        <form method="POST" action="{{ route('ideas.store') }}">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Titre :</h2>
            <input name="title" placeholder="{{ __('Donnez le titre de votre idée !') }}" class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('message') }}</input>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
            <br>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Application :</h2>
            <input name="application" placeholder="{{ __('À quelle application votre idée s\'applique t-elle ?') }}" class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('message') }}</input>

            <x-input-error :messages="$errors->get('application')" class="mt-2" />
            <br>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Description :</h2>
            <textarea name="message" placeholder="{{ __('Donnez vos idées !') }}" class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />

            @if(session('error'))

            <div style="color:rgb(248 113 113);" class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
                {{ session('error') }}
            </div>
            @endif

            @if(session('max_idea'))

        <div style="color:rgb(248 113 113);" class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">
            {{ session('max_idea') }}
        </div>
@endif


            <br>
            <x-primary-button class="mt-4">{{ __('Poster') }}</x-primary-button>

        </form>
        <!--Visualiser les idea du user-->
        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @foreach ($ideas as $idea)
            <div class="p-6 flex space-x-2">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg> -->

                <svg height="64px" width="64px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 283.994 283.994" xml:space="preserve" fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <g>
                            <g>
                                <g>
                                    <g id="XMLID_19_">
                                        <g>
                                            <g>
                                                <g>
                                                    <path style="fill:#FEDE94;" d="M122.086,132.667c3.136,3.199,8.236,3.199,11.372,0c3.168-3.168,3.168-8.331,0-11.499 l-2.217-2.281h24.772l-18.215,18.437c-1.489,1.521-2.344,3.611-2.344,5.765v54.74h-27.813V169.35 c0-2.914-1.552-5.607-4.055-7.064c-23.949-13.938-38.806-39.914-38.806-67.823c0-43.114,34.624-78.181,77.199-78.181 s77.231,35.068,77.231,78.181c0,28.732-15.522,55.088-40.484,68.805c-2.598,1.394-4.213,4.15-4.213,7.159v27.401h-22.967 v-51.382l29.587-29.936c2.281-2.344,2.978-5.829,1.742-8.87s-4.181-5.037-7.444-5.037h-63.609 c-3.263,0-6.209,1.996-7.444,5.037s-0.57,6.526,1.742,8.87L122.086,132.667z"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path style="fill:#2D213F;" d="M64.78,94.464c0,27.908,14.857,53.884,38.806,67.823c2.503,1.457,4.055,4.15,4.055,7.064 v28.478h27.813v-54.74c0-2.154,0.855-4.245,2.344-5.765l18.215-18.437h-24.772l2.217,2.281c3.168,3.168,3.168,8.331,0,11.499 c-3.136,3.199-8.236,3.199-11.372,0l-15.966-16.156c-2.312-2.344-2.978-5.829-1.742-8.87 c1.235-3.041,4.181-5.037,7.444-5.037h63.609c3.263,0,6.209,1.996,7.444,5.037s0.539,6.526-1.742,8.87l-29.587,29.936v51.382 h22.967v-27.401c0-3.009,1.616-5.765,4.213-7.159c24.962-13.717,40.484-40.073,40.484-68.805 c0-43.114-34.656-78.181-77.231-78.181S64.78,51.35,64.78,94.464z M235.303,94.464c0,33.072-17.011,63.546-44.698,80.652 v30.886c0,0.539-0.063,1.077-0.158,1.584c2.914,3.358,4.752,7.729,4.752,12.544v0.634c0,4.055-1.267,7.793-3.421,10.897 c2.154,3.073,3.421,6.811,3.421,10.866v0.634c0,10.517-8.426,19.038-18.785,19.038h-8.553v0.697 c0,11.626-9.345,21.098-20.844,21.098h-10.675c-11.499,0-20.844-9.472-20.844-21.098v-0.697h-10.707 c-10.359,0-18.785-8.521-18.785-19.038v-0.634c0-4.055,1.299-7.793,3.421-10.866c-2.122-3.104-3.421-6.842-3.421-10.897 v-0.634c0-5.29,2.154-10.074,5.607-13.526c-0.032-0.19-0.063-0.412-0.063-0.602v-32.09 c-26.546-17.296-42.86-47.327-42.86-79.448C48.688,42.385,90.535,0,141.98,0C193.456,0,235.303,42.385,235.303,94.464z M179.106,243.16v-0.634c0-1.489-1.204-2.724-2.693-2.724h-71.624c-1.489,0-2.693,1.235-2.693,2.724v0.634 c0,1.521,1.204,2.756,2.693,2.756h71.624C177.904,245.916,179.106,244.681,179.106,243.16z M179.106,220.764v-0.634 c0-1.489-1.204-2.724-2.693-2.724h-71.624c-1.489,0-2.693,1.235-2.693,2.724v0.634c0,1.521,1.204,2.756,2.693,2.756h71.624 C177.904,223.52,179.106,222.284,179.106,220.764z M151.768,262.896v-0.697h-20.179v0.697c0,2.629,2.154,4.783,4.752,4.783 h10.675C149.646,267.679,151.768,265.525,151.768,262.896z"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path style="fill:#C5CBCF;" d="M176.414,245.916H104.79c-1.489,0-2.693-1.235-2.693-2.756v-0.634 c0-1.489,1.204-2.724,2.693-2.724h71.624c1.489,0,2.693,1.235,2.693,2.724v0.634 C179.106,244.681,177.904,245.916,176.414,245.916z"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path style="fill:#EAEEEF;" d="M176.414,223.52H104.79c-1.489,0-2.693-1.235-2.693-2.756v-0.634 c0-1.489,1.204-2.724,2.693-2.724h71.624c1.489,0,2.693,1.235,2.693,2.724v0.634 C179.106,222.284,177.904,223.52,176.414,223.52z"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path style="fill:#C5CBCF;" d="M151.768,262.199v0.697c0,2.629-2.122,4.783-4.752,4.783h-10.675 c-2.598,0-4.752-2.154-4.752-4.783v-0.697H151.768z"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <g>
                                            <path style="fill:#F2CE75;" d="M103.586,162.286c2.503,1.457,4.055,4.15,4.055,7.064v28.478h27.813v-54.74 c0-2.154,0.855-4.245,2.344-5.765l1.438-1.454c-28.497-0.653-52.709-8.499-70.458-16.71 C74.702,137.086,86.939,152.599,103.586,162.286z"></path>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path style="fill:#F2CE75;" d="M163.156,134.701l-11.61,11.746v51.382h22.967v-27.401c0-3.009,1.616-5.765,4.213-7.159 c17.29-9.5,30.037-25.067,36.249-43.285C196.874,128.141,179.49,132.718,163.156,134.701z"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
                <div class="flex-1">
                    <div class="flex justify-between">
                        <div>
                            <span class="text-gray-800">{{ $idea->user->name }}</span>
                            <small class="ml-2 text-sm text-gray-600">{{ $idea->created_at->format('j M Y, g:i a') }} - {{ $idea->application }} > {{ $idea->title }}</small>
                            <!--Affichage de l'information indiquant qu'un idea a été modifié-->
                            @unless ($idea->created_at->eq($idea->updated_at))
                            <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                            @endunless
                        </div>
                        <!--Affichage du lien permettant d'éditer un idea si il a été créé par l'utilisateur-->
                        @if ($idea->user->is(auth()->user()))
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('ideas.edit', $idea)">
                                    {{ __('Edit') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('ideas.destroy', $idea) }}">
                                    @csrf
                                    @method('delete')
                                    <x-dropdown-link :href="route('ideas.destroy', $idea)" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                        @endif
                    </div>
                    <p class="mt-4 text-lg text-gray-900">{{ $idea->message }}</p>
                    
                </div>
            </div>
            <div class="p-6 flex space-x-2">
                        <!-- Affichage de l'idée -->

                        <div class="flex-1">
        
                            @foreach ($idea->comments as $comment)
                            <div class="mt-2 text-sm text-gray-700 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg> 
                        <small class="ml-2 text-sm text-gray-600">{{ $comment->created_at->format('j M Y, g:i a') }}  </small>
                        @unless ($comment->created_at->eq($comment->updated_at))
                            <small class="text-sm text-gray-600">  ({{ __('edited') }}) </small>
                            @endunless
                              <span class="font-bold">> {{ $comment->user->name }}</span>: {{ $comment->comment }}
                        @if ($comment->user->is(auth()->user()))
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('comments.edit', $comment)">
                                    {{ __('Edit') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                    @csrf
                                    @method('delete')
                                    <x-dropdown-link :href="route('comments.destroy', $comment)" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                        @endif
                            </div>
                            @endforeach

                            <!-- Formulaire pour ajouter un nouveau commentaire -->
                            <form method="POST" action="{{ route('comments.store') }}">
                                @csrf
                                <input hidden name="idea_id" value="{{$idea->id}}">
                                <input hidden name="user_id" value="{{$idea->id}}">
                                <textarea name="comment" placeholder="Laisser un commentaire..." class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                                <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                                <x-primary-button class="mt-2">{{ __('Commenter') }}</x-primary-button>
                            </form>
                        </div>
                    </div>
            @endforeach
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
            }

            acceptButton.addEventListener('click', function() {
                localStorage.setItem('cookiesAccepted', 'true');
                cookieBanner.style.display = 'none';
            });

            rejectButton.addEventListener('click', function() {
                localStorage.setItem('cookiesAccepted', 'false');
                cookieBanner.style.display = 'none';
            });
        });
    </script>

</x-app-layout>