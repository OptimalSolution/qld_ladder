@extends("backend.layouts.app")

@section("title")
    @lang("Ladder exclusions")
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs>
        <x-backend.breadcrumb-item type="active" icon="fa-solid fa-user-slash">
            @lang("Ladder exclusions")
        </x-backend.breadcrumb-item>
    </x-backend.breadcrumbs>
@endsection

@section("content")
    <div class="card mb-4">
        <div class="card-body">
            <x-backend.section-header>
                @lang("Ladder exclusions")
                <span class="text-medium-emphasis fw-normal fs-6 ms-2">
                    ({{ $exclusions->total() }} {{ __("total") }})
                </span>

                <x-slot name="toolbar">
                    <a href="{{ route("backend.dashboard") }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i>
                        {{ __("Dashboard") }}
                    </a>
                </x-slot>
            </x-backend.section-header>

            <p class="text-medium-emphasis">
                {{ __("RatingsCentral player IDs listed here are manually kept off the ladder. Changes take effect immediately.") }}
            </p>

            {{-- Add form --}}
            <form method="POST" action="{{ route("backend.ladder-exclusions.store") }}" class="row g-2 align-items-start mb-4">
                @csrf
                <div class="col-sm-3">
                    <input
                        type="text"
                        name="ratings_central_id"
                        value="{{ old("ratings_central_id") }}"
                        class="form-control @error("ratings_central_id") is-invalid @enderror"
                        placeholder="{{ __("RatingsCentral ID") }}"
                        required
                    />
                    @error("ratings_central_id")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <input
                        type="text"
                        name="note"
                        value="{{ old("note") }}"
                        class="form-control @error("note") is-invalid @enderror"
                        placeholder="{{ __("Note (optional) — why excluded") }}"
                    />
                    @error("note")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-plus"></i>
                        {{ __("Add exclusion") }}
                    </button>
                </div>
            </form>

            {{-- Per-row forms live outside the table (invalid to nest a <form> in <tr>);
                 cells reference them via the HTML5 form="" attribute. --}}
            @foreach ($exclusions as $exclusion)
                <form id="excl-update-{{ $exclusion->id }}" method="POST" action="{{ route("backend.ladder-exclusions.update", $exclusion) }}">
                    @csrf
                    @method("PUT")
                </form>
                <form
                    id="excl-delete-{{ $exclusion->id }}"
                    method="POST"
                    action="{{ route("backend.ladder-exclusions.destroy", $exclusion) }}"
                    onsubmit="return confirm('{{ __("Remove this exclusion?") }}');"
                >
                    @csrf
                    @method("DELETE")
                </form>
            @endforeach

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">{{ __("RC ID") }}</th>
                            <th scope="col">{{ __("Note") }}</th>
                            <th scope="col" class="text-end">{{ __("Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($exclusions as $exclusion)
                            <tr>
                                <td style="max-width: 200px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <input
                                            type="text"
                                            form="excl-update-{{ $exclusion->id }}"
                                            name="ratings_central_id"
                                            value="{{ $exclusion->ratings_central_id }}"
                                            class="form-control form-control-sm"
                                            required
                                        />
                                        <a
                                            href="https://www.ratingscentral.com/Player.php?PlayerID={{ $exclusion->ratings_central_id }}"
                                            target="_blank"
                                            rel="noopener"
                                            title="{{ __("View on RatingsCentral") }}"
                                        >
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        form="excl-update-{{ $exclusion->id }}"
                                        name="note"
                                        value="{{ $exclusion->note }}"
                                        class="form-control form-control-sm"
                                        placeholder="{{ __("—") }}"
                                    />
                                </td>
                                <td class="text-end">
                                    <button type="submit" form="excl-update-{{ $exclusion->id }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        {{ __("Save") }}
                                    </button>
                                    <button type="submit" form="excl-delete-{{ $exclusion->id }}" class="btn btn-outline-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-medium-emphasis">{{ __("No exclusions.") }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($exclusions->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $exclusions->links("pagination::bootstrap-5") }}
                </div>
            @endif
        </div>
    </div>
@endsection
