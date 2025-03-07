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
            
            
        </div>
    </section>
@endsection
