
<div class="row">
    <div class="col-sm-6 col-lg-3">
        <div class="card bg-info mb-4 text-white">
            <div class="card-body">
                <div class="fs-4 fw-semibold">{{ $ladder_club_count }}</div>
                <div>Clubs with ladder athletes</div>
                <div class="progress progress-white progress-thin my-2">
                    <div
                        class="progress-bar"
                        role="progressbar"
                        style="width: 100%"
                        aria-valuenow="100"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
                <small class="text-medium-emphasis-inverse">{{ $club_percentage }}% of {{ $club_count }} clubs</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card bg-primary mb-4 text-white">
            <div class="card-body">
                <div class="fs-4 fw-semibold">{{ $ladder_athletes_count }}</div>
                <div>Ladder Athletes</div>
                <div class="progress progress-white progress-thin my-2">
                    <div
                        class="progress-bar"
                        role="progressbar"
                        style="width: {{ $ladder_athletes_percentage }}%"
                        aria-valuenow="{{ $ladder_athletes_percentage }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
                <small class="text-medium-emphasis-inverse">{{ $ladder_athletes_percentage }}% of {{ $athletes_count }} athletes</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
    
    <div class="col-sm-6 col-lg-3">
        <div class="card bg-primary mb-4 text-white">
            <div class="card-body">
                <div class="fs-4 fw-semibold">{{ $ladder_juniors_count }}</div>
                <div>Ladder Juniors</div>
                <div class="progress progress-white progress-thin my-2">
                    <div
                        class="progress-bar"
                        role="progressbar"
                        style="width: {{ $ladder_juniors_percentage }}%"
                        aria-valuenow="{{ $ladder_juniors_percentage }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
                <small class="text-medium-emphasis-inverse">{{ $ladder_juniors_percentage }}% of {{ $junior_athletes_count }} juniors</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card bg-primary mb-4 text-white">
            <div class="card-body">
                <div class="fs-4 fw-semibold">{{ $ladder_seniors_count }}</div>
                <div>Ladder Seniors</div>
                <div class="progress progress-white progress-thin my-2">
                    <div
                        class="progress-bar"
                        role="progressbar"
                        style="width: {{ $ladder_seniors_percentage }}%"
                        aria-valuenow="{{ $ladder_seniors_percentage }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
                <small class="text-medium-emphasis-inverse">{{ $ladder_seniors_percentage }}% of {{ $senior_athletes_count }} seniors</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
</div>
<!-- /.row-->

<div class="row">
<div class="col-sm-6 col-lg-3">
        <div class="card bg-danger mb-4 text-white">
            <div class="card-body">
                <div class="fs-4 fw-semibold">{{ $inaccurate_birthdate_count }}</div>
                <div>Inaccurate birth dates</div>
                <div class="progress progress-white progress-thin my-2">
                    <div
                        class="progress-bar"
                        role="progressbar"
                        style="width: 100%"
                        aria-valuenow="100"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
                <small class="text-medium-emphasis-inverse">{{ $inaccurate_birthdate_percentage }}% of {{ $ladder_athletes_count }} ladder athletes</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body">
                <div class="fs-4 fw-semibold">Last Ratings Check</div>
                <div>{{ $ratings_last_checked }}</div>
                <div class="progress progress-thin my-2">
                    <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        style="width: 100%"
                        aria-valuenow="100"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
                <small class="text-medium-emphasis">RatingsCentral Ratings</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body">
                <div class="fs-4 fw-semibold">Last Ratings Update</div>
                <div>{{ $ratings_last_updated }}</div>
                <div class="progress progress-thin my-2">
                    <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        style="width: 100%"
                        aria-valuenow="100"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
                <small class="text-medium-emphasis">Ladder Ratings</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body">
                <div class="fs-4 fw-semibold">Full Athlete Import</div>
                <div>{{ $full_ratings_last_updated }}</div>
                <div class="progress progress-thin my-2">
                    <div
                        class="progress-bar bg-info"
                        role="progressbar"
                        style="width: 100%"
                        aria-valuenow="100"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
                <small class="text-medium-emphasis">RatingsCentral Athlete Import</small>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-3">
        <div class="card mb-4"  style="min-height: 136px;">
            <div class="card-body">
                <div class="fs-4 fw-semibold">Process RC Ratings Zip</div>
                <div class="progress progress-thin my-2">
                    <div
                        class="progress-bar bg-info"
                        role="progressbar"
                        style="width: 100%"
                        aria-valuenow="100"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
                <div>
                    <form action="{{ route('backend.upload.ratings') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group input-group-sm mb-2">
                            <input type="file" class="form-control form-control-sm" name="ratings_file" accept=".zip" onchange="toggleUploadButton(this)">
                            <button class="btn btn-xs btn-primary" type="submit" id="uploadButton" disabled>Upload</button>
                        </div>
                    </form>
                    
                    <script>
                        function toggleUploadButton(fileInput) {
                            document.getElementById('uploadButton').disabled = !fileInput.files.length;
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- /.col-->
</div>
<!-- /.row-->



<div class="row" style="display: none;">
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center p-3">
                <div class="bg-primary me-3 p-3 text-white">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-primary">$1.999,50</div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Widget title</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="#">
                    <span class="small fw-semibold">View More</span>
                    <i class="fa-solid fa-circle-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center p-3">
                <div class="bg-info me-3 p-3 text-white">
                    <i class="fa-solid fa-laptop"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-info">$1.999,50</div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Widget title</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="#">
                    <span class="small fw-semibold">View More</span>
                    <i class="fa-solid fa-circle-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center p-3">
                <div class="bg-warning me-3 p-3 text-white">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-warning">$1.999,50</div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Widget title</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="#">
                    <span class="small fw-semibold">View More</span>
                    <i class="fa-solid fa-circle-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-6 col-lg-3">
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center p-3">
                <div class="bg-danger me-3 p-3 text-white">
                    <i class="fa-regular fa-bell"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-danger">$1.999,50</div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Widget title</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2">
                <a class="btn-block text-medium-emphasis d-flex justify-content-between align-items-center" href="#">
                    <span class="small fw-semibold">View More</span>
                    <i class="fa-solid fa-circle-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- /.col-->
</div>
<!-- /.row-->
