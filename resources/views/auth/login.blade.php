<x-guest-layout>
    <!-- Background ng buong page -->
    <div class="h-screen w-screen flex items-center justify-center bg-gradient-to-br from-purple-300 via-purple-100 to-purple-400">

        <!-- Wrapper na medyo maliit -->
        <div class="w-11/12 max-w-6xl h-[90vh] flex rounded-2xl overflow-hidden shadow-2xl">
            
            <!-- Left Side with Background Image -->
            <div class="w-1/2 flex items-center justify-center relative bg-cover bg-center"
                 style="background-image: url('{{ asset('images/bg-image.png') }}');"> <!-- ilagay yung bg image mo dito -->

                <!-- Overlay gradient para hindi mawala yung purple feel -->
                <div class="absolute inset-0 bg-gradient-to-b from-purple-900 via-violet-800 to-purple-600 opacity-90"></div>

                <!-- Neon glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-600 opacity-20 blur-3xl"></div>

                <div class="relative text-center text-white z-10">
                    <img src="{{ asset('images/socialwork-logo.png') }}" 
                         alt="Social Work Department Logo" 
                         class="mx-auto w-48 h-48 mb-6 drop-shadow-lg">

                    <!-- Trimex Colleges Text -->
                    <p class="mt-2 text-2xl text-white drop-shadow-md" style="font-family: 'Times New Roman', serif;">
                        TRIMEX COLLEGES
                    </p>

                    <h1 class="text-2xl font-extrabold bg-gradient-to-r from-pink-400 to-purple-400 bg-clip-text text-transparent drop-shadow-md mt-2">
                        SOCIAL WORK DEPARTMENT
                    </h1>
                </div>
            </div>

            <!-- Right Side -->
            <div class="w-1/2 flex flex-col justify-center p-12 bg-gradient-to-br from-white to-purple-50">
                <h2 class="text-3xl font-bold text-purple-700 mb-8 text-center">Log in</h2>
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
                        <x-text-input id="email" class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-300 focus:ring-opacity-50"
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700" />
                        <x-text-input id="password" class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-300 focus:ring-opacity-50"
                            type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mb-6">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <div>
                        <x-primary-button class="w-full py-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-semibold shadow-md transition">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
