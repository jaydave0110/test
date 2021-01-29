<?php
use Illuminate\Support\Facades\Storage;
?>

@extends('layouts.app')

@section('title', 'Manage Builder Sites') 
@section('description', 'Manage Builder Sites') 

@section('extra-css')
 <link rel="stylesheet" href="{{asset('theme/plugins/bs-stepper/css/bs-stepper.min.css')}}">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{asset('theme/plugins/dropzone/min/dropzone.min.css')}}">
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            	<h1>Advanced Form</h1>
         	</div>
	        <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
	               <li class="breadcrumb-item"><a href="#">Home</a></li>
	               <li class="breadcrumb-item active">Advanced Form</li>
	            </ol>
	        </div>
      </div>
    </div>
   <!-- /.container-fluid -->
</section>

<section class="content">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="card card-default">
                           <div class="card-header">
                              <h3 class="card-title">Add Property Information</h3>
                           </div>
                           <div class="card-body p-0">
                              <div class="bs-stepper">
                                 <div class="bs-stepper-header" role="tablist">
                                    <!-- your steps here -->
                                    <div class="step" data-target="#primary-part">
                                       <button type="button" class="step-trigger" role="tab" aria-controls="primary-part" id="logins-part-trigger">
                                       <span class="bs-stepper-circle">1</span>
                                       <span class="bs-stepper-label">Primary Info</span>
                                       </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#information-part">
                                       <button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
                                       <span class="bs-stepper-circle">2</span>
                                       <span class="bs-stepper-label">Other information</span>
                                       </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#amenities-part">
                                       <button type="button" class="step-trigger" role="tab" aria-controls="amenities-part" id="information-part-trigger">
                                       <span class="bs-stepper-circle">3</span>
                                       <span class="bs-stepper-label">Amenities</span>
                                       </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#specification-part">
                                       <button type="button" class="step-trigger" role="tab" aria-controls="specification-part" id="information-part-trigger">
                                       <span class="bs-stepper-circle">4</span>
                                       <span class="bs-stepper-label">Specifications</span>
                                       </button>
                                    </div>
                                    <!-- <div class="line"></div>
                                    <div class="step" data-target="#offer-part">
                                       <button type="button" class="step-trigger" role="tab" aria-controls="offer-part" id="information-part-trigger">
                                       <span class="bs-stepper-circle">5</span>
                                       <span class="bs-stepper-label">Offer</span>
                                       </button>
                                    </div> -->
                                 </div>
                                 <div class="bs-stepper-content">
                                    <span style="color: red;">* Fields are neccessary</span>
                                 	@include('layouts.errorMessage')
                                 	{!! Form::open(array(
				                            'route' => 'sites.store',
				                            'id' => 'frmAdminBuilderSite', 
				                            'method'=>'POST', 
				                            'files'=> true
				                        )) !!}
                                    <!-- your steps content here 
                                    	First Step -->
                                    @include('sites.step1')
                                    <!-- your steps content here 
                                    	Second Step -->
                                    @include('sites.step2')
                                    <!-- your steps content here 
                                    	Third Step -->
                                    @include('sites.step3')
                                    
                                    <!-- your steps content here 
                                    	Fourth Step -->
                                    @include('sites.step4')
                                    <!-- your steps content here 
                                    	Fifth Step -->
                                    
                                 
                           	    	{!! Form::close() !!}
                                 </div>
                              </div>
                           </div>
                           <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                     </div>
                  </div>
               </div>
            </section>
