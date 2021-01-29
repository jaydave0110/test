<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use App\Http\Requests\CompanyRepresentativeRequest;
use Response;
use App\Models\Bookings;
use App\Models\BrokerUserManagement;
use App\Models\SalesUserManagement;
use App\Models\Commission;


class CompanyRepresentativeController extends Controller
{
    public function getRepresentativeList(Request $request)
    {
         $data= User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
            return $query->where('name','=', 'companyrepresentative');
        })->orderBy('id','desc')->get();

        return Response::json(['status'=>1,'data'=>$data]);

    }
    public function index(Request $request)
    {
         
         $data= User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
            return $query->where('name','=', 'companyrepresentative');
        })->orderBy('id','desc')->paginate(10);

        return view('CompanyRepresentative.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);;
    }

     
    public function create()
    {

        return view('CompanyRepresentative.create');
    }

     
    public function store(CompanyRepresentativeRequest $request)
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
        $user->assignRole(8);
        return redirect()->route('companyrepresentative.index')->with('success', 'Representative Details added successfully');

    }

     
    public function show($id)
    {
        $data = [];
        $data['title']= 'Company Representative Profile';
        $data['user']=User::with('states','cities')->where('id',$id)->first();
        
        return view('CompanyRepresentative.show',compact('data'));
    }

     
    public function edit($id)
    {
        $data =[];
        $data['user'] = User::where('id',$id)->first();
        return view('CompanyRepresentative.edit',compact('data'));
    }

     
    public function update(CompanyRepresentativeRequest $request, $id)
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
        return redirect()->route('companyrepresentative.index')->with('success', 'Representative Details updated successfully');              
    }

     
    public function destroy($id)
    {
        
        $user = User::find($id);
         
        $result= $user->delete();
        return redirect()->route('companyrepresentative.index')->with('success','Record Deleted Successfully');
    }


    public function companyTotalSalesHead(Request $request ,$id)
    {
        $data = [];
        $data['title'] ="Total Sales Head";
        $query = BrokerUserManagement::where('represent_id',$id)->pluck('sales_head_id');

        $data['listing'] =   User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
                return $query->where('name','=', 'saleshead');
            })->whereIn('id',$query)->orderBy('id','desc')->get();

        return view('CompanyRepresentative.totalsaleshead',compact('data'));
         
    }

    public function companyTotalBrokers(Request $request,$id)
    {   
        $data = [];
        $data['title'] ="Total Brokers";

         $query = BrokerUserManagement::where('represent_id',$id)->pluck('sales_head_id');

        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query

        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');

        $data['listing'] =   User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
                return $query->where('name','=', 'broker');
            })->whereIn('id',$brokerUnderSalesHead)->orderBy('id','desc')->get();


        return view('CompanyRepresentative.totalsaleshead',compact('data'));


    }

    public function companyTotalBookings(Request $request,$id)
    {   
        $data = [];
        $data['title'] ="Total Bookings";
        $data['listing'] = Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','units','final_amount','id','site_id','bhk')->where('company_represent_id',$id)->where('booking_status','1')->get();
        return view('CompanyRepresentative.totalBookings',compact('data'));
    }    


    public function companyTotalBrokerBookings(Request $request,$id)
    {   
        $data = [];
        $data['title'] ="Total Broker Bookings";
        $query = BrokerUserManagement::where('represent_id',$id)->pluck('sales_head_id');

        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query

        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');


        $data['listing'] = Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','units','final_amount','id','site_id','bhk')->whereIn('broker_id',$brokerUnderSalesHead)->where('booking_status',1)->get();
        return view('CompanyRepresentative.totalBookings',compact('data'));
    }

}
