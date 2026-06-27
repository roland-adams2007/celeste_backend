@extends('app')

@section('title', 'Sign In — ' . config('app.name'))
@section('description', 'Sign in to manage your guests')

@push('styles')
    <style>
        input:focus {
            outline: none;
            border-color: #b89c6e;
        }

        .shake {
            animation: shake 0.32s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-8px);
            }

            40% {
                transform: translateX(8px);
            }

            60% {
                transform: translateX(-4px);
            }

            80% {
                transform: translateX(4px);
            }
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-[#F9F4EE] p-5">

        <div class="w-full max-w-md bg-white border border-[#e5ddd3] px-8 py-10 md:px-10 shadow-lg transition-transform"
            id="login-card" x-data="loginForm()" :class="{ 'shake': shaking }">

            <div class="text-center mb-8">
                <img src="{{ asset('storage/app/logo.png') }}" alt="{{ config('app.name') }}"
                    class="h-12 sm:h-14 md:h-16 mx-auto mb-4 object-contain" />
                <span class="font-display text-3xl tracking-[0.08em] text-[#0E1A2B] block">
                    {{ config('app.name') }}
                </span>
                <span class="text-[10px] uppercase tracking-[0.22em] text-[#B89C6E] block mt-1">Admin Portal</span>
            </div>

            <div class="w-10 h-px bg-[#B89C6E] mx-auto mb-7"></div>

            <h2 class="text-sm font-medium text-[#0E1A2B] text-center mb-6 tracking-wide">
                Sign in to your account
            </h2>

            <div x-show="errorMessage" x-transition x-cloak style="display: none"
                class="bg-[#9b1c1c] text-white text-xs px-4 py-2.5 mb-5 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span x-text="errorMessage"></span>
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="email"
                        class="block text-[10px] uppercase tracking-[0.14em] text-[#6b7280] mb-1.5 font-medium">
                        Email address
                    </label>
                    <input type="email" id="email" x-model="form.email" @input="errorMessage = ''"
                        placeholder="Enter your email" required
                        class="w-full bg-[#F9F4EE] border border-[#e5ddd3] px-3 py-2.5 text-[13px] text-[#1a1a1a] transition-colors placeholder:text-[#b0a99e] placeholder:font-light" />
                </div>

                <div class="mb-4">
                    <label for="password"
                        class="block text-[10px] uppercase tracking-[0.14em] text-[#6b7280] mb-1.5 font-medium">
                        Password
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" id="password" x-model="form.password"
                            @input="errorMessage = ''" placeholder="••••••••" required
                            class="w-full bg-[#F9F4EE] border border-[#e5ddd3] px-3 py-2.5 pr-10 text-[13px] text-[#1a1a1a] transition-colors placeholder:text-[#b0a99e] placeholder:font-light" />
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#9ca3af] hover:text-[#0E1A2B] transition-colors">
                            <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-4 h-4"
                                x-effect="$nextTick(() => lucide.createIcons())"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-1 mb-6">
                    <label class="flex items-center gap-2 text-xs text-[#6b7280] font-light cursor-pointer select-none">
                        <input type="checkbox" x-model="form.remember"
                            class="accent-[#0E1A2B] w-3.5 h-3.5 cursor-pointer" />
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}"
                        class="text-xs text-[#6b7280] font-light border-b border-transparent hover:border-[#0E1A2B] hover:text-[#0E1A2B] transition-colors">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" :disabled="loading || redirecting"
                    class="w-full bg-[#0E1A2B] text-white text-xs font-medium tracking-[0.12em] uppercase py-3 flex items-center justify-center gap-2.5 hover:bg-[#B89C6E] transition-colors duration-200 disabled:opacity-60 disabled:cursor-not-allowed">
                    <template x-if="!loading && !redirecting">
                        <span class="flex items-center gap-2.5">
                            <i data-lucide="log-in" class="w-4 h-4"></i> Sign In
                        </span>
                    </template>
                    <template x-if="loading">
                        <span class="flex items-center gap-2.5">
                            <svg class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            Signing in...
                        </span>
                    </template>
                    <template x-if="redirecting">
                        <span class="flex items-center gap-2.5">
                            <svg class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            Redirecting...
                        </span>
                    </template>
                </button>
            </form>

            <div class="text-center mt-6 text-[11px] text-[#9ca3af] font-light">
                <a href="{{ route('frontend') }}"
                    class="text-[#6b7280] border-b border-transparent hover:border-[#0E1A2B] hover:text-[#0E1A2B] transition-colors">
                    ← Back to website
                </a>
                &nbsp;·&nbsp;
                <a href="#"
                    class="text-[#6b7280] border-b border-transparent hover:border-[#0E1A2B] hover:text-[#0E1A2B] transition-colors">
                    Privacy
                </a>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function loginForm() {
            return {
                form: {
                    email: '',
                    password: '',
                    remember: false,
                },
                loading: false,
                redirecting: false,
                shaking: false,
                showPassword: false,
                errorMessage: '',

                async submit() {
                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch('{{ route('login') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(this.form),
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.loading = false;
                            this.redirecting = true;
                            window.location.href = '{{ route('dashboard') }}';
                        } else {
                            this.errorMessage = data.message ?? 'Invalid email or password. Please try again.';
                            this.form.password = '';
                            this.triggerShake();
                        }
                    } catch (e) {
                        this.errorMessage = 'Something went wrong. Please try again.';
                        this.triggerShake();
                    } finally {
                        if (!this.redirecting) {
                            this.loading = false;
                        }
                    }
                },

                triggerShake() {
                    this.shaking = false;
                    this.$nextTick(() => {
                        this.shaking = true;
                        setTimeout(() => {
                            this.shaking = false;
                        }, 400);
                    });
                },
            };
        }
    </script>
@endpush
