<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Logs système') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Filtres -->
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
          <form method="GET" action="{{ route('logs.index') }}"
            class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
              <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de
                début</label>
              <input type="date" name="start_date" id="start_date" value="{{ $start_date }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex-1">
              <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de
                fin</label>
              <input type="date" name="end_date" id="end_date" value="{{ $end_date }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex-1">
              <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Niveau</label>
              <select name="level" id="level"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Tous les niveaux</option>
                <option value="info" {{ $level === 'info' ? 'selected' : '' }}>Information</option>
                <option value="warning" {{ $level === 'warning' ? 'selected' : '' }}>Avertissement
                </option>
                <option value="error" {{ $level === 'error' ? 'selected' : '' }}>Erreur</option>
              </select>
            </div>
            <div>
              <button type="submit"
                class="w-full md:w-auto px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Filtrer
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Liste des logs -->
      <div class="space-y-6">
        @foreach($logs as $log)
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg
                    @if($log->level === 'info') border-l-4 border-blue-500
           @elseif($log->level === 'warning') border-l-4 border-yellow-500
       @elseif($log->level === 'error') border-l-4 border-red-500
  @endif">
          <div class="p-4 sm:p-6">
          <div class="flex items-start">
            <!-- Icône -->
            <div class="flex-shrink-0">
            @if($log->level === 'info')
        <svg class="h-6 w-6" fill="none" stroke="#8BB9FF" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      @elseif($log->level === 'warning')
    <svg class="h-6 w-6" fill="none" stroke="#FFD699" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
    </svg>
  @elseif($log->level === 'error')
  <svg class="h-6 w-6" fill="none" stroke="#FFB3B3" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>
