<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BrokerUserManagement;
use Spatie\Permission\Models\Role;
use App\Models\Bookings;
 
use App\Models\SalesUserManagement;
use App\Models\Commission;

use DB; 
use Hash;
use Illuminate\Support\Arr;
use Response;
class BrokersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $data= User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
                return $query->where('name','=', 'broker');
            })->orderBy('id','desc')->paginate(10);
        return view('Brokers.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assignBroker(Request $request,$id)
    {
         
        $representative = $data= User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
            return $query->where('name','=', 'companyrepresentative');
        })->orderBy('id','desc')->get(['id','name']);

        $saleshead = $data= User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
            return $query->where('name','=', 'saleshead');
        })->orderBy('id','desc')->get(['id','name']);

        $brokerUserManagement = BrokerUserManagement::where('broker_id',$id)->first();

        return view('Brokers.assign',compact('representative','saleshead','brokerUserManagement'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeassign(Request $request)
    {
        $this->validate(request(),[
         'user_under'  => 'required',
        ]);

        $checkExists = BrokerUserManagement::where('broker_id',$request->id)->first();
        $broker_id = $request->id;

        if($request->user_under=="1")
        {
            $represent_id = $request->representative_id;
            $sales_head_id=null;
        }
        if($request->user_under=="2")
        {
            $represent_id = null;
            $sales_head_id=$request->sales_head_id;
        }
        if($request->user_under=="3")
        {
            $represent_id = $request->representative_id;
            $sales_head_id = $request->sales_head_id;
        }
        //dd($request->user_under,$sales_head_id,$represent_id);
        if($checkExists==""){
            $brokerUserManagement = new BrokerUserManagement();
            $brokerUserManagement->user_under = $request->user_under;
            $brokerUserManagement->broker_id = $broker_id;
            $brokerUserManagement->represent_id=$represent_id;
            $brokerUserManagement->sales_head_id=$sales_head_id;
            $brokerUserManagement->save();
        } else{
            $updateArray = [
                'user_under'=> $request->user_under,
                'broker_id'=> $broker_id,
                'represent_id'=>$represent_id,
                'sales_head_id'=>$sales_head_id
                            ];
            BrokerUserManagement::where('broker_id',$request->id)->update($updateArray);                
        }

        return redirect()->route('brokers.index')->with('success', 'Broker Assigned successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [];
        $data['title'] = "Broker Profile";
        $data['user']=User::with('states','cities')->where('id',$id)->first();
        
        return view('Brokers.show',compact('data'));
    }

    public function create(Request $request)
    {
        $data = [];
        return view('Brokers.create',compact('data'));
    } 

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone   = $request->phone;
        $user->city    = $request->city;
        $user->state   = $request->state;
        $user->address   = $request->address;
        $user->status   = 1;
        $user->save();
        $user->assignRole(6);

        return redirect()->route('brokers.index')->with('success', 'Broker Details added successfully');
    }

    public function edit($id)
    {
         $data =[];
        $data['user'] = User::where('id',$id)->first();

        return view('Brokers.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id',$id)->first();
        if($request->password!="")
        {
            $password = Hash::make($request->password);
        }  else {
            $password = $user->password;
        }

        $updateArray = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => $password,
                        'phone'    => $request->phone,
                        'city'     => $request->city,
                        'state'    => $request->state,
                        'address'  => $request->address
                    ];

        $result = User::where('id',$id)->update($updateArray);
         return redirect()->route('brokers.index')->with('success', 'Broker Details Updated successfully');           
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function brokerPending($user_id)
    {
        $matchPendingBooking = ['broker_id'=>$user_id,'booking_status'=>'0'];
        $data = [];
        $data['title'] ="Pending List";    

        /*$data['listing']  = DB::table('tbl_bookings')
                ->leftjoin('tbl_builder_sites','tbl_builder_sites.id','=','tbl_bookings.site_id')
                ->select('tbl_bookings.id','tbl_bookings.broker_id','tbl_bookings.customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'tbl_bookings.commission_amount','tbl_bookings.final_amount','tbl_builder_sites.site_name','tbl_bookings.bhk')
                ->where($matchPendingBooking)->get();*/




       $data['listing'] = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'sitesoffers'])->where($matchPendingBooking)->get();
        
        
         return view('Brokers.totalBookings',compact('data'));
         
    }

    public function brokerConfirm($user_id)
    {
        $matchconfirmBooking = ['broker_id'=>$user_id,'booking_status'=>'1'];
        $data = [];
        /*$data['bookings'] = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'sitesoffers'])->where($matchconfirmBooking)->paginate(10);*/

         $data = [];
        $data['title'] ="Confirm List";
       $data['listing'] = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'sitesoffers'])->where($matchconfirmBooking)->get();


        
         return view('Brokers.totalBookings',compact('data'));
         
    }

    public function brokerPercentage($user_id)
    {
        $matchPercentageBooking = ['broker_id'=>$user_id,'booking_status'=>'1','commission_type'=>'1'];
        $data = [];
        $data['title'] ="Percentage List";    
        $data['listing'] =     Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'sitesoffers'])->where($matchPercentageBooking)->get();
        return view('Brokers.totalBookings',compact('data')); 
    }


    public function brokerIndividual($user_id)
    {
        $matchIndividualBooking = ['broker_id'=>$user_id,'booking_status'=>'1','commission_type'=>'2'];
       
        
        $data = [];
        $data['title']="Individual Collection";    

        $data['listing']  = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'sitesoffers'])->where($matchIndividualBooking)->get();



        return view('Brokers.totalBookings',compact('data'));
        
    }

    public function brokerPackage($user_id)
    {
        $matchPackageBooking = ['broker_id'=>$user_id,'booking_status'=>'1'];
        $data = [];
        $data['title']="Package Listing";
        //Collection when fix pay is 0 consider Package
        $data['listing']  = Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','id','site_id','bhk')->where('commission_type',3)->where($matchPackageBooking)->paginate(10);

        return view('Brokers.totalBookings',compact('data'));
        
    }

    

}
