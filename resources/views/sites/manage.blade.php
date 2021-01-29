@extends('layouts.app')
@section('title', 'Manage Sites and properties')
@section('description', 'Manage Sites and properties')

@section('content')
 
            <!-- Content Header (Page header) -->
            <section class="content-header">
               <div class="container-fluid">
                  <div class="row mb-2">
                     <div class="col-sm-6">
                        <h1>Active - New properties</h1>
                     </div>
                     <div class="col-sm-6"> 
                        <a href="{{ route('sites.create') }}"><button style="float: right;" type="submit" class="btn btn-primary">Add New Site</button></a> 
                     </div>
                  </div>
               </div>
               <!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <section class="content">
               <div class="container-fluid">
                  <div class="row">
                     <!-- left column -->
                     <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                           <div class="card-header">
                              <h3 class="card-title">Advance Search</h3>
                           </div>
                           <!-- /.card-header -->
                           <!-- form start / Search Filter -->
                           <form method="get" action="{{ route('sites.search') }}">
                              @csrf
                              <div class="col-md-12">
                                 <div class="row">
                                    <div class="col-md-4">
                                       <div class="form-group">
                                          <label for="exampleInputEmail1">Site Name</label>
                                           {!! Form::text('site_name', null, array('placeholder' => 'Site name','class' => 'form-control')) !!}
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                          <label>Select City</label>

                                          {!! Form::select('city_id', $cities, null, ['class' => 'form-control select2', 'id' => 'ajaxCity', 'placeholder' => 'Select City']) !!}

                                          
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                          <label>Select  Area</label>

                                           
                                          
                                          <select name="area_id[]" class="form-control select2" multiple="multiple" id="ajaxArea" >
                                            <option value="">Select Area</option>
                                          </select>

                                           
                                          
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                          <label>Select  Property Type</label>
                                            {!! Form::select('cat_id', $propertyCategory, null, ['class' => 'form-control select2', 'placeholder' => 'Select Option', 'id' => 'ajaxPropertyCategorySingle']) !!}
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                           @php
                                           $extra = ['class' => 'form-control select2', 'id' => 'ajaxPropertySubCategorySingle'];
                                           if (isset($propertyType)) {
                                             $extra['placeholder'] = 'Select option';
                                           }
                                         @endphp

                                          <label>Select  Property Category</label>
                                         {!! Form::select('sub_cat_id', isset($propertyType) ? $propertyType : ['' => 'Select Option'], null, $extra) !!}
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label>Select Status</label>
                                        <select name="status" class="form-control" >
                                            <option value="">select</option>
                                            <option value="5">All</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                      </div>
                                    </div>    


                                 </div>
                              </div>
                              <div class="card-footer">
                                 <button type="submit" class="btn btn-primary">Search</button>
                              </div>
                           </form>
                        </div>
                        <!-- /.card-body -->
                     </div>
                  </div>
               </div>
            </section>

              <!-- Listing Start -->
            <div class="content">
               <div class="container">
                    @include('layouts.errorMessage')
                    
                    @if ($Sites->count() > 0){{ number_format($Sites->total()) }} sites found
                      @if (request() && request()->_token != '')
                          (According to filter applied this count is based on filter)
                      @endif
                    @foreach ($Sites as $key => $Site)

                      @php
                          $siteCover = \Helpers::getCoveredImage($Site);
                      @endphp

                      <div class="row">
                         <!-- /.col-md-6 -->
                         <div class="col-lg-12">
                            <div class="card card-primary card-outline">
                               <div class="row">
                                  <div class="col-md-3">
                                     <img style="width:100%;height:250px;" src="{{ $siteCover }}" />
                                  </div>
                                  <div class="col-md-9">
                                     <p style="Font-size:20px;">{{ Helpers::subString($Site->site_name, 40) }}</p>
                                     <p style="margin-bottom: 0px;"> <i class="fa fa-map-marker" aria-hidden="true"></i>  {!! $Site->address !!} 
                                                      @if ($Site->areas)
                                                          {{ $Site->areas->name }}
                                                      @endif

                                                      @if ($Site->cities)
                                                          {{ ', '.$Site->cities->name }}
                                                      @endif
                                     </h6>
                                     <p style="margin-bottom: 0px;"> <i class="fa fa-home"></i> 

                                      {!! $Site->possession_status == 1 ? '<span class="text-danger">Under Construction</span>' : '<span class="text-success">Ready to move</span>' !!} 

                                      </p>
                                     <p style="margin-bottom: 0px;"> <i class="fa fa-user" aria-hidden="true"></i> Builder: {{ $Site->users->name.' ('.$Site->users->phone.')' }} </p>
                                     @if ($Site->siteOffers)
                                                          <ul class="list-group siteoffers d-flex mt-2">
                                                              @foreach ($Site->siteOffers as $offer)
                                                                  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start p-2 pl-3 w-50">
                                                                      <div class="justify-content-between h5 pb-0 mb-0">{{ $offer->offer_details }}</div>
                                                                      <div class="small pt-0 mt-0">Offer Name {{$offer->option_name}}
                                                                        {{$offer->final_price}}
                                                                      </div>
                                                                    </a>
                                                              @endforeach
                                                          </ul>
                                                      @endif


                                     <div class="col-md-12" style="margin-top: 15px;">
                                        <div class="row">
                                           <div class="col-md-4">
                                              <a href="{{ route('sites.edit',$Site->id) }}" class="btn btn-block bg-gradient-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Site</a>
                                           </div>
                                            
                                           @if($Site->status==1)
                                           <div class="col-md-4" id="siteaction{{ $Site->id }}">
                                              <button type="button"  class="btn btn-block bg-gradient-danger changeSiteStatus" data-id="{{ $Site->id }}" data-tostatus="disable"><i class="fa fa-ban" aria-hidden="true"></i> Disable Site</button>
                                           </div>
                                           @else
                                           <div class="col-md-4" id="siteaction{{ $Site->id }}">
                                              <button type="button"  class="btn btn-block bg-gradient-success changeSiteStatus" data-id="{{ $Site->id }}" data-tostatus="disable"><i class="fa fa-ban" aria-hidden="true"></i> Enable Site</button>
                                           </div>
                                           @endif
                                           <div class="col-md-4">
                                              <a href="{{ route('siteoffers.index',$Site->id)}}" target="_blank" class="btn btn-block bg-gradient-warning"><i class="fa fa-bullhorn" aria-hidden="true"></i> Add Offer</a>
                                           </div>
                                        </div>
                                     </div>
                                  </div>
                               </div>
                            </div>
                            <section class="content">
                               <!-- Default box -->
                               <div class="card">
                                  <div class="card-body p-0">
                                    {!! Helpers::AdminGetSitePropertyTable($Site) !!}
                                      
                                  </div>
                                  <!-- /.card-body -->
                               </div>
                               <!-- /.card -->
                            </section>
                         </div>
                         <!-- /.col-md-6 -->
                      </div>
                    @endforeach
                    @include('Pagination.default', ['paginator' => $Sites])
                    @else
                    <div class="alert alert-danger text-center">No {{ $page_title }} found</div>
                  @endif
                  <!-- /.row -->
               </div>
               <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
