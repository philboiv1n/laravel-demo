<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('chirps.store') }}">
            @csrf
            <textarea
                name="message"
                placeholder="{{ __('À quoi pensez-vous?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('Envoyer') }}</x-primary-button>
        </form>

        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">

            @foreach ($chirps as $chirp)

                <div class="p-6 flex space-x-2">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>

                    <div class="flex-1">

                        <div class="flex justify-between items-center">

                            <div>
                                <span class="text-gray-800">{{ $chirp->user->name }}</span>
                                <small class="ml-2 text-sm text-gray-600">{{ $chirp->created_at->format('j M Y, g:i a') }}</small>
                                @unless ($chirp->created_at->eq($chirp->updated_at))
                                    <small class="text-sm text-gray-600"> &middot; {{ __('édité') }}</small>
                                @endunless
                            </div>

                            @if ($chirp->user->is(auth()->user()))
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('chirps.edit', $chirp)">
                                        {{ __('Éditer') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link onclick="showModal({{ $chirp->id }})" style="cursor:pointer;">
                                        Effacer
                                    </x-dropdown-link>

                                </x-slot>
                            </x-dropdown>
                            @endif

                        </div>

                        <p class="mt-4 text-lg text-gray-900">{{ $chirp->message }}</p>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

    <!-- Temporary inline CSS styling for the modal -->
    <div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; text-align: center; padding-top: 20%;">
    <div class="bg-white shadow-sm rounded-lg p-6" style="width:300px; margin: 0 auto;">
        <p>Voulez-vous vraiment effacer ce message?</p>
        <button style="margin: 12px 5px 0 5px; padding: 2px 6px; border: 1px solid #ccc;" onclick="deleteChirp()">Oui</button>
        <button style="margin: 12px 5px 0 5px; padding: 2px 6px; border: 1px solid #ccc;" onclick="closeModal()">Non</button>
    </div>
    </div>

</x-app-layout>

<!-- Temporary inline JavaScript for the modal -->
<script>

let currentChirpId = null;

function showModal(chirpId) {
    currentChirpId = chirpId;
    document.getElementById("deleteModal").style.display = "block";
}

function deleteChirp() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/chirps/' + currentChirpId;
    form.style.display = 'none';

    const token = document.createElement('input');
    token.name = '_token';
    token.value = '{{ csrf_token() }}';
    form.appendChild(token);

    const method = document.createElement('input');
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);

    document.body.appendChild(form);
    form.submit();
}

function closeModal() {
    document.getElementById("deleteModal").style.display = "none";
}</script>