@extends('layouts.app')
@section('title', 'Manage Bookings')
@section('description', 'Manage Bookings')

@section('content')
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Bookings List</h1>
         </div>
         <div class="col-sm-6"> 
            <a style="float: right;"  href="{{route('bookings.create')}}" class="btn btn-primary">Add New Booking</a> 
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
                           <h3 class="card-title">List Of Booking Information</h3>
                       </div>
                      <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Mobile Number</th>
                    <th>Email</th>
                    <th>Handled By</th>
                    <th>Site Offer</th>
                    <th>BHK</th>
                    <th>Package</th>
                    <th>Amount</th>
                    <th>Is Discount?</th>
                    <th>Discount Amount</th>
                    <th>Final Amount</th>
                    <th>Action</th>
                    <th>Manage</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($Bookings as $key => $booking) 
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$booking->customer_name}}</td>
                    <td>{{$booking->mobile_number}}</td>
                    <td>{{$booking->email}}</td>
                    <td>@if($booking->handle_by==1){{$booking->users->name}} @else Direct @endif</td>
                    <td>{{$booking->sites->site_name}}</td>
                    <td>{{$booking->bhk}}</td>
                    <td>{{$booking->sitesoffers->option_name}}</td>
                    <td>{{$booking->amount}}</td>
                    <td>{{$booking->is_discount}}</td>
                    <td>{{$booking->discount_amount}}</td>
                    <td>{{$booking->final_amount}}</td>
                    <td style="display: flex;"><a href="{{route('bookings.edit',$booking->id)}}" class="btn btn-sm btn-warning" style="margin-right: 10px;"><i class="fa fa-edit"></i></a>
                      <form style="float: right;" action="{{route('bookings.destroy',$booking->id)}}" method="POST">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <button class="btn btn-sm btn-danger" style="margin-right: 10px;"><i class="fa fa-trash" onclick="return confirm('Are you sure do you want to delete the record ?')"></i></button>
                      </form>  

                         
                    </td>
                    <td><a href="{{ route('bookings.payments',$booking->id) }}" class="btn btn-sm btn-default"><i class="fa fa-plus-square"></i>&nbsp;Payments</a></td>
                  </tr>
                  @endforeach 
                   
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Mobile Number</th>
                    <th>Email</th>
                    <th>Handled By</th>
                    <th>Site Offer</th>
                    <th>BHK</th>
                    <th>Package</th>
                    <th>Amount</th>
                    <th>Is Discount?</th>
                    <th>Discount Amount</th>
                    <th>Final Amount</th>
                    <th>Action</th>
                     <th>Manage</th>
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