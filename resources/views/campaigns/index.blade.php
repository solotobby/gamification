@extends('layouts.hub')


@section('title', 'Micro-Tasks')

@section('content')
    <div class="min-h-screen bg-gray-50">
        {{-- Hero Section --}}
        <div class="bg-white py-12 mb-8">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Discover Micro-Tasks
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl">
                    Browse available tasks and start earning today. Complete simple tasks and get paid instantly.
                </p>
            </div>
        </div>

        <div class="container mx-auto px-4 pb-12">
            {{-- Filters --}}
            <form method="GET" action="{{ route('tasks.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                {{-- Search --}}
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                {{-- Category --}}
                <select name="type"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($campaignTypes as $type)
                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Button --}}
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium w-full md:w-auto">
                    Apply Filters
                </button>

            </form>

            {{-- <select name="category"
                class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category')==$category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select> --}}

            <br>
        {{-- Campaign Cards Grid --}}
        @if($campaigns->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($campaigns as $campaign)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6">
                        {{-- Campaign Header --}}
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-lg">
                                    {{ substr($campaign->post_title, 0, 1) }}
                                </span>
                            </div>

                            @if($campaign->approved)
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Active
                                </span>
                            @endif
                        </div>

                        {{-- Campaign Details --}}
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            {{ $campaign->post_title }}
                        </h3>

                        {{-- Tags --}}
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $campaign->campaignType->name ?? 'N/A' }}
                            </span>
                            <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $campaign->currency }} {{ number_format($campaign->campaign_amount, 2) }}
                            </span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Progress</span>
                                <span>{{ $campaign->completed_count }}/{{ $campaign->number_of_staff }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full"
                                    style="width: {{ min(($campaign->completed_count / max($campaign->number_of_staff, 1)) * 100, 100) }}%">
                                </div>
                            </div>
                        </div>

                        {{-- Meta Info --}}
                        {{-- <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span>Posted {{ $campaign->created_at->diffForHumans() }}</span>
                        </div> --}}

                        {{-- CTA --}}
                        <a href="{{ route('tasks.show', $campaign->job_id) }}"
                            class="block w-full bg-blue-600 text-white text-center px-4 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                            View Details
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                {{ $campaigns->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <p class="text-gray-600 text-lg mb-4">No campaigns found matching your criteria.</p>
                <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline">
                    Clear all filters
                </a>
            </div>
        @endif
    </div>
    </div>
@endsection
