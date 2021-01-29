@extends('layouts.app')

@section('title', 'Direct Payment Information') 
@section('description', 'Direct Payment Information') 

@section('content')

<section class="content">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            	<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Direct Payment Information</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal">
                <div class="card-body">
                	 
                  <div class="form-group row">
                    <label for="inputEmail4" class="col-sm-4 col-form-label">Amount</label>
                    <div class="col-sm-6">
                        <label for="inputEmail4" class="col-form-label">{{$BookingDirectAmount->amount}}</label>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Status</label>
                    <div class="col-sm-6">
                        <label for="inputEmail3" class="col-form-label">@if($BookingDirectAmount->status==1) Paid @endif @if($BookingDirectAmount->status==0) Unpaid   @endif </label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Amount Date</label>
                    <div class="col-sm-6">
                        <label for="inputEmail3" class="col-form-label">{{ date('d/m/Y',strtotime($BookingDirectAmount->amount_date))}}</label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Payment Type</label>
                    <div class="col-sm-6">
                        <label for="inputEmail3" class="col-form-label"> @if($BookingDirectAmount->payment_type==0) Cash   @endif 
                    	@if($BookingDirectAmount->payment_type==1) Cheque @endif @if($BookingDirectAmount->payment_type==2) Online @endif</label>
                    </div>
                  </div>
                  @if($BookingDirectAmount->payment_type==1)
                    <div class="form-group row">
	                    <label for="inputEmail3" class="col-sm-4 col-form-label">Cheque Number</label>
	                    <div class="col-sm-6">
	                        <label for="inputEmail3" class="col-form-label">{{$BookingDirectAmount->cheque_number}}</label>
	                    </div>
                  	</div>
                  	<div class="form-group row">
	                    <label for="inputEmail3" class="col-sm-4 col-form-label">Bank Name</label>
	                    <div class="col-sm-6">
	                        <label for="inputEmail3" class="col-form-label">{{$BookingDirectAmount->bank_name}}</label>
	                    </div>
                  	</div>
                  	<div class="form-group row">
	                    <label for="inputEmail3" class="col-sm-4 col-form-label">Amount Date</label>
	                    <div class="col-sm-6">
	                        <label for="inputEmail3" class="col-form-label">{{ date('d/m/Y',strtotime($BookingDirectAmount->date_of_cheque))}}</label>
	                    </div>
                  	</div>
                  	<div class="form-group row">
	                    <label for="inputEmail3" class="col-sm-4 col-form-label">Cheque Image</label>
	                    <div class="col-sm-6">
	                    	<img src="{{asset('public/images/DirectBooking/chequeImage/'.$BookingDirectAmount->cheque_image)}}" height="200" width="400"> 
	                    </div>
                  	</div>
                  @endif
                  @if($BookingDirectAmount->payment_type==2)
                    <div class="form-group row">
	                    <label for="inputEmail3" class="col-sm-4 col-form-label">Transaction Id</label>
	                    <div class="col-sm-6">
	                        <label for="inputEmail3" class="col-form-label">{{$BookingDirectAmount->transaction_id}}</label>
	                    </div>
                  	</div>
                  	<div class="form-group row">
	                    <label for="inputEmail3" class="col-sm-4 col-form-label">Payment Mode</label>
	                    <div class="col-sm-6">
	                        <label for="inputEmail3" class="col-form-label">{{$BookingDirectAmount->payment_mode}}</label>
	                    </div>
                  	</div>
                  	 
                  	<div class="form-group row">
	                    <label for="inputEmail3" class="col-sm-4 col-form-label">Online Image</label>
	                    <div class="col-sm-6">
	                    	<img src="{{asset('public/images/DirectBooking/OnlineImage/'.$BookingDirectAmount->online_photo)}}" height="200" width="400"> 
	                    </div>
                  	</div>
                  @endif

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <a href="{{route('bookings.payments',['id'=>$BookingDirectAmount->booking_id])}}" type="submit" class="btn btn-info">Goto Booking</a>
                  <!-- <button type="submit" class="btn btn-default float-right">Cancel</button> -->
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
            </div>
    	</div>            
    </div>	
</section>
@endsection
