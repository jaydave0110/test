@extends('layouts.app')

@section('title', 'Manage Builder Sites') 
@section('description', 'Manage Builder Sites') 

@section('content')

<section class="content">
	 @include('layouts.errorMessage')
	{!! Form::open(array(
                            'route' => 'brokers.store', 
                            'method'=>'POST', 
                            'files'=> true
                    )) !!}
                    @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                  	<div class="card card-default">
	                    <div class="card-header">
                       		 <h3 class="card-title">Add New Broker Information</h3>
                             <a href="{{route('brokers.index')}}" style="float: right;" class="btn btn-sm btn-primary">Back</a>
                    	 </div>
                     	<div class="card-body">
                     		<h2>Broker Details</h2>
                            <span style="color:red;">* Are the required fields</span>
                     		<hr/>
                        	<div class="row">
                        		<div class="col-md-6">
		                            <div class="form-group">
		                                 <label  >Name <span style="color:red;">*</span></label>
		                                 <input type="text" name="name" value="" required class="form-control">
		                            </div>
		                        </div>
		                        <div class="col-md-6">
		                            <div class="form-group">
		                                <label>Mobile Number <span style="color:red;">*</span></label>
                                     <input type="text" name="phone" required class="form-control number" maxlength="10">
		                            </div>
		                        </div>
                                 
                        	</div>	
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                         <label  >Email Address <span style="color:red;">*</span></label>
                                         <input type="text" name="email" value="" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password <span style="color:red;">*</span></label>
                                     <input type="text" name="password" required class="form-control">
                                    </div>
                                </div>
                                 
                            </div> 

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                         <label  > State <span style="color:red;">*</span> </label>
                                        <select name="state" class="form-control">
                                                <option value="">Select State</option>
                                                <option value="5">Gujarat</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label  >City <span style="color:red;">*</span></label>
                                         <select name="city" class="form-control">
                                            <option value="">Select City</option>
                                            <option value="1">Vadodara</option>
                                            <option value="18">Ahmedabad</option>
                                            <option value="19">Surat</option>
                                        </select>
                                    </div>
                                </div>
                                 
                            </div>  

                            <div class="row">
                                <div class="col-md-6">
                                     <div class="form-group">
                                      <label  >Address</label>
                                        <textarea name="address" class="form-control"></textarea> 
                                    </div>
                                </div>
                                
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
    });

    

    
</script>

@endsection 

 