@endsection
@section('extra-scripts')


<script >
    $(document).on('change', 'select#ajaxPropertyCategorySingle', function(e) {
        e.preventDefault();
        var _this = this;
        var propertyCatId = $(_this).val();
        var ajaxPropertySubCategorySingle = 'ajaxPropertySubCategorySingle';
        var _parent = $('#ajaxPropertySubCategorySingle').parent();

        if (propertyCatId > 0) {
            $.ajax({
                url: '{{route('getPropertyType')}}',
                method: 'GET',
                data: $(_this).serialize(),
                beforeSend: function(data) {
                
                    $("select#" + ajaxPropertySubCategorySingle).empty();
                    $("select#" + ajaxPropertySubCategorySingle).append('<option value="">Please wait...</option>');
                    $("select#" + ajaxPropertySubCategorySingle).trigger("chosen:updated");

                },
                success: function(xhr, textStatus, jQxhr) {
                    $("select#" + ajaxPropertySubCategorySingle).empty();
                    $("select#" + ajaxPropertySubCategorySingle).append('<option value="">Select Option</option>');
                    $.each(xhr.data, function(key, value) {
                        $("select#" + ajaxPropertySubCategorySingle).append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $("select#" + ajaxPropertySubCategorySingle).trigger("chosen:updated");
                    //loadPlugins();
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        } else {
            $("select#" + ajaxPropertySubCategorySingle).empty();
            $("select#" + ajaxPropertySubCategorySingle).append('<option value="">Select Option</option>');
            $("select#" + ajaxPropertySubCategorySingle).trigger("chosen:updated");
        }
    });




    $(document).on('change', '#ajaxCity', function(e) {
        e.preventDefault();
        var cityId = $(this).val();
        var ajaxArea = 'ajaxArea';
        if (cityId > 0) {
            $.ajax({
                url: '{{route('getAreas')}}',
                method: 'GET',
                data: $(this).serialize(),
                beforeSend: function(data) {
                
                    $("select#" + ajaxArea).empty();
                    $("select#" + ajaxArea).append('<option value="">Please wait...</option>');
                    $("select#" + ajaxArea).trigger("chosen:updated");
                    
                },
                success: function(xhr, textStatus, jQxhr) {
                    
                    $("select#" + ajaxArea).empty();
                    
                    // if multiple selection then no need to display default option
                    if (typeof $("select#" + ajaxArea).attr('multiple') == 'undefined') {
                        $("select#" + ajaxArea).append('<option value="">Select Area</option>');
                    }
                    
                    $.each(xhr.data, function(key, value) {
                        $("select#" + ajaxArea).append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                    $("select#" + ajaxArea).trigger("chosen:updated");

                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        }
    });

   
    $(document).on('click', '.changeSiteStatus', function(e) {
        
        var site_id = $(this).data('id');

        var toStatus = $(this).data('tostatus');

        var toStatusArr = [];
        toStatusArr['enable'] = 'active';
        toStatusArr['disable'] = 'disable';
        toStatusArr['pending'] = 'mark as pending for verification';
        toStatusArr['delete'] = 'delete';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });


        if (confirm('Are you sure you want to ' + toStatusArr[toStatus] + ' this property')) {
            var action_btns = $("#siteaction" + site_id).html();
            $.ajax({
                url: "{{ route('sites.changeSiteStatus')}}",
                data: 'site_id=' + site_id + '&tostatus=' + toStatus,
                type: 'POST',
                beforeSend: function(data) {
                    $("#siteaction" + site_id).html('Please wait...');
                },
                success: function(xhr, textStatus, jQxhr) {
                    
                    $('.alert').removeClass('alert-info').removeClass('alert-warning').removeClass('alert-danger');
                    if (xhr.status == 'error') {
                        if(typeof jQxhr.responseJSON.site_limit_error != 'undefined'){
                            $('.alert').html(jQxhr.responseJSON.site_limit_error);
                            $('.alert').addClass('alert-danger');
                            $("#siteaction" + site_id).html(action_btns);
                        }else{
                            $("#siteaction" + site_id).html('<span class="small text-danger"><span class="fas fa-exclamation-circle"></span>&nbsp; Error occured, try again!</span>');
                        }
                    } else if (xhr.status == 'success') {
                        $("#siteaction" + site_id).html('<span class="small text-success text-center"><span class="fas fa-check-circle"></span>&nbsp; ' + xhr.message + '</span>');
                    }

                },
                error: function( jqXhr, textStatus, errorThrown ){
                    alert('Please refresh the page to continue.');
                }
            });
        }

    });

</script>
@endsection