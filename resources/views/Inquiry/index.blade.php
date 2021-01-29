@extends('layouts.app')
@section('title', 'Manage Site Offers ')
@section('description', 'Manage Site Offers')

@section('content')
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Inquiry Listing</h1>
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
                           <h3 class="card-title">Inquiry Information</h3>
                            
                       </div>
                      <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>User</th>
                    <th>Area</th>
                    <th>User Type</th>
                    <th>Property Type</th>
                    <th>Min Budget</th>
                    <th>Max Budget</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $key =>$value)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$value->users->name}}</td>
                      <td>{{$value->area}}</td>
                      <td>{{$value->users->roles[0]->name}}</td>
                      <td>{{$value->property_type}}</td>
                      <td>{{$value->min_budget}}</td>
                      <td>{{$value->max_budget}}</td>
                      <td> 
                        {!! Form::open(['method' => 'DELETE','route' => ['inquiry.destroy', $value->id],'style'=>'display:inline']) !!}
                                <button onclick="return confirm('Are you sure you want to delete this record ?');" type="submit" name="delete" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>  
                                 
                            {!! Form::close() !!}
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                   <tr>
                    <th>Id</th>
                    <th>User</th>
                    <th>Area</th>
                    <th>User Type</th>
                    <th>Property Type</th>
                    <th>Min Budget</th>
                    <th>Max Budget</th>
                    <th >Action</th>
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










