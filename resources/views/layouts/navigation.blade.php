<nav x-data="{ open: false }"
     class="bg-white border-b border-gray-300 shadow-sm">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- IZQUIERDA: Logo + Sistema --}}
            <div class="flex items-center space-x-4">

                {{-- Logo --}}
                <a href="{{ route('equipos.index') }}"
                   class="flex items-center space-x-2">
                    <x-application-logo class="block h-9 w-auto text-blue-600" />
                    <span class="font-semibold text-gray-800 text-sm uppercase tracking-wide">
                        PIHCSA
                    </span>
                </a>

                {{-- Separador --}}
                <span class="text-gray-300 hidden sm:block">|</span>

                {{-- Módulo --}}
                <a href="{{ route('equipos.index') }}"
                   class="text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-boxes mr-1"></i>
                    Gestión de Activos
                </a>
            </div>

            {{-- DERECHA: Usuario --}}
            <div class="hidden sm:flex sm:items-center">

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm
                                   text-gray-700 bg-white hover:bg-gray-100 focus:outline-none">

                            <i class="fas fa-user-circle mr-2 text-gray-500"></i>
                            <span>{{ Auth::user()->name }}</span>

                            <svg class="ml-2 h-4 w-4 fill-current text-gray-500"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <div class="px-4 py-2 text-xs text-gray-500">
                            Sesión iniciada como
                            <div class="font-semibold text-gray-800">
                                {{ Auth::user()->email }}
                            </div>
                        </div>

                        <div class="border-t border-gray-200"></div>

                    </x-slot>
                </x-dropdown>

            </div>

            {{-- HAMBURGER --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="p-2 rounded-md text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- RESPONSIVE --}}
    <div x-show="open" class="sm:hidden border-t border-gray-200">
        <div class="px-4 py-3">
            <div class="font-medium text-base text-gray-800">
                {{ Auth::user()->name }}
            </div>
            <div class="text-sm text-gray-500">
                {{ Auth::user()->email }}
            </div>
        </div>

        <div class="px-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('equipos.index')">
                Gestión de Activos
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('profile.edit')">
                Perfil
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Cerrar sesión
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
