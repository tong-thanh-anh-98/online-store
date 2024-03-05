@extends('admin.layouts.app')

@section('content')
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="" method="post" name="productForm" id="productForm">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">								
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title<span class="required">*</span></label>
                                            <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ $product->title }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug<span class="required">*</span></label>
                                            <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $product->slug }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="short_description">Short Description</label>
                                            <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Short Description">{{ $product->short_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">{{ $product->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="shipping_returns">Shipping and Returns </label>
                                            <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="Shipping and Returns">{{ $product->shipping_returns }}</textarea>
                                        </div>
                                    </div>                                     
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>								
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">    
                                        <br><span style="color: blue">Drop files here or click to upload.</span><br><br>                                            
                                    </div>
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="row" id="product-gallery">
                            @if ($productImages->isNotEmpty())
                                @foreach ($productImages as $image)
                                <div class="col-md-3" id="image-row-{{ $image->id }}">
                                    <div class="card">
                                        <input type="hidden" name="image_array[]" value="{{ $image->id }}">
                                        <img src="{{ asset('uploads/product/small/'.$image->image) }}" class="card-img-top" alt="">
                                        <div class="card-body">
                                            <a href="javascript:void(0)" onclick="deleteImage({{ $image->id }})" class="btn btn-sm btn-danger">Delete</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>								
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price<span class="required">*</span></label>
                                            <input type="text" name="price" id="price" class="form-control" placeholder="Price" value="{{ $product->price }}">
                                            <p class="error"></p>	
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price" value="{{ $product->compare_price }}">
                                            <p class="text-muted mt-3">
                                                <span style="color:#FF0000">To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.</span>
                                            </p>	
                                        </div>
                                    </div>                                            
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>								
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)<span class="required">*</span></label>
                                            <input type="text" name="sku" id="sku" class="form-control" placeholder="sku" value="{{ $product->sku }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" value="{{ $product->barcode }}">
                                        </div>
                                    </div>   
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" {{ $product->track_qty == 'Yes' ? 'checked' : '' }}>
                                                <label for="track_qty" class="custom-control-label">Track Quantity<span class="required">*</span></label>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" value="{{ $product->qty }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>                                         
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Related product</h2>
                                <div class="mb-3">
                                    <select multiple class="related-product w-100" name="related_products[]" id="related_products">
                                        @if (!empty($relatedProducts))
                                            @foreach ($relatedProducts as $relatedProduct)
                                                <option selected value="{{ $relatedProduct->id }}">{{ $relatedProduct->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product status<span class="required">*</span></h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select a status</option>
                                        <option {{ ($product->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                        <option {{ ($product->status == 0) ? 'selected' : ''}} value="0">Block</option>
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div> 
                        <div class="card">
                            <div class="card-body">	
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category<span class="required">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option {{ $product->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">Select a Sub Category</option>
                                        @if ($subCategories->isNotEmpty())
                                            @foreach ($subCategories as $subCategory)
                                                <option {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Select a brand</option>
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brand)
                                                <option {{ $product->brand_id == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Featured product<span class="required">*</span></h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="">Select a Featured</option>
                                        <option {{ $product->is_featured == 'Yes' ? 'selected' : '' }} value="Yes">Yes</option>                                              
                                        <option {{ $product->is_featured == 'No' ? 'selected' : '' }} value="No">No</option>
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>                                 
                    </div>
                </div>
                
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('customJs')
<script>
    // get the slug based on the input value of title
    $("#title").change(function() {
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

    // function that handles submitting data from a form
    $("#productForm").submit(function(e) {
        e.preventDefault();
        var formArray = $(this).serializeArray();
        $("button[type='submit']").prop('disable', true);
        $.ajax({
            url: '{{ route('products.update',$product->id) }}',
            type: 'put',
            data: formArray,
            dataType: 'json',
            success: function(response) {
                $("button[type='submit']").prop('disable', false);
                if (response["status"] == true) {
                    $('.error').removeClass('invalid-feedback').html('');
                    $('input[type="text"], select, input[type="number"]').removeClass('is-invalid');

                    window.location.href = "{{ route('products.index') }}";
                } else {
                    var errors = response['errors'];
                    $('.error').removeClass('invalid-feedback').html('');
                    $('input[type="text"], select, input[type="number"]').removeClass('is-invalid');

                    $.each(errors, function(key, value) {
                        $(`#${key}`).addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(value);
                    })
                }
            },
            error: function() {
                console.log("Something went wrong.");
            }
        })
    });

    // create sub_category selection by category_id
    $("#category").change(function(e) {
        e.preventDefault();
        var category_id = $(this).val();
        $.ajax({
            url: '{{ route('product-subcategories.index') }}',
            type: 'get',
            data: {category_id:category_id},
            dataType: 'json',
            success: function(response) {
                $("#sub_category").find("option").not(":first").remove();
                $.each(response["subCategories"], function(key, item) {
                    $("#sub_category").append(`<option value='${item.id}'>${item.name}</option>`)
                });
            },
            error: function() {
                console.log("Something went wrong.");
            }
        })
    });

    // Configure Dropzone for file upload
    Dropzone.autoDiscover = false;
    const dropzone = $("#image").dropzone({
        url: '{{ route('product-images.update') }}',
        maxFiles: 10,
        paramName: 'image',
        params: {
            'product_id': '{{ $product->id }}'
        },
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            var html =  `<div class="col-md-3" id="image-row-${response.image_id}">
                            <div class="card">
                                <input type="hidden" name="image_array[]" value="${response.image_id}">
                                <img src="${response.ImagePath}" class="card-img-top" alt="">
                                <div class="card-body">
                                    <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-sm btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>`;
            $('#product-gallery').append(html);
        },
        // remove uploaded file
        complete: function(file) {
            this.removeFile(file);
        }
    });

    // delete the HTML element with the corresponding id
    function deleteImage(id) {
        $('#image-row-'+id).remove();
        if (confirm('Are you sure want to delete image?')) {
            $.ajax({
                url: '{{ route('product-images.destroy') }}',
                type: "delete",
                data: {id:id},
                success: function (response) {
                    if (response.status == true) {
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    // Select-2
    $('.related-product').select2({
        ajax: {
            url: '{{ route('products.getProducts') }}',
            dataType: 'json',
            tags: true,
            multiple: true,
            minimumInputLength: 3,
            processResults: function (data) {
                return {
                    results: data.tags
                };
            }
        }
    });
</script>
@endsection