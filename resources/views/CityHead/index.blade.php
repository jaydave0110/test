@extends('layouts.app')
@section('title', 'Manage Site Offers ')
@section('description', 'Manage Site Offers')

@section('content')
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>City / Channel Head Listing</h1>
         </div>
         <div class="col-sm-6"> 
             
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
                           <h3 class="card-title">City / Channel Head Information</h3>
                           <a href="{{ route('cityhead.create') }}" class="btn btn-sm btn-primary" style="float: right;" >Create</a>
                       </div>
                      <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th width="25%">Action</th>
                    <th width="52%">Quick View</th>
                  </tr>
                  </thead>
                  <tbody>
                
                   
                  @foreach($data as $key =>$value)

                    <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$value->name}}</td>
                    <td>{{$value->email}}</td>
                    
                    <td><a href="{{route('cityhead.show',$value->id)}}" class="btn btn-sm btn-warning" style="margin-right: 10px;"><i class="fa fa-eye"></i></a>
                      <a href="{{route('cityhead.edit',$value->id)}}" class="btn btn-sm btn-primary" style="margin-right: 10px;"> <i class="fa fa-pencil-alt"></i>   </a>
                      @if($value->status==1)
                      <button    onclick="UpdateStatus('{{$value->id}}','{{$value->status}}')" class="btn btn-sm btn-success">Active</button>  
                      @else
                      <button    onclick="UpdateStatus('{{$value->id}}','{{ $value->status }}')" class="btn btn-sm btn-danger" >Inactive</button>  
                      @endif
                      {!! Form::open(['method' => 'DELETE','route' => ['cityhead.destroy', $value->id],'style'=>'display:inline']) !!}
                              <button onclick="return confirm('Are you sure you want to delete this record ?');" type="submit" name="delete" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>  
                               
                          {!! Form::close() !!}


                    </td>
                    <td>
                      <?php
                    
                       $data =  Helpers::getCityHeadDashboard($value->id);
                       $ds = json_decode($data,true);
                        
                      ?>

                       <a href="{{route('citysalesHead.show',$value->id)}}" class="btn btn-sm bg-indigo" style="margin-right: 10px;"><span class=" badge color-palette"> {{$ds['totalSalesHead']}}</span>
                            Sales Head
                      
                      </a>

                      <a href="{{route('cityTotalBrokers.show',$value->id)}}" class="btn btn-sm bg-navy" style="margin-right: 10px;"> <span class="badge color-palette">{{$ds['totalBroker']}}</span>
                           Total Brokers  
                      </a>
                      <a href="{{route('cityTotalBookings.show',$value->id)}}" class="btn btn-sm bg-info color-palette" style="margin-right: 10px;margin-top: 3px;">
                        <span class="badge color-palette">{{$ds['totalBookings']}}</span>
                           Total Bookings
                      </a>
                      <a href="{{route('cityTotalCommission.show',$value->id)}}" class="btn btn-sm bg-lightblue" style="margin-right: 10px;"> <span class="badge color-palette">{{$ds['totalCommission']}}</span>
                            Total Commission
                      </a> 

                       
                        
                        
                       
                    </td>
                  </tr>
                  @endforeach
                    
                  </tbody>
                  <tfoot>
                   <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th width="25%">Action</th>
                    <th width="52%">Quick View</th>
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


  function UpdateStatus(id,status)
  {
    
    var check  = confirm("Are You Sure You want to change the user status ?");
    if(check==true)
    {
      $.ajax({
          method: 'POST',
          url: '{{ route('users.updateStatus')}}',
          data: {
            'id':id ,'status': status, '_token': '{{ csrf_token() }}'
          },
          dataType: 'json',
          success: function(data){
              console.log(data);
              if(data.status=='success')
              {
                location.reload();

              } else {
                alert('Something Went Wrong Try Again');
              }
          }
      });

    }



    
   

  }

   


  $('.status').on('change.bootstrapSwitch', function(e,data) {
    alert(e.target.checked);
    var id = $(this).data("id");
    var getstatus = e.target.checked;
    if(getstatus==true){
      status=1;
    } else {
      status=0;
    }


    $.ajax({
        method: 'POST',
        url: '{{ route('users.updateStatus')}}',
        data: {
          'id':id ,'status': status, '_token': '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(data){
            console.log(data);
            if(data.status=='success')
            {
              toastr.success(data.message);
            } else {
              toastr.error(data.message);
            }
        }
    });

  });





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










