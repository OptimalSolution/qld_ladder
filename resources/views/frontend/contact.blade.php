@extends("frontend.layouts.app")

@section("title")
    Contact Us | {{ config("app.name") }}
@endsection

@section("content")
    <section class="bg-white dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl text-center px-4 py-12 sm:px-12">
            
            <h1
                class="mb-6 text-4xl font-extrabold leading-none tracking-tight text-center text-gray-900 dark:text-white sm:text-6xl"
            >
                Contact Us
            </h1>
            <p class="mb-3 text-md text-justify font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
                This table tennis ladder has been created by volunteers and administrators from clubs.
            </p>
            
            <p class="mb-3 text-md text-justify font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
                If you notice any incorrect details on the ladder (e.g. misspelled name, home club, age, etc.) please contact your club, so that they can update the information on RatingsCentral using their Director ID.
            </p>

            <p class="mb-3 text-md text-justify font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
                If your club cannot help, please reach out to us <a href="javasctipt:void(0);" class="text-blue-500 email-link hidden">via email</a> and we'll see what we can do on our end (to update the details or support the club to do so).
            </p>

            <p class="mb-3 text-md text-justify font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-xl xl:px-48">
                If you have any questions or concerns regarding this website, or any feature requests, please contact us <a href="javasctipt:void(0);" class="text-blue-500 email-link hidden">via email</a>
            </p>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('.email-link').each(function() {
                var $this = $(this);
                const contact = 'ladder' + '@' + 'ponglytics' + '.' + 'com';
                $this.before('at ');
                $this.text(contact);
                $this.attr('href', 'mailto:' + contact);
                $this.removeClass('hidden');
            });
        });
    </script>
@endsection
