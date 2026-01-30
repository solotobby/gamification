@extends('layouts.hub')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            {{-- Back Button --}}
            <a href="{{ route('career-hub.index') }}" class="inline-flex items-center text-blue-600 hover:underline mb-6">
                ← Back to opportunities
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Header Card --}}
                    <div class="bg-white rounded-lg shadow-sm p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-start space-x-4">
                                @if($job->company_logo)

                                    <img src="{{ displayImage($job->company_logo) }}" alt="{{ $job->company_name }}"
                                        class="w-12 h-12 rounded object-cover">
                                @else
                                    <div class="w-16 h-16 bg-blue-100 rounded flex items-center justify-center">
                                        <span class="text-blue-600 font-bold text-2xl">
                                            {{ substr($job->company_name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif

                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $job->title }}</h1>
                                    <p class="text-xl text-gray-600">{{ $job->company_name }}</p>
                                </div>
                            </div>

                            @if($job->tier === 'premium')
                                <span class="bg-yellow-400 text-yellow-900 px-4 py-2 rounded-full text-sm font-semibold">
                                    Premium
                                </span>
                            @endif
                        </div>

                        {{-- Quick Info --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="flex items-center space-x-2 text-gray-700">
                                <span>💼</span>
                                <span class="text-sm">{{ ucfirst($job->type) }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-700">
                                <span>📍</span>
                                <span class="text-sm">{{ $job->location }}</span>
                            </div>
                            @if($job->remote_allowed)
                                <div class="flex items-center space-x-2 text-blue-600">
                                    <span>🏠</span>
                                    <span class="text-sm font-medium">Remote OK</span>
                                </div>
                            @endif
                            @if($job->salary_range)
                                <div class="flex items-center space-x-2 text-green-600">
                                    <span>💰</span>
                                    <span class="text-sm font-medium">{{ $job->salary_range }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Meta --}}
                        <div class="flex items-center space-x-6 text-sm text-gray-500 pt-4 border-t">
                            <span>👁 {{ number_format($job->views_count) }} views</span>
                            <span>📝 {{ number_format($job->applications_count) }} applications</span>
                            <span>⏰ Posted {{ $job->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="bg-white rounded-lg shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Job Description</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    {{-- Responsibilities --}}
                    @if($job->responsibilities)
                        <div class="bg-white rounded-lg shadow-sm p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Responsibilities</h2>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($job->responsibilities)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Requirements --}}
                    @if($job->requirements)
                        <div class="bg-white rounded-lg shadow-sm p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Requirements</h2>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Benefits --}}
                    @if($job->benefits)
                        <div class="bg-white rounded-lg shadow-sm p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Benefits</h2>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($job->benefits)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Company Info --}}
                    @if($job->company_description)
                        <div class="bg-white rounded-lg shadow-sm p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">About {{ $job->company_name }}</h2>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($job->company_description)) !!}
                            </div>
                            @if($job->company_website)
                                <a href="{{ $job->company_website }}" target="_blank"
                                    class="inline-block mt-4 text-blue-600 hover:underline">
                                    Visit company website →
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="space-y-6 sticky top-4">
                        {{-- Apply Card --}}
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            @auth
                                @if($job->hasApplied(auth()->user()))
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                        <p class="text-green-800 font-medium">✓ You've already applied</p>
                                    </div>
                                @else
                                    <button onclick="document.getElementById('applicationModal').classList.remove('hidden')"
                                        class="w-full bg-blue-600 text-white px-6 py-4 rounded-lg hover:bg-blue-700 transition font-bold text-lg mb-4">
                                        Apply Now
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login', ['career_hub_redirect' => route('career-hub.show', $job->slug)]) }}"
                                    class="block w-full bg-blue-600 text-white text-center px-6 py-4 rounded-lg hover:bg-blue-700 transition font-bold text-lg mb-4">
                                    Login to Apply
                                </a>
                            @endauth

                            {{-- Job Summary --}}
                            <div class="space-y-4 text-sm">
                                <div class="pb-4 border-b">
                                    <p class="text-gray-500 mb-1">Job Type</p>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($job->type) }}</p>
                                </div>
                                <div class="pb-4 border-b">
                                    <p class="text-gray-500 mb-1">Location</p>
                                    <p class="font-semibold text-gray-900">{{ $job->location }}</p>
                                </div>
                                @if($job->salary_range)
                                    <div class="pb-4 border-b">
                                        <p class="text-gray-500 mb-1">Salary Range</p>
                                        <p class="font-semibold text-gray-900">{{ $job->salary_range }}</p>
                                    </div>
                                @endif
                                @if($job->expires_at)
                                    <div class="pb-4 border-b">
                                        <p class="text-gray-500 mb-1">Application Deadline</p>
                                        <p class="font-semibold text-gray-900">{{ $job->expires_at->format('M d, Y') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Micro-Jobs CTA --}}
                        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-sm p-6 text-white">
                            <div class="flex items-center mb-3">
                                <span class="text-3xl mr-3">⚡</span>
                                <h3 class="text-lg font-bold">Looking for Quick Gigs?</h3>
                            </div>
                            <p class="text-blue-100 text-sm mb-4">
                                Browse through Freebyz micro-jobs and start earning today!
                            </p>
                            <a href="/user/home"
                                class="block w-full bg-white text-blue-600 text-center px-4 py-3 rounded-lg hover:bg-blue-50 transition font-semibold">
                                Explore Micro-Jobs →
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

                        {{-- Related Jobs --}}
                        @if($relatedJobs->count())
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Similar Opportunities</h3>
                                <div class="space-y-4">
                                    @foreach($relatedJobs as $related)
                                        <a href="{{ route('career-hub.show', $related->slug) }}"
                                            class="block hover:bg-gray-50 p-3 rounded transition">
                                            <p class="font-semibold text-gray-900 mb-1">{{ $related->title }}</p>
                                            <p class="text-sm text-gray-600">{{ $related->company_name }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $related->location }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Application Modal --}}
    @auth
        @if(!$job->hasApplied(auth()->user()))
            <div id="applicationModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">Apply for {{ $job->title }}</h3>
                            <button onclick="document.getElementById('applicationModal').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-600">
                                ✕
                            </button>
                        </div>

                        <form method="POST" action="{{ route('career-hub.apply', $job) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Cover Letter (Optional)
                                    </label>
                                    <textarea name="cover_letter" rows="6"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="Tell us why you're a great fit..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Resume/CV (Optional)
                                    </label>
                                    <input type="file" name="resume" accept=".pdf,.doc,.docx"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <p class="text-xs text-gray-500 mt-1">PDF, DOC, or DOCX (max 5MB)</p>
                                </div>
                            </div>

                            <div class="flex space-x-4 mt-6">
                                <button type="submit"
                                    class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-bold">
                                    Submit Application
                                </button>
                                <button type="button" onclick="document.getElementById('applicationModal').classList.add('hidden')"
                                    class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth
@endsection
