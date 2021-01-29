@extends('layouts.app')
@section('title', 'Manage Site Offers ')
@section('description', 'Manage Site Offers')

@section('content')
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>{{$data['title']}}</h1>
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
            
            

            <div class="col-md-12 d-flex align-items-stretch">
              <div class="card bg-light" style="width: 100%;">
                <div class="card-header text-muted border-bottom-0">
                  
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-8">
                      <h2 class="lead"><b>{{ strtoupper($data['user']->name)}}</b></h2>
                      <p class="text-muted text-sm">  </p>
                      <table class="table table-striped">
                    <thead>
                     
                    </thead>
                    <tbody>
                       
                      <tr  >
                        <th><span><i class="fas fa-lg fa-envelope"></i></span> Mail : </th>
                        <th>{{ $data['user']->email}}</th>  
                      </tr>
                      <tr >
                         
                        <th><i class="fas fa-lg fa-building"></i> Address:</th>
                        <th>{{ ucfirst($data['user']->address)}}</th>
                         
                      </tr>

                      <tr  >
                        <th><span><i class="fas fa-archway"></i></span> State :</th>
                        <th>@if($data['user']->states!=""){{ ucfirst($data['user']->states->name) }}@endif</th>  
                      </tr>
                      <tr  >
                        <th><span><i class="fas fa-city"></i></span> City : </th>
                        <th>@if($data['user']->cities!=""){{ ucfirst($data['user']->cities->name)}}@endif</th>  
                      </tr>

                      <tr  >
                        <th><span><i class="fas fa-lg fa-phone"></i></span> Phone #: +</th>
                        <th>{{$data['user']->phone}}</th>  
                      </tr>
                      <tr  >
                        <th><span><i class="fas fa-city"></i></span> Status : </th>
                        <th>@if($data['user']->status==0) <span class="badge bg-danger">Inactive</span> @else <span class="badge bg-success">Active</span>  @endif</th>  
                      </tr>


                       
                      
                      
                    </tbody>
                  </table>
                    </div>
                    <div class="col-4 text-center">
                      @if($data['user']->profile_pic!="")
                      <img src="{{asset('public/images/profileImage/'.$data['user']->profile_pic)}}" alt="no-image" class="img-circle img-fluid" style="margin-top:42px">
                      @else
                       <img src="{{asset('public/images/noimage.png')}}" alt="user-avatar" class="img-circle img-fluid" style="margin-top: 40px;">
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                     
                     
                    <a href="{{ route('cityhead.index') }}" class="btn btn-sm btn-primary"   >Back</a>
                  </div>
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










