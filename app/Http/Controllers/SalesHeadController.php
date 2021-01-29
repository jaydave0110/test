<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesUserManagement;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use App\Http\Requests\SalesHeadRequest;

use App\Models\Bookings;
use App\Models\BrokerUserManagement;
 
use App\Models\Commission;


class SalesHeadController extends Controller
{
    
    public function index(Request $request)
    {
         
         $data= User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
            return $query->where('name','=', 'saleshead');
        })->orderBy('id','desc')->paginate(10);

        return view('SalesHead.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);;
    }

     
    public function create()
    {
         
        $representative = $data= User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
            return $query->where('name','=', 'companyrepresentative');
        })->orderBy('id','desc')->get(['id','name']);

        $cityhead = $data= User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
            return $query->where('name','=', 'cityhead');
        })->orderBy('id','desc')->get(['id','name']);

        return view('SalesHead.create',compact('representative','cityhead'));
    }

     
    public function store(SalesHeadRequest $request)
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
        $user->assignRole(4);

        $sales_head_id = $user->id;

        if($request->user_under=="1")
        {
            $represent_id = $request->representative_id;
            $city_head_id=null;
        }
        if($request->user_under=="2")
        {
            $represent_id = null;
            $city_head_id=$request->city_head_id;
        }
        if($request->user_under=="3")
        {
            $represent_id = $request->representative_id;
            $city_head_id = $request->city_head_id;
        }

     
        $salesUserManagement = new SalesUserManagement();

        $salesUserManagement->user_under = $request->user_under;
        $salesUserManagement->sales_head_id = $sales_head_id;
        $salesUserManagement->represent_id=$represent_id;
        $salesUserManagement->city_head_id=$city_head_id;
        $salesUserManagement->save();


        return redirect()->route('saleshead.index')->with('success', 'Sales Head Details added successfully');

    }

     
    public function show($id)
    {
        $data = [];
        $data['title']= 'Sales Head Profile';
        $data['user']=User::with('states','cities')->where('id',$id)->first();
        return view('SalesHead.show',compact('data'));
    }

     
    public function edit($id)
    {
        $data =[];
        $data['user'] = User::where('id',$id)->first();

        $representative =   User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
            return $query->where('name','=', 'companyrepresentative');
        })->orderBy('id','desc')->get(['id','name']);

        $cityhead =   User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
            return $query->where('name','=', 'cityhead');
        })->orderBy('id','desc')->get(['id','name']);

        $salesUserManagement = SalesUserManagement::where('sales_head_id',$id)->first();

        return view('SalesHead.edit',compact('data','representative','cityhead','salesUserManagement'));
    }

     
    public function update(SalesHeadRequest $request, $id)
    {
        $getRecord = User::where('id',$id)->first();
         //dd($request->all());
        if($request->has('password'))
        {
            $password = Hash::make($request->password);
        } else 
        {
            $password = $getRecord->password;
        }

        $updateArray = [
                         'name'=>$request->name,   
                         'email'=>$request->email,   
                         'password'=>$password,   
                         'phone'=>$request->phone,   
                         'city'=>$request->city,   
                         'state'=>$request->state,   
                         'address'=>$request->address,   
                        ]; 

        $user = User::where('id',$id)->update($updateArray);


        $sales_head_id = $id;

        if($request->user_under=="1")
        {
            $represent_id = $request->representative_id;
            $city_head_id=null;
        }
        if($request->user_under=="2")
        {
            $represent_id = null;
            $city_head_id=$request->city_head_id;
        }
        if($request->user_under=="3")
        {
            $represent_id = $request->representative_id;
            $city_head_id = $request->city_head_id;
        }

        $updateSalesArray = [
                    'user_under'=> $request->user_under,
                    'sales_head_id'=> $sales_head_id,
                    'represent_id'=> $represent_id,
                    'city_head_id'=> $city_head_id
                        ];

                         

        SalesUserManagement::where('sales_head_id',$id)->update($updateSalesArray);





        return redirect()->route('saleshead.index')->with('success', 'Sales Head Details updated successfully');              
    }

     
    public function destroy($id)
    {
        $user = User::find($id);
         
        $result= $user->delete();
        return redirect()->route('saleshead.index')->with('success','Record Deleted Successfully');
    }

    public function salesheadTotalBroker($sales_head_id)
    {
        $query = BrokerUserManagement::where('sales_head_id',$sales_head_id)->pluck('broker_id');
        $data = [];
        $data['title'] ="Total Brokers";
        $data['listing'] =   User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
                return $query->where('name','=', 'broker');
            })->whereIn('id',$query)->orderBy('id','desc')->get();

        return view('SalesHead.totalsaleshead',compact('data'));

    } 


    public function salesheadTotalBooking($sales_head_id)
    {
        $data = [];
        $data['title'] ="Total Bookings";
        $query = BrokerUserManagement::where('sales_head_id',$sales_head_id)->pluck('broker_id');

        $data['listing']  = Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','id','site_id','bhk')->whereIn('broker_id',$query)->where('booking_status','1')->get();
        return view('SalesHead.totalBookings',compact('data'));        
    }


    public function salesheadTotalCommission($sales_head_id)
    {
        $data = [];
        $data['title'] ="Total Commission";
        $query = BrokerUserManagement::where('sales_head_id',$sales_head_id)->pluck('broker_id');
        

        $data['listing']  = Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','id','site_id','bhk')->whereIn('broker_id',$query)->where('booking_status','1')->get();
        return view('SalesHead.totalBookings',compact('data'));        
    }


    
}
