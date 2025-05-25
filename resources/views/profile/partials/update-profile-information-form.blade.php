<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations du profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Mettez à jour vos informations personnelles et votre profil de joueur.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-md font-semibold mb-3 border-b pb-2">Informations de base</h3>

                <!-- Nom -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Nom')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Votre adresse email n\'est pas vérifiée.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cliquez ici pour renvoyer l\'email de vérification.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                        </p>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Avatar -->
                <div class="mb-4">
                    <x-input-label for="avatar" :value="__('Avatar')" />
                    <div class="mt-1 flex items-center">
                        <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 flex-shrink-0">
                            @if ($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <input type="file" id="avatar" name="avatar" class="ml-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    <p class="mt-1 text-sm text-gray-500">JPG, PNG, GIF. Max 2Mo.</p>
                </div>

                <!-- Bio -->
                <div class="mb-4">
                    <x-input-label for="bio" :value="__('Biographie')" />
                    <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('bio', $user->bio) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                </div>

                <!-- Date de naissance -->
                <div class="mb-4">
                    <x-input-label for="birth_date" :value="__('Date de naissance')" />
                    <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $user->birth_date)" />
                    <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
                </div>

                <!-- Localisation -->
                <div class="mb-4">
                    <x-input-label for="location" :value="__('Localisation')" />
                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $user->location)" placeholder="Ville, Pays" />
                    <x-input-error class="mt-2" :messages="$errors->get('location')" />
                </div>
            </div>

            <div>
                <h3 class="text-md font-semibold mb-3 border-b pb-2">Profil de joueur</h3>

                <!-- Plateforme préférée -->
                <div class="mb-4">
                    <x-input-label for="favorite_platform" :value="__('Plateforme préférée')" />
                    <select id="favorite_platform" name="favorite_platform" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">-- Sélectionner une plateforme --</option>
                        <option value="PC" {{ old('favorite_platform', $user->favorite_platform) == 'PC' ? 'selected' : '' }}>PC</option>
                        <option value="PlayStation 5" {{ old('favorite_platform', $user->favorite_platform) == 'PlayStation 5' ? 'selected' : '' }}>PlayStation 5</option>
                        <option value="PlayStation 4" {{ old('favorite_platform', $user->favorite_platform) == 'PlayStation 4' ? 'selected' : '' }}>PlayStation 4</option>
                        <option value="Xbox Series X/S" {{ old('favorite_platform', $user->favorite_platform) == 'Xbox Series X/S' ? 'selected' : '' }}>Xbox Series X/S</option>
                        <option value="Xbox One" {{ old('favorite_platform', $user->favorite_platform) == 'Xbox One' ? 'selected' : '' }}>Xbox One</option>
                        <option value="Nintendo Switch" {{ old('favorite_platform', $user->favorite_platform) == 'Nintendo Switch' ? 'selected' : '' }}>Nintendo Switch</option>
                        <option value="Mobile" {{ old('favorite_platform', $user->favorite_platform) == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('favorite_platform')" />
                </div>

                <!-- Discord -->
                <div class="mb-4">
                    <x-input-label for="discord_username" :value="__('Nom d\'utilisateur Discord')" />
                    <x-text-input id="discord_username" name="discord_username" type="text" class="mt-1 block w-full" :value="old('discord_username', $user->discord_username)" placeholder="utilisateur#0000" />
                    <x-input-error class="mt-2" :messages="$errors->get('discord_username')" />
                </div>

                <!-- PSN -->
                <div class="mb-4">
                    <x-input-label for="psn_username" :value="__('Nom d\'utilisateur PSN')" />
                    <x-text-input id="psn_username" name="psn_username" type="text" class="mt-1 block w-full" :value="old('psn_username', $user->psn_username)" />
                    <x-input-error class="mt-2" :messages="$errors->get('psn_username')" />
                </div>

                <!-- Xbox -->
                <div class="mb-4">
                    <x-input-label for="xbox_username" :value="__('Nom d\'utilisateur Xbox')" />
                    <x-text-input id="xbox_username" name="xbox_username" type="text" class="mt-1 block w-full" :value="old('xbox_username', $user->xbox_username)" />
                    <x-input-error class="mt-2" :messages="$errors->get('xbox_username')" />
                </div>

                <!-- Steam -->
                <div class="mb-4">
                    <x-input-label for="steam_username" :value="__('Nom d\'utilisateur Steam')" />
                    <x-text-input id="steam_username" name="steam_username" type="text" class="mt-1 block w-full" :value="old('steam_username', $user->steam_username)" />
                    <x-input-error class="mt-2" :messages="$errors->get('steam_username')" />
                </div>

                <!-- Nintendo -->
                <div class="mb-4">
                    <x-input-label for="nintendo_username" :value="__('Code ami Nintendo')" />
                    <x-text-input id="nintendo_username" name="nintendo_username" type="text" class="mt-1 block w-full" :value="old('nintendo_username', $user->nintendo_username)" placeholder="SW-XXXX-XXXX-XXXX" />
                    <x-input-error class="mt-2" :messages="$errors->get('nintendo_username')" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">{{ __('Enregistré.') }}</p>
            @endif
        </div>
    </form>
</section>