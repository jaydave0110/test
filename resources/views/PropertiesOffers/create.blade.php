@extends('layouts.app')

@section('title', 'Manage Builder Sites') 
@section('description', 'Manage Builder Sites') 

@section('content')

<section class="content">
	{!! Form::open(array(
                            'route' => 'properties.store', 
                            'method'=>'POST', 
                            'files'=> true
                    )) !!}
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                  	<div class="card card-default">
	                    <div class="card-header">
                       		 <h3 class="card-title">Add Site Offers Information</h3>
                    	 </div>
                    	 
                     	<div class="card-body">
                        	<div class="row">
                        		<div class="col-md-6">
		                            <div class="form-group">
		                                 <label  >Site </label>
		                                {!! Form::select('site_id',$Sites, null, ['class' => 'form-control select2', 'placeholder' => 'Select Option', 'id' => 'ajaxSelectSites']) !!} 
		                            </div>
		                        </div>
		                        <div class="col-md-6">
		                            <div class="form-group">
		                                 <label  >Property</label>
		                                 {!! Form::select('status',$Properties, (isset($properties->status) ? $properties->status : null), ['class' => 'form-control select2','id'=>'ajaxProperty']) !!}
		                            </div>
		                        </div>
                        	</div>	
                        	<div class="row">
                        		<div class="col-md-6">
		                            <div class="form-group">
		                                 <label  >Total Number Of Option </label>
		                                {!! Form::text('total_options', (isset($SitesOffers) ?$SitesOffers->total_options:'' ), ['placeholder' => 'Enter Total Options.','class' => 'form-control','id'=>'total_options']) !!}
		                            </div>
		                        </div>
		                        <div class="col-md-6">
		                            <div class="form-group">
		                                  
		                                 <button type="button" onclick="Addrows();" style="margin-top:30px; width: 100%;" class="btn btn-sm btn-primary">Add Row</button>
		                            </div>
		                        </div>
                        	</div>
                        	<div id="showRows"></div>	
	                        	<hr/>
	                        	<div class="row">
	                        		<div class="col-md-6">
			                            <div class="form-group">
			                                 <label  >Option Name </label>
			                                 {!! Form::text('option_name', (isset($SitesOffers) ?$SitesOffers->option_name:'' ), ['placeholder' => 'Enter Option Name.','class' => 'form-control']) !!}
			                            </div>
			                        </div>
			                        <div class="col-md-6">
			                            <div class="form-group">
			                                 <label  >Final Price</label>
			                                 {!! Form::text('final_price', (isset($SitesOffers) ?$SitesOffers->final_price:'' ), ['placeholder' => 'Enter Final Price.','class' => 'form-control']) !!}
			                            </div>
			                        </div>
	                        	</div>	
	                        	<div class="row">
	                        		<div class="col-md-6">
			                            <div class="form-group">
			                                 <label  >Govt Subcidy Price </label>
			                                 {!! Form::text('govt_subcidy_price', (isset($SitesOffers) ?$SitesOffers->govt_subcidy_price:'' ), ['placeholder' => 'Enter Govt Subcidy Price.','class' => 'form-control']) !!}
			                            </div>
			                        </div>
			                        <div class="col-md-6">
			                            <div class="form-group">
			                                 <label  >Basic Cost</label>
			                                 {!! Form::text('basic_cost	', (isset($SitesOffers) ?$SitesOffers->basic_cost	:'' ), ['placeholder' => 'Enter Basic Cost','class' => 'form-control']) !!}
			                            </div>
			                        </div>
	                        	</div>
	                        	<div class="row">
	                        		<div class="col-md-6">
			                            <div class="form-group">
			                                 <label  >Regular Cost </label>
			                                 {!! Form::text('reg_cost', (isset($SitesOffers) ?$SitesOffers->reg_cost:'' ), ['placeholder' => 'Enter Regular Cost','class' => 'form-control']) !!}
			                            </div>
			                        </div>
			                        <div class="col-md-6">
			                            <div class="form-group">
			                                 <label  >EMI Cost</label>
			                                 {!! Form::text('emi_cost	', (isset($SitesOffers) ?$SitesOffers->emi_cost	:'' ), ['placeholder' => 'Enter EMI Cost','class' => 'form-control']) !!}
			                            </div>
			                        </div>
	                        	</div>
	                        	<div class="row">
	                        		<div class="col-md-6">
			                            <div class="form-group">
			                                 <label  >Furniture Cost </label>
			                                 {!! Form::text('furniture_cost', (isset($SitesOffers) ?$SitesOffers->furniture_cost:'' ), ['placeholder' => 'Enter Furniture Cost','class' => 'form-control']) !!}
			                            </div>
			                        </div>
			                        <div class="col-md-6">
			                            <div class="form-group">
			                                 <label  >Unit Left</label>
			                                 {!! Form::text('unit_left	', (isset($SitesOffers) ?$SitesOffers->unit_left	:'' ), ['placeholder' => 'Enter Unit Left','class' => 'form-control']) !!}
			                            </div>
			                        </div>
	                        	</div>
	                        	<div class="row">
	                        		<div class="col-md-6">
			                            <div class="form-group">
			                                 <label  >Days Left </label>
			                                 {!! Form::text('home_appliance_cost', (isset($SitesOffers) ?$SitesOffers->home_appliance_cost:'' ), ['placeholder' => 'Enter Home Appliances Cost','class' => 'form-control']) !!}
			                            </div>
			                        </div>
			                        <div class="col-md-6">
			                            <div class="form-group">
			                                 <label  >Interest Subvention</label>
			                                 {!! Form::text('interest_subvention', (isset($SitesOffers) ?$SitesOffers->interest_subvention:'' ), ['placeholder' => 'Enter Unit Left','class' => 'form-control']) !!}
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
                        $("select#" + ajaxProperty).append('<option value="">Select City</option>');
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

    function Addrows(){
    	var total_options = $("#total_options").val();
    	alert(total_options);
    }
</script>

@endsection 

 