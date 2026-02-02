@extends('layouts.hub')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        {{-- Back Button --}}
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center text-blue-600 hover:underline mb-6">
            ← Back to Tasks
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Header Card --}}
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-16 bg-blue-100 rounded flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-2xl">
                                    {{ substr($campaign['post_title'], 0, 1) }}
                                </span>
                            </div>

                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $campaign['post_title'] }}</h1>
                                <p class="text-xl text-gray-600">{{ $campaign['campaignType']['name'] }}</p>
                            </div>
                        </div>

                        @if($campaign['approved'])
                            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold">
                                Active
                            </span>
                        @endif
                    </div>

                    {{-- Quick Info --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span>💼</span>
                            <span class="text-sm">{{ $campaign['campaignType']['name'] }}</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span>📁</span>
                            <span class="text-sm">{{ $campaign['campaignCategory']['name'] }}</span>
                        </div>
                        <div class="flex items-center space-x-2 text-green-600">
                            <span>💰</span>
                            <span class="text-sm font-medium">
                                {{ $campaign['currency'] }} {{ number_format($campaign['campaign_amount'], 2) }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2 text-blue-600">
                            <span>👥</span>
                            <span class="text-sm font-medium">{{ $campaign['number_of_staff'] }} workers</span>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div class="pt-4 border-t">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Task Progress</span>
                            <span class="font-medium">{{ $campaign['completed_count'] }}/{{ $campaign['number_of_staff'] }} completed</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div
                                class="bg-blue-600 h-3 rounded-full transition-all"
                                style="width: {{ min(($campaign['completed_count'] / max($campaign['number_of_staff'], 1)) * 100, 100) }}%"
                            ></div>
                        </div>
                    </div>

                    {{-- Meta --}}
                    {{-- <div class="flex items-center space-x-6 text-sm text-gray-500 pt-4 border-t mt-4">
                        <span>👁 {{ number_format($campaign['impressions']) }} views</span>
                        <span>⏰ Posted {{ \Carbon\Carbon::parse($campaign['created_at'])->diffForHumans() }}</span>
                    </div> --}}
                </div>

                {{-- Description --}}
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Campaign Description</h2>
                    <div class="prose max-w-none text-gray-700">
                        {!! $campaign['description'] !!}
                    </div>
                </div>

                {{-- Instructions --}}
                @if($campaign['proof'])
                    <div class="bg-white rounded-lg shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Campaign Instructions</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! $campaign['proof'] !!}
                        </div>
                    </div>
                @endif

                 {{-- CTA Section --}}
                <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Ready to Get Started?</h3>
                    <p class="text-gray-600 mb-6">
                        Login to perform this task and earn {{ $campaign['currency'] }} {{ number_format($campaign['campaign_amount'], 2) }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a
                            href="{{ route('login') }}?redirect={{ $campaign['job_id'] }}"
                            class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold"
                        >
                            <i class="fas fa-sign-in-alt me-2"></i>Perform Task
                        </a>
                        <a
                            href="{{ route('register') }}?redirect={{ $campaign['job_id'] }}"
                            class="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 transition font-semibold"
                        >
                            Sign up now
                        </a>
                    </div>
                </div>

                {{-- Features Section --}}
                <div class="bg-gray-50 rounded-lg p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-4xl mb-3">🔒</div>
                            <h5 class="font-semibold text-gray-900 mb-2">Secure Payment</h5>
                            <p class="text-sm text-gray-600">Get paid safely after approval</p>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl mb-3">⚡</div>
                            <h5 class="font-semibold text-gray-900 mb-2">Quick Approval</h5>
                            {{-- <p class="text-sm text-gray-600">{{ $campaign['approval_time'] ?? 24 }}-hour automatic approval</p> --}}
                            <p class="text-sm text-gray-600">24-hour automatic approval</p>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl mb-3">👥</div>
                            <h5 class="font-semibold text-gray-900 mb-2">Join Thousands</h5>
                            <p class="text-sm text-gray-600">Of satisfied workers</p>
                        </div>
                    </div>
                </div>


            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="space-y-6 sticky top-4">
                    {{-- Summary Card --}}
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            Campaign Summary
                        </h3>
                        <div class="space-y-4 text-sm">
                            <div class="pb-4 border-b">
                                <p class="text-gray-500 mb-1">Campaign Type</p>
                                <p class="font-semibold text-gray-900">{{ $campaign['campaignType']['name'] }}</p>
                            </div>
                            <div class="pb-4 border-b">
                                <p class="text-gray-500 mb-1">Category</p>
                                <p class="font-semibold text-gray-900">{{ $campaign['campaignCategory']['name'] }}</p>
                            </div>
                            <div class="pb-4 border-b">
                                <p class="text-gray-500 mb-1">Payment per Job</p>
                                <p class="font-semibold text-green-600">
                                    {{ $campaign['currency'] }} {{ number_format($campaign['campaign_amount'], 2) }}
                                </p>
                            </div>
                            <div class="pb-4 border-b">
                                <p class="text-gray-500 mb-1">Workers Needed</p>
                                <p class="font-semibold text-gray-900">{{ $campaign['number_of_staff'] }} workers</p>
                            </div>
                            <div>
                                <p class="text-gray-500 mb-1">Spots Remaining</p>
                                <p class="font-semibold text-blue-600">
                                    {{ max(0, $campaign['number_of_staff'] - $campaign['completed_count']) }} spots
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Share Card --}}
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-share-alt mr-2 text-blue-600"></i>
                            Share This Campaign
                        </h3>

                        @php
                            $campaignUrl = url()->current();
                            $shareText = "Complete this {$campaign['post_title']} task on Freebyz to earn {$campaign['currency']} {$campaign['campaign_amount']}! {$campaignUrl}";
                        @endphp

                        <div class="space-y-3">
                            <button
                                type="button"
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg transition font-medium text-sm"
                                onclick="copyShareLink()"
                            >
                                <i class="fas fa-link mr-2"></i>Copy Link
                            </button>

                            <a
                                href="https://wa.me/?text={{ rawurlencode($shareText) }}"
                                target="_blank"
                                class="block w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg transition font-medium text-sm text-center"
                            >
                                <i class="fab fa-whatsapp mr-2"></i>Share on WhatsApp
                            </a>

                            <a
                                href="https://x.com/intent/tweet?text={{ rawurlencode($shareText) }}"
                                target="_blank"
                                class="block w-full bg-black hover:bg-gray-800 text-white px-4 py-3 rounded-lg transition font-medium text-sm text-center"
                            >
                                <i class="fab fa-x-twitter mr-2"></i>Share on X
                            </a>

                            <a
                                href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($campaignUrl) }}"
                                target="_blank"
                                class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition font-medium text-sm text-center"
                            >
                                <i class="fab fa-facebook-f mr-2"></i>Share on Facebook
                            </a>
                        </div>

                        <input type="hidden" id="shareLink" value="{{ $campaignUrl }}">
                        <small class="text-success mt-2 hidden" id="copyMessage">✓ Link copied to clipboard!</small>
                    </div>

                    {{-- Career Hub CTA --}}
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-sm p-6 text-white">
                        <div class="flex items-center mb-3">
                            <span class="text-3xl mr-3">💼</span>
                            <h3 class="text-lg font-bold">Looking for Full-Time Jobs?</h3>
                        </div>
                        <p class="text-blue-100 text-sm mb-4">
                            Explore professional opportunities in our Career Hub!
                        </p>
                        <a
                            href="{{ route('jobs') }}"
                            class="block w-full bg-white text-blue-600 text-center px-4 py-3 rounded-lg hover:bg-blue-50 transition font-semibold"
                        >
                            Browse Jobs →
                        </a>
                    </div>

                     <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg shadow-sm p-6 text-white">
                            <div class="flex items-center mb-3">
                                <span class="text-3xl mr-3">⚡</span>
                                <h3 class="text-lg font-bold">Has your Content ever made you enough money? </h3>
                            </div>
                            <p class="text-purple-100 text-sm mb-4">
                                Monetize your posts and content on our social media platform - Payhankey
                            </p>
                            <a href="https://payhankey.com/"
                                class="block w-full bg-white text-purple-600 text-center px-4 py-3 rounded-lg hover:bg-purple-50 transition font-semibold">
                                Get Started →
                            </a>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyShareLink() {
        const shareLink = document.getElementById('shareLink').value;
        const copyMessage = document.getElementById('copyMessage');

        navigator.clipboard.writeText(shareLink).then(function () {
            copyMessage.classList.remove('hidden');
            setTimeout(function () {
                copyMessage.classList.add('hidden');
            }, 3000);
        }, function (err) {
            alert('Failed to copy link. Please copy manually: ' + shareLink);
        });
    }
</script>
@endsection
