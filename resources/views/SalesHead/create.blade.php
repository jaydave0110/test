@extends('layouts.app')

@section('title', 'Manage Builder Sites') 
@section('description', 'Manage Builder Sites') 

@section('content')

<section class="content">
	 @include('layouts.errorMessage')
	{!! Form::open(array(
                            'route' => 'saleshead.store', 
                            'method'=>'POST', 
                            'files'=> true
                    )) !!}
                    @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                  	<div class="card card-default">
	                    <div class="card-header">
                       		 <h3 class="card-title">Add New Sales Head Information</h3>
                             <a href="{{route('saleshead.index')}}" style="float: right;" class="btn btn-sm btn-primary">Back</a>
                    	 </div>
                     	<div class="card-body">
                     		<h2>Sales Head  Details</h2>
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
                            <hr/>
                            <div class="row">
                                <div class="col-md-12">
                                     <div class="form-group">
                                      <label  >Select Below Under which User ?</label>
                                      <input type="radio" value="1" name="user_under" onclick="displayData(1)">
                                      <label>Company Representative</label>   
                                      <input type="radio" value="2" name="user_under" onclick="displayData(2)">
                                      <label>City Head</label>   
                                      <input type="radio" value="3" name="user_under" onclick="displayData(3)">
                                      <label>Both</label>   
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" id="representative">
                                     <div class="form-group">
                                      <label  >Select Company Representative</label>
                                      <select name="representative_id" class="form-control">
                                          <option value="">Select Representative</option>
                                          @foreach($representative as $rep)
                                          <option  value="{{$rep->id}}">{{$rep->name}}</option>
                                          @endforeach
                                      </select> 
                                    </div>
                                </div>
                                <div class="col-md-6" id="cityhead">
                                     <div class="form-group">
                                      <label>Select City Head</label>
                                        <select name="city_head_id" class="form-control">
                                          <option value="">Select City Head</option>
                                          @foreach($cityhead as $ctyhead)
                                          <option  value="{{$ctyhead->id}}">{{$ctyhead->name}}</option>
                                          @endforeach
                                        </select> 

                                         
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





$("#representative").hide();
$("#cityhead").hide();

function displayData(id)
{

    if(id==1)
    {
        $("#representative").show();
        $("#cityhead").hide();
    }
    if(id==2)
    {
        $("#representative").hide();
        $("#cityhead").show();
    }
    if(id==3)
    {
        $("#representative").show();
        $("#cityhead").show();
    }

}
   
    

    
</script>

@endsection 

 