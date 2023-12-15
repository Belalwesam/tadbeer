@extends('admin.layout.app')

@section('title')
    @lang('nav.helpers')
@endsection

@section('css-vendor')
    <link rel="stylesheet" href="{{ asset('/dashboard/assets/vendor/libs/select2/select2.css') }}" />
@endsection

{{-- main content --}}
@section('content')
    <div class="card">
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
                <h6 class="card-title mb-0">@lang('helpers.filter')</h6>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addHelperModal" tabindex="-1" aria-labelledby="addHelperModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHelperModalLabel">@lang('helpers.add_helper')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="addHelperForm">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">@lang('helpers.name')</label>
                            <input type="text" name="name" placeholder="@lang('helpers.name')" id="name"
                                class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">@lang('helpers.nationality')</label>
                            <select name="nationality" id="nationality" class="form-select">
                                <option value="">@lang('general.please_select')</option>
                            </select>
                        </div>
                    </form>
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
                    });
                    $('#nationality').each(function() {
                        var $this = $(this);
                        $this.wrap('<div class="position-relative"></div>').select2({
                            placeholder: "@lang('general.please_select')",
                            dropdownParent: $this.parent()
                        });
                    });
                }
            })

            // ----- crud operations

            //create new ajax request
            $('body').on('click', '#submit-create-btn', function() {
                let data = {
                    _token: "{!! csrf_token() !!}",
                    name: $('#name').val(),
                }
                let formBtn = $(this) // the button that sends the reuquest (to minipulate ui)

                $.ajax({
                    method: 'POST',
                    url: "{!! route('admin.categories.store') !!}",
                    data: data,
                    beforeSend: function() {
                        formBtn.html(
                            '<span class="spinner-border" role="status" aria-hidden="true"></span>'
                        )
                        formBtn.prop('disabled', true)
                    },
                    success: function(response) {
                        successMessage("@lang('general.create_success')")
                        $('#addCategoryModal').modal('toggle')
                        document.getElementById("addCategoryForm").reset();
                    },
                    error: function(response) {
                        errorMessage("@lang('general.error')")
                        displayErrors(response, false)
                    },
                }).done(function() {
                    formBtn.html("@lang('general.create')")
                    formBtn.prop('disabled', false)
                    $('#addCategoryModal').modal('toggle')
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
