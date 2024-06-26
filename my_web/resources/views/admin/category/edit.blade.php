@extends('admin.layouts.app')

@section('content')

    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="" method="post" id="updateCategoryForm" name="updateCategoryForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name<span style="color:#FF0000">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $category->name}}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug<span style="color:#FF0000">*</span></label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $category->slug}}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" id="image_id" name="image_id" value="">
                                    <label for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br><span style="color: blue">Drop files here or click to upload.</span><br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="category-gallery">
                                <div class="col-md-3">
                                @if (!empty($category->image))
                                    <div class="card">
                                        <img src="{{ asset('uploads/category/thumbnail/'.$category->image) }}" height="300" width="300">
                                    </div>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status<span style="color:#FF0000">*</span></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select a status</option>
                                        <option {{ ($category->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                        <option {{ ($category->status == 0) ? 'selected' : ''}} value="0">Block</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="showHome">Show on Home<span style="color:#FF0000">*</span></label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option value="">Select display on Home</option>
                                        <option {{ ($category->showHome == 'Yes') ? 'selected' : ''}} value="Yes">Yes</option>
                                        <option {{ ($category->showHome == 'No') ? 'selected' : ''}} value="No">No</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('customJs')
    <script>
        $("#updateCategoryForm").submit(function(e) {
            e.preventDefault();
            var element = $(this);
            $("button[type='submit']").prop('disable', true);

            $.ajax({
                url: '{{ route('categories.update',$category->id ) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type='submit']").prop('disable', false);

                    if (response["status"] == true) {
                        window.location.href = "{{ route('categories.index') }}";
                        $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#status").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                        $("#showHome").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
                    } else {
                        if (response['notFound'] == true) {
                            window.location.href = "{{ route('categories.index') }}";
                        }
                        var errors = response['errors'];

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
                        if (errors['showHome']) {
                            $("#showHome").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['showHome']);
                        } else {
                            $("#showHome").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();
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
            $("button[type='submit']").prop('disable', true);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    $("button[type='submit']").prop('disable', false);
                    if (response["status"] == true) {
                        $("#slug").val(response["slug"]);
                    }
                }
            });
        });

        // Configure Dropzone for file upload
        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: '{{ route('temp-images.create') }}',
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                $("#image_id").val(response.image_id);
            }
        });
    </script>

@endsection