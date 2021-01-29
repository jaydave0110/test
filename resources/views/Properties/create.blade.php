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
  <style type="text/css">
  	.hide { display: none; }
.show { display: block; }
  </style>
@endsection

@section('content')
<section class="content">
    @include('layouts.errorMessage')
	{!! Form::open(array(
                            'route' => 'properties.store', 
                            'method'=>'POST', 
                            'files'=> true
                    )) !!}
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                  	<div class="card card-default">
                         <input type="hidden" name="city_id" value="{{$Sites->city_id}}">   
	                    @include('Properties.informations')
	                    @include('Properties.pricing')
                        <div class="card card-default escalation_block">
                            <div class="card-header">
                                <h3 class="card-title">Escalation </h3>
                            </div>
                            <div class="card-body">
                                <div class="row escalation_block">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label>is Escalation ? <span style="color: red;">*</span></label>
                                        <select name="is_escalation" id="is_escalation" class="form-control">
                                            <option>select </option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row" id="show_escalation">
                                    <div class="col-md-3">
                                        <input type="checkbox" name="is_garden_facing" value="1"><label>&nbsp; Garden Facing</label> 
                                        <input type="text" name="garden_facing_amount" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" name="is_club_house_facing" value="1">
                                        <label>Club House Facing</label> 
                                        <input type="text" name="club_house_facing_amount" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" name="id_road_facing" value="1">
                                        <label>Road Facing</label>
                                        <input type="text" name="road_facing_amount" class="form-control" >
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <input type="checkbox" name="is_croner_flat" value="1">
                                        <label>Corner Flats</label>
                                         <input type="text" name="corner_flat_amount" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="checkbox" name="is_others" value="1">
                                        <label>Others</label>                               
                                        <input type="text" name="other_amount" class="form-control">
                                    </div>
                                        
                                        
                                </div>
                            </div>  
                        </div>

                        <div class="card card-default escalation_floor">
                            <div class="card-header">
                                <h3 class="card-title">Escalation Floors</h3>
                            </div>
                            <div class="card-body">
                                <div class="row escalation_block">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label>select total Floor ? <span style="color: red;">*</span></label>
                                        <select id="total_escalation_floors" name="total_escalation_floors"  class="form-control">
                                            <option>select </option>
                                            <?php $i=0;
                                            for($i=0;$i<=20;$i++){
                                            ?>
                                            <option value="{{$i}}">{{$i}}</option>
                                        <?php } ?>
                                            
                                        </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row" id="show_floor_escalation">
                                    
                                </div>
                            </div>
                        </div>


	                    @include('Properties.features')
	                    @include('Properties.constructions')
	                    <div class="card card-default ">
	                     	<div class="card-header">
	                        	<h3 class="card-title">Photos</h3>
	                     	</div>
	                     	<div class="card-body">
	                     		<div class="file-uploader-container">
				                    <div class="file-upload-inprogress hide">Uploading...</div>
				                    <div class="file-uploader">+ Upload Photos</div>
				                    <input type="file" name="tmp_image[]" multiple="multiple" data-type="properties" class="ajaxQuickImageUpload" />
				                </div>
	                     	</div>	
	                    </div>
	                   	<div class="row" id="propertyImagePreview"></div>
				        @foreach ($photo_type as $key => $val)
				            @if (isset($properties) && isset($properties->propertyImages))
				                @if (count($properties->propertyImages) > 0)
				                    @if (\Helpers::typePhotoAvailable($properties->propertyImages, $key))
				                        <h3 class="block-heading">{{ $val }}</h3>
				                        <hr size="pixels">
				                        <div class="row">
				                            @foreach ($properties->propertyImages as $i => $image)
				                                @if ($image->image_type == $key)
				                                    {!! \Helpers::displayPropertiesImages($image, $i) !!}
				                                @endif
				                            @endforeach
				                        </div>
				                    @endif
				                @endif
				            @endif
				        @endforeach
                        <hr/>
                        <div class="card card-default ">
                           <div class="card-header">
                                                   <h3 class="card-title">Brokerage Type Details</h3>
                                                </div>
                            <div class="card-body" id="commerical-block-none">
                                <div class="row">
                                    <div class="col-md-4">

                                        <div class="form-group">
                                                <label>Fix Pay ?</label>
                                                <select name="brokrage_type" id="brokrage_type" class="form-control" style="width: 100%;">
                                                   <option value="" selected="selected">Select Pay Type</option>
                                                   <option @if (isset($Sites->brokrage_type)) {{ $Sites->brokrage_type == 1 ? 'selected' : '' }} @endif value="1"> Yes  </option>
                                                   <option @if (isset($Sites->brokrage_type)) {{ $Sites->brokrage_type == 0 ? 'selected' : '' }} @endif value="0"> No </option>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="fix_pay_type" >
                                        <div class="form-group" >
                                            <label> Select Applicable To </label>
                                            <select name="fix_pay_type" id="fix_pay_set_type" class="form-control" style="width: 100%;">
                                               <option value="" selected="selected">Select Applicable To</option>
                                               <option @if (isset($Sites->fix_pay_type)) {{ $Sites->fix_pay_type == 1 ? 'selected' : '' }} @endif value="1"> Individual  </option>
                                               <option @if (isset($Sites->fix_pay_type)) {{ $Sites->fix_pay_type == 0 ? 'selected' : '' }} @endif value="0"> Package </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="fix_pay_amount">
                                        <div class="form-group" >
                                            <label> Enter Amount </label>
                                            <input type="text" name="fix_pay_amount" class="form-control" placeholder="Enter Salary/Fix Pay">
                                        </div>
                                    </div>
                                </div>
                                <label>Commission Details</label>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-3"  >
                                        <div class="form-group">
                                                <label>Is Commission  ?</label>
                                                <select name="is_commission" id="is_commission" class="form-control" style="width: 100%;">
                                                   <option value="" selected="selected">Select Commission</option>
                                                   <option @if (isset($Sites->is_commission)) {{ $Sites->is_commission == 1 ? 'selected' : '' }} @endif value="1"> Yes  </option>
                                                   <option @if (isset($Sites->is_commission)) {{ $Sites->is_commission == 0 ? 'selected' : '' }} @endif value="0"> No </option>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="commission_percent">
                                        <div class="form-group">
                                                <label>Percentage(%)</label>
                                                {!! Form::text('commission_percent',  (isset($Sites->commission_percent) ? $Sites->commission_percent : old('commission_percent') ), array('placeholder' => 'Enter Commission Percent','class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="commission_basic_price">
                                        <div class="form-group">
                                                <label>Basic Price  </label>
                                                {!! Form::text('commission_basic_price',  (isset($Sites->commission_basic_price) ? $Sites->commission_basic_price : old('commission_basic_price') ), array('placeholder' => 'Enter Basic Price','class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="commission_amount">
                                        <div class="form-group">
                                                <label>Commission Amount  </label>
                                                {!! Form::text('commission_amount',  (isset($Sites->commission_amount) ? $Sites->commission_amount : old('commission_amount') ), array('placeholder' => 'Enter Commission Amount','class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>    
                            </div> 
                        </div>     

				        <div class="col-md-12">
				        <button type="submit" class="btn btn-primary" style="margin-bottom: 15px;">
                                <span class="fas fa-save"></span>&nbsp; Submit & Save
                            </button>
				    	</div>

	            	</div>
            	</div>
            </div>
        </div>
        
    {!! Form::close() !!}
</section>
@include('layouts.loadingPopup')
@endsection 
@section('extra-scripts')


<script >
$("#total_escalation_floors").change(function(){
    $("#show_floor_escalation").empty();

    var escal = $(this).val();
    var html='';
    for(var i =0;i<=escal;i++)
    {
        
        html += '<div class="col-md-12" style="margin-top:5px;">\<input type="text" name="escalation_floor_price[]" placeholder="Enter '+ getOrdinal(i, 0, 'Ground') +'Floor Price " class="col-md-9 form-control">\</div>';


    }

    $('#show_floor_escalation').append(html);

    
});  
 






    $("#show_escalation").hide();
    $("#is_escalation").change(function(){
        var id = $(this).val();
        if(id==1){
            $("#show_escalation").show();
        } else {
            $("#show_escalation").hide();
        }
    });




            $("#fix_pay_type").hide();
            $("#fix_pay_amount").hide();


            $("#commission_percent").hide();
            $("#commission_basic_price").hide();
            $("#commission_amount").hide();


    $("#fix_pay_set_type").change(function(){

        var id = $(this).val();
         
        if(id==1)
        {
            $("#fix_pay_amount").show();
        } else {
            $("#fix_pay_amount").hide();
        }

    }) ;      

    $("#brokrage_type").change(function(){
        var id = $(this).val();
         
        if(id==1)
        {
            $("#fix_pay_type").show();
             
        }
        if(id==0)
        {
            $("#fix_pay_type").hide();
             
        }
    });

     $("#is_commission").change(function(){
        var id = $(this).val();
         
        if(id==1)
        {
            $("#commission_percent").show();
            $("#commission_basic_price").show();
            $("#commission_amount").show();
        }
        if(id==0)
        {
           $("#commission_percent").hide();
            $("#commission_basic_price").hide();
            $("#commission_amount").hide();
        }
    });




	function getOrdinal(n, c, v) {

        if (typeof c != 'undefined' && n == c) return v;

        var s=["th","st","nd","rd"],
           v=n%100;

        return n+(s[(v-20)%10]||s[v]||s[0]);
    }

	$(document).on('change','#ajaxPropertyCategory',function(){
        if($(this).val() == 2){
            $('.pricing_block').slideUp();
            $('.escalation_block').slideUp();
        }else{
            $('.pricing_block').slideDown();
            $('.escalation_block').slideDown();
        }
    });


	$('*[class*="ptype-"]').addClass('hide').removeClass('show');

    // if property edit page then display input according to property category
    if (typeof $("#ajaxPropertyCategory").val() != 'undefined') {
        if (typeof $("#ajaxPropertySubCategory").val() != 'undefined') {
        	 
            showHidePropertyInput($("#ajaxPropertyCategory").val(), $("#ajaxPropertySubCategory").val());
        } else {
        	alert($("#ajaxPropertyCategory").val());
            showHidePropertyInput($("#ajaxPropertyCategory").val(), 0);
        }

    }
    function showHidePropertyInput(catId, subCat) {
        $('*[class*="ptype-"]').addClass('hide').removeClass('show');
        $('*[class*="escalation_floor"]').addClass('hide').removeClass('show');
         
        if (catId == 1) {
                $('#commerical-block').addClass('show').removeClass('hide');
                $('#commerical-block-none').addClass('show').removeClass('hide');
            if (subCat > 0) {
                if (subCat == 9) {
                    $('*[class*="ptype-residential-housevilla"]').addClass('show').removeClass('hide');
                } else if (subCat == 10) {
                    $('*[class*="ptype-residential-openplots"]').addClass('show').removeClass('hide');
                } else if (subCat == 14) {
                    
                    $('*[class*="ptype-residential-flat"]').addClass('show').removeClass('hide');

                    $('*[class*="escalation_floor"]').addClass('show').removeClass('hide');

                }
                $('*[class*="ptype-residential-all"]').addClass('show').removeClass('hide');
            } else {
                $('*[class*="ptype-residential"]').addClass('show').removeClass('hide');
            }
        } else if (catId == 2) {
                $('#commerical-block').addClass('hide').removeClass('show');
                $('#commerical-block-none').addClass('hide').removeClass('show');
                $('*[class*="ptype-commercial"]').addClass('show').removeClass('hide');

        } else if (catId == 3) {
            
            $('#commerical-block').addClass('show').removeClass('hide');
            $('#commerical-block-none').addClass('show').removeClass('hide');
            $('*[class*="ptype-industrial"]').addClass('show').removeClass('hide');

        } else if (catId == 4) {
            
            $('#commerical-block').addClass('show').removeClass('hide');
            $('#commerical-block-none').addClass('show').removeClass('hide');
            $('*[class*="ptype-land"]').addClass('show').removeClass('hide');

        } else {
            
            $('#commerical-block').addClass('show').removeClass('hide');
            $('#commerical-block-none').addClass('show').removeClass('hide');
            $('*[class*="ptype-"]').addClass('hide').removeClass('show');
        }
    }



    $(document).on('change', 'select#ajaxPropertySubCategory', function(e) {
        showHidePropertyInput($("select#ajaxPropertyCategory").val(), $(this).val());
    });

    $(document).on('change', 'select#ajaxPropertyCategory', function(e) {
        
        e.preventDefault();
        var _this = this;
        var propertyCatId = $(_this).val();
        var ajaxPropertySubCategory = 'ajaxPropertySubCategory';
        

        showHidePropertyInput(propertyCatId, 0);

        if (propertyCatId > 0) {
            $.ajax({
                url: "{{url('/getPropertyType')}}",
                method: 'GET',
                data: $(_this).serialize(),
                beforeSend: function(data) {
                
                    $("select#" + ajaxPropertySubCategory).empty();
                    $("select#" + ajaxPropertySubCategory).append('<option value="">Please wait...</option>');
                    $("select#" + ajaxPropertySubCategory).trigger("chosen:updated");

                },
                success: function(xhr, textStatus, jQxhr) {
                   $("select#" + ajaxPropertySubCategory).empty();
                    
                    // if multiple selection then no need to display default option
                     
                    $("select#" + ajaxPropertySubCategory).append('<option value="">Select Option</option>');
                    $.each(xhr.data, function(key, value) {
                        $("select#" + ajaxPropertySubCategory).append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                    $("select#" + ajaxPropertySubCategory).trigger("chosen:updated");
                  /*  var _select, multiple = '';
                    if (propertyCatId == 2 || propertyCatId == 3) {
                        multiple = 'multiple'; 
                    }
                    _select = '<select name="sub_cat_id" ' + multiple + ' class="form-control cselect" id="ajaxPropertySubCategory">';
                    if (multiple == '') {
                        _select += '<option value="">Select Option</option>';
                    }
                    $.each(xhr.data, function(key, value) {
                        _select += '<option value="' + value.id + '">' + value.name + '</option>';
                    });
                    _select += '</select>';
                    $("#" + $(_parent).attr('id')).html(_select);*/
                    //loadPlugins();
                },
                
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        } else {
            $("select#" + ajaxPropertySubCategory).empty();
            $("select#" + ajaxPropertySubCategory).append('<option value="">Select Option</option>');
            $("select#" + ajaxPropertySubCategory).trigger("chosen:updated");
        }
    });







    $(document).on('change', 'select#ajaxPropertyCategorySingle', function(e) {
        
        e.preventDefault();
        var _this = this;
        var propertyCatId = $(_this).val();
        var ajaxPropertySubCategorySingle = 'ajaxPropertySubCategorySingle';
        var _parent = $('#ajaxPropertySubCategorySingle').parent();

        if (propertyCatId > 0) {
            $.ajax({
                url: '{{url('/public/getPropertyType')}}',
                method: 'GET',
                data: $(_this).serialize(),
                beforeSend: function(data) {
                
                    $("select#" + ajaxPropertySubCategorySingle).empty();
                    $("select#" + ajaxPropertySubCategorySingle).append('<option value="">Please wait...</option>');
                    $("select#" + ajaxPropertySubCategorySingle).trigger("chosen:updated");

                },
                success: function(xhr, textStatus, jQxhr) {
                    $("select#" + ajaxPropertySubCategorySingle).empty();
                    $("select#" + ajaxPropertySubCategorySingle).append('<option value="">Select Option</option>');
                    $.each(xhr.data, function(key, value) {
                        $("select#" + ajaxPropertySubCategorySingle).append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $("select#" + ajaxPropertySubCategorySingle).trigger("chosen:updated");
                    //loadPlugins();
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        } else {
            $("select#" + ajaxPropertySubCategorySingle).empty();
            $("select#" + ajaxPropertySubCategorySingle).append('<option value="">Select Option</option>');
            $("select#" + ajaxPropertySubCategorySingle).trigger("chosen:updated");
        }
    });





	$(document).on('change', 'select#no_of_bedrooms', function() {
        var b = '';
        for (var i = 1; i <= $(this).val(); i++) {
            b += '<h3 class="block-heading">' + getOrdinal(i) + ' Bedroom plan</h3><hr size="pixels">\
                    <div class="row">\
                        <div class="form-group col-6">\
                            <div class="row">\
                                <div class="col-5">\
                                    <label class="form-control-label">Bedroom area:</label>\
                                </div>\
                                <div class="col-7">\
                                    <input class="form-control" name="meta[bedrooms]['+i+'][bedroom_area]" type="text" placeholder="Bedroom area (Sq. Ft.)" />\
                                </div>\
                            </div>\
                        </div>\
                        <div class="form-group col-6">\
                            <div class="row">\
                                <div class="col-5">\
                                    <label class="form-control-label">Balcony Area:</label>\
                                </div>\
                                <div class="col-7">\
                                    <input class="form-control" name="meta[bedrooms]['+i+'][bedroom_balcony]" type="text" placeholder="Attached Balcony area" />\
                                </div>\
                            </div>\
                        </div>\
                        <div class="form-group col-6">\
                            <div class="row">\
                                <div class="col-5">\
                                    <label class="form-control-label">Attached Bathroom area:</label>\
                                </div>\
                                <div class="col-7">\
                                    <input class="form-control" name="meta[bedrooms]['+i+'][bedroom_bathroom]" type="text" placeholder="Attached Bathroom area" />\
                                </div>\
                            </div>\
                        </div>\
                        <div class="form-group col-6">\
                            <div class="row">\
                                <div class="col-5">\
                                    <label class="form-control-label">Dressing space area in attach bathroom:</label>\
                                </div>\
                                <div class="col-7">\
                                    <input class="form-control" name="meta[bedrooms]['+i+'][bedroom_bathroom_dressing_space]" type="text" placeholder="Dressing area" />\
                                </div>\
                            </div>\
                        </div>\
                        <div class="form-group col-6">\
                            <div class="row">\
                                <div class="col-5">\
                                    <label class="form-control-label">Is it Master bedroom ?</label><br \>\
                                </div>\
                                <div class="col-7">\
                                    <input class="form-control" type="radio" name="meta[bedrooms][master_bedroom]" value="1" />Yes\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>';
        }

        $("#bedrooms_details_injector").html(b);
        loadPlugins();
    });

    $(document).on('keyup','.min_area,.sqft_price',function(){
        console.log('asd');
        var CUR_ROW = $(this).closest('.main_row');
        var starting_price = 0;
        if(CUR_ROW.find('.min_area').val() && CUR_ROW.find('.sqft_price').val()){
            var min_area = CUR_ROW.find('.min_area').val().replace(/,/g , '');
            var sqft_price = CUR_ROW.find('.sqft_price').val().replace(/,/g , '');
            starting_price = parseFloat(sqft_price)*parseFloat(min_area);
        }
        CUR_ROW.find('.starting_price').val(starting_price.toFixed(2));
    })  

    $(document).on('change', 'select#total_floors', function() {
        var b = '';
        for (var i = 0; i <= parseInt($(this).val()); i++) {
            b += '<div class="card card-default">\
                        <div class="card-header">\
                           <h3 class="card-title">'+ getOrdinal(i, 0, 'Ground') + ' Floor</h3>\
                        </div>\
                        <div class="card-body">\
                           <div class="row">\
                              <div class="col-md-3">\
                                 <div class="form-group">\
                                    <label>* Minimum Varient In Sq.Ft.:</label>\
                                    <input type="text" class="form-control number min_area" name="meta[floors]['+i+'][min_area]" type="text" placeholder="Smallest varient in Sq.Ft." >\
                                 </div>\
                              </div>\
                              <div class="col-md-3">\
                                 <div class="form-group">\
                                    <label>* Maximum Varient In Sq.Ft.:</label>\
                                    <input type="text" class="form-control number" name="meta[floors]['+i+'][max_area]" type="text" placeholder="Largest varient in Sq.Ft.">\
                                 </div>\
                              </div>\
                              <div class="col-md-3">\
                                 <div class="form-group">\
                                    <label>Total Units:</label>\
                                    <input type="text" class="form-control number" name="meta[floors]['+i+'][total_units]" type="text" placeholder="Total units">\
                                 </div>\
                              </div>\
                              <div class="col-md-3">\
                                 <div class="form-group">\
                                    <label>Booked Units:</label>\
                                    <input type="text" class="form-control number" name="meta[floors]['+i+'][booked]" type="text" placeholder="Booked units">\
                                 </div>\
                              </div>\
                              <div class="col-md-3">\
                                 <div class="form-group">\
                                    <label>Available Units:</label>\
                                    <input type="text" class="form-control number" name="meta[floors]['+i+'][available]" type="text" placeholder="Available units">\
                                 </div>\
                              </div>\
                              <div class="col-md-3">\
                                 <div class="form-group">\
                                    <label>*Price per Sq.Ft.:</label>\
                                    <input type="text" class="form-control number sqft_price" name="meta[floors]['+i+'][price_sq_ft]" type="text" placeholder="Price per Sq.Ft." >\
                                 </div>\
                              </div>\
                              <div class="col-md-3">\
                                 <div class="form-group">\
                                    <label>Starting Price:</label>\
                                    <input type="text" class="form-control number starting_price"  name="meta[floors]['+i+'][price]" type="text" placeholder="Price">\
                                 </div>\
                              </div>\
                               <div class="col-md-3">\
                                 <div class="form-group">\
                                    <label>Escalation Price:</label>\
                                    <input type="text" class="form-control number escalation_price"  name="meta[floors]['+i+'][escalation_price]" type="text" placeholder="Escalation Price">\
                                 </div>\
                              </div>\
                           </div>\
                        </div>\
                     </div>';
        }
        // <div class="form-group col-6">\
        //                     <div class="row">\
        //                         <div class="col-5">\
        //                             <label class="form-control-label">Total Sq.Ft.:</label>\
        //                         </div>\
        //                         <div class="col-7">\
        //                             <input class="form-control number" name="meta[floors]['+i+'][area]" type="text" placeholder="Total Sq.Ft." />\
        //                         </div>\
        //                     </div>\
        //                 </div>\

        $("#floor_details_injector").html(b);
        loadPlugins();
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
                url: '{{route('tempChangeImageType')}}',
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

</script>>
@endsection 