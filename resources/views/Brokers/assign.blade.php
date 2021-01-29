@extends('layouts.app')
@section('title', 'Manage Site Offers ')
@section('description', 'Manage Site Offers')

@section('content')
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Brokers Assign </h1>
         </div>
         <div class="col-sm-6"> 
             
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>
<section class="content">
 
      
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                       @include('layouts.errorMessage')
                      {!! Form::open(array(
                                                'route' => 'broker.storeassign', 
                                                'method'=>'POST', 
                                                'files'=> true
                                        )) !!}
                                        @csrf
                      <div class="card-header">
                           <h3 class="card-title">Brokers Assign </h3>
                            <a href="{{route('brokers.index')}}" style="float: right;" class="btn btn-primary">Back</a>
                       </div>
                      <div class="card-body">
                        <input type="hidden" name="id" value="{{request()->route('id')}}">
                        @if($brokerUserManagement=="")
                        <div class="row">
                                <div class="col-md-12">
                                     <div class="form-group">
                                      <label  >Select Below Under which User ?</label>
                                      <input type="radio" value="1" name="user_under" onclick="displayData(1)"     >
                                      <label>Company Representative</label>   
                                      <input type="radio" value="2" name="user_under" onclick="displayData(2)"  >
                                      <label>Sales Head</label>   
                                      <input type="radio" value="3" name="user_under" onclick="displayData(3)"  >
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
                                        <select name="sales_head_id" class="form-control">
                                          <option value="">Select Sales Head</option>
                                          @foreach($saleshead as $salhead)
                                          <option    value="{{$salhead->id}}">{{$salhead->name}}</option>
                                          @endforeach
                                        </select> 

                                         
                                    </div>
                                </div>
                        </div>

                        @else
                        <div class="row">
                                <div class="col-md-12">
                                     <div class="form-group">
                                      <label  >Select Below Under which User ?</label>
                                      <input type="radio" value="1" name="user_under" onclick="displayData(1)"  @if($brokerUserManagement->user_under==1) checked @endif   >
                                      <label>Company Representative</label>   
                                      <input type="radio" value="2" name="user_under" onclick="displayData(2)" @if($brokerUserManagement->user_under==2) checked @endif >
                                      <label>Sales Head</label>   
                                      <input type="radio" value="3" name="user_under" onclick="displayData(3)" @if($brokerUserManagement->user_under==3) checked @endif >
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
                                          <option @if($brokerUserManagement->represent_id==$rep->id) selected @endif value="{{$rep->id}}">{{$rep->name}}</option>
                                          @endforeach
                                      </select> 
                                    </div>
                                </div>
                                <div class="col-md-6" id="cityhead">
                                     <div class="form-group">
                                      <label>Select Sales Head</label>
                                        <select name="sales_head_id" class="form-control">
                                          <option value="">Select Sales Head</option>
                                          @foreach($saleshead as $salhead)
                                          <option @if($brokerUserManagement->represent_id==$salhead->id) selected @endif  value="{{$salhead->id}}">{{$salhead->name}}</option>
                                          @endforeach
                                        </select> 

                                         
                                    </div>
                                </div>
                        </div>




                        @endif











                      
                      </div>
                      <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <span class="fas fa-save"></span>&nbsp; Submit & Save
                            </button>

                      </div> 
                      {!! Form::close() !!}
               
            </div>
        </div>
        
    
    </div>
</section> 

 
@endsection
@section('extra-scripts')




 
<script>


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
@if($brokerUserManagement!="")

<script>
  var user_under = "{{ $brokerUserManagement->user_under }}";
 
if(user_under==1)
    {
        $("#representative").show();
        $("#cityhead").hide();
    }
    if(user_under==2)
    {
        $("#representative").hide();
        $("#cityhead").show();
    }
    if(user_under==3)
    {
        $("#representative").show();
        $("#cityhead").show();
    }

</script>
@endif
@endsection










