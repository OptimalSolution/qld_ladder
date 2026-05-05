<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\DashboardSegments;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardSegmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $user = User::whereId(1)->first();
        $this->actingAs($user);
    }

    public function test_each_dashboard_segment_page_returns_200(): void
    {
        foreach (DashboardSegments::keys() as $key) {
            $response = $this->get(route('backend.dashboard.segment', $key));
            $response->assertStatus(200);
        }
    }

    public function test_unknown_segment_returns_404(): void
    {
        $response = $this->get('/admin/dashboard/segment/not_a_real_segment');
        $response->assertStatus(404);
    }

    public function test_paginator_total_matches_underlying_query_count(): void
    {
        foreach (DashboardSegments::keys() as $key) {
            $expectedTotal = DashboardSegments::resolve($key)['builder']->count();

            $forPage = DashboardSegments::resolve($key);
            $this->assertNotNull($forPage);

            if ($forPage['entity'] === 'club') {
                $paginator = $forPage['builder']
                    ->withCount(['athletes' => fn ($q) => $q->recentlyPlayed()])
                    ->orderBy('name')
                    ->paginate(50);
            } else {
                $paginator = $forPage['builder']
                    ->with('club')
                    ->orderBy('name')
                    ->paginate(50);
            }

            $this->assertSame($expectedTotal, $paginator->total(), "Mismatch for segment: {$key}");
        }
    }
}
