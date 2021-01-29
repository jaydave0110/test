@extends('layouts.app')


@section('content')
 
    <section class="content">

      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row d-flex align-items-stretch">
            <div class="col-12 col-sm-12 col-md-12 d-flex align-items-stretch">
              <div class="card bg-light" style="width:100%;">
                <div class="card-header text-muted border-bottom-0">
                  @if(!empty($user->getRoleNames()))

                        @foreach($user->getRoleNames() as $v)

                             {{ $v }} 

                        @endforeach

                    @endif

                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-4">
                      <h2 class="lead"><b><i class="fas fa-user"></i> {{ ucfirst($user->name)}}</b></h2>
                      <p class="text-muted text-sm"><b>Specialized In / For : </b> {{$user->specialise_in}} / {{$user->specialise_for}} </p>
                      <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address: {{$user->address}}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone #: {{$user->phone}}</li>
                         
                      </ul>
                    </div>
                    <div class="col-3">
                        <ul class="ml-4 mb-0   fa-ul text-muted" style="margin-top: 70px;">
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> State: @if($user->states!=""){{$user->states->name}}@endif</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> City #: @if($user->cities!=""){{$user->cities->name}}@endif</li>
                      </ul>
                    </div>
                    <div class="col-5 text-center">
                      <img src="{{asset('/images/profilepic/'.$user->profile_pic)}}" alt="user-avatar" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                     
                    <a href="{{route('users.index')}}" class="btn btn-sm btn-primary">
                      <i class="fas fa-previous"></i> BACK
                    </a>
                  </div>
                </div>
              </div>
            </div>
             
            
          </div>
        </div>
        <!-- /.card-body -->
        
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->

    </section>
    

 
@endsection