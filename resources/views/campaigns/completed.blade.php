@extends('layouts.hub')

@section('title', 'Micro-Tasks')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            {{-- Icon --}}
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            {{-- Content --}}
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Campaign Completed</h1>
            <p class="text-lg text-gray-600 mb-6">
                This campaign has reached its maximum number of workers and is no longer accepting submissions.
            </p>

            {{-- Campaign Title --}}
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $campaign['post_title'] }}</h3>
                <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                    <span>💰 {{ $campaign['currency'] }} {{ number_format($campaign['campaign_amount'], 2) }}</span>
                    <span>•</span>
                    <span>✓ {{ $campaign['completed_count'] }}/{{ $campaign['number_of_staff'] }} Workers</span>
                </div>
            </div>

            {{-- Info Alert --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-blue-800">
                        Don't worry! There are many other campaigns available on Freebyz.
                    </p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a
                    href="{{ route('tasks.index') }}"
                    class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold"
                >
                    <i class="fas fa-search mr-2"></i>Browse More Campaigns
                </a>
                <a
                    href="{{ route('login') }}"
                    class="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 transition font-semibold"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Dashboard
                </a>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4 pt-8 border-t border-gray-200">
                <div>
                    <div class="text-2xl font-bold text-gray-900">100%</div>
                    <div class="text-xs text-gray-500">Completed</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $campaign['number_of_staff'] }}</div>
                    <div class="text-xs text-gray-500">Total Workers</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600">{{ $campaign['currency'] }} {{ number_format($campaign['campaign_amount'] * $campaign['number_of_staff'], 2) }}</div>
                    <div class="text-xs text-gray-500">Total Paid Out</div>
                </div>
            </div>
        </div>

        {{-- Alternative Options --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-start">
                    <div class="text-3xl mr-4">⚡</div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Similar Campaigns</h3>
                        <p class="text-sm text-gray-600 mb-3">Find tasks like this one still accepting workers</p>
                        <a
                            href="{{ route('tasks.index', ['type' => $campaign['campaign_type']]) }}"
                            class="text-blue-600 hover:underline text-sm font-medium"
                        >
                            Browse Similar →
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-start">
                    <div class="text-3xl mr-4">💼</div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Career Opportunities</h3>
                        <p class="text-sm text-gray-600 mb-3">Looking for long-term opportunities?</p>
                        <a href="{{ route('jobs') }}" class="text-blue-600 hover:underline text-sm font-medium">
                            Explore Jobs →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Newsletter Signup --}}
        <div class="mt-8 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-sm p-8 text-white text-center">
            <h3 class="text-xl font-bold mb-2">Never Miss a Campaign</h3>
            <p class="text-blue-100 text-sm mb-4">
                Get notified when new campaigns matching your interests are posted
            </p>
            <a
                href="{{ route('register') }}"
                class="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition font-semibold"
            >
                Sign Up for Free
            </a>
        </div>
    </div>
</div>
@endsection
