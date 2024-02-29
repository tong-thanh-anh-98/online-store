@extends('admin.layouts.app')

@section('content')
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="" name="subCategoryForm" id="subCategoryForm">
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Category<span style="color:#FF0000">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option {{ ($subCategory->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name<span style="color:#FF0000">*</span></label>
                                    <input type="text" value="{{ $subCategory->name }}" name="name" id="name" class="form-control" placeholder="Name">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug<span style="color:#FF0000">*</span></label>
                                    <input type="text" readonly value="{{ $subCategory->slug }}" name="slug" id="slug" class="form-control" placeholder="Slug">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Status<span style="color:#FF0000">*</span></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select a status</option>
                                        <option {{ ($subCategory->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                        <option {{ ($subCategory->status == 0) ? 'selected' : ''}} value="0">Block</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>						
                        </div>
                    </div>							
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
         $("#subCategoryForm").submit(function(e) {
            e.preventDefault();
            var element = $('#subCategoryForm');
            $("button[type=submit]").prop('disable', true);

            $.ajax({
                url: '{{ route('sub-categories.update', $subCategory->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disable', false);

                    if (response["status"] == true) {
                        window.location.href = "{{ route('sub-categories.index') }}";

                        $("#category").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#status").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();

                    } else {
                        if (response['notFound'] == true) {
                            window.location.href = "{{ route('sub-categories.index') }}";
                            return false;
                        }
                        var errors = response['errors'];

                        if (errors['category']) {
                            $("#category").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['category']);
                        } else {
                            $("#category").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        }
                        if (errors['name']) {
                            $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        }
                        if (errors['slug']) {
                            $("#slug").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
                        } else {
                            $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        }
                        if (errors['status']) {
                            $("#status").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['status']);
                        } else {
                            $("#status").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        }
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong.");
                }
            })
        });

        // get the slug based on the input value of name
        $("#name").change(function() {
            element = $(this);
            $("button[type=submit]").prop('disable', true);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disable', false);
                    
                    if (response["status"] == true) {
                        $("#slug").val(response["slug"]);
                    }
                }
            });
        });
    </script>
@endsection