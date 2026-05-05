@extends("backend.layouts.app")

@section("title")
    {{ $segment_title }}
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs>
        <x-backend.breadcrumb-item type="active" icon="fa-solid fa-list">
            {{ $segment_title }}
        </x-backend.breadcrumb-item>
    </x-backend.breadcrumbs>
@endsection

@section("content")
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <x-backend.section-header>
                    {{ $segment_title }}
                    <span class="text-medium-emphasis fw-normal fs-6 ms-2">
                        ({{ $records->total() }} {{ __("total") }})
                    </span>

                    <x-slot name="toolbar">
                        <a href="{{ route("backend.dashboard") }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i>
                            {{ __("Dashboard") }}
                        </a>
                    </x-slot>
                </x-backend.section-header>
            </div>

            <div class="table-responsive">
                @if ($entity === "club")
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col">{{ __("Club") }}</th>
                                <th scope="col">{{ __("City") }}</th>
                                <th scope="col" class="text-end">{{ __("Ratings Central club ID") }}</th>
                                <th scope="col" class="text-end">{{ __("Ladder athletes") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $club)
                                <tr>
                                    <td>{{ $club->name }}</td>
                                    <td>{{ $club->city ?? "—" }}</td>
                                    <td class="text-end">{{ $club->ratings_central_club_id }}</td>
                                    <td class="text-end">{{ $club->athletes_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-medium-emphasis">{{ __("No records.") }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col">{{ __("Name") }}</th>
                                <th scope="col">{{ __("Club") }}</th>
                                <th scope="col">{{ __("Birth date") }}</th>
                                <th scope="col">{{ __("Last played") }}</th>
                                <th scope="col" class="text-end">{{ __("RC ID") }}</th>
                                <th scope="col" class="text-end">{{ __("TTA ID") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $athlete)
                                <tr>
                                    <td>{{ $athlete->name }}</td>
                                    <td>{{ $athlete->club->name ?? "—" }}</td>
                                    <td>
                                        @if ($athlete->birth_date)
                                            {{ \Carbon\Carbon::parse($athlete->birth_date)->format("Y-m-d") }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if ($athlete->last_played)
                                            {{ \Carbon\Carbon::parse($athlete->last_played)->format("Y-m-d") }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-end">{{ $athlete->ratings_central_id }}</td>
                                    <td class="text-end">{{ $athlete->tta_id ?? "—" }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-medium-emphasis">{{ __("No records.") }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>

            @if ($records->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $records->links("pagination::bootstrap-5") }}
                </div>
            @endif
        </div>
    </div>
@endsection
