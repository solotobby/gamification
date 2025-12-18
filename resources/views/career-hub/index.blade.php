@extends('layouts.hub')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Hero Section --}}
    <div class="bg-white py-12 mb-8">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Find your next opportunity
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl">
                Discover full-time roles, part-time positions, and exciting gigs.
                Join thousands of professionals finding their perfect match.
            </p>

            {{-- Stats --}}
            {{-- <div class="grid grid-cols-3 gap-8 mt-8 max-w-2xl">
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_jobs']) }}</div>
                    <div class="text-sm text-gray-600">Active opportunities</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['premium_jobs']) }}</div>
                    <div class="text-sm text-gray-600">Premium listings</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['companies']) }}</div>
                    <div class="text-sm text-gray-600">Hiring companies</div>
                </div>
            </div> --}}
        </div>
    </div>

    <div class="container mx-auto px-4 pb-12">
        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('career-hub.index') }}" class="space-y-4">
                {{-- Search --}}
                <div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search jobs, companies..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                {{-- Filter Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <select name="type" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="fulltime" {{ request('type') == 'fulltime' ? 'selected' : '' }}>Full Time</option>
                        <option value="parttime" {{ request('type') == 'parttime' ? 'selected' : '' }}>Part Time</option>
                        <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>Internship</option>
                        <option value="gig" {{ request('type') == 'gig' ? 'selected' : '' }}>Gig</option>
                    </select>

                    <select name="tier" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">All Tiers</option>
                        <option value="free" {{ request('tier') == 'free' ? 'selected' : '' }}>Free</option>
                        <option value="premium" {{ request('tier') == 'premium' ? 'selected' : '' }}>Premium</option>
                    </select>

                    <input
                        type="text"
                        name="location"
                        value="{{ request('location') }}"
                        placeholder="Location"
                        class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >

                    <div class="flex items-center space-x-2">
                        <input
                            type="checkbox"
                            name="remote"
                            id="remote"
                            value="1"
                            {{ request('remote') ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <label for="remote" class="text-gray-700">Remote OK</label>
                    </div>
                </div>

                {{-- Salary Range --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input
                        type="number"
                        name="salary_min"
                        value="{{ request('salary_min') }}"
                        placeholder="Min Salary"
                        class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                    <input
                        type="number"
                        name="salary_max"
                        value="{{ request('salary_max') }}"
                        placeholder="Max Salary"
                        class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium"
                    >
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        {{-- Job Cards Grid --}}
        @if($jobs->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($jobs as $job)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6">
                    {{-- Company Logo & Premium Badge --}}
                    <div class="flex items-start justify-between mb-4">
                        @if($job->company_logo)
                            <img
                                src="{{ displayImage($job->company_logo) }}"
                                alt="{{ $job->company_name }}"
                                class="w-12 h-12 rounded object-cover"
                            >
                        @else
                            <div class="w-12 h-12 bg-blue-100 rounded flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-lg">
                                    {{ substr($job->company_name, 0, 1) }}
                                </span>
                            </div>
                        @endif

                        @if($job->tier === 'premium')
                            <span class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-semibold">
                                Premium
                            </span>
                        @endif
                    </div>

                    {{-- Job Details --}}
                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                        {{ $job->title }}
                    </h3>
                    <p class="text-gray-600 mb-1">{{ $job->company_name }}</p>
                    <p class="text-sm text-gray-500 mb-4">
                        📍 {{ $job->location }}
                        @if($job->remote_allowed)
                            <span class="text-blue-600">• Remote OK</span>
                        @endif
                    </p>

                    {{-- Tags --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                            {{ ucfirst($job->type) }}
                        </span>
                        @if($job->salary_range)
                            <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $job->salary_range }}
                            </span>
                        @endif
                    </div>

                    {{-- Meta Info --}}
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                        {{-- <span>👁 {{ number_format($job->views_count) }} views</span> --}}
                        {{-- <span></span> --}}
                        <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                    </div>

                    {{-- CTA --}}
                    <a
                        href="{{ route('career-hub.show', $job->slug) }}"
                        class="block w-full bg-blue-600 text-white text-center px-4 py-3 rounded-lg hover:bg-blue-700 transition font-medium"
                    >
                        View Details
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <p class="text-gray-600 text-lg mb-4">No opportunities found matching your criteria.</p>
                <a href="{{ route('career-hub.index') }}" class="text-blue-600 hover:underline">
                    Clear all filters
                </a>
            </div>
        @endif
    </div>

    {{-- CTA Section --}}
    {{-- <div class="bg-blue-600 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                Sign up today and start earning in naira and dollars!
            </h2>
            <p class="text-blue-100 mb-8 max-w-2xl mx-auto">
                Start getting hired and making deposits. Free transfer. No limits. No sign-up fees. No monthly bills.
            </p>
            @guest
                <a
                    href="{{ route('register') }}"
                    class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg hover:bg-gray-100 transition font-bold text-lg"
                >
                    Sign up for free
                </a>
            @endguest
        </div>
    </div> --}}
</div>
@endsection
