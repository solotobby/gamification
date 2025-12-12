@extends('layouts.main.master')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Post New Job</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.career-hub.index') }}">Career Hub</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Post Job</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    <form method="POST" action="{{ route('admin.career-hub.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Job Information -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Job Information</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-8 mb-4">
                        <label class="form-label">Job Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               name="title" value="{{ old('title') }}"
                               placeholder="e.g. Senior Laravel Developer" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label">Job Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                            <option value="">Select type</option>
                            <option value="fulltime" {{ old('type') == 'fulltime' ? 'selected' : '' }}>Full Time</option>
                            <option value="parttime" {{ old('type') == 'parttime' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ old('type') == 'internship' ? 'selected' : '' }}>Internship</option>
                            <option value="gig" {{ old('type') == 'gig' ? 'selected' : '' }}>Gig</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label">Tier <span class="text-danger">*</span></label>
                        <select class="form-select @error('tier') is-invalid @enderror" name="tier" required>
                            <option value="free" {{ old('tier') == 'free' ? 'selected' : '' }}>Free</option>
                            <option value="premium" {{ old('tier') == 'premium' ? 'selected' : '' }}>Premium</option>
                        </select>
                        @error('tier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                               name="location" value="{{ old('location') }}"
                               placeholder="e.g. Lagos, Nigeria" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-4">
                        <label class="form-label d-block">&nbsp;</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remote_allowed"
                                   id="remote_allowed" value="1" {{ old('remote_allowed') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remote_allowed">
                                Remote OK
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label">Job Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  name="description" rows="6" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label">Responsibilities</label>
                        <textarea class="form-control @error('responsibilities') is-invalid @enderror"
                                  name="responsibilities" rows="4">{{ old('responsibilities') }}</textarea>
                        @error('responsibilities')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label">Requirements</label>
                        <textarea class="form-control @error('requirements') is-invalid @enderror"
                                  name="requirements" rows="4">{{ old('requirements') }}</textarea>
                        @error('requirements')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label">Benefits</label>
                        <textarea class="form-control @error('benefits') is-invalid @enderror"
                                  name="benefits" rows="4">{{ old('benefits') }}</textarea>
                        @error('benefits')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Information -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Salary Information</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label">Minimum Salary</label>
                        <input type="number" class="form-control @error('salary_min') is-invalid @enderror"
                               name="salary_min" value="{{ old('salary_min') }}" step="0.01" placeholder="50000">
                        @error('salary_min')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label">Maximum Salary</label>
                        <input type="number" class="form-control @error('salary_max') is-invalid @enderror"
                               name="salary_max" value="{{ old('salary_max') }}" step="0.01" placeholder="100000">
                        @error('salary_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label">Currency</label>
                        <select class="form-select @error('currency') is-invalid @enderror" name="currency">
                            <option value="NGN" {{ old('currency') == 'NGN' ? 'selected' : '' }}>NGN</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                        </select>
                        @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Information -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Company Information</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                               name="company_name" value="{{ old('company_name') }}" required>
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Company Logo</label>
                        <input type="file" class="form-control @error('company_logo') is-invalid @enderror"
                               name="company_logo" accept="image/*">
                        <small class="text-muted">Max 2MB, JPG or PNG</small>
                        @error('company_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label">Company Description</label>
                        <textarea class="form-control @error('company_description') is-invalid @enderror"
                                  name="company_description" rows="4">{{ old('company_description') }}</textarea>
                        @error('company_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label">Company Website</label>
                        <input type="url" class="form-control @error('company_website') is-invalid @enderror"
                               name="company_website" value="{{ old('company_website') }}" placeholder="https://example.com">
                        @error('company_website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Settings -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Additional Settings</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Application Deadline</label>
                        <input type="date" class="form-control @error('expires_at') is-invalid @enderror"
                               name="expires_at" value="{{ old('expires_at') }}" min="{{ date('Y-m-d') }}">
                        <small class="text-muted">Leave empty for no deadline</small>
                        @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="block block-rounded">
            <div class="block-content block-content-full text-end">
                <a href="{{ route('admin.career-hub.index') }}" class="btn btn-alt-secondary me-2">
                    <i class="fa fa-times me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-check me-1"></i> Post Job
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
