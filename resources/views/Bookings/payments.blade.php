@extends('layouts.app')

@section('title', 'Manage Booking Payments') 
@section('description', 'Manage Booking Payments') 

@section('content')

<section class="content">
<div class="container-fluid">
          <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fa fa-inr"></i>
              Bookings Payment Information
            </h3>
            <a href="{{route('bookings.index')}}" class="btn btn-sm btn-primary" style="float: right;">BACK TO Bookings</a>
          </div>
          <div class="card-body">
            
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">TA</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">DA</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">CA</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-settings-tab" data-toggle="pill" href="#custom-content-below-settings" role="tab" aria-controls="custom-content-below-settings" aria-selected="false">LA</a>
              </li>
            </ul>
            @include('layouts.errorMessage')
            <div class="tab-content" id="custom-content-below-tabContent">
                <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                	@include('Bookings.totalamount')
                	</div>
              	<div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
	                @include('Bookings.directamount')
              	</div>
              	<div class="tab-pane fade" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                	@include('Bookings.cashamount')
              	</div>
              	<div class="tab-pane fade" id="custom-content-below-settings" role="tabpanel" aria-labelledby="custom-content-below-settings-tab">
	                @include('Bookings.loanamount')
              	</div>
            </div>
          </div>
        </div>   
             
</div>                     		

</section>                    
@include('layouts.loadingPopup')
@endsection 
@section('extra-scripts')
<script src="{{asset('theme/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script>
	$(function () {
  bsCustomFileInput.init();
});

$('#reservationdateOne').datetimepicker({
        format: 'DD/MM/YYYY'
    });
$('#reservationdateTwo').datetimepicker({
        format: 'DD/MM/YYYY'
    });
$('#reservationdateThree').datetimepicker({
        format: 'DD/MM/YYYY'
    });
$('#reservationdatefour').datetimepicker({
        format: 'DD/MM/YYYY'
    });
$('#reservationdatefive').datetimepicker({
        format: 'DD/MM/YYYY'
    });
 
<?php 
	if($BookingTotalDetails==""){
 ?>
	
    $("#Online").hide();
    $("#Cheque").hide();
<?php
} else {
	if($BookingTotalDetails->payment_type==1)
	{ ?>
		 
		$("#Cheque").show();
		$("#Online").hide();	
		<?php
	}
	if($BookingTotalDetails->payment_type==2)
	{	
		?>
		 
		$("#Cheque").hide();
		$("#Online").show();	
		<?php
	}
} 
?>

$("#daOnline").hide();
$("#daCheque").hide();
$("#caOnline").hide();
$("#caCheque").hide();

function cashowRows()
{
	var id =  $("#ca_payment_type").val();
	 

	if(id==0 || id==null)
	{
		$("#caOnline").hide();
		$("#caCheque").hide();
	}	
	if(id==1)
	{
		$("#caOnline").hide();
		$("#caCheque").show();
	}
	if(id==2)
	{
		$("#caOnline").show();
		$("#caCheque").hide();
	}

}


function dashowRows()
{
	var id =  $("#da_payment_type").val();
	 

	if(id==0 || id==null)
	{
		$("#daOnline").hide();
		$("#daCheque").hide();
	}	
	if(id==1)
	{
		$("#daOnline").hide();
		$("#daCheque").show();
	}
	if(id==2)
	{
		$("#daOnline").show();
		$("#daCheque").hide();
	}

}

function showRows(){
	var id =  $("#payment_type").val();
	 

	if(id==0 || id==null)
	{
		$("#Online").hide();
		$("#Cheque").hide();
	}	
	if(id==1)
	{
		$("#Online").hide();
		$("#Cheque").show();
	}
	if(id==2)
	{
		$("#Online").show();
		$("#Cheque").hide();
	}
}

	
</script>
@endsection
