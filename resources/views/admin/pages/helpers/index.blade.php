@extends('admin.layout.app')

@section('title')
    @lang('nav.helpers')
@endsection

@section('css-vendor')
    <link rel="stylesheet" href="{{ asset('/dashboard/assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('/dashboard/assets/vendor/libs/dropzone/dropzone.css') }}" />

    <style>
        .select2-error-class {
            border: 1px solid red;
            box-shadow: none;
        }

        .file-upload-error {
            border-color: red !important;
        }
    </style>
@endsection

{{-- main content --}}
@section('content')
    <div class="card mb-3">
        <div class="card-header border-bottom d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">@lang('nav.helpers')</h5>
            {{-- check if auth user has ability to create  --}}
            @if (auth('admin')->user()->hasAbilityTo('create helpers'))
                <button class="btn btn-primary" data-bs-target="#addHelperModal" data-bs-toggle="modal"><i
                        class="bx bx-plus me-0 me-lg-2"></i><span
                        class="d-none d-lg-inline-block">@lang('helpers.add_helper')</span></button>
            @endif
        </div>
        <div class="card-body">
            <div class="filter-container mt-3 pb-3 border-bottom">
                <h6 class="card-title mb-3">@lang('helpers.filter')</h6>
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <input type="text" name="search_text" id="search_text"
                            class="form-control text-search-input-helper" placeholder="@lang('general.search') ...">
                    </div>
                    <div class="col-12 col-sm-4">
                        <select name="search_nationality" id="search_nationality" class="form-select search-input-helper">
                            <option value="">@lang('helpers.nationality')</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <select name="search_category" id="search_category" class="form-select search-input-helper">
                            <option value="">@lang('helpers.category')</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="fetch-results d-flex justify-content-between px-2 pt-2">
                <div class="total-results-number">
                    <small class="text-light fw-semibold">Total Results : <span id="results-number-placeholder"
                            class="fw-bold text-dark"></span></small>
                </div>
                <div class="results-per-page">
                    <small class="text-light fw-semibold">Total Pages : <span id="total-pages-placeholder"
                            class="fw-bold text-dark"></span></small>
                </div>
            </div>
        </div>
    </div>


    {{-- helpers container --}}
    <div class="row mt-0 g-4 helpers-container" id="helpers-container" style="min-height:250px"></div>

    <div class="custome-pagination-container mt-5">
        <ul class="pagination justify-content-start" id="pagination-list-element">
        </ul>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addHelperModal" tabindex="-1" aria-labelledby="addHelperModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHelperModalLabel">@lang('helpers.add_helper')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div action="" id="addHelperForm">

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">@lang('helpers.name')</label>
                                    <input type="text" name="name" id="name" placeholder="@lang('helpers.name')"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nationality" class="form-label">@lang('helpers.nationality')</label>
                                    <select name="nationalityy" id="nationality" class="form-select">
                                        <option value="">@lang('general.please_select')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="number" class="form-label">@lang('helpers.age')</label>
                                    <input type="number" min="1" name="age" id="age" class="form-control"
                                        placeholder="@lang('helpers.age')">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">@lang('helpers.category')</label>
                                    <select name="category_id" id="category_id" class="form-select">
                                        <option value="">@lang('general.please_select')</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="religion" class="form-label">@lang('helpers.religion')</label>
                                    <select name="religion" id="religion" class="form-select">
                                        <option value="">@lang('general.please_select')</option>
                                        <option value="islam">@lang('helpers.islam')</option>
                                        <option value="christian">@lang('helpers.christian')</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 d-flex align-items-center">
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="featured" id="featured">
                                        <label class="form-check-label" for="featured">@lang('helpers.featured')</label>
                                      </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="video" class="form-label">@lang('helpers.video')</label>
                                    <form action="/upload" class="dropzone needsclick" id="dropzone-basic-video">
                                        <div class="dz-message needsclick">
                                            @lang('general.drag_&_drop')
                                        </div>
                                        <div class="fallback">
                                            <input name="video" id="video" type="file" accept="video/mp4" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="avatar" class="form-label">@lang('helpers.avatar')</label>
                                    <form action="/upload" class="dropzone needsclick" id="dropzone-basic-avatar">
                                        <div class="dz-message needsclick">
                                            @lang('general.drag_&_drop')
                                        </div>
                                        <div class="fallback">
                                            <input name="avatar" id="avatar" type="file" accept="image/*" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="resume" class="form-label">@lang('helpers.resume')</label>
                            <form action="/upload" class="dropzone needsclick" id="dropzone-basic-resume">
                                <div class="dz-message needsclick">
                                    @lang('general.drag_&_drop')
                                </div>
                                <div class="fallback">
                                    <input name="resume" id="resume" type="file" accept=".pdf" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-create-btn" class="btn btn-primary">@lang('general.create')</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('general.cancel')</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editHelperModal" tabindex="-1" aria-labelledby="editHelperModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHelperModalLabel">@lang('helpers.edit_helper')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div action="" id="editHelperForm">

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="edit_name" class="form-label">@lang('helpers.name')</label>
                                    <input type="text" name="edit_name" id="edit_name"
                                        placeholder="@lang('helpers.name')" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="edit_nationality" class="form-label">@lang('helpers.nationality')</label>
                                    <select name="edit_nationalityy" id="edit_nationality" class="form-select">
                                        <option value="">@lang('general.please_select')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="edit_age" class="form-label">@lang('helpers.age')</label>
                                    <input type="number" min="1" name="edit_age" id="edit_age"
                                        class="form-control" placeholder="@lang('helpers.age')">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="edit_category_id" class="form-label">@lang('helpers.category')</label>
                                    <select name="edit_category_id" id="edit_category_id" class="form-select">
                                        <option value="">@lang('general.please_select')</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="video" class="form-label">@lang('helpers.video')</label>
                                    <form action="/upload" class="dropzone needsclick" id="dropzone-basic-video-edit">
                                        <div class="dz-message needsclick">
                                            @lang('general.drag_&_drop')
                                        </div>
                                        <div class="fallback">
                                            <input name="edit_video" id="edit_video" type="file"
                                                accept="video/mp4" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="avatar" class="form-label">@lang('helpers.avatar')</label>
                                    <form action="/upload" class="dropzone needsclick" id="dropzone-basic-avatar-edit">
                                        <div class="dz-message needsclick">
                                            @lang('general.drag_&_drop')
                                        </div>
                                        <div class="fallback">
                                            <input name="edit_avatar" id="edit_avatar" type="file"
                                                accept="image/*" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="resume" class="form-label">@lang('helpers.resume')</label>
                            <form action="/upload" class="dropzone needsclick" id="dropzone-basic-resume-edit">
                                <div class="dz-message needsclick">
                                    @lang('general.drag_&_drop')
                                </div>
                                <div class="fallback">
                                    <input name="edit_resume" id="edit_resume" type="file" accept=".pdf" />
                                </div>
                            </form>
                        </div>
                        <input type="hidden" name="edit_id" id="edit_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-edit-btn" class="btn btn-primary">@lang('general.edit')</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('general.cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script-vendor')
    <script src="{{ asset('/dashboard/assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/block-ui/block-ui.js') }}"></script>
