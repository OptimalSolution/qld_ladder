@extends("frontend.layouts.app")

@section("title")
    {{ $page_title }}
@endsection

@section("content")
    <section class="bg-gray-50 dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-12 text-center sm:px-12">
            <h1
                class="mb-6 text-4xl font-extrabold leading-none tracking-tight text-gray-900 dark:text-white sm:text-6xl"
            >
                Gender Group Ladders
            </h1>
            <p class="mb-10 text-nd font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48" style="text-align: justify;">
                The following tables show the <b class="text-gray-900 dark:text-white">{{ $ladder_total }} athletes</b> eligible for the QLD Gender Group Ladder.
                There are a total of {{ $athlete_total }} registered players in Queensland in {{ count($gender_groups) }} gender groups. 
                To be considered for the ladder, players must have played since the start of <b class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::now()->startOfYear()->subYears(1)->format('F jS, Y') }}</b>
            </p>
            <p class="w-full mb-1 text-nd font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
            </p>    


            <x-athlete-table 
                :athletes="$athletes" 
                :columns="['rank', 'name', 'rating', 'age', 'gender', 'club']"
            />


    </section>
@endsection
