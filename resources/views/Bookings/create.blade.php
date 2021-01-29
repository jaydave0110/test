@extends('layouts.app')

@section('title', 'Manage Builder Sites') 
@section('description', 'Manage Builder Sites') 

@section('content')
<style type="text/css"> 
    .select3{
        width: 510px;
    }
</style>
<section class="content">
	 @include('layouts.errorMessage')
	{!! Form::open(array(
                            'route' => 'bookings.store', 
                            'method'=>'POST', 
                            'files'=> true
                    )) !!}
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                  	<div class="card card-default">
	                    <div class="card-header">
                       		 <h3 class="card-title">Add Bookings Information</h3>
                             <a href="{{route('bookings.index')}}" style="float: right;" class="btn btn-sm btn-primary">Back To Bookings</a>
                    	 </div>
                     	<div class="card-body">
                     		<h2>Customer Details</h2>
                            <span style="color:red;">* Are the required fields</span>
                     		<hr/>
                        	<div class="row">
                        		<div class="col-md-4">
		                            <div class="form-group">
		                                 <label  >Site </label>
		                                {!! Form::select('site_id',$Sites, null, ['class' => 'form-control select2', 'placeholder' => 'Select Option', 'id' => 'ajaxSelectSites']) !!} 
		                            </div>
		                        </div>
		                        <div class="col-md-4">
		                            <div class="form-group">
		                                 <label  >Property <span style="color:red;">*</span></label>
		                                 {!! Form::select('property',$SiteOfferData, (isset($SiteOfferData->property_id) ? $SiteOfferData->property_id : null), ['class' => 'form-control select2','id'=>'ajaxProperty']) !!}
		                            </div>
		                        </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                         <label  >Site Offers <span style="color:red;">*</span></label>
                                         {!! Form::select('package',$SiteOfferData, (isset($SiteOfferData->package) ? $SiteOfferData->package : null), ['class' => 'form-control select2','id'=>'ajaxOffers']) !!}
                                    </div>
                                </div>
                        	</div>	

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Select Commission</label>
                                    <select name="commission_type" id="commission_type" class="form-control" onchange="checkCommissionType()">
                                        <option value="">Select</option>
                                        <option value="1">Percentage & Amount</option>
                                        <option value="2">Fix Pay - Individual</option>
                                        <option value="3">Fix Pay - Package</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="commission_amount">
                                    <label>Amount ( Commission )</label>
                                    <input type="text" name="commission_amount" class="form-control number" name="">
                                </div>
                            </div>

                            <hr/>
                        	<div class="row">
                        		<div class="col-md-6">
		                            <div class="form-group">
		                                 <label  > Customer Name <span style="color:red;">*</span> </label>
		                                 {!! Form::text('customer_name', (isset($SitesOffers) ?$SitesOffers->customer_name:'' ), ['placeholder' => 'Enter Customer Name','class' => 'form-control']) !!}
		                            </div>
		                        </div>
		                        <div class="col-md-6">
		                            <div class="form-group">
		                                 <label  >Mobile Number</label>
		                                 {!! Form::text('mobile_number', (isset($SitesOffers) ?$SitesOffers->mobile_number:'' ), ['placeholder' => 'Enter Mobile Number','class' => 'form-control']) !!}
		                            </div>
		                        </div>
                        	</div>	
                        	<div class="row">
                        		<div class="col-md-6">
		                            <div class="form-group">
		                                 <label  >Address </label>
		                                 {!! Form::text('address', (isset($SitesOffers) ?$SitesOffers->address:'' ), ['placeholder' => 'Enter Address','class' => 'form-control']) !!}
		                            </div>
		                        </div>
		                        <div class="col-md-6">
		                            <div class="form-group">
		                                 <label  >Email Address</label>
		                                 {!! Form::text('email', (isset($SitesOffers) ?$SitesOffers->email	:'' ), ['placeholder' => 'Enter email','class' => 'form-control']) !!}
		                            </div>
		                        </div>
                        	</div>
                        	<div class="row">
                        		<div class="col-md-6">
		                            <div class="form-group">
		                                 <label >Select Handled By <span style="color:red;">*</span></label>
		                                 <select id="handle_by" name="handle_by" class="form-control ">
		                                 	<option value="">Select</option>
		                                 	<option value="0">Direct</option>
		                                 	<option value="1">Broker</option>
		                                 </select>
		                            </div>
		                        </div>
		                        <div class="col-md-6" id="broker_data" >
		                            <div class="form-group" >
		                                <label>Brokers List </label>
		                                <select name="broker_id" class="form-control select2 select3" id="brokerlist">
		                                </select>
		                            </div>
                                </div>    

                                <div class="col-md-6" id="represent_data">    
                                    <div class="form-group" >
                                        <label>Select Company Representative </label>
                                        <select name="represent_id" class="form-control select2 select3" id="representlist">
                                        </select>
                                    </div>
		                        </div>

		                        
                        	</div>
                        	<div class="row">
                        		 
		                        <div class="col-md-4">
		                            <div class="form-group">
		                                 <label  >Amount <span style="color:red;">*</span></label>
		                                 {!! Form::text('amount', (isset($SitesOffers) ?$SitesOffers->amount	:'' ), ['placeholder' => 'Enter Actual Amount','class' => 'form-control number','id'=>'amount']) !!}
		                            </div>
		                        </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                         <label  >Units</label>
                                         {!! Form::text('units', (isset($SitesOffers) ?$SitesOffers->units  :'' ), ['placeholder' => 'Enter Units','class' => 'form-control']) !!}
                                    </div>
                                </div>
		                        <div class="col-md-4">
                                    <div class="form-group">
                                         <label  >Is discount ? </label><br>
                                         <input type="radio" name="is_discount" value="1" onclick="isdiscount(1)"> Yes 
                                         <input type="radio" name="is_discount" value="0" onclick="isdiscount(0)"> No 
                                    </div>
                                </div>

                        	</div>
                        	<div class="row">
                        		
		                        <div class="col-md-6" >
		                            <div class="form-group" id="show_discount" style="display: none;">
		                                 <label  >Discount Amount</label>
		                                 {!! Form::text('discount_amount', (isset($SitesOffers) ?$SitesOffers->discount_amount:'' ), ['placeholder' => 'Enter Discount Amount','class' => 'form-control number','onblur'=>'updatefinalAmount()','id'=>'discount']) !!}
		                            </div>
		                        </div>
		                        <div class="col-md-6">
		                            <div class="form-group">
		                                 <label  >BHK</label>
		                                 {!! Form::text('bhk', (isset($SitesOffers) ?$SitesOffers->bhk	:'' ), ['placeholder' => 'Enter BHK','class' => 'form-control']) !!}
		                            </div>
		                        </div>
                        	</div>
                        	<div class="row">
                        		<div class="col-md-6">
		                            <div class="form-group">
		                                 <label  >Final Amount <span style="color:red;">*</span></label>
		                                 {!! Form::text('final_amount', (isset($SitesOffers) ?$SitesOffers->final_amount:'' ), ['placeholder' => 'Enter Final Amount','class' => 'form-control number','id'=>'final_amount']) !!}
		                            </div>
		                        </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label  >Booking Status <span style="color:red;">*</span></label>
                                        <select id="booking_status" name="booking_status" class="form-control">
                                            <option value="">Select</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Confirmed</option>
                                         </select>
                                    </div>
                                </div>
                        	</div>
                            <div class="row">
                                
                            </div>
                        	
	                    </div>
				    	<div class="card-footer">
				    		<button type="submit" class="btn btn-primary">
                                <span class="fas fa-save"></span>&nbsp; Submit & Save
                            </button>
                        </div>    
	            	</div>
            	</div>
            </div>
        </div>
        
    {!! Form::close() !!}
    </div>