@include('layouts.loadingPopup')
@endsection 
@section('extra-scripts')
<script src="{{asset('theme/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script src="{{asset('theme/plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
<script src="{{ \URL::asset('js/sites/validation/buildersite.js') }}"></script>
 
<script >



$("#fixpay").hide();
$("#percentage").hide();

 $("#brokrage_type").change(function(){
 	var id = $(this).val();
 	if(id=="")
 	{	$("#fixpay").hide();
 		$("#percentage").hide();
 		//alert("Select Brokrage Please");
 	} else if(id==1) {
 		$("#fixpay").show();
 		$("#percentage").hide();
 	} else if(id==0) {
 		$("#percentage").show();
 		$("#fixpay").hide();
 	}
 });



  $(document).on('change', '#ajaxState', function(e) {
        
        e.preventDefault();

        var stateid = $(this).val();
         
        var ajaxCity = 'ajaxCity';

        if (stateid > 0) {
            $.ajax({
                url: '{{route('getCities')}}',
                method: 'GET',
                data: $(this).serialize() ,
                beforeSend: function(data) {
                
                    $("select#" + ajaxCity).empty();
                    $("select#" + ajaxCity).append('<option value="">Please wait...</option>');
                    $("select#" + ajaxCity).trigger("chosen:updated");
                    
                },
                success: function(xhr, textStatus, jQxhr) {
                    
                    $("select#" + ajaxCity).empty();
                    
                    // if multiple selection then no need to display default option
                   /* if (typeof $("select#" + ajaxCity).attr('multiple') == 'undefined') {
                        $("select#" + ajaxCity).append('<option value="">Select </option>');
                    }
                    */
                    $.each(xhr.data, function(key, value) {
                        $("select#" + ajaxCity).append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                    $("select#" + ajaxArea).trigger("chosen:updated");

                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        }
    });


 $(document).on('change', '#ajaxCity', function(e) {
        
        e.preventDefault();

        var cityId = $(this).val();
        var ajaxArea = 'ajaxArea';

        if (cityId > 0) {
            $.ajax({
                url: '{{route('getAreas')}}',
                method: 'GET',
                data: $(this).serialize(),
                beforeSend: function(data) {
                
                    $("select#" + ajaxArea).empty();
                    $("select#" + ajaxArea).append('<option value="">Please wait...</option>');
                    $("select#" + ajaxArea).trigger("chosen:updated");
                    
                },
                success: function(xhr, textStatus, jQxhr) {
                    
                    $("select#" + ajaxArea).empty();
                    
                    // if multiple selection then no need to display default option
                    /*if (typeof $("select#" + ajaxArea).attr('multiple') == 'undefined') {
                        $("select#" + ajaxArea).append('<option value="">Select </option>');
                    }*/
                    
                    $.each(xhr.data, function(key, value) {
                        $("select#" + ajaxArea).append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                    $("select#" + ajaxArea).trigger("chosen:updated");

                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        }
    });
 

 $("#loanBank").hide();

 $("#loanapproval").change(function(){
 	var id = $(this).val();
 	if(id=="")
 	{
 		alert("Select Loan Approval Please");
 	} else if(id==1) {
 		$("#loanBank").show();
 	} else {
 		$("#loanBank").hide();
 	}
 });



$("#possesion_set").hide();

 $("#possession_status").change(function(){
 	var id = $(this).val();
 	if(id=="")
 	{
 		alert("Select Loan Approval Please");
 	} else if(id==1) {
 		$("#possesion_set").show();
 	} else {
 		$("#possesion_set").hide();
 	}
 });

$("#is_sample_house_available").hide();

 $("#sample_house_available").change(function(){
 	var id = $(this).val();
 	if(id=="")
 	{
 		alert("Select Loan Sample House Available Please");
 	} else if(id==1) {
 		$("#is_sample_house_available").show();
 	} else {
 		$("#is_sample_house_available").hide();
	}
 });

 $(document).on('click', '.deleteSiteImage', function(e) {

        var imageId = $(this).attr('data-imageid');
        
        if (typeof imageId != 'undefined' && imageId > 0) {
            if (confirm('Are you sure you want to delete this image')) {
                $.ajax({
                    url: '/deleteBuilderImage/' + imageId,
                    method: 'GET',
                    data: $(this).serialize(),
                    beforeSend: function(data) {
                        $("#imageContainer" + imageId).css('opacity', '0.4');
                    },
                    success: function(xhr, textStatus, jQxhr) {
                        if (xhr.status == 'success') {
                            $("#imageContainer" + imageId).remove();    
                        } else {
                            alert('problem deleting image');
                        }
                    },
                    error: function( jqXhr, textStatus, errorThrown ){
                        alert('Please refresh the page to continue.');
                    }
                });
            } else {
                return false;
            }
        } else {
            alert('Invalid url please refresh page and try again.');
        }
    });



	 

	 // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  });

   
  // DropzoneJS Demo Code End
</script>

<script>
	
    $(document).on('change', '.ajaxSiteImageUpload', function(e) {
        var type = $(this).data('type');
        var form = new FormData();
        $.each($(this)[0].files, function(i, file) {
            form.append('siteImage[]', file);
        });
        
        if (typeof $(this).data('siteid') != 'undefined') {
            var siteId = $(this).data('siteid');
            form.append('site_id', siteId);
        } else {
            alert('please refresh page and try again');
            return false;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        $.ajax({
            url: "{{route('siteQuickImageUpload')}}",
            data: form,
            type: 'POST',
            contentType: false,
            processData: false,
            beforeSend: function(data) {
                $(".ajaxSiteImageUpload").val('');
                $("#errImageExt").remove();
                $(".file-upload-inprogress").removeClass('hide');
            },
            success: function(xhr, textStatus, jQxhr) {
                $(".file-upload-inprogress").addClass('hide');
                if (xhr.status == 'success') {
                    for (var i = 0; i < xhr.images.length; i++) { 
                        $("#builderSitesImagePreview").prepend(sitesDirectImagePreview(xhr.images[i], siteId));
                    }
                }
                if (xhr.errors != '') {
                    $(".file-upload-inprogress").parent().parent()
                        .prepend('<div class="alert alert-danger" id="errImageExt">' + xhr.errors + '</div>');
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
                alert('Please refresh the page to continue.');
            }
        });
    });

    $(document).on('change', '.ajaxQuickImageUpload', function(e) {
        var type = $(this).data('type');
        var form = new FormData();
        $.each($(this)[0].files, function(i, file) {
            form.append('tempImage[]', file);
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        $.ajax({
            url: "{{route('tempImageUpload')}}",
            data: form,
            type: 'POST',
            contentType: false,
            processData: false,
            beforeSend: function(data) {
                $(".ajaxQuickImageUpload").val('');
                $("#errImageExt").remove();
                $(".file-upload-inprogress").removeClass('hide');
                $('#loadingPopup').modal('show');
            },
            success: function(xhr, textStatus, jQxhr) {
                $('#loadingPopup').modal('hide');
                $(".file-upload-inprogress").addClass('hide');
                if (xhr.status == 'success') {
                    for (var i = 0; i < xhr.images.length; i++) { 

                        if (type == 'buildersites') {
                        	 console.log(xhr.images[i].url);
                            $("#builderSitesImagePreview").prepend(sitesImagePreview(xhr.images[i]));
                        } else if (type == 'properties') {
                            $("#propertyImagePreview").prepend(propertyImagePreview(xhr.images[i]));
                        }
                    }
                }
                if (xhr.errors != '') {
                    $(".file-upload-inprogress").parent().parent()
                        .prepend('<div class="alert alert-danger" id="errImageExt">' + xhr.errors + '</div>');
                }
            },
            error: function( jqXhr, textStatus, errorThrown ){
                alert('Please refresh the page to continue.');
            }
        });
    });

    function sitesImagePreview(imageDetails) {
    	console.log("sitesImagePreview"+imageDetails.url);
        return '<div class="col-4">\
            <div class="card propertyImageCard" id="tmpImgContainer' + imageDetails.id + '" data-id="' + imageDetails.id + '">\
                <input type="hidden" name="temp_images[]" value="' + imageDetails.id + '" />\
                <img class="card-img-top" src="' + imageDetails.url + '" />\
                <div class="card-body">\
                    <div class="dropdown tmpImageDropdown">\
                        <label>\
                            <input type="radio" name="tmpCoverImage" class="updateTempCoverImage" value="' + imageDetails.id + '" /> &nbsp;Make Cover\
                        </label><br \>\
                        <button class="btn btn-sm btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                            Select Image type\
                        </button>\
                        <div class="dropdown-menu">\
                            <button class="dropdown-item" type="button" data-image-type="project_pictures">\
                                Project Pictures</button>\
                            <button class="dropdown-item" type="button" data-image-type="house_pictures">\
                                House Pictures</button>\
                            <button class="dropdown-item" type="button" data-image-type="amenities_pictures">\
                                Amenities Pictures</button>\
                            <button class="dropdown-item" type="button" data-image-type="sequence_diagrams">\
                            Main Plan Diagram</button>\
                        </div>\
                        <button class="btn btn-sm btn-danger propPreviewImgDelete" type="button"><i class="fas fa-trash-alt"></i></button>\
                    </div>\
                </div>\
            </div>\
        </div>';
    }

    function propertyImagePreview(imageDetails) {
    	console.log("propertyImagePreview"+imageDetails.url);
        return '<div class="col-4">\
            <div class="card propertyImageCard" data-id="' + imageDetails.id + '">\
                <input type="hidden" name="temp_images[]" value="' + imageDetails.id + '" />\
                <img class="card-img-top" src="' + imageDetails.url + '" />\
                <div class="card-body">\
                    <div class="dropdown tmpImageDropdown">\
                      <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="propImages" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                        Select Image type\
                      </button>\
                      <div class="dropdown-menu" aria-labelledby="propImages">\
                        <button class="dropdown-item" type="button" data-image-type="layout_diagrams">\
                            Layout Diagram</button>\
                      </div>\
                      <div class="dropdown-menu" aria-labelledby="propImages">\
                        <button class="dropdown-item" type="button" data-image-type="bathroom">\
                            Bathroom</button>\
                      </div>\
                      <div class="dropdown-menu" aria-labelledby="propImages">\
                        <button class="dropdown-item" type="button" data-image-type="bedroom">\
                            Bedroom</button>\
                      </div>\
                      <div class="dropdown-menu" aria-labelledby="propImages">\
                        <button class="dropdown-item" type="button" data-image-type="porch">\
                            Porch or Balcony</button>\
                      </div>\
                      <div class="dropdown-menu" aria-labelledby="propImages">\
                        <button class="dropdown-item" type="button" data-image-type="store_room">\
                            Store room</button>\
                      </div>\
                      <button class="btn btn-danger btn-sm propPreviewImgDelete" type="button">Delete</button>\
                    </div>\
                </div>\
            </div>\
        </div>';
    }

    $(document).on('click', '.propPreviewImgDelete', function(e) {
        var _this = this;
        var imgId = $(this).parents().eq(2).data('id');
        if (imgId > 0) {
            if (confirm('Are you sure you want to delete this image')) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    }
                });

                $.ajax({
                    url: '{{route('tempRemoveImg')}}',
                    method: 'POST',
                    data: 'image_id=' + imgId,
                    beforeSend: function(data) {
                        $('#loadingPopup').modal('show');
                    },
                    success: function(xhr, textStatus, jQxhr) {
                        $('#loadingPopup').modal('hide');
                        $(_this).parents().eq(3).remove();
                    },
                    error: function( jqXhr, textStatus, errorThrown ){
                        alert('Please refresh the page to continue.');
                    }
                });
            }    
        } else {
            alert('Error occured please refresh the page');
            return false;
        }
    });


    function sitesDirectImagePreview(imageDetails, siteId) {
     	

        return '<div class="col-4" id="imageContainer' + imageDetails.id + '">\
            <div class="card propertyImageCard">\
                <img class="card-img-top" src="' + imageDetails.url + '" />\
                <div class="card-body">\
                    <div class="dropdown siteImageDropdown" data-siteid="' + siteId + '" data-imageid="' + imageDetails.id + '">\
                        <label>\
                            <input type="radio" name="coverImage" class="updateCoverImage" data-siteid="' + siteId + '" value="' + imageDetails.id + '" /> &nbsp;Make Cover\
                        </label><br \>\
                        <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="siteImages" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                            Select Image type\
                        </button>\
                        <div class="dropdown-menu" aria-labelledby="siteImages">\
                            <button class="dropdown-item" type="button" data-image-type="project_pictures">\
                                Project Pictures</button>\
                            <button class="dropdown-item" type="button" data-image-type="house_pictures">\
                                House Pictures</button>\
                            <button class="dropdown-item" type="button" data-image-type="amenities_pictures">\
                                Amenities Pictures</button>\
                            <button class="dropdown-item" type="button" data-image-type="sequence_diagrams">\
                            Main Plan Diagram</button>\
                        </div>\
                        <button type="button" title="Delete this image" class="btn btn-sm btn-danger deleteSiteImage" data-imageid="' + imageDetails.id + '"><i class="fas fa-trash-alt"></i>&nbsp; Delete</button>\
                    </div>\
                </div>\
            </div>\
        </div>';
    }

    /* Dropdown Change Type */
    $(document).on('click', '.tmpImageDropdown .dropdown-menu .dropdown-item', function(e) {
        e.preventDefault();
        var _this = this;
        var imgId = $(_this).parents().eq(3).data('id');
        var imageTypeTitle = $(this).html();
        var imageType = $(this).data('image-type');

        if (imgId > 0) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });

            $.ajax({
                url: '{{ route('tempChangeImageType') }}',
                method: 'POST',
                data: 'image_id=' + imgId + '&image_category=' + imageType,
                beforeSend: function(data) {
                    $(_this).parent().prev().html('Wait...');
                },
                success: function(xhr, textStatus, jQxhr) {
                    if (xhr.status == 'success') {
                        $(_this).parent().prev().html(imageTypeTitle);
                    } else {
                        console.log('error occured!');
                    }
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        } else {
            alert('Error occured please refresh the page');
            return false;
        }
    });

     $(document).on('change', '.updateTempCoverImage', function(e) {
        if ($(this).is(":checked")) {

            var _this = this;
            var imgId = $(this).val();
            var imgArray = [];
            
            // send all uploaded image information
            $('#builderSitesImagePreview .propertyImageCard').each(function(i, obj) {
                imgArray[i] = $(this).data('id');
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });

            $.ajax({
                url: '{{ route('tempUpdatesitecover')}}',
                method: 'POST',
                data: '&imgId=' + imgId + '&imageList=' + JSON.stringify(imgArray),
                beforeSend: function(data) {
                    $('#loadingPopup').modal('show');
                    $("#tmpImgContainer" + imgId).css('opacity', '0.4').css('transition', '.5s');
                },
                success: function(xhr, textStatus, jQxhr) {
                    $('#loadingPopup').modal('hide');
                    $("#tmpImgContainer" + imgId).css('opacity', '1').css('transition', '.5s');
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        }
    });

    $(document).on('change', '.updateCoverImage', function(e) {
        if ($(this).is(":checked")) {
            var _this = this;
            var imgId = $(this).val();
            var siteId = $(this).data('siteid');

            $.ajax({
                url: '{{url("/updatesitecover")}}'+'/' + imgId + '/' + siteId,
                method: 'GET',
                beforeSend: function(data) {
                    $("#imageContainer" + imgId).css('opacity', '0.4').css('transition', '.5s');
                },
                success: function(xhr, textStatus, jQxhr) {
                    $("#imageContainer" + imgId).css('opacity', '1').css('transition', '.5s');
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        }
    });

    $(document).on('click', '.siteImageDropdown .dropdown-menu .dropdown-item', function(e) {
        e.preventDefault();
        var _this = this;
        var imgId = $(_this).parents().eq(1).data('imageid');
        var siteId = $(_this).parents().eq(1).data('siteid');
        var imageTypeTitle = $(this).html();
        var imageType = $(this).data('image-type');

        if (imgId > 0) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });

            $.ajax({
                url: '{{route('siteChangeImageType')}}',
                method: 'POST',
                data: 'image_id=' + imgId + '&site_id=' + siteId + '&image_category=' + imageType,
                beforeSend: function(data) {
                    $(_this).parent().prev().html('Wait...');
                },
                success: function(xhr, textStatus, jQxhr) {
                    if (xhr.status == 'success') {
                        $(_this).parent().prev().html(imageTypeTitle);
                    } else {
                        console.log('error occured!');
                    }
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        } else {
            alert('Error occured please refresh the page');
            return false;
        }
    });

    $(function () {
  bsCustomFileInput.init();
});



</script>

@endsection