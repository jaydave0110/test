<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\SiteOffers;
use App\Models\Sites;
use App\Models\Properties;
use App\Models\Bookings;
use App\Models\BookingTotalAmount;
use App\Models\BookingDirectAmount;
use App\Models\BookingCashAmount;
use App\Models\BookingLoanAmount;

use App\Http\Requests\BookingsRequest;

use App\Http\Requests\BookingTotalRequest;
use App\Http\Requests\BookingCashRequest;
use App\Http\Requests\BookingLoanRequest;
use App\Http\Requests\BookingDirectRequest;

use Carbon\Carbon;
class BookingsController extends Controller
{
    public function __construct()
    { 
        $this->middleware('auth');
        $this->page_data['page_title'] = 'Site offers';
        $this->page_data['perpage'] = 10;
    }

    public function getBrokerList(Request $request)
    {
        
        $handle_by = (int) $request->handle_by;
        if ($handle_by > 0) {
            $brokerlist = User::role("broker")->select(['id', 'name'])->orderBy('name')
                            ->get(); 
       
        
            return response()->json(array('status' => 'success', 'data' => $brokerlist));
        }
        return response()->json(array('status' => 'error', 'data' => 'invalid request')); 
    }

    public function index(Request $request)
    {
        $Bookings = Bookings::with('sites','sitesoffers','users')->get();
        
        return view('Bookings.manage', compact('Bookings'))
                        ->with('i', ($request->input('SiteOffer', 1) - 1) * $this->page_data['perpage'])
                        ->with($this->page_data);
    }

     
    public function create(Request $request)
    {
        $Sites = Sites::pluck('site_name','id');
        $SiteOfferData = [''=>'Select Option'];
        return view('Bookings.create', compact('Sites','SiteOfferData'))
                        ->with('i', ($request->input('SiteOffer', 1) - 1) * $this->page_data['perpage'])
                        ->with($this->page_data);
    }

     
    public function store(BookingsRequest $request)
    {

        $Bookings = new Bookings();
        $Bookings->site_id = $request->site_id;
        $Bookings->package = $request->package;
        $Bookings->customer_name = $request->customer_name;
        $Bookings->mobile_number = $request->mobile_number;
        $Bookings->address = $request->address;
        $Bookings->email = $request->email;
        if($request->handle_by==0)
        {
            $company_represent_id = $request->represent_id;
            $broker_id = $request->broker_id;
        } 

        if($request->handle_by==1){
            $company_represent_id = "";
            $broker_id = $request->broker_id;
        }

        $Bookings->handle_by = $request->handle_by;
        $Bookings->broker_id = $broker_id;
        $Bookings->company_represent_id = $company_represent_id;
        $Bookings->amount = $request->amount;
        $Bookings->is_discount = $request->is_discount;
        $Bookings->discount_amount = $request->discount_amount;
        $Bookings->bhk = $request->bhk;
        $Bookings->units = $request->units;
        $Bookings->final_amount = $request->final_amount;
        $Bookings->booking_status = $request->booking_status;

        $Bookings->property_id=$request->property;
        $Bookings->commission_type=$request->commission_type;
        $Bookings->commission_amount=$request->commission_amount;
        
        $Bookings->save();
        return redirect()->route('bookings.index')->with('success', 'Booking Details added successfully');

    }

     
    public function show($id)
    {
        //
    }

    
    public function edit(Request $request,$id)
    {  


        $Bookings = Bookings::with('sites','sitesoffers','users')->where('id',$id)->first();
        $Sites = Sites::pluck('site_name','id');
        //$SitesSelected = Sites::where('id',$Bookings->site_id)->pluck('site_name','id');
        
        $Properties = Properties::where('site_id',$Bookings->site_id)->pluck('sub_title','id');

        $SiteOfferData = SiteOffers::where('property_id',$Bookings->property_id)->pluck('option_name','id');

        $BrokerData=  User::role("broker")->get(); 
        $Companyrepresentative=  User::role("companyrepresentative")->get(); 

        return view('Bookings.edit', compact('Bookings','Sites','Properties','SiteOfferData','BrokerData','Companyrepresentative'))
                        ->with('i', ($request->input('Bookings', 1) - 1) * $this->page_data['perpage'])
                        ->with($this->page_data);
    }

     
    public function update(BookingsRequest $request, $id)
    {
        if($request->handle_by==0)
        {
            $company_represent_id = $request->represent_id;
            $broker_id = null;
        } 

        if($request->handle_by==1){
            $company_represent_id = null;
            $broker_id = $request->broker_id;
        }


        $updateArray = ['site_id'=>$request->site_id,
                        'package'=>$request->package,
                        'customer_name'=>$request->customer_name,
                        'mobile_number'=>$request->mobile_number,
                        'address'=>$request->address,
                        'email'=>$request->email,
                        'handle_by'=>$request->handle_by,
                        'broker_id'=>$broker_id,
                        'company_represent_id'=>$company_represent_id,
                        'amount'=>$request->amount,
                        'is_discount'=>$request->is_discount,
                        'discount_amount'=>$request->discount_amount,
                        'bhk'=>$request->bhk,
                        'units'=>$request->units,
                        'final_amount'=>$request->final_amount,
                        'booking_status'=>$request->booking_status,
                        'property_id'=>$request->property,
                        'commission_type'=>$request->commission_type,
                        'commission_amount'=>$request->commission_amount
                       ];

        Bookings::where('id',$id)->update($updateArray);
        return redirect()->route('bookings.index')->with('success', 'Booking Details updated successfully');               
    }

     
    public function destroy($id)
    {
        
        $Bookings=Bookings::find($id);
        $Bookings->delete();
        return redirect()->back()->with('success','Bookings deleted successfully');
    }


