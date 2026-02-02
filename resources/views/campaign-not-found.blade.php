@extends('layouts.hub')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            {{-- Icon --}}
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            {{-- Content --}}
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Campaign Not Found</h1>
            <p class="text-lg text-gray-600 mb-6">
                We couldn't find the campaign you're looking for. It may have been removed or the link is incorrect.
            </p>

            {{-- Alert Box --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-yellow-800">
                        This campaign may no longer be available or never existed.
                    </p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a
                    href="{{ route('tasks.index') }}"
                    class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold"
                >
                    <i class="fas fa-search mr-2"></i>Browse All Campaigns
                </a>
                <a
                    href="{{ route('login') }}"
                    class="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 transition font-semibold"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Dashboard
                </a>
            </div>

            {{-- Additional Help --}}
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-4">
                    Need help? Our support team is here for you.
                </p>
                <a
                    href="https://freebyz.com/contact"
                    class="text-blue-600 hover:underline font-medium"
                >
                    Contact Support →
                </a>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start">
                    <div class="text-3xl mr-4">⚡</div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Explore Micro-Jobs</h3>
                        <p class="text-sm text-gray-600 mb-3">Browse hundreds of available tasks</p>
                        <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline text-sm font-medium">
                            View Campaigns →
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start">
                    <div class="text-3xl mr-4">💼</div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Career Opportunities</h3>
                        <p class="text-sm text-gray-600 mb-3">Find full-time and part-time jobs</p>
                        <a href="{{ route('jobs') }}" class="text-blue-600 hover:underline text-sm font-medium">
                            Browse Jobs →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
