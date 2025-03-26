<div class="gender-groups">
    @foreach($genders as $gender) 
        <a href="{{ route($routeName, ['gender_group' => $gender, 'age_group' => $ageGroup, 'club_id' => $clubId, 'club_slug' => $clubSlug]) }}" 
           class="inline-block px-4 py-2 m-1 text-sm font-medium {{ $gender == $genderGroup ? 'text-blue-700 bg-gray-100 border-blue-700 ring-2 ring-blue-700 dark:bg-gray-700 dark:text-white dark:border-blue-700' : 'text-gray-900 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600' }} rounded-full hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700">
            {{ $gender }}
        </a>
    @endforeach
</div> 