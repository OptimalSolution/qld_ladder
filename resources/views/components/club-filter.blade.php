@props(['gender_group', 'age_group', 'club_id', 'preferred_route' => null, 'selected_location' => null])

@php
    use Illuminate\Support\Facades\Route;
    $preferred_route = $preferred_route ?? Route::currentRouteName();
@endphp
<div class=" items-center">

    <button id="clubDropdownButton" data-dropdown-toggle="clubListing" class="inline-block px-4 py-2 m-1 text-sm font-medium {{ true ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700" type="button">
        {{  $selected_location ?? 'All Clubs Combined (' . $clubs->count() . ' Clubs)' }}
    </button>

    <!-- Dropdown menu -->
    <div id="clubListing" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 text-left" aria-labelledby="dropdownClubButton">
            <li>
                <a href="{{ route($preferred_route, ['age_group' => $age_group, 'club_id' => 'all', 'club_slug' => 'combined', 'gender_group' => $gender_group]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">All Clubs Combined ({{ $club_groups->count() }} Clubs)</a>
            </li>
            <li class="bg-gray-400 dark:bg-gray-800">
                <hr class="my-1 border-gray-200 dark:border-gray-600">
                <span class="block px-4 py-2 text-blue-500 dark:text-blue-400">Regions</span>
                <hr class="my-1 border-gray-200 dark:border-gray-600">
            </li>
            @foreach($regions as $region)
                <li>
                    <a href="{{ route($preferred_route, ['age_group' => $age_group, 'club_id' => 'region-' . $region->id, 'club_slug' => Str::slug($region->name), 'gender_group' => $gender_group]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $region->name }} ({{ $region->clubs->count() }} Clubs)</a>
                </li>
            @endforeach
            <li class="bg-gray-400 dark:bg-gray-800">
                <hr class="my-1 border-gray-200 dark:border-gray-600">
                <span class="block px-4 py-2 text-blue-500 dark:text-blue-400">Sub Regions</span>
                <hr class="my-1 border-gray-200 dark:border-gray-600">
            </li>
            @foreach($sub_regions as $sub_region)
                <li>
                    <a href="{{ route($preferred_route, ['age_group' => $age_group, 'club_id' => 'sub-region-' . $sub_region->id, 'club_slug' => Str::slug($sub_region->name), 'gender_group' => $gender_group]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $sub_region->name }} ({{ $sub_region->clubs->count() }} Clubs)</a>
                </li>
            @endforeach
            <li class="bg-gray-400 dark:bg-gray-800">
                <hr class="my-1 border-gray-200 dark:border-gray-600">
                <span class="block px-4 py-2 text-blue-500 dark:text-blue-400">Clubs</span>
                <hr class="my-1 border-gray-200 dark:border-gray-600">
            </li>
            @foreach($club_groups as $club)
                <li>
                    <a href="{{ route($preferred_route, ['age_group' => $age_group, 'club_id' => $club->ratings_central_club_id, 'club_slug' => $club ? Str::slug($club->name) : 'unaffiliated', 'gender_group' => $gender_group]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $club->name }} {{ $club->status === 'Inactive' ? '(Inactive)' : '' }}</a>
                </li>
            @endforeach
        </ul>
    </div>

    <button id="copyLinkBtn" class="hidden ml-2 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" title="Copy link to clipboard" alt="Copy link to clipboard">
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