@extends("frontend.layouts.app")

@section("title")
    {{ app_name() }}
@endsection

@section("content")
    <section class="bg-white dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-24 text-center sm:px-12">
            <h1 class="mb-6 text-4xl font-extrabold leading-none tracking-tight text-gray-900 dark:text-white sm:text-6xl">Club Ladders</h1>
            <p class="mb-10 text-md font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48" style="text-align: justify;">
                The following tables show the <b class="text-gray-900 dark:text-white">{{ $athletes->count() }} athletes</b> eligible for the QLD <b class="text-gray-900 dark:text-white">Club Ladder</b>. To be considered for this ladder, players must have played since <b class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::now()->startOfYear()->subYears(1)->format('F jS, Y') }}</b>.
Junior ages listed below represent the age of the player by the <b class="text-gray-900 dark:text-white">end of the year</b>.
            </p>
            <p class="mb-1 text-md font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
            <div class="flex justify-center">
                <div class="relative inline-block text-left">
                    <div class="mb-2 text-lg font-medium text-gray-700 dark:text-gray-300">
                        Select a club from the dropdown menu to view their ladder:
                    </div>
                    <div class="flex items-center">
                        
                        <select id="clubSelect" class="block w-full px-4 py-2 text-xl text-gray-700 bg-white dark:bg-gray-700 dark:text-white border-0 focus:outline-none focus:ring-0 rounded-full">
                            @foreach($club_groups as $club)
                                <option value="{{ route('club-filter', ['club_id' => $club->ratings_central_club_id, 'club_slug' => $club ? Str::slug($club->name) . '1' : 'unaffiliated', 'gender_group' => 'Mixed']) }}" {{ $club_id == $club->ratings_central_club_id ? 'selected' : '' }}>{{ $club->name }}</option>
                            @endforeach
                        </select>
                        <button id="copyLinkBtn" class="ml-2 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" title="Copy link to clipboard" alt="Copy link to clipboard">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </button>
                    </div>
                    <script>
                        document.getElementById('clubSelect').addEventListener('change', function() {
                            if (this.value) {
                                window.location.href = this.value;
                            }
                        });
                        
                        document.getElementById('copyLinkBtn').addEventListener('click', function() {
                            const selectElement = document.getElementById('clubSelect');
                            const linkToCopy = selectElement.value;
                            
                            navigator.clipboard.writeText(linkToCopy).then(function() {
                                // Create and show a toast message
                                const toast = document.createElement('div');
                                toast.textContent = 'Link copied!';
                                toast.style.position = 'absolute';
                                toast.style.top = '7px';
                                toast.style.right = '0';
                                toast.style.transform = 'translateX(100%)';
                                toast.style.backgroundColor = '#4CAF50';
                                toast.style.color = 'white';
                                toast.style.padding = '5px 10px';
                                toast.style.borderRadius = '4px';
                                toast.style.fontSize = '14px';
                                toast.style.zIndex = '1000';
                                
                                const button = document.getElementById('copyLinkBtn');
                                button.parentNode.style.position = 'relative';
                                button.parentNode.appendChild(toast);
                                
                                setTimeout(function() {
                                    toast.remove();
                                }, 2000);
                            });
                        });
                    </script>
                </div>
            </div>
            </p>
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
