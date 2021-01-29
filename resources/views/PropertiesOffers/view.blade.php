@extends('layouts.app')
@section('title', 'Manage Site Offers ')
@section('description', 'Manage Site Offers')

@section('content')
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>List Offers</h1>
         </div>
         <div class="col-sm-6"> 
            <a style="float: right;"  href="{{route('propertiesoffers.index',['id'=>$PropertiesOffers[0]->property_id])}}" class="btn btn-primary">Back</a> 
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>
<section class="content">
  @include('layouts.errorMessage')
   
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                      <div class="card-header">
                           <h3 class="card-title">Property Offers Information</h3>
                       </div>
                      <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Property Name</th>
                    <th>Option Name</th>
                    <th>Final Price</th>
                    <th>Govt Subcidy</th>
                    <th>Basic Cst</th>
                    <th>Reg Cst</th>
                    <th>Emi Cst</th>
                    <th>Home App Cst</th>
                    <th>Unit Left</th>
                    <th>Daysleft</th>
                    <th>Interest Subv</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                
                   
                  @foreach($PropertiesOffers as $key => $siteOffr) 
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$siteOffr->properties->sub_title}}</td>
                    <td>{{$siteOffr->option_name}}</td>
                    <td>{{$siteOffr->final_price}}</td>
                    <td>{{$siteOffr->govt_subcidy_price}}</td>
                    <td>{{$siteOffr->basic_cost}}</td>
                    <td>{{$siteOffr->reg_cost}}</td>
                    <td>{{$siteOffr->emi_cost}}</td>
                    <td>{{$siteOffr->home_appliance_cost}}</td>
                    <td>{{$siteOffr->unit_left}}</td>
                    <td>{{$siteOffr->days_left}}</td>
                    <td>{{$siteOffr->interest_subvention}}</td>
                    <td><a href="{{route('propertiesoffers.edit',$siteOffr->id)}}" class="btn btn-sm btn-warning" style="margin-right: 10px;"><i class="fa fa-edit"></i></a>
                      <a onclick="confirm('Are you sure do u want to delete these record ?')" href="{{ route('propertiesoffers.destroy',['id'=>$siteOffr->id]) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                  @endforeach 
                   
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                   <th>Property Name</th>
                    <th>Option Name</th>
                    <th>Final Price</th>
                    <th>Govt Subcidy</th>
                    <th>Basic Cst</th>
                    <th>Reg Cst</th>
                    <th>Emi Cst</th>
                    <th>Home App Cst</th>
                    <th>Unit Left</th>
                    <th>Daysleft</th>
                    <th>Interest Subv</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
               
            </div>
        </div>
        
    
    </div>
</section> 

 
@endsection
@section('extra-scripts')

<script>
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