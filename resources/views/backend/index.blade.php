@extends("backend.layouts.app")

@section("title")
    @lang("Dashboard")
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs />
@endsection

@section("content")
    <div class="card mb-4">
        <div class="card-body">
            <x-backend.section-header>
                @lang("Admin Dashboard")

                <x-slot name="toolbar">
                    <button
                        class="btn btn-outline-primary mb-1"
                        type="button"
                        data-toggle="tooltip"
                        data-coreui-placement="top"
                        title="Tooltip"
                    >
                        <i class="fa-solid fa-bullhorn"></i>
                    </button>
                </x-slot>
            </x-backend.section-header>

            <!-- Dashboard Content Area -->
            @include("backend.includes.dashboard_cards") 
            <!-- / Dashboard Content Area -->
        </div>
    </div>

    <div class="card mb-4" id="excluded">
        <div class="card-body">
            <x-backend.section-header>
                @lang("Athletes not on the ladder")
                <span class="text-medium-emphasis fw-normal fs-6 ms-2">
                    ({{ $excluded_total }} {{ __("total") }})
                </span>
            </x-backend.section-header>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                {{-- Reason filter buttons --}}
                <div class="d-flex flex-wrap gap-2">
                    <a
                        href="{{ route("backend.dashboard", array_filter(["excluded_search" => $excluded_search])) }}#excluded"
                        class="btn btn-sm {{ $excluded_reason === "" ? "btn-primary" : "btn-outline-secondary" }}"
                    >
                        {{ __("All") }}
                        <span class="badge text-bg-light ms-1">{{ $excluded_total }}</span>
                    </a>
                    @foreach (\App\Support\DashboardSegments::EXCLUSION_REASONS as $reason)
                        @php $reason_count = $excluded_reason_counts[$reason] ?? 0; @endphp
                        @if ($reason_count > 0)
                            <a
                                href="{{ route("backend.dashboard", array_filter(["excluded_search" => $excluded_search, "excluded_reason" => $reason])) }}#excluded"
                                class="btn btn-sm {{ $excluded_reason === $reason ? "btn-primary" : "btn-outline-secondary" }}"
                            >
                                {{ $reason }}
                                <span class="badge text-bg-light ms-1">{{ $reason_count }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>

                {{-- Search --}}
                <form method="GET" action="{{ route("backend.dashboard") }}" class="d-flex gap-2">
                    @if ($excluded_reason !== "")
                        <input type="hidden" name="excluded_reason" value="{{ $excluded_reason }}" />
                    @endif
                    <input
                        type="search"
                        name="excluded_search"
                        value="{{ $excluded_search }}"
                        class="form-control form-control-sm"
                        placeholder="{{ __("Search name or RC ID") }}"
                        style="min-width: 220px;"
                    />
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    @if ($excluded_search !== "")
                        <a href="{{ route("backend.dashboard", array_filter(["excluded_reason" => $excluded_reason])) }}#excluded" class="btn btn-outline-secondary btn-sm">
                            {{ __("Clear") }}
                        </a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">{{ __("Name") }}</th>
                            <th scope="col">{{ __("Reason") }}</th>
                            <th scope="col" class="text-end">{{ __("RC ID") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($excluded_athletes as $athlete)
                            <tr>
                                <td>{{ $athlete->name }}</td>
                                <td>{{ $athlete->reason }}</td>
                                <td class="text-end">
                                    @if (!empty($athlete->ratings_central_id))
                                        <a
                                            href="https://www.ratingscentral.com/Player.php?PlayerID={{ $athlete->ratings_central_id }}"
                                            target="_blank"
                                            rel="noopener"
                                        >
                                            {{ $athlete->ratings_central_id }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-medium-emphasis">{{ __("No records.") }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($excluded_athletes->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $excluded_athletes->links("pagination::bootstrap-5") }}
                </div>
            @endif
        </div>
    </div>
@endsection
