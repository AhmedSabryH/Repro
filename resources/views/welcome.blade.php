<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark" dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repro Tool</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="text-gray-300 antialiased relative overflow-x-hidden">
<!-- Background Effects -->
<div class="fixed inset-0 grid-bg pointer-events-none z-0"></div>
<div class="floating-orb orb-1"></div>
<div class="floating-orb orb-2"></div>
<div class="floating-orb orb-3"></div>

<div class="relative z-10 p-4 md:p-6 lg:p-8 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-4/5 glass-strong rounded-2xl shadow-2xl overflow-hidden animate-fade-in">
        <!-- Header -->
        <header class="relative bg-gradient-to-r from-dark-800 via-dark-700 to-dark-800 p-8 border-b border-white/10">

            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-secondary/5 to-accent/5"></div>
            <div class="relative flex items-center mb-2 justify-between">
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center shadow-glow animate-pulse-slow">
                        <i class="fas fa-clipboard-check text-white text-xl"></i>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold gradient-text tracking-tight">
                        Repro Tool
                    </h1>
                </div>
                <div>
                    <form method="POST" action="{{ route('set.locale') }}">
                        @csrf
                        <input type="hidden" name="locale" id="localeInput" value="{{ app()->getLocale() }}">
                        <button type="submit"
                                class="px-3 py-1 rounded-lg border border-white/20 bg-dark-700 hover:bg-dark-600 text-sm font-semibold transition-all duration-300">
                            {{ strtoupper(app()->getLocale()) === 'EN' ? 'ع' : 'EN' }}
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-gray-400 ml-14 max-w-2xl text-sm md:text-base leading-relaxed inline">
                {{__('app.document_test_cases')}}
                <span class="text-primary font-medium">"{{__('app.suggested_solution')}}"</span>.
            </p>
        </header>

        <!-- Main Content -->
        <div class="p-6 md:p-8 space-y-8">
            <form id="testCaseForm" class="space-y-6">
                <!-- Test Type & URL Grid -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="testType" class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <i class="fas fa-tasks text-primary"></i>
                            <span>{{ __('app.test_type') }}</span>
                            <span class="text-accent">*</span>
                        </label>
                        <input type="text" id="testType" required
                               class="w-full px-4 py-3 bg-dark-800/50 border border-white/10 rounded-xl text-gray-100 placeholder-gray-600 input-glow transition-all duration-300 focus:outline-none"
                               placeholder="{{ __('app.test_type_placeholder') }}">
                    </div>

                    <div class="space-y-2">
                        <label for="errorUrl" class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <i class="fas fa-link text-primary"></i>
                            <span>{{ __('app.error_url') }}</span>
                            <span class="text-accent">*</span>
                        </label>
                        <input type="url" id="errorUrl" required
                               class="w-full px-4 py-3 bg-dark-800/50 border border-white/10 rounded-xl text-gray-100 placeholder-gray-600 input-glow transition-all duration-300 focus:outline-none font-mono text-sm"
                               placeholder="{{ __('app.error_url_placeholder') }}">
                    </div>
                </div>

                <!-- Steps to Reproduce Section -->
                <div class="gradient-border rounded-2xl p-1">
                    <div class="bg-dark-800/30 rounded-2xl p-6 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                            <h3 class="flex items-center gap-2 text-lg font-bold text-white">
                                <i class="fas fa-list-ol text-secondary"></i>
                                <span>{{ __('app.steps_to_reproduce') }}</span>
                                <span class="text-accent">*</span>
                            </h3>
                            <button type="button" id="addStepBtn"
                                    class="group relative px-4 py-2 bg-dark-700 hover:bg-dark-600 border border-white/10 rounded-lg text-sm font-semibold text-primary transition-all duration-300 hover:shadow-glow hover:scale-105 active:scale-95 flex items-center gap-2 overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-primary/10 to-secondary/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <i class="fas fa-plus transition-transform group-hover:rotate-90"></i>
                                <span>{{ __('app.add_step') }}</span>
                            </button>
                        </div>

                        <div id="stepsList" class="space-y-3 max-h-96 overflow-y-auto scrollbar-custom pr-2">
                            <div
                                class="no-steps-message text-center py-8 text-gray-500 border-2 border-dashed border-white/10 rounded-xl bg-dark-800/30"
                                id="noStepsMessage">
                                <i class="fas fa-hand-pointer text-3xl mb-2 text-primary/50"></i>
                                <p>{{ __('app.click_to_add') }} <span class="text-primary font-semibold">"{{ __('app.add_step') }}"</span> {{ __('app.to_begin_adding') }}
                                    {{ __('app.steps_to_reproduce') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account & Environment Row -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="accountType" class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <i class="fas fa-user-tag text-primary"></i>
                            <span>{{ __('app.account_type') }}</span>
                            <span class="text-accent">*</span>
                        </label>
                        <div class="relative">
                            <select id="accountType" required
                                    class="w-full px-4 py-3 bg-dark-800/50 border border-white/10 rounded-xl text-gray-100 appearance-none input-glow transition-all duration-300 focus:outline-none cursor-pointer">
                                <option value=""
                                        class="bg-dark-800 text-black">{{ __('app.select_account_type_placeholder') }}</option>
                                <option value="Super Admin"
                                        class="bg-dark-800 text-black">{{ __('app.super_admin') }}</option>
                                <option value="Admin" class="bg-dark-800 text-black">{{ __('app.admin') }}</option>
                                <option value="User" class="bg-dark-800 text-black">{{ __('app.user') }}</option>
                                <option value="Guest" class="bg-dark-800 text-black">{{ __('app.guest') }}</option>
                                <option value="Other" class="bg-dark-800 text-black">{{ __('app.other') }}</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="testEnvironment"
                               class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <i class="fas fa-server text-primary"></i>
                            <span>{{ __('app.test_environment') }}</span>
                            <span class="text-accent">*</span>
                        </label>
                        <div class="relative">
                            <select id="testEnvironment" required
                                    class="w-full px-4 py-3 bg-dark-800/50 border border-white/10 rounded-xl text-gray-100 appearance-none input-glow transition-all duration-300 focus:outline-none cursor-pointer">
                                <option value=""
                                        class="bg-dark-800 text-black">{{ __('app.select_environment_placeholder') }}</option>
                                <option value="Local" class="bg-dark-800 text-black">{{ __('app.local') }}</option>
                                <option value="Staging" class="bg-dark-800 text-black">{{ __('app.staging') }}</option>
                                <option value="Production"
                                        class="bg-dark-800 text-black">{{ __('app.production') }}</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Other Account Type (Conditional) -->
                <div id="otherAccountContainer" class="hidden space-y-2 animate-fade-in">
                    <label for="otherAccount" class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                        <i class="fas fa-edit text-secondary"></i>
                        <span>{{ __('app.specify_other_account') }}</span>
                    </label>
                    <input type="text" id="otherAccount"
                           class="w-full px-4 py-3 bg-dark-800/50 border border-secondary/30 rounded-xl text-gray-100 placeholder-gray-600 input-glow transition-all duration-300 focus:outline-none"
                           placeholder="{{ __('app.other_account_placeholder') }}">
                </div>

                <!-- Test Date -->
                <div class="space-y-2">
                    <label for="testDate" class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                        <i class="fas fa-calendar-alt text-primary"></i>
                        <span>{{ __('app.test_date') }}</span>
                        <span class="text-accent">*</span>
                    </label>
                    <input type="date" id="testDate" required
                           class="w-full px-4 py-3 bg-dark-800/50 border border-white/10 rounded-xl text-gray-100 input-glow transition-all duration-300 focus:outline-none [color-scheme:dark]">
                </div>

                <!-- Text Areas Grid -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="expectedResult" class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span>{{ __('app.expected_result') }}</span>
                            <span class="text-accent">*</span>
                        </label>
                        <textarea id="expectedResult" required rows="4"
                                  class="w-full px-4 py-3 bg-dark-800/50 border border-white/10 rounded-xl text-gray-100 placeholder-gray-600 input-glow transition-all duration-300 focus:outline-none resize-y min-h-[100px]"
                                  placeholder="{{ __('app.expected_result_placeholder') }}"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label for="actualResult" class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <i class="fas fa-times-circle text-red-400"></i>
                            <span>{{ __('app.actual_result') }}</span>
                            <span class="text-accent">*</span>
                        </label>
                        <textarea id="actualResult" required rows="4"
                                  class="w-full px-4 py-3 bg-dark-800/50 border border-white/10 rounded-xl text-gray-100 placeholder-gray-600 input-glow transition-all duration-300 focus:outline-none resize-y min-h-[100px]"
                                  placeholder="{{ __('app.actual_result_placeholder') }}"></textarea>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="suspectedCause" class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <i class="fas fa-search text-secondary"></i>
                            <span>{{ __('app.suspected_cause') }}</span>
                            <span class="text-accent">*</span>
                        </label>
                        <textarea id="suspectedCause" required rows="3"
                                  class="w-full px-4 py-3 bg-dark-800/50 border border-white/10 rounded-xl text-gray-100 placeholder-gray-600 input-glow transition-all duration-300 focus:outline-none resize-y"
                                  placeholder="{{ __('app.suspected_cause_placeholder') }}"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label for="suggestedSolution"
                               class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <i class="fas fa-lightbulb text-yellow-400"></i>
                            <span>{{ __('app.suggested_solution_field') }}</span>
                            <span
                                class="text-gray-500 text-xs font-normal">({{ __('app.optional') ?? 'Optional' }})</span>
                        </label>
                        <textarea id="suggestedSolution" rows="3"
                                  class="w-full px-4 py-3 bg-dark-800/50 border border-white/10 rounded-xl text-gray-100 placeholder-gray-600 input-glow transition-all duration-300 focus:outline-none resize-y"
                                  placeholder="{{ __('app.suggested_solution_placeholder') }}"></textarea>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-4">
                    <button type="button" id="copyBtn" disabled
                            class="group relative w-full md:w-auto mx-auto block px-8 py-4 btn-primary rounded-xl text-white font-bold text-lg shadow-glow disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none transition-all duration-300 hover:shadow-glow-lg hover:-translate-y-1 active:translate-y-0">
                                        <span class="relative z-10 flex items-center justify-center gap-3">
                                            <i class="far fa-copy text-xl group-hover:scale-110 transition-transform"></i>
                                            <span>{{ __('app.copy_test_case') }}</span>
                                        </span>
                    </button>
                </div>
            </form>

            <!-- Output Section -->
            <div class="mt-8 pt-8 border-t border-white/10">
                <h3 class="flex items-center gap-2 text-lg font-bold text-white mb-4">
                    <i class="fas fa-code text-secondary"></i>
                    <span>{{ __('app.formatted_output') }}</span>
                    <span class="text-xs text-gray-500 font-normal ml-2">{{ __('app.ready_for_jira') }}</span>
                </h3>
                <div class="relative group">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-primary via-secondary to-accent rounded-xl opacity-20 group-hover:opacity-40 transition-opacity blur"></div>
                    <div
                        class="relative bg-dark-900/80 border border-white/10 rounded-xl p-6 font-mono text-sm text-gray-300 min-h-[200px] max-h-[400px] overflow-y-auto scrollbar-custom whitespace-pre-wrap"
                        id="outputArea">
                        <span class="text-gray-600 italic">{{ __('app.fill_out_form') }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center pt-6 border-t border-white/5">
                <p class="text-sm text-gray-500 flex items-center justify-center gap-2">
                    <i class="fas fa-info-circle text-primary/70"></i>
                    <span>{{ __('app.complete_fields_message') }}</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Notification Toast -->
<div id="notification"
     class="notification fixed top-6 right-6 z-50 glass-strong border border-green-500/30 rounded-xl p-4 shadow-2xl flex items-center gap-3">
    <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
        <i class="fas fa-check-circle text-green-400 text-xl"></i>
    </div>
    <div>
        <h4 class="text-white font-semibold text-sm">{{ __('app.success') }}</h4>
        <p class="text-gray-400 text-xs">{{ __('app.copied_to_clipboard') }}</p>
    </div>
</div>
</body>
@php
    $translations = [
        'describe_step' => __('app.describe_step'),
        'click_add_step' => __('app.click_add_step'),
        'add_step' => __('app.add_step'),
        'begin_adding_steps' => __('app.begin_adding_steps')
    ];
@endphp

<script>
    window.translations = @json($translations);
</script>

</html>
