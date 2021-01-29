<div class="col-md-12">
	                  	{!! Form::open(array(
	                            'route' => 'storeloanamount.store', 
	                            'method'=>'POST', 
	                            'files'=> true
	                    )) !!}
	                    <input type="hidden" name="booking_id" value="{{$bookingid}}">
	                  	<div class="row">
			                <div class="col-md-6">
				                <div class="form-group">
				                   <label>Bank Name</label>
				                   <input  name="bank_name" value="{{isset($BookingLoanAmount->bank_name)? $BookingLoanAmount->bank_name:''}}" type="text"  class="form-control">
				                </div>
				            </div>
	                   		<div class="col-md-6">
				                <div class="form-group">
				                	<label>Amount Sanction </label>
				                	<input  name="amount_sanction" value="{{isset($BookingLoanAmount->amount_sanction)? $BookingLoanAmount->amount_sanction:''}}" type="text"  class="form-control">
					                  
				                </div>
		              		</div>
		              	</div>
		              	<div class="row">	
	              			<div class="col-md-6">
	              				<div class="form-group">
				                  <label>LA amount</label>
				                   <input  name="la_amount" value="{{isset($BookingLoanAmount->la_amount)? $BookingLoanAmount->la_amount:''}}" type="text"  class="form-control">
					                  
				                </div>
	             			</div>	
	             			<div class="col-md-6">
	             				<div class="form-group">
				                  <label>EMI</label>
				                    <input  name="emi" value="{{isset($BookingLoanAmount->emi)? $BookingLoanAmount->emi:''}}" type="text"  class="form-control">
				                </div>
	             			</div>
	             		</div>
	             		<div class="row">
	             			<div class="col-md-6">
	             				<button class="btn btn-sm btn-primary">Save</button>
	             			</div>	
	             		</div>
	             		{!! Form::close() !!}
	                </div>