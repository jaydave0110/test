<div class="col-md-12">
                  	
                  	{!! Form::open(array(
                            'route' => 'storetotalamount.store', 
                            'method'=>'POST', 
                            'files'=> true
                    )) !!}
                    <input type="hidden" name="booking_id" value="{{$bookingid}}">
                  	<div class="row">
		                <div class="col-md-6">
			                <div class="form-group">
			                   <label>Amount</label>
			                   <input name="amount" value="{{isset($BookingTotalDetails->amount)? $BookingTotalDetails->amount:''}}" type="text" name="" class="form-control">
			                </div>
			            </div>
                   		<div class="col-md-6">
			                <div class="form-group">
			                	<label>Date </label>
				                 <div class="input-group date" id="reservationdateOne" data-target-input="nearest">
			                        <input name="amount_date" value="{{isset($BookingTotalDetails->amount_date)? 
			                        date('d/m/Y',strtotime($BookingTotalDetails->amount_date)):''}}"  type="text" class="form-control datetimepicker-input" data-target="#reservationdateOne"/>
			                        <div class="input-group-append" data-target="#reservationdateOne" data-toggle="datetimepicker">
			                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
			                        </div>
	                    		</div>
			                </div>
	              		</div>
	              	</div>
	              	<div class="row">	
              			<div class="col-md-6">
              				<div class="form-group">
			                  <label>Payment type</label>
			                  <select name="payment_type" id="payment_type" class="form-control" onchange="showRows();" style="width: 100%;">
			                    <option value="" selected="selected">Select</option>
			                    <option @if (isset($BookingTotalDetails->payment_type)) {{ $BookingTotalDetails->payment_type == 0 ? 'selected' : '' }} @endif  value="0">Cash</option>
			                    <option @if (isset($BookingTotalDetails->payment_type)) {{ $BookingTotalDetails->payment_type == 1 ? 'selected' : '' }} @endif  value="1">Cheque</option>
			                    <option @if (isset($BookingTotalDetails->payment_type)) {{ $BookingTotalDetails->payment_type == 2 ? 'selected' : '' }} @endif  value="2">Online</option>
			                   </select>
			                </div>
             			</div>	
             			<div class="col-md-6">
             				<div class="form-group">
			                  <label>Status</label>
			                  <select name="status" id="status" class="form-control" onchange="showRows();" style="width: 100%;">
			                    <option  value="" selected="selected">Select</option>
			                    <option @if (isset($BookingTotalDetails->status)) {{ $BookingTotalDetails->status == 0 ? 'selected' : '' }} @endif  value="0">Unpaid</option>
			                    <option @if (isset($BookingTotalDetails->status)) {{ $BookingTotalDetails->status == 1 ? 'selected' : '' }} @endif value="1">Paid</option>
			                   </select>
			                </div>
             			</div>
             		</div>
             		<div class="row" id="Cheque" @if (isset($BookingTotalDetails->payment_type)) {{ $BookingTotalDetails->payment_type == 1 ? '' : '' }} @endif>
             			<div class="col-md-6">
			                <div class="form-group">
			                   <label>Cheque Number</label>
			                   <input type="text" value="{{isset($BookingTotalDetails->cheque_number)? $BookingTotalDetails->cheque_number:''}}"  name="cheque_number" class="form-control">
			                </div>
			                <div class="form-group">
			                	<label>Cheque Date </label>
				                <div class="input-group date" id="reservationdate" data-target-input="nearest">
			                        <input name="date_of_cheque" value="{{isset($BookingTotalDetails->date_of_cheque)? 
			                        date('d/m/Y',strtotime($BookingTotalDetails->date_of_cheque)):''}}" type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
			                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
			                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
			                        </div>
	                    		</div>
			                </div>
			            </div>
			            <div class="col-md-6">
			                <div class="form-group">
			                	<label>Bank Name</label>
			                    <input type="text" value="{{isset($BookingTotalDetails->bank_name)? $BookingTotalDetails->bank_name:''}}"  name="bank_name" class="form-control">
			                </div>
			                <div class="form-group">
			                	<label>Cheque Image</label>
			                	 <div class="custom-file">
			                      <input type="file"  name="cheque_image" class="custom-file-input" id="customFile">
			                      <label class="custom-file-label" for="customFile">Choose file</label>
			                    </div>
			                    @isset(($BookingTotalDetails->cheque_image))
			                    <a href="{{asset('public/images/totalBooking/chequeImage/'.$BookingTotalDetails->cheque_image)}}" target="_blank">View</a>
			                    
			                    @endisset 	
			                </div>
			            </div>	
             		</div>
             		<div class="row" id="Online"  @if (isset($BookingTotalDetails->payment_type)) {{ $BookingTotalDetails->payment_type == 2 ? 'visibility=visible' : '' }} @endif >
             			<div class="col-md-4">
			                <div class="form-group">
			                	<label>Transaction ID</label>
			                    <input type="text" value="{{isset($BookingTotalDetails->transaction_id)? $BookingTotalDetails->transaction_id:''}}" name="transaction_id" class="form-control">
			                </div>
			            </div>
			            <div class="col-md-4">
			                <div class="form-group">
			                	<label>Payment Mode</label>
			                    <input type="text" value="{{isset($BookingTotalDetails->payment_mode)? $BookingTotalDetails->payment_mode:''}}" name="payment_mode" class="form-control">
			                </div>
			            </div>
			            <div class="col-md-4">
			                <div class="form-group">
			                	<label>Transaction Image</label>
			                	 <div class="custom-file">
			                      <input type="file" name="online_photo" class="custom-file-input" id="customFile">
			                      <label class="custom-file-label" for="customFile">Choose file</label>
			                    </div>
			                </div>
			                 @isset(($BookingTotalDetails->online_photo ))
			                   @if($BookingTotalDetails->online_photo!="")		
			                    <a href="{{asset('public/images/totalBooking/OnlineImage/'.$BookingTotalDetails->online_photo)}}" target="_blank">View</a>
			                   @endif 
			                @endisset 

			            </div>
             		</div>
             		<div class="row">
             			<div class="col-md-6">
             				<button class="btn btn-sm btn-primary">Save</button>
             			</div>	
             		</div>
             		{!! Form::close() !!}
                  </div>