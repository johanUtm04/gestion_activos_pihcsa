<section>

    {{-- ENCABEZADO --}}
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800">
            Seguridad de la Cuenta
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Actualiza tu contraseña para mantener segura tu cuenta en el sistema PIHCSA.
        </p>
    </header>

    {{-- FORMULARIO --}}
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        {{-- CONTRASEÑA ACTUAL --}}
        <div>
            <x-input-label
                for="update_password_current_password"
                :value="__('Contraseña actual')"
            />

            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="current-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2"
            />
        </div>

        {{-- NUEVA CONTRASEÑA --}}
        <div>
            <x-input-label
                for="update_password_password"
                :value="__('Nueva contraseña')"
            />

            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password')"
                class="mt-2"
            />
        </div>

        {{-- CONFIRMACIÓN --}}
        <div>
            <x-input-label
                for="update_password_password_confirmation"
                :value="__('Confirmar contraseña')"
            />

            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2"
            />
        </div>

        {{-- ACCIONES --}}
        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>
                Guardar cambios
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <span class="text-sm text-green-600">
                    Contraseña actualizada correctamente.
                </span>
            @endif
        </div>
    </form>

</section>
