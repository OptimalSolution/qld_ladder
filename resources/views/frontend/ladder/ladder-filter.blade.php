@extends("frontend.layouts.app")

@section("title")
    {{ $page_title ?? "Ladder Categories" }}
@endsection

@php
    $from_club_message = $selected_location ? 'from ' . $selected_location : '';
@endphp

@section("content")
<style>
    #scroll-to-top {
        position: fixed;
        bottom: 16px;
        right: 16px;
        z-index: 1000; /* Ensure it's above other elements */
        display: block; /* Ensure it's displayed */
    }
    
    @media (max-width: 768px) {
        #scroll-to-top {
            right: 24px; /* More from the right on mobile */
        }
    }
</style>
    <section class="bg-gray-50 dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-12 text-center sm:px-12">
            <h1 class="mb-6 text-4xl font-extrabold leading-none tracking-tight text-gray-900 dark:text-white sm:text-6xl">
                Queensland Table Tennis Ladder
            </h1>
            <p class="mb-1 text-nd font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48" style="text-align: justify;">
                This project is a collaborative initiative between clubs to create an interactive, <b class="text-blue-500">unofficial</b> ladder ranking system for table tennis players in Queensland, utilising the RatingsCentral system. The following table shows the <b class="text-gray-900 dark:text-white">{{ ($athletes->count() > 1) ? $athletes->count() : '' }} {{ Str::plural('athlete', $athletes->count()) }}</b> eligible for the <b class="text-gray-900 dark:text-white">{{ $age_groups[$age_group] }} {{ $gender_group }}</b> ladder.
                To be considered for the ladder, players <b class="text-gray-900 dark:text-white">{{ $from_club_message }}</b> must have played since the start of <b class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::now()->startOfYear()->subYears(1)->format('F jS, Y') }}</b>
            </p>

            <p class="my-6 text-nd text-centerfont-normal text-gray-500 dark:text-gray-100 sm:px-16 sm:text-xl xl:px-48">
                Clicking on a player's name or club will allow you to see more information about them. Use the filters below to check out view the different categories:
            </p>

            <div id="charts-container" class="hidden">
                <div class="flex flex-wrap justify-center items-start gap-4">
                    <x-gender-distribution />
                    <x-rating-distribution />
                </div>

                <button id="hide-charts-btn" data-collapse-toggle="charts-container" class="w-1/5 block mx-auto mt-4 mb-4 px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-full text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Hide Charts
                </button>
            </div>
            
            <script>
                $(document).ready(function() {
                    $('.show-charts-btn, #hide-charts-btn').on('click', function() {
                        $('#charts-container').slideToggle(300);
                    });
                });
            </script>

            <hr class="mt-8 border-gray-200 dark:border-gray-600">

            <div class="py-6 px-4 mx-auto max-w-screen-xl sm:py-14 lg:px-6">
                <div class="space-y-8 md:grid md:grid-cols-3 lg:grid-cols-3 md:gap-12 md:space-y-0 mb-4">
                    <!-- Gender Groups -->
                    <div class="justify-center items-center">
                        <div class="flex justify-center items-center show-charts-btn" title="Show Charts">
                            <h3 class="mb-2 text-xl font-bold dark:text-white">Gender Groups</h3>
                            <svg alt="Chart logo" class="w-5 h-5 ml-2 mb-1 text-white items-center" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                        </div>
                        <x-gender-filter :genderGroup="$gender_group" :ageGroup="$age_group" :clubId="$club_id" :clubSlug="$club_slug" routeName="ladder-filter" />
                    </div>

                    <!-- Age Divisions -->
                    <div>
                        <h3 class="mb-2 text-xl font-bold dark:text-white">Age Divisions</h3>

                        <button id="dropdownDividerButton" data-dropdown-toggle="juniorListing" class="inline-block px-4 py-2 m-1 text-sm font-medium {{ juniorCategorySelected($age_groups[$age_group]) ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700" type="button">Juniors {{ juniorCategorySelected($age_groups[$age_group]) ? '- ' . $age_group : '' }}
                        </button>

                        <!-- Dropdown menu -->
                        <div id="juniorListing" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDividerButton">
                                @foreach($age_groups as $age_group_slug => $age_group_name)
                                    @if(juniorCategorySelected($age_group_name))
                                        <li>
                                            <a href="{{ route('ladder-filter', ['age_group' => $age_group_slug, 'gender_group' => $gender_group, 'club_id' => $club_id, 'club_slug' => $club_slug]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $age_group_name }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <button id="dropdownDividerButton" data-dropdown-toggle="seniorsListing" class="inline-block px-4 py-2 m-1 text-sm font-medium {{ seniorCategorySelected($age_groups[$age_group]) ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700" type="button">Seniors {{ seniorCategorySelected($age_groups[$age_group]) ? '- ' . $age_group : '' }}
                        </button>

                        <!-- Dropdown menu -->
                        <div id="seniorsListing" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDividerButton">
                                @foreach($age_groups as $age_group_slug => $age_group_name)
                                    @if(seniorCategorySelected($age_group_name))
                                        <li>
                                            <a href="{{ route('ladder-filter', ['age_group' => $age_group_slug, 'gender_group' => $gender_group, 'club_id' => $club_id, 'club_slug' => $club_slug]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $age_group_name }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route('ladder-filter', ['age_group' => 'Open', 'gender_group' => $gender_group, 'club_id' => $club_id, 'club_slug' => $club_slug]) }}" 
                           class="inline-block px-4 py-2 m-1 text-sm font-medium {{ $age_group === 'Open' ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700">Open
                        </a>
                    </div>

                    <!-- Clubs -->
                    <div class="mb-10">

                    <div class="flex justify-center items-center show-charts-btn" title="Show Charts">
                        <h3 class="mb-2 text-xl font-bold dark:text-white">Clubs & Regions</h3>
                        <svg class="w-6 h-6 ml-2 mb-1 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5"/>
                        </svg>
                    </div>

                        <x-club-filter :gender_group="$gender_group" :age_group="$age_group" :club_id="$club_id" :club_slug="$club_slug" :selected_location="$selected_location" />
                    </div>
                </div>
            </div>


            <x-athlete-table 
                :athletes="$athletes" 
                :columns="!empty($club_id) && is_numeric($club_id) ? ['rank', 'name', 'rating', 'age', 'gender'] : ['rank', 'name', 'rating', 'age', 'gender', 'club']"
            />
            <x-scroll-to-top />
    </section>
@endsection
