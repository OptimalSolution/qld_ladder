<button id="scroll-to-top" type="button" class="rounded-full text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" style="display: none;">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 19V5m-7 7l7-7 7 7" />
    </svg>
</button>

<script>
    $(document).ready(function() {
        // Show button when user scrolls down 300px from the top
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('#scroll-to-top').fadeIn();
            } else {
                $('#scroll-to-top').fadeOut();
            }
        });
        
        // Smooth scroll to top when button is clicked
        $('#scroll-to-top').click(function() {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
    });
</script> 