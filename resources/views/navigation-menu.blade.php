<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset(config('app.logo_path', 'storage/logo-cafe.png')) }}"
                            alt="{{ config('app.name', 'Laravel') }} Logo"
                            class="object-cover w-10 h-10 border-2 border-gray-200 rounded-full">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    {{-- MENU UNTUK SEMUA STAF (OWNER & KASIR) --}}
                    @can('is-cashier')
                        <x-nav-link href="{{ route('admin.orders') }}" :active="request()->routeIs('admin.orders')">
                            {{ __('Pesanan Masuk') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.table-status') }}" :active="request()->routeIs('admin.table-status')">
                            {{ __('Status Meja') }}
                        </x-nav-link>
                    @endcan

                    {{-- MENU KHUSUS OWNER --}}
                    @can('is-owner')
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.menus') }}" :active="request()->routeIs('admin.menus')">
                            {{ __('Menu') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.categories') }}" :active="request()->routeIs('admin.categories')">
                            {{ __('Kategori') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.tables') }}" :active="request()->routeIs('admin.tables')">
                            {{ __('Meja') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.inventory') }}" :active="request()->routeIs('admin.inventory')">
                            {{ __('Inventaris') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.reports') }}" :active="request()->routeIs('admin.reports')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.promos') }}" :active="request()->routeIs('admin.promos')">
                            {{ __('Promo') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.users') }}" :active="request()->routeIs('admin.users')">
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.stock-history') }}" :active="request()->routeIs('admin.stock-history')">
                            {{ __('Riwayat Stok') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative ms-3">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50">
                                    {{ Auth::user()->name }}
                                    <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-200"></div>

                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        {{-- Responsive Links --}}
    </div>
</nav>
