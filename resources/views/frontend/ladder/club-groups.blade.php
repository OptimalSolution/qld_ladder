@extends("frontend.layouts.app")

@section("title")
    {{ $page_title }}
@endsection

@section("content")
@php
    
    
@endphp
    <section class="bg-white dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-12 text-center sm:px-12">
            <h1 class="mb-6 text-4xl font-extrabold leading-none tracking-tight text-gray-900 dark:text-white sm:text-6xl">Club & Regional Ladders</h1>
            <p class="mb-10 text-md font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48" style="text-align: justify;">
                The following table shows the <b class="text-gray-900 dark:text-white">{{ $athletes->count() }} {{ $athletes->count() > 1 ? 'athletes' : 'athlete' }}</b> eligible for the <b class="text-gray-900 dark:text-white">{{ $gender_group }} Club Ladder</b>. To be considered for this ladder, players must have played since <b class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::now()->startOfYear()->subYears(1)->format('F jS, Y') }}</b>.
Junior ages listed below represent the age of the player by the <b class="text-gray-900 dark:text-white">end of the year</b>.
            </p>
            <p class="mb-1 text-md font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
            <div class="flex justify-center">
                <div class="relative inline-block text-center">
                    <div class="mb-2 text-lg font-medium text-gray-700 dark:text-gray-300">
                        Select a club from the dropdown menu to view their ladder:
                    </div>
                    <x-club-filter :gender_group="$gender_group" :club_id="$club_id" :preferred_route="'club-filter'" />
                </div>
            </div>
            </p>
            <div class="gender-groups mt-4 flex justify-center">
            @foreach($genders as $gender) 
                <a href="{{ route('club-filter', ['club_id' => $club_id, 'club_slug' => $club_slug, 'gender_group' => $gender]) }}" class="inline-block px-4 py-2 m-1 text-sm font-medium {{ $gender == $gender_group ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700">
                    {{ $gender }}
                </a>
            @endforeach
            </div>
            <div class="my-6 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block w-full sm:px-2 lg:px-2">
                            <div class="shadow overflow-hidden border border-gray-200 sm:rounded-lg">
                                <table class="w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-middle text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Rank
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-middle text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-middle text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Rating
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-middle text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Age
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-middle text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Gender
                                            </th>
                                            @if(isset($mixed_clubs) && $mixed_clubs)
                                            <th scope="col" class="px-6 py-3 text-middle text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Club
                                            </th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @if($athletes->isEmpty())
                                            <tr class="bg-white dark:bg-gray-800">
                                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="text-lg text-gray-500 dark:text-gray-400">
                                                        No athletes from this club are currently eligible for the ladder
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach($athletes as $athlete)
                                                <tr class="{{ $loop->iteration % 2 == 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-100 dark:bg-gray-900' }}">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-lg text-gray-900 dark:text-white">
                                                            {{ $loop->iteration }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-lg text-gray-900 dark:text-white">
                                                            {{ $athlete->name }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-lg text-gray-900 dark:text-white">
                                                            {{ $athlete->rating }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                    @if(empty($athlete->birth_date))
                                                        <div class="text-lg text-gray-200 dark:text-gray-600">
                                                            [Unlisted]
                                                        </div>
                                                    @else
                                                        <div class="text-lg text-gray-900 dark:text-white">
                                                            {{ $athlete->age }}
                                                        </div>
                                                    @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-lg text-gray-900 dark:text-white">
                                                            {{ $athlete->sex }}
                                                        </div>
                                                    </td>
                                                    @if($mixed_clubs)
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-lg text-gray-900 dark:text-white">
                                                        @if(!empty($athlete->club?->website))
                                                            <a class="text-blue-500" href="{{ $athlete->club->website }}" target="_blank">{{ $athlete->club->name ?? '' }}</a>
                                                        @else
                                                            {{ $athlete->club->name ?? '' }}
                                                        @endif
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            
            
        </div>
    </section>

@endsection