</section>                    
@include('layouts.loadingPopup')
@endsection 
@section('extra-scripts')
<script >
	$('.number').keypress(function(event) {
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});


    function checkCommissionType()
    {
        var id = $("#commission_type").val();
        
        if(id==3){
            $("#commission_amount").hide();
        } else{
            $("#commission_amount").show();
        }
    }

	function updatefinalAmount()
	{
		var amount = $("#amount").val();
		var discount_amount = $("#discount").val();

		var final_amount = parseFloat(amount)-parseFloat(discount_amount);
		$("#final_amount").val(final_amount);
		alert( final_amount); 
	}

	function isdiscount(id)
	{
		alert(id);
		if(id==1)
		{
			$("#show_discount").show();
		} 
		if(id==0){

			$("#show_discount").hide();	

		}
	}
 


	$(document).on('change', '#ajaxSelectSites', function(e) {
        
        e.preventDefault();

        var stateid = $(this).val();
         
        var ajaxProperty = 'ajaxProperty';

        if (stateid > 0) {
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });


            $.ajax({
                url: '{{route('getPropertiesOfSites')}}',
                method: 'POST',
                data: $(this).serialize() ,
                beforeSend: function(data) {
                
                    $("select#" + ajaxProperty).empty();
                    $("select#" + ajaxProperty).append('<option value="">Please wait...</option>');
                    $("select#" + ajaxProperty).trigger("chosen:updated");
                    
                },
                success: function(xhr, textStatus, jQxhr) {
                    
                    $("select#" + ajaxProperty).empty();
                    
                    // if multiple selection then no need to display default option
                    if (typeof $("select#" + ajaxProperty).attr('multiple') == 'undefined') {
                        $("select#" + ajaxProperty).append('<option value="">Select Option</option>');
                    }
                    
                    $.each(xhr.data, function(key, value) {
                        $("select#" + ajaxProperty).append('<option value="' + value.id + '">' + value.sub_title + '</option>');
                    });

                    $("select#" + ajaxProperty).trigger("chosen:updated");

                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        }
    });

    $(document).on('change', '#ajaxProperty', function(e) {
        
        e.preventDefault();

        var stateid = $(this).val();
         
        var ajaxOffers = 'ajaxOffers';

        if (stateid > 0) {
        	 $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });


            $.ajax({
                url: '{{route('getOffersOfProperty')}}',
                method: 'POST',
                data: $(this).serialize() ,
                beforeSend: function(data) {
                
                    $("select#" + ajaxOffers).empty();
                    $("select#" + ajaxOffers).append('<option value="">Please wait...</option>');
                    $("select#" + ajaxOffers).trigger("chosen:updated");
                    
                },
                success: function(xhr, textStatus, jQxhr) {
                    
                    $("select#" + ajaxOffers).empty();
                    
                    // if multiple selection then no need to display default option
                    if (typeof $("select#" + ajaxOffers).attr('multiple') == 'undefined') {
                        $("select#" + ajaxOffers).append('<option value="">Select Option</option>');
                    }
                    
                    $.each(xhr.data, function(key, value) {
                        $("select#" + ajaxOffers).append('<option value="' + value.id + '">' + value.option_name + '</option>');
                    });

                    $("select#" + ajaxOffers).trigger("chosen:updated");

                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        }
    });


    $("#broker_data").hide();
    $("#represent_data").hide();

    $(document).on('change', '#handle_by', function(e) {
        
        e.preventDefault();

        var stateid = $(this).val();
         
        var brokerlist = 'brokerlist';

        if (stateid > 0) {
        	 $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
              $("#broker_data").show();
             $("#represent_data").hide();
            if(stateid==1){
            $.ajax({
                url: '{{route('getBrokerList')}}',
                method: 'POST',
                data: $(this).serialize() ,
                beforeSend: function(data) {
                
                    $("select#" + brokerlist).empty();
                    $("select#" + brokerlist).append('<option value="">Please wait...</option>');
                    $("select#" + brokerlist).trigger("chosen:updated");
                    
                },
                success: function(xhr, textStatus, jQxhr) {
                    
                    $("select#" + brokerlist).empty();
                    
                    // if multiple selection then no need to display default option
                    if (typeof $("select#" + brokerlist).attr('multiple') == 'undefined') {
                        $("select#" + brokerlist).append('<option value="">Select Option</option>');
                    }
                    
                    $.each(xhr.data, function(key, value) {
                        $("select#" + brokerlist).append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                    $("select#" + brokerlist).trigger("chosen:updated");

                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });

            } 

        } 


        if(stateid==0) {


             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            $("#broker_data").hide();
            $("#represent_data").show();
            var representlist = 'representlist';
            $.ajax({
                url: '{{route('getRepresentativeList')}}',
                method: 'POST',
                data: $(this).serialize() ,
                beforeSend: function(data) {
                
                    $("select#" + representlist).empty();
                    $("select#" + representlist).append('<option value="">Please wait...</option>');
                    $("select#" + representlist).trigger("chosen:updated");
                    
                },
                success: function(xhr, textStatus, jQxhr) {
                    
                    $("select#" + representlist).empty();
                    
                    // if multiple selection then no need to display default option
                    if (typeof $("select#" + representlist).attr('multiple') == 'undefined') {
                        $("select#" + representlist).append('<option value="">Select Option</option>');
                    }
                    
                    $.each(xhr.data, function(key, value) {
                        $("select#" + representlist).append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                    $("select#" + representlist).trigger("chosen:updated");

                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });

        } 
    });

    

    
</script>

@endsection 

 