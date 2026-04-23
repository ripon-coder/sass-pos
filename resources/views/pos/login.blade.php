<!DOCTYPE html>
<html lang="en" class="bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NexPOS SaaS</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 antialiased text-slate-900">

    <!-- MAIN LAYOUT -->
    <div class="min-h-screen w-full flex overflow-hidden" x-data="loginForm()" x-init="$refs.tenant.focus()">
        
        <!-- LEFT PANEL (BRANDING / FEATURES) -->
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-between bg-gradient-to-br from-blue-900 to-slate-900 text-white p-12 relative overflow-y-auto max-h-screen">
            <!-- Subtle background effect -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <div class="absolute -top-[20%] -left-[10%] w-[80%] h-[80%] bg-gradient-to-br from-blue-500/20 to-transparent rounded-full opacity-60 blur-3xl"></div>
                <div class="absolute bottom-[10%] right-[-10%] w-[60%] h-[60%] bg-gradient-to-tl from-emerald-500/10 to-transparent rounded-full opacity-60 blur-3xl"></div>
            </div>
            
            <div class="z-10 mt-2">
                <!-- Logo -->
                <div class="flex items-center space-x-2 font-bold text-2xl tracking-tight">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span>NexPOS</span>
                </div>
            </div>
            
            <div class="z-10 mt-12 mb-8 flex-1 flex flex-col justify-center">
                <h1 class="text-3xl xl:text-4xl font-semibold mb-8 leading-snug tracking-tight">The operating system for multi-store retail.</h1>
                
                <!-- Features -->
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 mt-1 w-10 h-10 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium text-white">Multi-branch Management</h3>
                            <p class="text-sm text-blue-100/70 mt-1 max-w-sm">Control all your locations and staff from a single centralized dashboard.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 mt-1 w-10 h-10 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium text-white">Lightning-fast POS</h3>
                            <p class="text-sm text-blue-100/70 mt-1 max-w-sm">Designed for speed and reliability to keep your checkout lines moving quickly.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 mt-1 w-10 h-10 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium text-white">Real-time Inventory</h3>
                            <p class="text-sm text-blue-100/70 mt-1 max-w-sm">Never run out of stock with automatic synchronization across all your stores.</p>
                        </div>
                    </div>
                </div>

                <!-- Abstract Dashboard Preview Illustration -->
                <div class="relative mt-12 group perspective-1000 hidden xl:block">
                    <!-- Top Card -->
                    <div class="relative z-20 w-full max-w-[320px] bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-2xl transition-all duration-500 transform group-hover:-translate-y-1 group-hover:rotate-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-white">Daily Revenue</div>
                                    <div class="text-[11px] text-slate-400">Downtown Branch</div>
                                </div>
                            </div>
                            <div class="text-emerald-400 text-xs font-semibold bg-emerald-400/10 px-2 py-1 rounded">+14.5%</div>
                        </div>
                        <div class="text-2xl font-bold text-white">$4,289.00</div>
                    </div>
                    
                    <!-- Background Card -->
                    <div class="absolute top-4 left-4 z-10 w-full max-w-[320px] bg-slate-800/60 border border-slate-700/80 rounded-xl p-5 shadow-lg transform rotate-2 transition-all duration-500 group-hover:rotate-3 group-hover:translate-x-2 backdrop-blur-sm opacity-80">
                         <div class="h-16 flex items-end">
                             <div class="flex space-x-1 w-full items-end h-full opacity-40">
                                 <div class="w-1/6 bg-blue-400 rounded-t h-2/6"></div>
                                 <div class="w-1/6 bg-blue-400 rounded-t h-4/6"></div>
                                 <div class="w-1/6 bg-blue-400 rounded-t h-3/6"></div>
                                 <div class="w-1/6 bg-blue-400 rounded-t h-5/6"></div>
                                 <div class="w-1/6 bg-blue-400 rounded-t h-full"></div>
                                 <div class="w-1/6 bg-blue-400 rounded-t h-4/6"></div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
            
            <div class="z-10">
                <!-- Trust Signals -->
                <div class="flex items-center space-x-3 mb-8 text-sm text-blue-100/70">
                    <div class="flex -space-x-2">
                        <img class="w-8 h-8 rounded-full border-2 border-slate-900" src="https://i.pravatar.cc/100?img=1" alt="User">
                        <img class="w-8 h-8 rounded-full border-2 border-slate-900" src="https://i.pravatar.cc/100?img=5" alt="User">
                        <img class="w-8 h-8 rounded-full border-2 border-slate-900" src="https://i.pravatar.cc/100?img=3" alt="User">
                        <div class="w-8 h-8 rounded-full border-2 border-slate-900 bg-slate-800 flex items-center justify-center text-[10px] font-bold text-white">+99</div>
                    </div>
                    <div>
                        <div class="flex text-amber-400 text-xs mb-0.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        </div>
                        <span class="font-medium text-white">Trusted by 100+ retail stores</span>
                    </div>
                </div>

                <div class="flex items-center space-x-5 text-blue-100/50 text-sm font-medium">
                    <span>© <span x-text="new Date().getFullYear()"></span> NexPOS SaaS</span>
                    <a href="#" class="hover:text-white transition-colors">Privacy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms</a>
                </div>
            </div>
        </div>
        
        <!-- RIGHT PANEL (FORM) -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-white relative overflow-y-auto max-h-screen z-10 lg:rounded-l-[2.5rem] shadow-[-20px_0_40px_rgba(0,0,0,0.05)] lg:-ml-8">
            
            <!-- Mobile Logo -->
            <div class="lg:hidden absolute top-6 left-6 flex items-center space-x-2 font-bold text-xl text-slate-900 tracking-tight">
                <div class="w-8 h-8 bg-blue-600 text-white rounded flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span>NexPOS</span>
            </div>

            <!-- Inner Container -->
            <div class="w-full max-w-md py-12 lg:py-0 lg:pl-4">
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="text-2xl sm:text-3xl font-semibold text-slate-900 tracking-tight">Welcome back</h2>
                    <p class="mt-2 text-sm text-slate-500">Enter your credentials to access your terminal.</p>
                </div>
                
                <!-- Global Error Message -->
                <div x-cloak x-show="globalError" class="mb-6 rounded-lg bg-red-50 p-4 border border-red-100" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="h-4 w-4 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800" x-text="globalError"></p>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-5" @keydown.enter="submit" novalidate>
                    <!-- Business Name / Store ID -->
                    <div>
                        <label for="tenant" class="block text-sm font-medium text-slate-700 mb-1.5">Business Name / Store ID</label>
                        <div class="relative">
                            <input type="text" name="tenant" id="tenant" x-ref="tenant" x-model="tenant" 
                                @input="errors.tenant = null"
                                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.tenant, 'border-slate-300 focus:ring-blue-500 focus:border-blue-500': !errors.tenant}"
                                class="block w-full rounded-lg border py-2.5 px-3.5 text-slate-900 placeholder:text-slate-400 focus:ring-2 sm:text-sm sm:leading-6 transition-all shadow-sm outline-none bg-white" 
                                placeholder="e.g. Acme Corp">
                            
                            <!-- Error Icon -->
                            <div x-cloak x-show="errors.tenant" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <p x-cloak x-show="errors.tenant" x-text="errors.tenant" class="mt-1.5 text-xs text-red-600 font-medium"></p>
                    </div>

                    <!-- Email / Phone -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email or Phone</label>
                        <div class="relative">
                            <input id="email" name="email" type="text" x-model="email" autocomplete="email" 
                                @input="errors.email = null"
                                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.email, 'border-slate-300 focus:ring-blue-500 focus:border-blue-500': !errors.email}"
                                class="block w-full rounded-lg border py-2.5 px-3.5 text-slate-900 placeholder:text-slate-400 focus:ring-2 sm:text-sm sm:leading-6 transition-all shadow-sm outline-none bg-white" 
                                placeholder="cashier@store.com">
                            
                            <!-- Error Icon -->
                            <div x-cloak x-show="errors.email" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <p x-cloak x-show="errors.email" x-text="errors.email" class="mt-1.5 text-xs text-red-600 font-medium"></p>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'" x-model="password" autocomplete="current-password" 
                                @input="errors.password = null"
                                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.password, 'border-slate-300 focus:ring-blue-500 focus:border-blue-500': !errors.password}"
                                class="block w-full rounded-lg border py-2.5 pl-3.5 pr-10 text-slate-900 placeholder:text-slate-400 focus:ring-2 sm:text-sm sm:leading-6 transition-all shadow-sm outline-none bg-white" 
                                placeholder="••••••••">
                            
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors" tabindex="-1">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-cloak x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p x-cloak x-show="errors.password" x-text="errors.password" class="mt-1.5 text-xs text-red-600 font-medium"></p>
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center pt-1">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-colors cursor-pointer">
                        <label for="remember-me" class="ml-2.5 block text-sm text-slate-700 cursor-pointer">Remember me for 30 days</label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" :disabled="isLoading" class="group relative flex w-full justify-center items-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-70 disabled:cursor-not-allowed transition-all">
                            <span x-show="!isLoading">Continue to Dashboard</span>
                            <svg x-show="!isLoading" class="w-4 h-4 ml-2 opacity-80 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            
                            <!-- Spinner -->
                            <span x-cloak x-show="isLoading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Authenticating...
                            </span>
                        </button>
                    </div>
                    
                    <!-- Security Note -->
                    <div class="mt-4 flex items-center justify-center space-x-1.5 text-xs text-slate-500 font-medium">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Secure, end-to-end encrypted connection</span>
                    </div>
                </form>

                <!-- Footer / Extras -->
                <div class="mt-10 pt-6 border-t border-slate-100 text-center text-sm text-slate-500">
                    Don't have an account? <a href="#" class="font-medium text-blue-600 hover:text-blue-700 hover:underline underline-offset-4 transition-colors">Start your 14-day free trial</a>
                </div>
            </div>
        </div>
        
    </div>

    <script>
        function loginForm() {
            return {
                tenant: '',
                email: '',
                password: '',
                showPassword: false,
                isLoading: false,
                globalError: null,
                errors: {
                    tenant: null,
                    email: null,
                    password: null
                },
                
                validate() {
                    let isValid = true;
                    this.errors = { tenant: null, email: null, password: null };
                    
                    if (!this.tenant.trim()) {
                        this.errors.tenant = 'Business Name or Store ID is required';
                        isValid = false;
                    }
                    if (!this.email.trim()) {
                        this.errors.email = 'Email or phone number is required';
                        isValid = false;
                    }
                    if (!this.password) {
                        this.errors.password = 'Password is required';
                        isValid = false;
                    }
                    
                    return isValid;
                },

                submit() {
                    this.globalError = null;
                    
                    if (!this.validate()) {
                        if (this.errors.tenant) this.$refs.tenant.focus();
                        else if (this.errors.email) document.getElementById('email').focus();
                        else if (this.errors.password) document.getElementById('password').focus();
                        return;
                    }
                    
                    this.isLoading = true;
                    
                    setTimeout(() => {
                        if (this.email === 'admin@example.com' && this.password === 'password') {
                            window.location.href = '/dashboard';
                        } else {
                            this.globalError = 'Invalid credentials. Please check your details and try again.';
                            this.isLoading = false;
                            
                            this.errors.email = ' '; 
                            this.errors.password = ' ';
                            
                            document.getElementById('password').focus();
                        }
                    }, 1000);
                }
            }
        }
    </script>
</body>
</html>
