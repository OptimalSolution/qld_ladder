<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LadderExclusion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;

class LadderExclusionController extends Controller
{
    /**
     * Changing the exclusion list changes who appears on the ladder, so every mutation
     * flushes the application cache (ladder query cache, guest HTML, dashboard counts).
     */
    private function flushDependentCaches(): void
    {
        Artisan::call('cache:clear');
    }

    public function index()
    {
        $exclusions = LadderExclusion::orderBy('ratings_central_id')->paginate(50);

        return view('backend.ladder-exclusions.index', compact('exclusions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ratings_central_id' => ['required', 'string', 'max:50', Rule::unique('ladder_exclusions', 'ratings_central_id')],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        LadderExclusion::create($data);
        $this->flushDependentCaches();

        flash('Exclusion added.')->success();

        return redirect()->route('backend.ladder-exclusions.index');
    }

    public function update(Request $request, LadderExclusion $ladder_exclusion)
    {
        $data = $request->validate([
            'ratings_central_id' => ['required', 'string', 'max:50', Rule::unique('ladder_exclusions', 'ratings_central_id')->ignore($ladder_exclusion->id)],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $ladder_exclusion->update($data);
        $this->flushDependentCaches();

        flash('Exclusion updated.')->success();

        return redirect()->route('backend.ladder-exclusions.index');
    }

    public function destroy(LadderExclusion $ladder_exclusion)
    {
        $ladder_exclusion->delete();
        $this->flushDependentCaches();

        flash('Exclusion removed.')->success();

        return redirect()->route('backend.ladder-exclusions.index');
    }
}