    public function payments(Request $request,$id)
    {
        $bookingid = $id;
        $BookingTotalDetails = BookingTotalAmount::where('booking_id',$id)->first();
        $BookingDirectAmount = BookingDirectAmount::where('booking_id',$id)->orderBy('id','desc')->get();
        $BookingCashAmount = BookingCashAmount::where('booking_id',$id)->orderBy('id','desc')->get();
        $BookingLoanAmount = BookingLoanAmount::where('booking_id',$id)->orderBy('id','desc')->first();

        return view('Bookings.payments', compact('bookingid','BookingTotalDetails','BookingDirectAmount','BookingCashAmount','BookingLoanAmount'))
                        ->with('i', ($request->input('Bookings', 1) - 1) * $this->page_data['perpage'])
                        ->with($this->page_data);
    }

    public function storetotalamount(BookingTotalRequest $request)
    {   
        $BookingTotalAmount = new BookingTotalAmount();

        if($request->payment_type==1){

            if ($request->hasFile('cheque_image')) {
                $image = $request->file('cheque_image');
                $cheque_image = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/totalBooking/chequeImage');
                $image->move($destinationPath, $cheque_image);
            } else {
                $cheque_image ="";
            }

            $cheque_number = $request->cheque_number;
            $bank_name = $request->bank_name;
            $date_of_cheque =  Carbon::createFromFormat('d/m/Y', $request->date_of_cheque)->format('Y-m-d');
            $date_of_cheque = date('Y-m-d H:i:s', strtotime($date_of_cheque)) ;
            $BookingTotalAmount->cheque_number =$cheque_number  ; 
            $BookingTotalAmount->bank_name = $bank_name ; 
            $BookingTotalAmount->date_of_cheque =  $date_of_cheque;
            $BookingTotalAmount->cheque_image = $cheque_image ;
        } 

        if($request->payment_type==2){

            if ($request->hasFile('online_photo')) {
                $image = $request->file('online_photo');
                $online_photo = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/totalBooking/OnlineImage');
                $image->move($destinationPath, $online_photo);
            } else {

                $online_photo ="";
            }
        }
        
        $BookingTotalAmount->booking_id = $request->booking_id ; 
        $BookingTotalAmount->amount = $request->amount ; 
        $BookingTotalAmount->status = $request->status; 

        $amount_date =  Carbon::createFromFormat('d/m/Y', $request->amount_date)->format('Y-m-d');
        $BookingTotalAmount->amount_date = date('Y-m-d H:i:s', strtotime($amount_date));  
        $BookingTotalAmount->payment_type = $request->payment_type ; 
         
        $BookingTotalAmount->transaction_id = isset($request->transaction_id) ? $request->transaction_id: ''; 
        $BookingTotalAmount->payment_mode = isset($request->payment_mode) ? $request->payment_mode: '' ; 
        $BookingTotalAmount->online_photo = isset($request->online_photo) ? $online_photo: '' ;   
        $BookingTotalAmount->save();

        return redirect()->back()->with('success','Booking Total Amount Details Saved successfully');
         
    }

