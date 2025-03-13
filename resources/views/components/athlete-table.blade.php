@props([
    'athletes',
    'columns' => ['rank', 'name', 'rating', 'age', 'gender', 'club'],
    'emptyMessage' => 'No athletes are currently eligible for the ladder'
])

<div {{ $attributes->merge(['class' => 'relative overflow-x-auto shadow-lg rounded-lg border border-gray-200']) }}>
    <table class="w-full text-lg text-left rtl:text-right text-gray-800 dark:text-gray-200">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                @if(in_array('rank', $columns))
                <th scope="col" class="px-6 py-3 w-16">
                    Rank
                </th>
                @endif
                @if(in_array('name', $columns))
                <th scope="col" class="px-6 py-3">
                    Name
                </th>
                @endif
                @if(in_array('rating', $columns))
                <th scope="col" class="px-6 py-3">
                    Rating
                </th>
                @endif
                @if(in_array('age', $columns))
                <th scope="col" class="px-6 py-3">
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
                @if(in_array('rank', $columns))
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $loop->iteration }}
                </th>
                @endif
                @if(in_array('name', $columns))
                <td class="px-6 py-4">
                    {{ $athlete->name }}
                </td>
                @endif
                @if(in_array('rating', $columns))
                <td class="px-6 py-4">
                    {{ $athlete->rating }}
                </td>
                @endif
                @if(in_array('age', $columns))
                <td class="px-6 py-4">
                    {{ $athlete->age }}
                </td>
                @endif
                @if(in_array('gender', $columns))
                <td class="px-6 py-4">
                    {{ $athlete->sex }}
                </td>
                @endif
                @if(in_array('club', $columns))
                <td class="px-6 py-4">
                @if(!empty($athlete->club?->website))
                    <a class="text-blue-500" href="{{ $athlete->club->website }}" target="_blank">{{ $athlete->club->name ?? '' }}</a>
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