@endif
            </div>

            <!-- Contenu -->
            <div class="ml-4 flex-1">
            @if(str_contains($log->message, "Échec de connexion"))
          <div class="space-y-2">
            <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
            <svg class="h-5 w-5" fill="none" stroke="#FFD699" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="flex items-center gap-2">
            <span class="text-sm text-gray-900 dark:text-gray-100">{{ $log->message }}</span>
            </div>
            </div>
            <span class="text-xs text-gray-500 dark:text-gray-400">
            {{ $log->created_at->format('d/m/Y H:i:s') }}
            </span>
            </div>
            @if($log->context)
          @php
      $context = json_decode($log->context, true);
     @endphp
          <div
          class="ml-8 grid grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
          <div class="flex items-center gap-2">
          <span class="font-medium">Guard :</span>
          <span class="text-gray-900 dark:text-gray-100">{{ $context['guard'] }}</span>
          </div>
          <div class="flex items-center gap-2">
          <span class="font-medium">IP :</span>
          <span class="text-gray-900 dark:text-gray-100">{{ $context['ip'] }}</span>
          </div>
          <div class="flex items-center gap-2">
          <span class="font-medium">Utilisateur existe :</span>
          <span
          class="text-gray-900 dark:text-gray-100">{{ $context['user_exists'] ? 'Oui' : 'Non' }}</span>
          </div>
          <div class="col-span-2 flex items-center gap-2">
          <span class="font-medium">Navigateur :</span>
          <span
          class="text-gray-900 dark:text-gray-100">{{ Str::limit($context['user_agent'], 100) }}</span>
          </div>
          </div>
      @endif
          </div>
      @elseif(str_contains($log->message, "Connexion réussie"))
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5" fill="none" stroke="#90EE90" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $log->message }}</span>
                </div>
            </div>
            <span class="text-xs text-gray-500 dark:text-gray-400">
                {{ $log->created_at->addHour()->format('d/m/Y H:i:s') }}
            </span>
        </div>
    </div>
  @elseif(str_contains($log->message, "Nouvelle idée créée"))
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
      <svg class="h-5 w-5" fill="none" stroke="#8BB9FF" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
      </svg>
      <div class="flex items-center gap-2">
      <span class="text-sm text-gray-900 dark:text-gray-100">Nouvelle idée créée
      :</span>
      <span
      class="text-sm font-medium text-gray-900 dark:text-gray-100">"{{ $log->context['title'] }}"</span>
      <span class="text-sm text-gray-500 dark:text-gray-400">#{{ $log->context['idea_id'] }}</span>
      </div>
      </div>
      <span class="text-xs text-gray-500 dark:text-gray-400">
      {{ $log->created_at->format('d/m/Y H:i:s') }}
      </span>
    </div>
  @elseif(str_contains($log->message, "Modification de l'idée"))
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5" fill="none" stroke="#FFB347" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $log->message }}</span>
            </div>
            <span class="text-xs text-gray-500 dark:text-gray-400">
                {{ $log->created_at->format('d/m/Y H:i:s') }}
            </span>
        </div>
        @if($log->context)
            @php
                $context = $log->context;
                $changes = json_decode($context['changes'], true);
            @endphp
            <div class="ml-8 space-y-2 text-sm">
                <div class="grid gap-2 text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Idée #{{ $context['idea_id'] }}</span>
                    </div>
                    @foreach($changes as $field => $change)
                        <div class="space-y-1">
                            <div class="font-medium">{{ ucfirst($field) }} :</div>
                            <div class="pl-4 border-l-2 border-gray-300 dark:border-gray-700">
                                <div class="line-through">{{ $change['old'] }}</div>
                                <div class="text-green-600 dark:text-green-400">{{ $change['new'] }}</div>
                            </div>
                        </div>
                    @endforeach
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Modifié par :</span>
                        <span>Utilisateur #{{ $context['modified_by'] }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
  @elseif(str_contains($log->message, "Modification du commentaire"))
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5" fill="none" stroke="#FFB347" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $log->message }}</span>
            </div>
            <span class="text-xs text-gray-500 dark:text-gray-400">
                {{ $log->created_at->format('d/m/Y H:i:s') }}
            </span>
        </div>
        @if($log->context)
            <div class="ml-8 space-y-2 text-sm">
                <div class="grid gap-2 text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Commentaire #{{ $log->context['comment_id'] }} sur l'idée #{{ $log->context['idea_id'] }}</span>
                    </div>
                    <div class="space-y-1">
                        <div class="font-medium">Modifications :</div>
                        <div class="pl-4 border-l-2 border-gray-300 dark:border-gray-700">
                            <div class="line-through" style="text-decoration: line-through;">{{ $log->context['changes']['old_content'] }}</div>
                            <div class="text-green-600 dark:text-green-400">{{ $log->context['changes']['new_content'] }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Modifié par :</span>
                        <span>Utilisateur #{{ $log->context['modified_by'] }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
  @elseif(str_contains($log->message, "Suppression du commentaire"))
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <svg class="h-5 w-5" fill="none" stroke="#FF5252" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-900 dark:text-gray-100">Commentaire supprimé :</span>
          <span class="text-sm font-medium text-gray-900 dark:text-gray-100 line-through text-red-600 dark:text-red-400">"{{ $log->context['content'] }}"</span>
          <span class="text-sm text-gray-500 dark:text-gray-400">#{{ $log->context['comment_id'] }} sur l'idée #{{ $log->context['idea_id'] }}</span>
          <span class="text-xs text-red-600 dark:text-red-400 ml-2 border border-red-300 dark:border-red-700 rounded-full px-2 py-0.5">{{ $log->context['deletion_reason'] }}</span>
          <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">par utilisateur #{{ $log->context['deleted_by'] }}</span>
        </div>
      </div>
      <span class="text-xs text-gray-500 dark:text-gray-400">
        {{ $log->created_at->format('d/m/Y H:i:s') }}
      </span>
    </div>
  @elseif(str_contains($log->message, "Suppression de l'idée"))
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <svg class="h-5 w-5" fill="none" stroke="#FF5252" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-900 dark:text-gray-100">Idée supprimée :</span>
          <span class="text-sm font-medium text-gray-900 dark:text-gray-100 line-through text-red-600 dark:text-red-400">"{{ $log->context['title'] }}"</span>
          <span class="text-sm text-gray-500 dark:text-gray-400">#{{ $log->context['idea_id'] }}</span>
          <span class="text-xs text-red-600 dark:text-red-400 ml-2 border border-red-300 dark:border-red-700 rounded-full px-2 py-0.5">{{ $log->context['deletion_reason'] }}</span>
          <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">par utilisateur #{{ $log->context['deleted_by'] }}</span>
        </div>
      </div>
      <span class="text-xs text-gray-500 dark:text-gray-400">
        {{ $log->created_at->format('d/m/Y H:i:s') }}
      </span>
    </div>
  @elseif($log->message === "Nouveau commentaire ajouté sur l'idée #" . ($log->context['idea_id'] ?? ''))
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-3">
    <svg class="h-5 w-5" fill="none" stroke="#8BB9FF" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
    </svg>
    <div class="flex items-center gap-2">
    <span class="text-sm text-gray-900 dark:text-gray-100">Nouveau commentaire
    #{{ $log->context['comment_id'] }}</span>
    <span class="text-sm text-gray-500 dark:text-gray-400">sur l'idée
    #{{ $log->context['idea_id'] }} :</span>
    <span
    class="text-sm font-medium text-gray-900 dark:text-gray-100">"{{ $log->context['content'] }}"</span>
    </div>
    </div>
    <span class="text-xs text-gray-500 dark:text-gray-400">
    {{ $log->created_at->format('d/m/Y H:i:s') }}
    </span>
  </div>
@else
  <div class="flex items-center justify-between">
    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
    {{ $log->message }}
    </p>
    <span class="text-xs text-gray-500 dark:text-gray-400">
    {{ $log->created_at->format('d/m/Y H:i:s') }}
    </span>
  </div>
  @if($log->context)
    <div class="mt-2">
    <pre
    class="text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 rounded p-2 overflow-x-auto">
                 {{ is_array($log->context) ? json_encode($log->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $log->context }}
                </pre>
    </div>
  @endif
@endif
            </div>
          </div>
          </div>
        </div>
    @endforeach
      </div>

      <!-- Pagination -->
      <div class="mt-6">
        {{ $logs->withQueryString()->links() }}
      </div>
    </div>
  </div>
</x-app-layout>