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
            <div class="filter-container mt-3">
                <h6 class="card-title mb-3">@lang('helpers.filter')</h6>
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <input type="text" name="search_text" id="search_text" class="form-control search-input-helper"
                            placeholder="@lang('general.search') ...">
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
        </div>
    </div>

    {{-- helpers container --}}

    <div class="row mt-0 g-4 helpers-container" id="helpers-container" style="min-height:250px">

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
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">@lang('general.edit')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="editCategoryForm">
                        <div class="form-group mb-3">
                            <label for="edit_name" class="form-label">@lang('categories.name')</label>
                            <input type="text" name="edit_name" placeholder="@lang('categories.name')" id="edit_name"
                                class="form-control">
                        </div>
                        <input type="hidden" id="edit_id">
                    </form>
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
                        $('#search_nationality').append(output)
                    });
                    $('#nationality').each(function() {
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


            // fill helpers page function (helpers list) and call it after
            function fillHelpers() {
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
                }
                $.ajax({
                    method: "GET",
                    url: "{!! route('admin.helpers.helpers_list') !!}",
                    data: data,
                    success: function(response) {
                        let output = ``
                        response.forEach(helper => {
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

                        $('.helpers-container').append(output)
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

            // the event listener for changing the search values
            $('body').on('input' , '.search-input-helper' , function() {
                $('#helpers-container').html('')
                fillHelpers()
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
                        successMessage("@lang('general.create_success')")
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
                    formBtn.html("@lang('general.create')")
                    formBtn.prop('disabled', false)
                }).fail(function() {
                    formBtn.html("@lang('general.create')")
                    formBtn.prop('disabled', false)
                })
            })

            //populate table when pressing edit admin (from table)
            $('body').on('click', '.edit-btn', function() {
                $('#edit_name').val($(this).data('name'))
                $('#edit_id').val($(this).data('id'))
            })
            //edit ajax request
            $('body').on('click', '#submit-edit-btn', function() {
                let data = {
                    _token: "{!! csrf_token() !!}",
                    name: $('#edit_name').val(),
                    id: $('#edit_id').val(),
                }
                let formBtn = $(this) // the button that sends the reuquest (to minipulate ui)

                $.ajax({
                    method: 'PATCH',
                    url: "{!! route('admin.categories.update') !!}",
                    data: data,
                    beforeSend: function() {
                        formBtn.html(
                            '<span class="spinner-border" role="status" aria-hidden="true"></span>'
                        )
                        formBtn.prop('disabled', true)
                    },
                    success: function(response) {
                        successMessage("@lang('general.edit_success')")
                        $('#editCategoryModal').modal('toggle')
                    },
                    error: function(response) {
                        errorMessage("@lang('general.error')")
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
                            url: "{!! route('admin.categories.delete') !!}",
                            data: data,
                            success: function(response) {
                                successMessage("@lang('general.edit_success')")

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
