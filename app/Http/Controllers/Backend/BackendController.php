<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Setting;
use App\Models\Club;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BackendController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $rc_zip_last_processed_raw = Setting::get('rc_zip_last_processed');
        $rc_zip_last_processed = $rc_zip_last_processed_raw ? Carbon::parse($rc_zip_last_processed_raw)->format('F jS, h:i A') : '[Never]';

        // Common cutoffs
        $age_minimum = now()->subYears(3)->startOfYear();
        $junior_cutoff_date = now()->startOfYear()->subYears(19);
        
        /********************
         * Global Stats
         ********************/
        $athletes_count = Athlete::count();

        // Junior athletes query
        $junior_athletes = Athlete::whereNotNull('birth_date')->where('birth_date', '!=', '')
            ->where('birth_date', '>=', $junior_cutoff_date->format('Y-m-d'))
            ->where('birth_date', '<=', $age_minimum->format('Y-m-d'));

        // Senior athletes query
        $senior_athletes = Athlete::whereNotNull('birth_date')->where('birth_date', '!=', '')
            ->where('birth_date', '<=', $junior_cutoff_date->format('Y-m-d'));

        $junior_athletes_count = $junior_athletes->count();
        $senior_athletes_count = $senior_athletes->count();

        /********************
         * Ladder Stats
         ********************/
        
        $ladder_club_count = Club::whereHas('athletes', function ($query) {
            $query->recentlyPlayed();
        })->count();

        $club_count = Club::count();
        $club_percentage = round($ladder_club_count / $club_count * 100);

        $ladder_athletes_count = Athlete::recentlyPlayed()->count();
        
        $ladder_juniors_count = Athlete::whereNotNull('birth_date')->where('birth_date', '!=', '')
            ->where('birth_date', '>=', $junior_cutoff_date->format('Y-m-d'))
            ->where('birth_date', '<=', $age_minimum->format('Y-m-d'))
            ->recentlyPlayed()->count();

        $ladder_seniors_count = Athlete::whereNotNull('birth_date')->where('birth_date', '!=', '')
            ->where('birth_date', '<=', $junior_cutoff_date->format('Y-m-d'))
            ->recentlyPlayed()->count();

        $registered_ladder_athletes = Athlete::registeredWithTTA()->recentlyPlayed();
        $registered_ladder_athletes_count = $registered_ladder_athletes->count();


        $ladder_juniors_percentage = $junior_athletes_count > 0 ? round($ladder_juniors_count / $junior_athletes_count * 100) : 0;
        $ladder_seniors_percentage = $senior_athletes_count > 0 ? round($ladder_seniors_count / $senior_athletes_count * 100) : 0;        
        $ladder_athletes_percentage = $athletes_count > 0 ? round($ladder_athletes_count / $athletes_count * 100) : 0;
        $registered_ladder_athletes_percentage = $ladder_athletes_count > 0 ? round($registered_ladder_athletes_count / $ladder_athletes_count * 100) : 0;
        
        /********************
         * Birthday Stats
         ********************/
        $inaccurate_birthdate_count = Athlete::where('birth_date', '')
            ->orWhere('birth_date', '>', $age_minimum->format('Y-m-d'))
            ->recentlyPlayed()
            ->count();

        $inaccurate_birthdate_percentage = $ladder_athletes_count > 0 ? round($inaccurate_birthdate_count / $ladder_athletes_count * 100) : 0;

        /********************
         * Atheles with 2 or more number of recent events
         ********************/
        $athletes_with_2_or_more_events = Athlete::whereHas('eventInfo', function ($query) {
            $query->where('number_of_events', '>=', 2);
        })->count();

        $athletes_with_2_or_more_events_percentage = $ladder_athletes_count > 0 ? round($athletes_with_2_or_more_events / $ladder_athletes_count * 100) : 0;

        /********************
         * Unchecked Athletes
         ********************/
        $unchecked_athletes = Athlete::recentlyPlayed()->whereDoesntHave('eventInfo')->count();
        $unchecked_athletes_percentage = $ladder_athletes_count > 0 ? round($unchecked_athletes / $ladder_athletes_count * 100) : 0;

        return view('backend.index', compact(
            'athletes_count',
            'junior_athletes_count',
            'senior_athletes_count',
            'ladder_athletes_count',
            'ladder_athletes_percentage',
            'ladder_juniors_count',
            'ladder_seniors_count',
            'ladder_juniors_percentage',
            'ladder_seniors_percentage',
            'ladder_club_count',
            'club_count',
            'club_percentage',
            'inaccurate_birthdate_count',
            'inaccurate_birthdate_percentage',
            'rc_zip_last_processed',
            'registered_ladder_athletes_count',
            'registered_ladder_athletes_percentage',
            'athletes_with_2_or_more_events',
            'athletes_with_2_or_more_events_percentage',
            'unchecked_athletes',
            'unchecked_athletes_percentage'
        ));
    }

    public function uploadRatings(Request $request)
    {
        $request->validate([
            'ratings_file' => 'required|file|mimes:zip|max:10240', // 10MB max
        ]);

        $file = $request->file('ratings_file');
        Storage::disk('public')->putFileAs('', $file, 'RC_Lists.zip');
        flash("Ratings file uploaded successfully. Processing in progress...")->success()->important();

        // Run the artisan command import:rc-info
        Artisan::call('import:rc-info');
        return redirect()->back();
    }
}
