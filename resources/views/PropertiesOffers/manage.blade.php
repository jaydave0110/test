@extends('layouts.app')
@section('title', 'Manage Sites and properties')
@section('description', 'Manage Sites and properties')

@section('content')
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Active - New Offers </h1>
         </div>
         <div class="col-sm-3"> 
         </div> 
         <div class="col-sm-3"> 
            <a style="display:inline-block;"  href="{{ route('propertiesoffers.view',['id'=>$Properties->id])}}" class="btn btn-primary">View Property Offers</a> 

             <a style="display:inline-block;"  href="{{ route('sites.index')}}" class="btn btn-primary">BACK  </a> 

         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>
<section class="content">
  @include('layouts.errorMessage')
  {!! Form::open(array(
                            'route' => 'siteoffers.store', 
                            'method'=>'POST', 
                            'files'=> true
                    )) !!}
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                      <div class="card-header">
                           <h3 class="card-title">Add Property Offers Information {{$Properties->sub_title}}</h3>
                       </div>
                      <div class="card-body">
                           
                           <input type="hidden" name="site_id" value="{{$Siteid}}">
                          
                            
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
                                       {!! Form::text('basic_cost', (isset($SitesOffers) ?$SitesOffers->basic_cost :'' ), ['placeholder' => 'Enter Basic Cost','class' => 'form-control']) !!}
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
                                       {!! Form::text('emi_cost', (isset($SitesOffers) ?$SitesOffers->emi_cost :'' ), ['placeholder' => 'Enter EMI Cost','class' => 'form-control']) !!}
                                  </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label  >Days Left </label>
                                       {!! Form::text('days_left', (isset($SitesOffers) ?$SitesOffers->days_left:'' ), ['placeholder' => 'Enter Days Left','class' => 'form-control']) !!}
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                       <label  >Unit Left</label>
                                       {!! Form::text('unit_left', (isset($SitesOffers) ?$SitesOffers->unit_left:'' ), ['placeholder' => 'Enter Unit Left','class' => 'form-control']) !!}
                                  </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label  >Furniture Components ? </label><br/>
                                       <input type="radio" onclick="checkfurniture();" name="is_furniture" value="0"> No
                                       <input type="radio" onclick="checkfurniture();" name="is_furniture" value="1"> Yes
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                       <label  >Interest Subvention</label>
                                       {!! Form::text('interest_subvention', (isset($SitesOffers) ?$SitesOffers->interest_subvention:'' ), ['placeholder' => 'Enter Interest Subvention','class' => 'form-control']) !!}
                                  </div>
                              </div>
                            </div>

                            <div class="row" id="furniture_cost">
                               <hr/>
                              <div class="col-md-12">
                                <div class="form-group">
                                     <label>Furniture Cost ? </label>
                                     <input type="text" name="furniture_cost" class="form-control">
                                </div>  
                              </div>
                            </div>
                            <div class="row" id="furniture_details">
                              @foreach($FurnitureDetails as $furniture) 
                              <div class="col-md-3">
                                  <input type="checkbox"  name="furniture_components[]" value="{{$furniture->id}}">  
                                  <label>{{$furniture->name}}</label>  <label class="text text-green"> {{$furniture->cost}}</label><br/>
                              </div>
                              @endforeach 
                            </div>
                            <hr/>

                            <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label>Registration  ? </label>
                                    <input type="radio" onclick="checkRegistration();" name="is_registration" value="0"> No
                                    <input type="radio" onclick="checkRegistration();" name="is_registration" value="1"> Yes
                                  </div>
                              </div>
                              <div class="col-md-6" id="registration_cost">
                                <div class="form-group">
                                    <label>Registration Cost</label>
                                    <input type="text" class="form-control" name="registration_cost">
                                  </div>
                              </div>
                            </div>
                            <div class="row"   id="registration_details">
                              <div class="col-md-2">
                                  <div class="form-group">
                                    <label>Stamp Cost</label>
                                    <input type="text" class="form-control" name="stamp_cost">
                                  </div>
                              </div>
                              <div class="col-md-2">
                                  <div class="form-group">
                                    <label>GST Cost</label>
                                    <input type="text" class="form-control" name="gst_cost">
                                  </div>
                              </div>
                              <div class="col-md-2">
                                  <div class="form-group">
                                    <label>Maintainance Cost</label>
                                    <input type="text" class="form-control" name="maintainance_cost">
                                  </div>
                              </div>
                              <div class="col-md-4">
                                  <div class="form-group">
                                    <label>Development Cost</label>
                                    <input type="text" class="form-control" name="development_cost">
                                  </div>
                              </div>
                              <div class="col-md-2">
                                  <div class="form-group">
                                    <label>Other Expense</label>
                                    <input type="text" class="form-control" name="other_expense">
                                  </div>
                              </div>
                            </div>
                            <hr/>

                            <div class="row">
                              <div class="col-md-4">
                                  <div class="form-group">
                                    <label>Kitchen Furniture  ? </label>
                                    <input type="radio" onclick="checkKitchen();" name="kitchen_components" value="0"> No
                                    <input type="radio" onclick="checkKitchen();" name="kitchen_components" value="1"> Yes
                                  </div>
                              </div>
                              <div class="col-md-4" id="kitchen_cost">
                                <div class="form-group">
                                    <label>Kitchen Cost</label>
                                    <input type="text" class="form-control" name="kitchen_cost">
                                  </div>
                              </div>
                              <div class="col-md-4" id="platform_cost">
                                <div class="form-group">
                                    <label>Platform </label>
                                    <input type="text" class="form-control" name="platform_cost">
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

 
@endsection
@section('extra-scripts')

<script>
  $("#furniture_details").hide();
  $("#furniture_cost").hide();
  $("#registration_details").hide();
  $("#registration_cost").hide();
  $("#kitchen_cost").hide();
  $("#platform_cost").hide();

  function checkfurniture()
  {
    var furniture  = $('input[name="is_furniture"]:checked').val();
     
    if(furniture==1){
      $("#furniture_details").show();
      $("#furniture_cost").show();
    } 
    if(furniture==0){
      $("#furniture_details").hide();
      $("#furniture_cost").hide();
    }
  }

  function checkRegistration()
  {
    
    var registaion  = $('input[name="is_registration"]:checked').val();
     
    if(registaion==1){
      $("#registration_details").show();
      $("#registration_cost").show();
    } 
    if(registaion==0){
      $("#registration_details").hide();
      $("#registration_cost").hide();
    }
     
  }

  function checkKitchen()
  {
    
    var kitchen  = $('input[name="kitchen_components"]:checked').val();
     
    if(kitchen==1){
      $("#kitchen_cost").show();
      $("#platform_cost").show();
    } 
    if(kitchen==0){
      $("#kitchen_cost").hide();
      $("#platform_cost").hide();
    }
  }






  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
@endsection