@endsection
@section('script')
    <script>
        $('document').ready(function() {

            // populate countries in the select options and initiate select2
            $.ajax({
                url: "{{ asset('/dashboard/assets/json/countries.json') }}",
                method: "GET",
                success: function(response) {
                    response.forEach(country => {
                        let output = `
                            <option value="${country.name}">${country.name}</option>
                        `
                        $('#nationality').append(output)
                        $('#edit_nationality').append(output)
                        $('#search_nationality').append(output)
                    });
                    $('#nationality').each(function() {
                        var $this = $(this);
                        $this.wrap('<div class="position-relative"></div>').select2({
                            placeholder: "@lang('general.please_select')",
                            dropdownParent: $this.parent()
                        });
                    });
                    $('#edit_nationality').each(function() {
                        var $this = $(this);
                        $this.wrap('<div class="position-relative"></div>').select2({
                            placeholder: "@lang('general.please_select')",
                            dropdownParent: $this.parent()
                        });
                    });
                    $('#search_nationality').each(function() {
                        var $this = $(this);
                        $this.wrap('<div class="position-relative"></div>').select2({
                            placeholder: "@lang('helpers.nationality')",
                            dropdownParent: $this.parent()
                        });
                    });

                }
            })

            function generatePagination(data) {
                let total_results = data.total_results;
                let total_pages = data.total_pages
                let current_page = +data.current_page
                $('#pagination-list-element').html('');
                //fill the results boxes under the search filter box
                $('#results-number-placeholder').html(total_results)
                $('#total-pages-placeholder').html(total_pages)

                //generate the links for 

                if (total_pages > 1 && total_pages < 5) {
                    if (total_pages >= 2) {
                        let output = ``

                        //check current page to add previous chevron or not
                        if (current_page > 1) {
                            output += `
                                <li class="page-item prev">
                                    <a class="page-link" id="pagination-nav" href="javascript:void(0);" data-page = '${current_page - 1}'><i class="tf-icon bx bx-chevrons-left"></i></a>
                                </li>`
                        }
                        for (let i = 1; i <= total_pages; i++) {
                            output += `
                            <li class="page-item ${i === current_page ? 'active' : ''}">
                                <a class="page-link" id="pagination-nav" href="javascript:void(0);" data-page = '${i}'>${i}</a>
                            </li>   
                        `
                        }

                        //check current page to add next chevron or not 
                        if (current_page < total_pages) {
                            output += `
                                <li class="page-item prev">
                                    <a class="page-link" id="pagination-nav" href="javascript:void(0);" data-page = '${current_page + 1}'><i class="tf-icon bx bx-chevrons-right"></i></a>
                                </li>`
                        }
                        $('#pagination-list-element').html(output);
                    }
                } else if (total_pages > 1 && total_pages > 5) {
                    let output = ``
                    //check current page to add previous chevron or not
                    if (current_page > 1) {
                        output += `
                                <li class="page-item first">
                                <a class="page-link"  id="pagination-nav" data-page = '1'  href="javascript:void(0);"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                </li>
                                <li class="page-item prev">
                                    <a class="page-link" id="pagination-nav" href="javascript:void(0);" data-page = '${current_page - 1}'><i class="tf-icon bx bx-chevron-left"></i></a>
                                </li>`
                    }
                    for (let i = current_page; i <= current_page + 4; i++) {

                        if (i > total_pages) {
                            break
                        }
                        if (i === 1) {
                            output += `
                            <li class="page-item ${i === current_page ? 'active' : ''}">
                                <a class="page-link" id="pagination-nav" href="javascript:void(0);" data-page = '${i}'>${i}</a>
                            </li>   
                        `
                        } else {
                            if (i !== current_page + 4) {
                                output += `
                                    <li class="page-item ${i === current_page ? 'active' : ''}">
                                        <a class="page-link" id="pagination-nav" href="javascript:void(0);" data-page = '${i}'>${i}</a>
                                    </li>   
                                `
                            } else {
                                output += `
                                    <li class="page-item">
                                        <a class="page-link" href="javascript:void(0);">...</a>
                                    </li>   
                                `
                            }
                        }
                    }

                    //check current page to add next chevron or not 
                    if (current_page < total_pages) {
                        output += `
                                <li class="page-item prev">
                                    <a class="page-link" id="pagination-nav" href="javascript:void(0);" data-page = '${current_page + 1}'><i class="tf-icon bx bx-chevron-right"></i></a>
                                </li>
                                <li class="page-item first">
                                <a class="page-link"  id="pagination-nav" data-page = '${total_pages}'  href="javascript:void(0);"><i class="tf-icon bx bx-chevrons-right"></i></a>
                                </li>
                                `
                    }
                    $('#pagination-list-element').html(output);
                }
            }

            // fill helpers page function (helpers list) and call it after
            function fillHelpers(page = 1) {
                $('#helpers-container').block({
                    message: '<div class="spinner-border text-primary" role="status"></div>',
                    timeout: 0,
                    css: {
                        backgroundColor: 'transparent',
                        border: '0'
                    },
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: 0.8
                    }
                });

                let data = {
                    text: $('#search_text').val(),
                    category: $('#search_category').val(),
                    nationality: $('#search_nationality').val(),
                    page: page
                }
                $.ajax({
                    method: "GET",
                    url: "{!! route('admin.helpers.helpers_list') !!}",
                    data: data,
                    success: function(response) {
                        generatePagination(response)
                        $('#helpers-container').html('')
                        let output = ``
                        response.helpers.forEach(helper => {
                            output += `
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-body text-center">
                                        ${helper.actions}
                                        <div class="mx-auto mb-3">
                                            <img src="${helper.avatar}" alt="Avatar Image #${helper.id}" class="rounded-circle w-px-100">
                                        </div>
                                        <h5 class="mb-1 card-title">${helper.name}</h5>
                                        <span>${helper.category.name}</span>
                                        <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                                            <a href="javascript:;" class="me-1"><span class="badge bg-label-secondary">${helper.age} @lang('helpers.year')</span></a>
                                            <a href="javascript:;"><span class="badge bg-label-success">${helper.nationality}</span></a>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <a href="${helper.video}" class="btn btn-primary d-flex align-items-center me-3"><i
                                                    class="bx bx-video me-1"></i>@lang('helpers.short_video')</a>
                                            <a href="${helper.resume}" target="_blank" class="btn btn-label-secondary d-flex align-items-center"><i
                                                    class="bx bxs-file-pdf me-1"></i>@lang('helpers.short_resume')</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `
                        })

                        $('.helpers-container').html(output)
                        $('#helpers-container').unblock()
                    },
                    error: function(response) {
                        Swal.fire({
                            title: "Error",
                            text: "@lang('general.error')",
                            icon: "error",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                            buttonsStyling: false,
                        });
                    }
                })
            }
            fillHelpers()

            // run the pagination 
            $('body').on('click', '#pagination-nav', function() {
                let page = $(this).data('page')
                fillHelpers(page);
            })

            // the event listener for changing the search values
            $('body').on('input', '.search-input-helper', function() {
                fillHelpers()
            })
            // this one is for the text input field to avoid data overlapping
            var timeout = null;
            $('body').on('keyup', '.text-search-input-helper', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    fillHelpers()
                }, 700);
            })


            //initialise dropzone.js
            const previewTemplate = `<div class="dz-preview dz-file-preview">
                        <div class="dz-details">
                        <div class="dz-thumbnail">
                            <img data-dz-thumbnail>
                            <span class="dz-nopreview">No preview</span>
                            <div class="dz-success-mark"></div>
                            <div class="dz-error-mark"></div>
                            <div class="dz-error-message"><span data-dz-errormessage></span></div>
                            <div class="progress">
                            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                            </div>
                        </div>
                        <div class="dz-filename" data-dz-name></div>
                        <div class="dz-size" data-dz-size></div>
                        </div>
                        </div>`;
            const myDropzoneVideo = new Dropzone('#dropzone-basic-video', {
                previewTemplate: previewTemplate,
                parallelUploads: 1,
                maxFilesize: 5,
                addRemoveLinks: true,
                maxFiles: 1
            });
            const myDropzoneAvatar = new Dropzone('#dropzone-basic-avatar', {
                previewTemplate: previewTemplate,
                parallelUploads: 1,
                maxFilesize: 5,
                addRemoveLinks: true,
                maxFiles: 1
            });
            const myDropzoneResume = new Dropzone('#dropzone-basic-resume', {
                previewTemplate: previewTemplate,
                parallelUploads: 1,
                maxFilesize: 5,
                addRemoveLinks: true,
                maxFiles: 1
            });
            const myDropzoneVideoEdit = new Dropzone('#dropzone-basic-video-edit', {
                previewTemplate: previewTemplate,
                parallelUploads: 1,
                maxFilesize: 5,
                addRemoveLinks: true,
                maxFiles: 1
            });
            const myDropzoneAvatarEdit = new Dropzone('#dropzone-basic-avatar-edit', {
                previewTemplate: previewTemplate,
                parallelUploads: 1,
                maxFilesize: 5,
                addRemoveLinks: true,
                maxFiles: 1
            });
            const myDropzoneResumeEdit = new Dropzone('#dropzone-basic-resume-edit', {
                previewTemplate: previewTemplate,
                parallelUploads: 1,
                maxFilesize: 5,
                addRemoveLinks: true,
                maxFiles: 1
            });

            let videoFile;
            myDropzoneVideo.on("addedfile", function(file) {
                // Access the selected file here
                videoFile = file;
            });
            let avatarFile;
            myDropzoneAvatar.on("addedfile", function(file) {
                // Access the selected file here
                avatarFile = file;
            });
            let resumeFile;
            myDropzoneResume.on("addedfile", function(file) {
                // Access the selected file here
                resumeFile = file;
            });


            let videoFileEdit;
            myDropzoneVideoEdit.on("addedfile", function(file) {
                // Access the selected file here
                videoFileEdit = file;
            });
            let avatarFileEdit;
            myDropzoneAvatarEdit.on("addedfile", function(file) {
                // Access the selected file here
                avatarFileEdit = file;
            });
            let resumeFileEdit;
            myDropzoneResumeEdit.on("addedfile", function(file) {
                // Access the selected file here
                resumeFileEdit = file;
            });



            // ----- crud operations

            //create new ajax request
            $('body').on('click', '#submit-create-btn', function() {
                //reset the errorr messages before sending the form
                $('.file-upload-error').each(function() {
                    $(this).removeClass('file-upload-error')
                })
                $('.file-upload-error-message').each(function() {
                    $(this).remove()
                })
                let data = new FormData();

                //append to form data
                data.append('name', $('#name').val())
                data.append('religion', $('#religion').val())
                data.append('featured', $('#featured').is(":checked") ? 1 : 0)
                data.append('nationality', $('#nationality').val())
                data.append('age', $('#age').val())
                data.append('category_id', $('#category_id').val())
                data.append('video', videoFile)
                data.append('avatar', avatarFile)
                data.append('resume', resumeFile)
                data.append('_token', "{!! csrf_token() !!}")

                let formBtn = $(this) // the button that sends the reuquest (to minipulate ui)
                $.ajax({
                    method: 'POST',
                    url: "{!! route('admin.helpers.store') !!}",
                    data: data,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        formBtn.html(
                            '<span class="spinner-border" role="status" aria-hidden="true"></span>'
                        )
                        formBtn.prop('disabled', true)
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Good Job",
                            text: "@lang('general.create_success')",
                            icon: "success",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                            buttonsStyling: false,
                        }).then(result => {
                            if (result.value) {
                                fillHelpers()
                            }
                        });
                        $('#addHelperModal').modal('toggle')
                    },
                    error: function(response) {
                        errorMessage("@lang('general.error')")
                        // validation for select2 
                        if ($('#nationality').val() == '') {
                            $('#select2-nationality-container').addClass('select2-error-class')
                        }

                        // display errors in case video , avatar or resume has an error
                        let errors = (response.responseJSON.errors)
                        if (errors.hasOwnProperty('avatar')) {
                            $('#dropzone-basic-avatar').addClass('file-upload-error')
                            let errorElement =
                                `<small class="text-danger file-upload-error-message">${errors.avatar[0]}</small>`
                            $('#dropzone-basic-avatar').after(errorElement)
                        }
                        if (errors.hasOwnProperty('video')) {
                            $('#dropzone-basic-video').addClass('file-upload-error')
                            let errorElement =
                                `<small class="text-danger file-upload-error-message">${errors.video[0]}</small>`
                            $('#dropzone-basic-video').after(errorElement)
                        }
                        if (errors.hasOwnProperty('resume')) {
                            $('#dropzone-basic-resume').addClass('file-upload-error')
                            let errorElement =
                                `<small class="text-danger file-upload-error-message">${errors.resume[0]}</small>`
                            $('#dropzone-basic-resume').after(errorElement)
                        }
                        displayErrors(response, false)
                    },
                }).done(function() {
                    $('#name').val('')
                    $('#nationality').val('').trigger('change')
                    $('#age').val('')
                    $('#category_id').val('')
                    $('#religion').val('')
                    $('#feaatured').prop('checked', false)
                    formBtn.html("@lang('general.create')")
                    formBtn.prop('disabled', false)
                }).fail(function() {
                    formBtn.html("@lang('general.create')")
                    formBtn.prop('disabled', false)
                })
            })

            //populate table when pressing edit admin (from table)
            $('body').on('click', '.edit-btn', function() {
                $('#edit_id').val($(this).data('id'))
                $('#edit_name').val($(this).data('name'))
                $('#edit_age').val($(this).data('age'))
                $('#edit_category_id').val($(this).data('category'))
                $('#edit_nationality').val($(this).data('nationality')).trigger('change')
                $('.file-upload-error').each(function() {
                    $(this).removeClass('file-upload-error')
                })
                $('.file-upload-error-message').each(function() {
                    $(this).remove()
                })
                myDropzoneVideoEdit.removeAllFiles()
                myDropzoneAvatarEdit.removeAllFiles()
                myDropzoneResumeEdit.removeAllFiles()
                videoFileEdit = null
                videoAvatarEdit = null
                videoResumeEdit = null
            })
            //edit ajax request
            $('body').on('click', '#submit-edit-btn', function() {
                //reset the errorr messages before sending the form
                $('.file-upload-error').each(function() {
                    $(this).removeClass('file-upload-error')
                })
                $('.file-upload-error-message').each(function() {
                    $(this).remove()
                })
                let data = new FormData();
                //append to form data
                data.append('name', $('#edit_name').val())
                data.append('id', $('#edit_id').val())
                data.append('nationality', $('#edit_nationality').val())
                data.append('age', $('#edit_age').val())
                data.append('category_id', $('#edit_category_id').val())
                data.append('_token', '{!! csrf_token() !!}')
                data.append('_method', 'PATCH')
                // check if there are files sent for this request
                if (videoFileEdit) {
                    data.append('video', videoFileEdit)
                }

                if (avatarFileEdit) {
                    data.append('avatar', avatarFileEdit)
                }

                if (resumeFileEdit) {
                    data.append('resume', resumeFileEdit)
                }
                // console.log(data)
                // return
                let formBtn = $(this) // the button that sends the reuquest (to minipulate ui)

                $.ajax({
                    method: 'POST',
                    url: "{!! route('admin.helpers.update') !!}",
                    processData: false,
                    contentType: false,
                    data: data,
                    beforeSend: function() {
                        formBtn.html(
                            '<span class="spinner-border" role="status" aria-hidden="true"></span>'
                        )
                        formBtn.prop('disabled', true)
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Good Job",
                            text: "@lang('general.edit_success')",
                            icon: "success",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                            buttonsStyling: false,
                        }).then(result => {
                            if (result.value) {
                                fillHelpers()
                            }
                        });
                        $('#editHelperModal').modal('toggle')
                    },
                    error: function(response) {
                        if ($('#edit_nationality').val() == '') {
                            $('#select2-edit_nationality-container').addClass(
                                'select2-error-class')
                        }

                        // display errors in case video , avatar or resume has an error
                        let errors = (response.responseJSON.errors)
                        if (errors.hasOwnProperty('avatar')) {
                            $('#dropzone-basic-avatar-edit').addClass('file-upload-error')
                            let errorElement =
                                `<small class="text-danger file-upload-error-message">${errors.avatar[0]}</small>`
                            $('#dropzone-basic-avatar-edit').after(errorElement)
                        }
                        if (errors.hasOwnProperty('video')) {
                            $('#dropzone-basic-video-edit').addClass('file-upload-error')
                            let errorElement =
                                `<small class="text-danger file-upload-error-message">${errors.video[0]}</small>`
                            $('#dropzone-basic-video-edit').after(errorElement)
                        }
                        if (errors.hasOwnProperty('resume')) {
                            $('#dropzone-basic-resume-edit').addClass('file-upload-error')
                            let errorElement =
                                `<small class="text-danger file-upload-error-message">${errors.resume[0]}</small>`
                            $('#dropzone-basic-resume-edit').after(errorElement)
                        }
                        displayErrors(response, true)
                    },
                }).done(function() {
                    formBtn.html("@lang('general.edit')")
                    formBtn.prop('disabled', false)
                }).fail(function() {
                    formBtn.html("@lang('general.edit')")
                    formBtn.prop('disabled', false)
                })
            })

            //delete btn (from table)
            $('body').on('click', '.delete-btn', function() {
                let id = $(this).data('id')
                Swal.fire({
                    title: "@lang('general.confirmation')",
                    text: " @lang('general.cant_revert')",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: "@lang('general.cancel')",
                    confirmButtonText: "@lang('general.delete')",
                    customClass: {
                        confirmButton: 'btn btn-danger me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        //ajax delete call
                        let data = {
                            _token: "{!! csrf_token() !!}",
                            id: id,
                        }
                        $.ajax({
                            method: 'DELETE',
                            url: "{!! route('admin.helpers.delete') !!}",
                            data: data,
                            success: function(response) {
                                Swal.fire({
                                    title: "Good Job",
                                    text: "@lang('general.delete_success')",
                                    icon: "success",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                    buttonsStyling: false,
                                }).then(result => {
                                    if (result.value) {
                                        fillHelpers()
                                    }
                                });
                            },
                            error: function(response) {
                                errorMessage("@lang('general.error')")
                            },
                        })
                    }
                });
            })
        })
    </script>
@endsection
