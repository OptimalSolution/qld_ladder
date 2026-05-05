<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Club;
use App\Models\Setting;
use App\Support\DashboardSegments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

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

        /********************
         * Global Stats
         ********************/
        $athletes_count = Athlete::count();

        $junior_athletes = DashboardSegments::globalJuniorAthletesQuery();
        $senior_athletes = DashboardSegments::globalSeniorAthletesQuery();

        $junior_athletes_count = $junior_athletes->count();
        $senior_athletes_count = $senior_athletes->count();

        /********************
         * Ladder Stats
         ********************/

        $ladder_club_count = DashboardSegments::ladderClubsQuery()->count();

        $club_count = Club::count();
        $club_percentage = $club_count > 0 ? round($ladder_club_count / $club_count * 100) : 0;

        $ladder_athletes_count = DashboardSegments::ladderAthletesQuery()->count();

        $ladder_juniors_count = DashboardSegments::ladderJuniorsQuery()->count();

        $ladder_seniors_count = DashboardSegments::ladderSeniorsQuery()->count();

        $registered_ladder_athletes = DashboardSegments::registeredLadderAthletesQuery();
        $registered_ladder_athletes_count = $registered_ladder_athletes->count();

        $ladder_juniors_percentage = $junior_athletes_count > 0 ? round($ladder_juniors_count / $junior_athletes_count * 100) : 0;
        $ladder_seniors_percentage = $senior_athletes_count > 0 ? round($ladder_seniors_count / $senior_athletes_count * 100) : 0;
        $ladder_athletes_percentage = $athletes_count > 0 ? round($ladder_athletes_count / $athletes_count * 100) : 0;
        $registered_ladder_athletes_percentage = $ladder_athletes_count > 0 ? round($registered_ladder_athletes_count / $ladder_athletes_count * 100) : 0;

        /********************
         * Birthday Stats
         ********************/
        $inaccurate_birthdate_count = DashboardSegments::inaccurateBirthdateQuery()->count();

        $inaccurate_birthdate_percentage = $ladder_athletes_count > 0 ? round($inaccurate_birthdate_count / $ladder_athletes_count * 100) : 0;

        /********************
         * Athletes with just 1 event
         ********************/
        $athletes_with_just_1_event_count = DashboardSegments::athletesWithJustOneEventQuery()->count();

        $athletes_with_just_1_event_percentage = $ladder_athletes_count > 0 ? round($athletes_with_just_1_event_count / $ladder_athletes_count * 100) : 0;

        /********************
         * Unchecked Athletes
         ********************/
        $unchecked_athletes = DashboardSegments::uncheckedAthletesQuery()->count();
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
            'athletes_with_just_1_event_count',
            'athletes_with_just_1_event_percentage',
            'unchecked_athletes',
            'unchecked_athletes_percentage'
        ));
    }

    /**
     * Paginated drill-down for a dashboard stat segment.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboardSegment(string $segment)
    {
        $resolved = DashboardSegments::resolve($segment);
        if ($resolved === null) {
            abort(404);
        }

        $builder = $resolved['builder'];

        if ($resolved['entity'] === 'club') {
            $records = $builder
                ->withCount(['athletes' => fn ($q) => $q->recentlyPlayed()])
                ->orderBy('name')
                ->paginate(50)
                ->withQueryString();
        } else {
            $records = $builder
                ->with('club')
                ->orderBy('name')
                ->paginate(50)
                ->withQueryString();
        }

        return view('backend.dashboard.segment', [
            'segment' => $segment,
            'segment_title' => $resolved['title'],
            'entity' => $resolved['entity'],
            'records' => $records,
        ]);
    }

    public function uploadRatings(Request $request)
    {
        $request->validate([
            'ratings_file' => 'required|file|mimes:zip|max:10240', // 10MB max
        ]);

        $file = $request->file('ratings_file');
        Storage::disk('public')->putFileAs('', $file, 'RC_Lists.zip');
        flash('Ratings file uploaded successfully. Processing in progress...')->success()->important();

        // Run the artisan command import:rc-info
        Artisan::call('import:rc-info');

        return redirect()->back();
    }

    public function updateLadder(Request $request)
    {
        Artisan::call('cron:update-ladder');
        flash('Ladder update started...')->success()->important();

        return redirect()->back();
    }
}
