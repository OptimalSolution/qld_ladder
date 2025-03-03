<div class="relative inline-block text-left">
<div class="mb-2 text-lg font-medium text-gray-700 dark:text-gray-300">
    To narrow it down to a certain club, select one below:
</div>
<div class="flex items-center">
    
    <select id="clubSelect" class="block w-full px-4 py-2 text-xl text-gray-700 bg-white dark:bg-gray-700 dark:text-white border-0 focus:outline-none focus:ring-0 rounded-full">
        @foreach($clubs as $club)
            <option value="{{ route('club-filter', ['club_id' => $club->ratings_central_club_id, 'club_slug' => Str::slug($club->name), 'gender_group' => 'Mixed']) }}" {{ ($club_id ?? 0) == $club->ratings_central_club_id ? 'selected' : '' }}>{{ $club->name }}</option>
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