    public function storedirectamount(BookingDirectRequest $request)
    {
        $BookingDirectAmount = new BookingDirectAmount();

        if($request->payment_type==1){
            
            if ($request->hasFile('cheque_image')) {
                $image = $request->file('cheque_image');
                $cheque_image = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/DirectBooking/chequeImage');
                $image->move($destinationPath, $cheque_image);
            } else {
                $cheque_image ="";
            }

            $cheque_number = $request->cheque_number;
            $bank_name = $request->bank_name;
            $date_of_cheque =  Carbon::createFromFormat('d/m/Y', $request->date_of_cheque)->format('Y-m-d');
            $date_of_cheque = date('Y-m-d H:i:s', strtotime($date_of_cheque)) ;
            $BookingDirectAmount->cheque_number =$cheque_number  ; 
            $BookingDirectAmount->bank_name = $bank_name ; 
            $BookingDirectAmount->date_of_cheque =  $date_of_cheque;
            $BookingDirectAmount->cheque_image = $cheque_image ;
        } 

        if($request->payment_type==2){

            if ($request->hasFile('online_photo')) {
                $image = $request->file('online_photo');
                $online_photo = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/DirectBooking/OnlineImage');
                $image->move($destinationPath, $online_photo);
            } else {

                $online_photo ="";
            }
        }
        
        $BookingDirectAmount->booking_id = $request->booking_id ; 
        $BookingDirectAmount->amount = $request->amount ; 
        $BookingDirectAmount->status = $request->status; 
        $amount_date =  Carbon::createFromFormat('d/m/Y', $request->amount_date)->format('Y-m-d');
        $BookingDirectAmount->amount_date = date('Y-m-d H:i:s', strtotime($amount_date));  
        $BookingDirectAmount->payment_type = $request->payment_type ; 
         
        $BookingDirectAmount->transaction_id = isset($request->transaction_id) ? $request->transaction_id: ''; 
        $BookingDirectAmount->payment_mode = isset($request->payment_mode) ? $request->payment_mode: '' ; 
        $BookingDirectAmount->online_photo = isset($request->online_photo) ? $online_photo: '' ;   
        $BookingDirectAmount->save();


        return redirect()->back()->with('success','Booking Direct Amount Details Saved successfully');
    }

    public function storecashamount(BookingCashRequest $request)
    {
        $BookingCashAmount = new BookingCashAmount();

        if($request->payment_type==1){
            
            if ($request->hasFile('cheque_image')) {
                $image = $request->file('cheque_image');
                $cheque_image = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/CashBooking/chequeImage');
                $image->move($destinationPath, $cheque_image);
            } else {
                $cheque_image ="";
            }

            $cheque_number = $request->cheque_number;
            $bank_name = $request->bank_name;
            $date_of_cheque =  Carbon::createFromFormat('d/m/Y', $request->date_of_cheque)->format('Y-m-d');
            $date_of_cheque = date('Y-m-d H:i:s', strtotime($date_of_cheque)) ;
            $BookingCashAmount->cheque_number =$cheque_number  ; 
            $BookingCashAmount->bank_name = $bank_name ; 
            $BookingCashAmount->date_of_cheque =  $date_of_cheque;
            $BookingCashAmount->cheque_image = $cheque_image ;
        } 

        if($request->payment_type==2){

            if ($request->hasFile('online_photo')) {
                $image = $request->file('online_photo');
                $online_photo = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/CashBooking/OnlineImage');
                $image->move($destinationPath, $online_photo);
            } else {

                $online_photo ="";
            }
        }
        
        $BookingCashAmount->booking_id = $request->booking_id ; 
        $BookingCashAmount->amount = $request->amount ; 
        $BookingCashAmount->status = $request->status;
        
        $amount_date =  Carbon::createFromFormat('d/m/Y', $request->amount_date)->format('Y-m-d');

        $BookingCashAmount->amount_date = date('Y-m-d H:i:s', strtotime($amount_date)); 
        $BookingCashAmount->payment_type = $request->payment_type ; 
         
        $BookingCashAmount->transaction_id = isset($request->transaction_id) ? $request->transaction_id: ''; 
        $BookingCashAmount->payment_mode = isset($request->payment_mode) ? $request->payment_mode: '' ; 
        $BookingCashAmount->online_photo = isset($request->online_photo) ? $online_photo: '' ;   
        $BookingCashAmount->save();


        return redirect()->back()->with('success','Booking Cash Amount Details Saved successfully');
    }

    public function storeloanamount(BookingLoanRequest $request)
    {
        $BookingLoanAmount = new BookingLoanAmount();

        $BookingLoanAmount->booking_id= $request->booking_id;
        $BookingLoanAmount->bank_name= $request->bank_name;
        $BookingLoanAmount->amount_sanction= $request->amount_sanction;
        $BookingLoanAmount->la_amount= $request->la_amount;
        $BookingLoanAmount->emi= $request->emi;
        $BookingLoanAmount->save();
        return redirect()->back()->with('success','Booking Loan Details Saved successfully');
         
    }

    public function directpaymentinfo($bookingid,$id)
    {
        $BookingDirectAmount = BookingDirectAmount::where('booking_id',$bookingid)->where('id',$id)->orderBy('id','desc')->first();
        return view('Bookings.directpaymentinfo',compact('BookingDirectAmount'));

    } 
    public function cashpaymentinfo($bookingid,$id)
    {
        $BookingCashAmount = BookingCashAmount::where('booking_id',$bookingid)->where('id',$id)->orderBy('id','desc')->first();
        return view('Bookings.cashpaymentinfo',compact('BookingCashAmount'));

    } 

    public function deletedirectpayment($id)
    {
        $BookingDirectAmount = BookingDirectAmount::find($id);
        $BookingDirectAmount->delete();
        return redirect()->back()->with('success','Record Deleted successfully');
    }
    public function deletecashpayment($id)
    {
        $BookingCashAmount = BookingCashAmount::find($id);
        $BookingCashAmount->delete();
        return redirect()->back()->with('success','Record Deleted successfully');
    }


}
