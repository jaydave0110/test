@extends('layouts.app')
@section('title', 'Manage Sites and properties')
@section('description', 'Manage Sites and properties')

@section('content')
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Active - New Offers To {{$SiteData->site_name}}</h1>
         </div>
         <div class="col-sm-3"> 
         </div> 
         <div class="col-sm-3"> 
            <a style="display:inline-block;"  href="{{ route('siteoffers.view',['id'=>$SiteData->id])}}" class="btn btn-primary">View Site Offers</a> 

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
                           <h3 class="card-title">Add Site Offers Information</h3>
                       </div>
                      <div class="card-body">
                          <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label  >Site </label>
                                     {!! Form::text('site_name', (isset($SitesOffers) ?$SitesOffers->site_name:$SiteData->site_name ), ['class' => 'form-control','readonly'=>'readonly']) !!} 
                                     <input type="hidden" name="site_id" value="{{$SiteData->id}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label  >Property</label>
                                     {!! Form::select('property_id',$Properties, (isset($properties->status) ? $properties->status : null), ['class' => 'form-control select2','id'=>'ajaxProperty']) !!}
                                </div>
                            </div>
                          </div>  
                           
                          
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
                                       <label  >Benifits  </label>
                                       {!! Form::text('reg_cost', (isset($SitesOffers) ?$SitesOffers->reg_cost:'' ), ['placeholder' => 'Enter Benifits','class' => 'form-control']) !!}
                                  </div>
                              </div>

                              <div class="col-md-6">
                                  <div class="form-group">
                                       <label  >Benifited Cost</label>
                                       {!! Form::text('basic_cost', (isset($SitesOffers) ?$SitesOffers->basic_cost :'' ), ['placeholder' => 'Enter Benifited Cost','class' => 'form-control']) !!}
                                  </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                       <label  >Govt Subcidy Price </label>

                                       <input type="text" class="form-control" value="267000" name="govt_subcidy_price" readonly="readonly">
                                       
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  
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
                                       <label  >Interest Subvention</label>
                                       {!! Form::text('interest_subvention', (isset($SitesOffers) ?$SitesOffers->interest_subvention:'' ), ['placeholder' => 'Enter Interest Subvention','class' => 'form-control']) !!}
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                       <label  >EMI Cost</label>
                                       {!! Form::text('emi_cost', (isset($SitesOffers) ?$SitesOffers->emi_cost :'' ), ['placeholder' => 'Enter EMI Cost','class' => 'form-control']) !!}
                                  </div>
                              </div>
                            </div>
                            <hr/>
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label  >Furniture Components ? </label><br/>
                                       <input type="radio" onclick="checkfurniture();" name="is_furniture" value="0"> No
                                       <input type="radio" onclick="checkfurniture();" name="is_furniture" value="1"> Yes
                                  </div>
                              </div>
                              <div class="col-md-6">
                                   
                              </div>
                            </div>

                            <div class="row" id="furniture_cost">
                               <hr/>
                              <div class="col-md-6">
                                <div class="form-group">
                                     <label>Furniture Cost ? </label>
                                     <input type="text" name="furniture_cost" class="form-control ">
                                </div>  
                              </div>
                              <div class="col-md-6">
                                 
                              </div>
                            </div>
                            <div class="row" id="furniture_details">
                              @foreach($FurnitureDetails as $furniture) 
                              <div class="col-md-3">
                                  <input type="checkbox" class="selector"  name="furniture_components[]" value="{{$furniture->id}}">  
                                  <label>{{$furniture->name}}</label>  <br/>
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
                              
                            </div>
                            <div class="row">
                                <div class="col-md-6" id="registration_cost">
                                  <div class="form-group">
                                    <label>Registration Cost</label>
                                    <input type="text" class="form-control" name="registration_cost">
                                  </div>
                                </div>
                                <div class="col-md-6"></div>
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
                              
                            </div>
                            <div class="row">
                              <div class="col-md-6" id="kitchen_cost">
                                <div class="form-group">
                                    <label>Kitchen Cost</label>
                                    <input type="text" class="form-control" name="kitchen_cost">
                                  </div>
                              </div>
                              <div class="col-md-6"></div>
                            </div>
                            <div class="row" id="kitchen_extra">
                               <div class="col-md-2">
                                    <div class="form-group">
                                      <input type="checkbox" value="1" name="platform_cost">
                                      <label>Platform </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                      <input type="checkbox" value="1" name="kitchen_overhead">
                                      <label>Kitchen Overhead</label>
                                    </div>
                                </div>
                                <div class="col-md-2">    
                                    <div class="form-group">
                                      <input type="checkbox" value="1" name="kitchen_loft_work">
                                      <label>Loft work</label>
                                    </div>
                                </div>
                                <div class="col-md-2">    
                                    <div class="form-group">
                                      <input type="checkbox" value="1" name="kitchen_service_cabinet">
                                      <label>Service Cabinet</label>
                                    </div>
                                </div>
                                <div class="col-md-2">    
                                    <div class="form-group">
                                      <input type="checkbox" value="1" name="kitchen_service_overhead">
                                      <label>Service Overhead</label>
                                    </div>
                                </div>    
                            </div>
                            
                            <hr/>
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label>Home Appliances  ? </label>
                                    <input type="radio" onclick="checkHomeAppliances();" name="is_home_appliances" value="0"> No
                                    <input type="radio" onclick="checkHomeAppliances();" name="is_home_appliances" value="1"> Yes
                                  </div>
                              </div>
                              <div class="col-md-6" >
                                 
                              </div>
                               

                            </div>

                            <div class="row">
                              <div class="col-md-6" id="home_appliances_cost">
                                <div class="form-group">
                                    <label>Home Appliances Cost</label>
                                    <input type="text" class="form-control" name="home_appliances_cost">
                                  </div>
                              </div>
                              <div class="col-md-6" >
                                 
                              </div>
                            </div>



                            <div class="row" id="home_appliances_extra">
                               
                                 
                                    <div class="form-group" style="margin-right: 10px;">
                                      <input type="checkbox" name="is_ac" value="1">
                                      <label>1.5 ton Split AC</label>
                                    </div> 
                                
                                  
                                    <div class="form-group" style="margin-right: 10px;">
                                      <input type="checkbox" name="is_tv" value="1">
                                      <label>32 Inch Smart TV</label>
                                    </div> 
                                
                                   
                                    <div class="form-group" style="margin-right: 10px;">
                                      <input type="checkbox"  name="is_refrigeration" value="1">
                                      <label>220 LTR Double Door Refrigerator</label>
                                    </div> 
                                
                                
                                    <div class="form-group" style="margin-right: 10px;">
                                      <input type="checkbox" name="is_washing_machine" value="1">
                                      <label>6.5 Kg to 7.5 Kg Fully Automatic Washing Machine</label>
                                    </div>
                                 
                                 
                                    <div class="form-group" style="margin-right: 10px;" >
                                      <input type="checkbox" name="is_others" value="1">
                                      <label>Others</label>
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
  $("#kitchen_extra").hide();
  $("#platform_cost").hide();
  
  $("#home_appliances_cost").hide();
  $("#home_appliances_extra").hide();


  function checkHomeAppliances()
  {
    var is_home_appliances  = $('input[name="is_home_appliances"]:checked').val();
    if(is_home_appliances==1){
      $("#home_appliances_cost").show();
      $("#home_appliances_extra").show();
    } 
    if(is_home_appliances==0){
      $("#home_appliances_cost").hide();
      $("#home_appliances_extra").hide();
    }

  }
















  function checkfurniture()
  {
    var furniture  = $('input[name="is_furniture"]:checked').val();
     
    if(furniture==1){
      
      $('.selector').prop('checked', true);
      $("#furniture_details").show();
      $("#furniture_cost").show();
    } 
    if(furniture==0){
      $('.selector').prop('checked', false);
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
      $("#kitchen_extra").show();
    } 
    if(kitchen==0){
      $("#kitchen_cost").hide();
      $("#platform_cost").hide();
      $("#kitchen_extra").hide();
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