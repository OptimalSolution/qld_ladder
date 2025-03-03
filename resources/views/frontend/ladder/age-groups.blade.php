@extends("frontend.layouts.app")

@section("title")
    {{ app_name() }}
@endsection

@section("content")
<section class="bg-gray-50 dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-24 text-center sm:px-12">
            <h1
                class="mb-6 text-4xl font-extrabold leading-none tracking-tight text-gray-900 dark:text-white sm:text-6xl"
            >
                Age Group Ladders
            </h1>
            <p class="mb-10 text-nd font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48" style="text-align: justify;">
                The following tables show the <b class="text-gray-900 dark:text-white">{{ $athletes->count() }} athletes</b> eligible for the QLD <b class="text-gray-900 dark:text-white">{{ $age_groups[$group] }}</b> Ladder.
                To be considered for this ladder, players must have played since <b class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::now()->startOfYear()->subYears(1)->format('F jS, Y') }}</b>.
                Junior ages listed below represent the age of the player by the <b class="text-gray-900 dark:text-white">end of the year</b>.
            </p>
            <p class="w-full mb-1 text-nd font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
            </p>    

            <div class="age-groups mb-6">
            @foreach($age_groups as $slug => $age_group) 
                <a href="{{ route('age-groups-subgroup', ['group' => $slug, 'gender' => $gender_group]) }}" class="inline-block px-4 py-2 m-1 text-sm font-medium {{ $slug == $group ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700">
                    {{ $age_group }}
                </a>
            @endforeach
            </div>
                <div class="mt-10">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white" style="line-height: 26px;">{{ $age_groups[$group] }} <br/><span class="text-gray-500 dark:text-gray-400 text-lg">({{ $athletes->count() }} athlete{{ $athletes->count() == 1 ? '' : 's' }})</span></h2>
                    <div class="gender-groups mt-4">
                    @foreach($gender_groups as $gender) 
                        <a href="{{ route('age-groups-subgroup', ['group' => $group, 'gender' => $gender]) }}" class="inline-block px-4 py-2 m-1 text-sm font-medium {{ $gender == $gender_group ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700">
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
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Club
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
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
                                                <td class="px-6 py-4 whitespace-nowrap text-left">
                                                    <div class="text-lg text-gray-900 dark:text-white">
                                                        @if(!empty($athlete->club?->website))
                                                            <a class="text-blue-500" href="{{ $athlete->club->website }}" target="_blank">{{ $athlete->club->name ?? '' }}</a>
                                                        @else
                                                            {{ $athlete->club->name ?? '' }}
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>    
            
        </div>
    </section>

@endsection
