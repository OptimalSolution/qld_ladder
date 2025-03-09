@extends("frontend.layouts.app")

@section("title")
    {{ app_name() }}
@endsection

@section("content")
    <section class="bg-white dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-12 text-center sm:px-12">
            
            <h1
                class="mb-6 text-4xl font-extrabold leading-none tracking-tight text-gray-900 dark:text-white sm:text-6xl"
            >
                Queensland State Ladder
            </h1>
            <p class="mb-1 text-nd font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
            This project is a collaborative initiative between clubs to create an interactive ladder ranking system for table tennis players in Queensland, utilising the RatingsCentral system. Feel free to select the different categories, or search for an athlete. 
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-6">
                <a href="{{ route('age-groups') }}" class="w-32 sm:w-auto">
                    <div class="flex flex-col items-center justify-center p-4 bg-blue-100 dark:bg-blue-900 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Search by Age Group</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300 text-center mt-1">Find players by their age category</p>
                    </div>
                </a>
                
                <a href="{{ route('club-groups') }}" class="w-32 sm:w-auto">
                    <div class="flex flex-col items-center justify-center p-4 bg-green-100 dark:bg-green-900 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Search by Club</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300 text-center mt-1">Find players from specific clubs</p>
                    </div>
                </a>
            </div>
            
            <div class="flex items-center justify-center relative mt-4 w-full">
                <input 
                    type="text" 
                    class="flex w-1/2 py-2 px-4 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                    placeholder="Coming soon..." 
                    disabled
                />
                <div class="ml-2">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 absolute" fill="none" stroke="currentColor" style="bottom: 10px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </section>
@endsection
