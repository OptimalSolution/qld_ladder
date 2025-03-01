@extends("frontend.layouts.app")

@section("title")
    {{ app_name() }}
@endsection

@section("content")
    <section class="bg-white dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-24 text-center sm:px-12">
            
            <h1
                class="mb-6 text-4xl font-extrabold leading-none tracking-tight text-gray-900 dark:text-white sm:text-6xl"
            >
                Gender Group Ladders
            </h1>
            <p class="mb-10 text-nd font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
                The following tables show the current players in the QLD Gender Group Ladder. <br/>
                There are {{ $athlete_total }} players in {{ count($gender_groups) }} gender groups.
            </p>
            <p class="w-full mb-1 text-nd font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
            </p>    

            <div class="grid grid-cols-2 gap-4 mt-2">
            @foreach($gender_groups as $gender)
                <div class="col">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $gender }} - {{ $gender_grouped_athletes[$gender]->count() }} athletes</h2>
                    <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-2 lg:px-2">
                            <div class="shadow overflow-hidden border border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-middle text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Rank
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-middle text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Rating
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Club
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($gender_grouped_athletes[$gender] as $athlete)
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
                                                    <div class="text-lg text-gray-900 dark:text-white">
                                                        {{ $athlete->club->nickname ?? '' }}
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
            @endforeach
            </div>
        </div>
    </section>

@endsection
