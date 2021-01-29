<div class="col-md-12">
	                  	 
  	{!! Form::open(array(
            'route' => 'storedirectamount.store', 
            'method'=>'POST', 
            'files'=> true
    )) !!}
    @csrf
    <input type="hidden" name="booking_id" value="{{$bookingid}}">
  	<div class="row">
        <div class="col-md-6">
            <div class="form-group">
               <label>Amount</label>
               <input name="amount" type="text"  class="form-control">
            </div>
        </div>
   		<div class="col-md-6">
            <div class="form-group">
            	<label>Date </label>
                 <div class="input-group date" id="reservationdateTwo" data-target-input="nearest">
                    <input name="amount_date" type="text" class="form-control datetimepicker-input" data-target="#reservationdateTwo"/>
                    <div class="input-group-append" data-target="#reservationdateTwo" data-toggle="datetimepicker">
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
              <select name="payment_type" id="da_payment_type" class="form-control" onchange="dashowRows();" style="width: 100%;">
                <option value="" selected="selected">Select</option>
                <option value="0">Cash</option>
                <option value="1">Cheque</option>
                <option value="2">Online</option>
               </select>
            </div>
			</div>	
			<div class="col-md-6">
				<div class="form-group">
              <label>Status</label>
              <select name="status" id="da_payment_type" class="form-control"   style="width: 100%;">
                <option value="" selected="selected">Select</option>
                <option value="0">Unpaid</option>
                <option value="1">Paid</option>
               </select>
            </div>
			</div>
		</div>
		<div class="row" id="daCheque" >
			<div class="col-md-6">
            <div class="form-group">
               <label>Cheque Number</label>
               <input type="text" name="cheque_number" class="form-control">
            </div>
            <div class="form-group">
            	<label>Cheque Date </label>
                <div class="input-group date" id="reservationdateThree" data-target-input="nearest">
                    <input name="date_of_cheque" type="text" class="form-control datetimepicker-input" data-target="#reservationdateThree"/>
                    <div class="input-group-append" data-target="#reservationdateThree" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
        		</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            	<label>Bank Name</label>
                <input type="text" name="bank_name" class="form-control">
            </div>
            <div class="form-group">
            	<label>Cheque Image</label>
            	 <div class="custom-file">
                  <input type="file" name="cheque_image" class="custom-file-input" id="customFile">
                  <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
        </div>	
		</div>
		<div class="row" id="daOnline">
			<div class="col-md-4">
            <div class="form-group">
            	<label>Transaction ID</label>
                <input type="text" name="transaction_id" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
            	<label>Payment Mode</label>
                <input type="text" name="payment_mode" class="form-control">
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
        </div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<button class="btn btn-sm btn-primary">Save</button>
			</div>	
		</div>
		{!! Form::close() !!}
</div>
<div class="row" style="margin-top:15px">
<div class="col-md-12">
<div class="card">
              <div class="card-header">
                <h3 class="card-title">Direct Payment History</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Sr.No</th>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Manage</th>
                  </tr>
                  </thead>
                  <tbody>

                  @if(count($BookingDirectAmount)>0)	
                  @foreach($BookingDirectAmount as $key =>$directAmount)
                  <tr>
                    <td>{{ $key+1 }}</td>
                    <td>@if($directAmount->payment_type==0) Cash   @endif 
                    	@if($directAmount->payment_type==1) Cheque @endif @if($directAmount->payment_type==2) Online @endif</td>
                    <td>{{$directAmount->amount}} </td>
                    <td>
                    	@if($directAmount->status==1) Paid @endif @if($directAmount->status==0) Unpaid   @endif 
                    </td>
                    <td> {{ date('d/m/Y',strtotime($directAmount->amount_date))   }}</td>
                    <td><a target="_blank" href="{{route('directpaymentinfo',['bookingid'=>$bookingid,'id'=>$directAmount->id])}}" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> View</a> | <a href="{{route('deletedirectpayment',['id'=>$directAmount->id])}}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a> </td>
                  </tr>
                  @endforeach
                  @endif
                

                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Sr.No</th>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Manage</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
</div>
</div>