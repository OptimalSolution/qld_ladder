@extends("frontend.layouts.app")

@section("title")
    {{ $page_title ?? "Ladder Categories" }}
@endsection

@section("content")
    <section class="bg-gray-50 dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-12 text-center sm:px-12">
            <h1
                class="mb-6 text-4xl font-extrabold leading-none tracking-tight text-gray-900 dark:text-white sm:text-6xl"
            >
                Ladder Categories
            </h1>
            <p class="mb-1 text-nd font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48" style="text-align: justify;">
                The following table shows the <b class="text-gray-900 dark:text-white">{{ $athletes->count() }} athlete{{ $athletes->count() == 1 ? '' : 's' }}</b> eligible for the this ladder category.
                To be considered for the ladder, players must have played since the start of <b class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::now()->startOfYear()->subYears(1)->format('F jS, Y') }}</b>
            </p>

            <div class="py-1 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
                <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0">
                    <div>
                        <h3 class="mb-2 text-xl font-bold dark:text-white">Gender Groups</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                        <div class="gender-groups mt-4">
                            <x-gender-filter :genderGroup="$gender_group" :ageGroup="$age_group" :clubId="$club_id" :clubSlug="$club_slug" routeName="ladder-filter" />
                        </div>
                        </p>
                    </div>
                    <div>
                        <h3 class="mb-2 text-xl font-bold dark:text-white">Age Divisions</h3>

                        <button id="dropdownDividerButton" data-dropdown-toggle="juniorListing" class="inline-block px-4 py-2 m-1 text-sm font-medium {{ str_contains($age_group, 'Under') ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700" type="button">Juniors
                        </button>

                        <!-- Dropdown menu -->
                        <div id="juniorListing" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDividerButton">
                                @foreach($age_groups as $age_group_slug => $age_group)
                                    @if(str_contains($age_group, 'Under') && $age_group_slug !== 'U21')
                                        <li>
                                            <a href="{{ route('ladder-filter', ['age_group' => $age_group_slug, 'gender_group' => $gender_group, 'club_id' => $club_id, 'club_slug' => $club_slug]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $age_group }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <button id="dropdownDividerButton" data-dropdown-toggle="seniorsListing" class="inline-block px-4 py-2 m-1 text-sm font-medium {{ str_contains($age_group, 'Over') ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700" type="button">Seniors
                        </button>

                        <!-- Dropdown menu -->
                        <div id="seniorsListing" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDividerButton">
                                @foreach($age_groups as $age_group_slug => $age_group)
                                    @if($age_group_slug !== 'Open' && (str_contains($age_group, 'Over') || $age_group_slug === 'U21'))
                                        <li>
                                            <a href="{{ route('ladder-filter', ['age_group' => $age_group_slug, 'gender_group' => $gender_group, 'club_id' => $club_id, 'club_slug' => $club_slug]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $age_group }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route('ladder-filter', ['age_group' => 'Open', 'gender_group' => $gender_group, 'club_id' => $club_id, 'club_slug' => $club_slug]) }}" 
                           class="inline-block px-4 py-2 m-1 text-sm font-medium {{ $age_group === 'Open' ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700">Open
                        </a>
                    </div>
                    <div>
                        <h3 class="mb-2 text-xl font-bold dark:text-white">Club Selection</h3>
                        
                    </div>
                </div>
            </div>


            <x-athlete-table 
                :athletes="$athletes" 
                :columns="['rank', 'name', 'rating', 'age', 'gender', 'club']"
            />


    </section>
@endsection
