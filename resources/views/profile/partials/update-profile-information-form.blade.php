<div class="max-w-xl mx-auto">

    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">
                Perfil de Usuario
            </h2>
            <p class="text-sm text-gray-500">
                PIHCSA · Información básica de la cuenta
            </p>
        </div>

        {{-- Form verificación --}}
        <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
            @csrf
        </form>

        {{-- Form perfil --}}
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="px-6 py-5 space-y-4">

                {{-- Nombre --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nombre
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        autofocus
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring focus:ring-blue-200"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Correo electrónico
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring focus:ring-blue-200"
                    >

                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    {{-- Email no verificado --}}
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2 rounded-md bg-yellow-50 border border-yellow-200 p-3 text-sm text-yellow-800">
                            Tu correo no está verificado.
                            <button
                                form="send-verification"
                                class="underline ml-1 font-medium hover:text-yellow-900"
                            >
                                Reenviar correo de verificación
                            </button>
                        </div>
                    @endif

                    {{-- Mensaje verificación enviada --}}
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600">
                            Se ha enviado un nuevo enlace de verificación.
                        </p>
                    @endif
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent
                           rounded-md font-semibold text-sm text-white hover:bg-blue-700
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Guardar cambios
                </button>

                @if (session('status') === 'profile-updated')
                    <span class="text-sm text-green-600">
                        ✔ Cambios guardados
                    </span>
                @endif
            </div>

        </form>

    </div>

</div>
