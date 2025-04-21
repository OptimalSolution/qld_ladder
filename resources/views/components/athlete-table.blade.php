@props([
    'athletes',
    'columns' => ['rung', 'name', 'rating', 'age', 'gender', 'club'],
    'emptyMessage' => 'No athletes are currently eligible for the ladder'
])

<div id="scrollInfo" class="md:hidden text-xl text-gray-500 dark:text-gray-300 p-2 bg-gray-50 dark:bg-gray-800 flex items-center justify-end scroll-info transition-opacity duration-300">
    <span>Scroll for more info</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-1 mt-[2px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="animation: scrollArrow 0.5s ease-in-out infinite;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
    </svg>
</div>

<div id="tableContainer" {{ $attributes->merge(['class' => 'relative overflow-x-auto shadow-lg rounded-lg border border-gray-200']) }}>
    <table class="w-full text-lg text-left rtl:text-right text-gray-800 dark:text-gray-200">
        <thead class="text-md text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                @if(in_array('rung', $columns))
                <th scope="col" class="px-2 text-center">
                    Rung
                </th>
                @endif
                @if(in_array('name', $columns))
                <th scope="col" class="px-2 py-3">
                    Name
                </th>
                @endif
                @if(in_array('rating', $columns))
                <th scope="col" class="px-6 py-3">
                    Rating
                </th>
                @endif
                @if(in_array('age', $columns))
                <th scope="col" class="px-2 py-3">
                    Age
                </th>
                @endif
                @if(in_array('gender', $columns))
                <th scope="col" class="px-6 py-3">
                    Gender
                </th>
                @endif
                @if(in_array('club', $columns))
                <th scope="col" class="px-6 py-3">
                    Club
                </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if(!isset($athletes) || $athletes->isEmpty())
                <tr class="bg-white dark:bg-gray-800">
                    <td colspan="{{ count($columns) }}" class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-lg text-gray-500 dark:text-gray-400">
                            {{ $emptyMessage }}
                        </div>
                    </td>
                </tr>
            @endif
            @foreach($athletes as $athlete)
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200 w-16">
                @if(in_array('rung', $columns))
                <th scope="row" class="px-1 py-1 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $loop->iteration }}
                </th>
                @endif
                @if(in_array('name', $columns))
                <td class="px-1 py-4">
                    @if(!empty($athlete->ratings_central_id))
                        <a href="https://goodgame.pingponghero.com/#/player/RatingsCentral/{{ $athlete->ratings_central_id }}/{{ Str::studly(Str::slug($athlete->name)) }}" class="text-blue-500 hover:underline" target="_blank">
                            {{ $athlete->name }}
                            <svg class="hidden w-3.5 h-3.5 ml-1 inline-block text-gray-800 dark:text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778"/>
                            </svg>
                        </a>
                    @else
                        {{ $athlete->name }}
                    @endif
                </td>
                @endif
                @if(in_array('rating', $columns))
                <td class="text-center">
                    @if(!empty($athlete->ratings_central_id))
                        <a href="https://www.ratingscentral.com/Player.php?PlayerID={{ $athlete->ratings_central_id }}" target="_blank" class="text-blue-500 hover:underline">{{ $athlete->rating }}</a>
                    @else
                        {{ $athlete->rating }}
                    @endif
                </td>
                @endif
                @if(in_array('age', $columns))
                <td class="text-center">
                    {{ $athlete->ageRange() }}
                </td>
                @endif
                @if(in_array('gender', $columns))
                <td class="text-center">
                    {{ $athlete->sex }}
                </td>
                @endif
                @if(in_array('club', $columns))
                <td class="px-2 py-1 whitespace-nowrap">
                @if(!empty($athlete->clubWebsite()))
                    <a class="text-blue-500" href="{{ $athlete->clubWebsite() }}" target="_blank">{{ $athlete->club->name ?? '' }}</a>
                @else
                    {{ $athlete->club->name ?? '' }}
                @endif
                </td>
                @endif
            </tr>
            @endforeach                        
        </tbody>
    </table>
</div>

<style>
    @keyframes scrollArrow {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(3px); }
    }
</style> 

<script>
    $(document).ready(function() {
        $('#tableContainer').on('scroll', function() {
            if ($(this).scrollLeft() > 20) {
                $('#scrollInfo').addClass('opacity-0');
            } 
        });
    });
</script> 