<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Response;
use Spatie\Permission\Models\Role; 
use Illuminate\Support\Arr;
use App\Models\Helpers;

use Mail;
 
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;

use App\Models\Areas;
use App\Models\Cities; 
use App\Models\States;
use App\Models\Countries; 
use App\Models\Banks;
use App\Models\Sites;
use App\Models\SitesImages;
use App\Models\SiteMarketers;
use App\Models\SiteMetas;
use App\Models\SiteLoans;
use App\Models\SiteOffers;
use App\Models\FurnitureDetails;

use App\Models\Properties;
use App\Models\PropertyCategory; 
use App\Models\PropertySubCategory;
use App\Models\Bookings;
use App\Models\BrokerUserManagement;
use App\Models\SalesUserManagement;
use App\Models\Commission;
use App\Models\Inquiry;

use DB; 

use Auth;


class BrokerController extends Controller
{
    public function registerBroker(Request $request)
    {
        
        if($request->name!="" || $request->email!="" || $request->password!="" || $request->user_type){

        	$input = $request->all();
        	//check email taken or not
        	$checkemail = User::where('email',$request->email)->first();
        	if($checkemail==null){
	        	$password = Hash::make($input['password']);
	        	$user = new User();
	        	$user->name = $request->name;
	        	$user->email = $request->email;
	        	$user->password = $password;
                $user->phone   = $request->phone;
                $user->city    = $request->city;
                $user->state   = $request->state;
	        	if($user->save()) 
	            {	$user->assignRole($request->user_type);
	    	        // send welcome note email to new registered user
	    	       // \Mail::to($request->email)->send(new UserRegister($request->fullname));
	            	return Response::json([
	                        'status' => 1,
	                        'message' => 'Registration successfully done'
	                    ]);
	        	}
	        	else
	        	{
	        		return Response::json(['status' => 0,'message'=>'Registration did not successfully']);
	        	}

        	}
        	else
	        {
	            	return Response::json(['status' => 0,'message'=>'Email Already taken try again']);
	        }	
        }
    }

    public function login(Request $request)
    {
    	$data = [];
    	$email = $request->email;
        $password = $request->password;

        $userArray = User::where('email',$email)->first();
        if($userArray!=''){

            //Check Status Active or not

            $checkStatus = User::where('email',$email)->where('status',1)->first();

            if($checkStatus!=""){
               $userPassword = $userArray['password'];
                if(Hash::check($password,$userPassword)){

                    
                    if($userArray->hasRole(['broker','user','companyrepresentative']))
                    {
                         
    				    return Response::json(['status'=>1,'message'=>'Successfully LoggedIn','data'=>$userArray]);
                    } else {
                        return Response::json(['status'=>0,'message'=>'User has Not Assigned Roles Contact Administrator']);   
                    }

                } else {
                    return Response::json(['status'=>0,'message'=>'Password Field Is Incorrect']);
                }
                
            } else {
                return Response::json(['status'=>0,'message'=>'User status is inactive please contact administrator']);   
            }    


        } else {
            return Response::json(['status'=>0,'message'=>'Invalid Credentials']); 
        }
    }

    public function forgotsPassword(Request $request)
    {
    	$user = User::where('id',$request->user_id)->first();
        $userEmail = $user->email;
        $checkemail = User::where('email',$request->email)->first();
        if($checkemail!=null){
            if ($checkemail->email==$user->email ){
                

                $isVerified = User::select(['sms_verified'])->where('id', $request->user_id)->first();
        
		        if ($isVerified->sms_verified == '0') {
		            
		            // generate otp
		            $otp = (new \Rych\Random\Random())->getRandomInteger(100000, 999999);

		            $user = User::find($request->user_id);
		            $user->phone = $request->phone;
		            $user->sms_verification_code = $otp;
		            $user->sms_verified = '0';
		            $user->updated_at = date('Y-m-d H:i:s');
		            $user->save();

		            // send otp for verification of offer to builder
		            Helpers::sendSMS($request->phone, sprintf(Helpers::smsTemplate('forgotPasswordUser'), $otp));
		            
		            return Response::json(['status'=>1,'message'=>'Enter Valid OTP and procced']);
		        }

		        return false;

            }  
        } else {
        	return Response::json(['status'=>0,'message'=>'Email is invalid. Please try again']);
        }
    }

    public function updateProfile(Request $request)
    {
        $userArray = User::where('id',$request->user_id)->first();
        if($userArray!=''){
            
            if ($request->hasFile('profile_pic')) {
                $image = $request->file('profile_pic');
                $profile_pic = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/profileImage');
                $image->move($destinationPath, $profile_pic);
            } else {
                $profile_pic ="";
            }


            $updateArray = ['name'=>$request->name,'phone'=>$request->phone,'city'=>$request->city,'state'=>$request->state,'address'=>$request->address,'profile_pic'=>$profile_pic,'specialise_in'=>$request->specialise_in,'specialise_for'=>$request->specialise_for,'upload_via_api'=>1];
            $result = User::where('id',$request->user_id)->update($updateArray);

            if($result==1){
                $userArray = User::with('cities')->where('id',$request->user_id)->first();
                return Response::json(['status'=>1,'message'=>'Profile Successfully Updated','data'=>$userArray]);
            } else {
                return Response::json(['status'=>0,'message'=>'Something Went Wrong Try Again..']);     
            }
        } else {
            return Response::json(['status'=>0,'message'=>'No User Found']); 
        }
    }

    public function brokerdashboard(Request $request)
    {
        
        if($request->has('user_id'))
        {
            $data = [];
            $matchPendingBooking = ['broker_id'=>$request->user_id,'booking_status'=>'0'];
            $data['mybooking_pending'] = Bookings::where($matchPendingBooking)->count();
            
            $matchConfirmedBooking = ['broker_id'=>$request->user_id,'booking_status'=>'1'];
            $data['mybooking_confirmed'] = Bookings::where($matchConfirmedBooking)->count();

            // Collection based on Percentage 
            $matchPercentageBooking = ['broker_id'=>$request->user_id,'booking_status'=>'1','commission_type'=>'1'];           
            $mycollection_percentage = Bookings::with(['properties'])->where($matchPercentageBooking)->sum('commission_amount');
           
            $data['mycollection_percentage'] =$mycollection_percentage;

            // Collection based on Individual when fix pay           
             
            $matchIndividualBooking = ['broker_id'=>$request->user_id,'booking_status'=>'1','commission_type'=>'2'];           
            $individualDetails = Bookings::with(['properties'])->where($matchIndividualBooking)->sum('commission_amount');

            $data['mycollection_individual'] =$individualDetails ;
            
            //Collection when fix pay is 0 consider Package
            $matchPackageBooking = ['broker_id'=>$request->user_id,'booking_status'=>'1','commission_type'=>'3'];           
            $packageDetails = Bookings::with(['properties'])->where($matchPackageBooking)->count();
             
            $data['mycollection_package'] =$packageDetails;
           

            if($data){
                return Response::json(['status'=>1,'data'=>$data]);
            } else {
                return Response::json(['status'=>0,'message'=>'No Record Found']);
            }

        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
    }

    public function myPendingBooking(Request $request)
    {
        $matchPendingBooking = ['broker_id'=>$request->user_id,'booking_status'=>'0'];
         

        $data['bookings']  = DB::table('tbl_bookings')
                ->leftjoin('tbl_builder_sites','tbl_builder_sites.id','=','tbl_bookings.site_id')
                ->select('tbl_bookings.id','tbl_bookings.broker_id','tbl_bookings.customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'tbl_bookings.final_amount','tbl_builder_sites.site_name','tbl_bookings.bhk')
                ->where($matchPendingBooking)->paginate(10);




       /*$data['bookings'] = Bookings::select("customer_name","DB::raw('DATE(created_at) AS submitted'))",'final_amount')->with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'sitesoffers'])->where($matchPendingBooking)->paginate(10);
      */  
        
        if($data['bookings']->total()>0){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
         
    }

    public function myConfirmedBooking(Request $request)
    {
        $matchconfirmBooking = ['broker_id'=>$request->user_id,'booking_status'=>'1'];
        $data = [];
        /*$data['bookings'] = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'sitesoffers'])->where($matchconfirmBooking)->paginate(10);*/


       $data['bookings'] = DB::table('tbl_bookings')
                ->leftjoin('tbl_builder_sites','tbl_builder_sites.id','=','tbl_bookings.site_id')
                ->select('tbl_bookings.id','tbl_bookings.broker_id','tbl_bookings.customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'tbl_bookings.commission_amount','tbl_builder_sites.site_name','tbl_bookings.bhk','tbl_bookings.final_amount')
                ->where($matchconfirmBooking)->paginate(10);


        
        if($data['bookings']->total()>0){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
         
    }

    public function myPercentage(Request $request)
    {
        $matchPercentageBooking = ['broker_id'=>$request->user_id,'booking_status'=>'1','commission_type'=>'1'];
         /*$data['bookings']= Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','id','site_id','bhk')->where($matchPercentageBooking)->paginate(10);
       $data['bookings'] = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        }])->where($matchPendingBooking)->paginate(10);*/
            
        $data['bookings'] =    Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'properties','sitesoffers'])->where($matchPercentageBooking)->paginate(10);


        if($data['bookings']->total()>0){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
        
    }

    public function myCollectionIndividual(Request $request)
    {
        $matchIndividualBooking = ['broker_id'=>$request->user_id,'booking_status'=>'1','commission_type'=>'2'];

        $data['bookings']  = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'properties','sitesoffers'])->where($matchIndividualBooking)->paginate(10);

        if($data['bookings']->total()>0){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
        
    }

    public function myCollectionPackage(Request $request)
    {
        $matchPackageBooking = ['broker_id'=>$request->user_id,'booking_status'=>'1'];
        $data = [];
        //Collection when fix pay is 0 consider Package
        $data['bookings']  = Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','id','site_id','bhk')->where('commission_type',3)->where($matchPackageBooking)->paginate(10);

        if($data['bookings']->total()>0){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
        
    }

    public function pendingDetails(Request $request)
    {
        $matchPendingBooking = ['broker_id'=>$request->broker_id,'booking_status'=>'0','id'=>$request->id];

        
        $data  = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');            
        },'properties','sitesoffers'])->where($matchPendingBooking)->first();

        if($data !=""){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }

    }

    public function confirmedDetails(Request $request)
    {
        $matchconfirmBooking = ['broker_id'=>$request->broker_id,'booking_status'=>'1','id'=>$request->id];

        $data = [];
        $data  = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'properties','sitesoffers'])->where($matchconfirmBooking)->first();

        if($data!=""){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }

    }

    public function percentageDetails(Request $request)
    {
        $matchPendingBooking = ['broker_id'=>$request->broker_id,'booking_status'=>'1','id'=>$request->id,'commission_type'=>'1'];
         

        $data = Bookings::with(['users'=>function($query){
                    $query->addSelect('id','name','phone');
                },'properties','sites'=>function($query){
                    $query->addSelect('id','site_name');
                },'properties','sitesoffers'])
            ->where($matchPendingBooking)->first();

        /*$data['bookings'] = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        }])->where($matchPendingBooking)->paginate(10);*/
        
        if($data!=""){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
        
    }

    public function individualDetails(Request $request)
    {
        $matchIndividualBooking = ['broker_id'=>$request->broker_id,'booking_status'=>'1','id'=>$request->id,'commission_type'=>'2'];

       
        $data = Bookings::with(['users'=>function($query){
                    $query->addSelect('id','name','phone');
                },'properties','sites'=>function($query){
                $query->addSelect('id','site_name');
            },'sitesoffers'])->where($matchIndividualBooking)->first();
        
       
        if($data!=""){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
       
    }

    public function collectionPackageDetail(Request $request)
    {
        $collectionPackageDetail = ['broker_id'=>$request->broker_id,'booking_status'=>'1','id'=>$request->id,'commission_type'=>'3'];
        $data = [];
        //Collection when fix pay is 0 consider Package
        $data  = Bookings::with(['users'=>function($query){
                    $query->addSelect('id','name','phone');
                },'properties','sites'=>function($query){
                $query->addSelect('id','site_name');
            }])->where($collectionPackageDetail)->first();

        if($data!=""){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
        
    }



    public function salesHeadDashboard(Request $request)
    {
        $query = BrokerUserManagement::where('sales_head_id',$request->sales_head_id)->pluck('broker_id');

        $totalBookings = Bookings::whereIn('broker_id',$query)->where('booking_status',1)->count();
        $totalCommission = Bookings::whereIn('broker_id',$query)->where('booking_status',1)->get();


        $checkSalesHeadCommission = Commission::where('sales_head',$request->sales_head_id)->first();
        $commission = $checkSalesHeadCommission->sales_head_commission;
        $data = [];
        $data['totalBroker']=count($query);
        $data['totalBookings']=$totalBookings;
        $data['totalCommission']=count($totalCommission)*$commission;
        if($data!=""){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
         
    }

    public function getSalesHeadTotalBroker(Request $request)
    {   
        $query = BrokerUserManagement::where('sales_head_id',$request->sales_head_id)->pluck('broker_id');
        $brokerlist =   User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
                return $query->where('name','=', 'broker');
            })->whereIn('id',$query)->orderBy('id','desc')->get();

         if($brokerlist!=""){
            return Response::json(['status'=>1,'data'=>$brokerlist]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
         
    }

    public function getSalesHeadTotalBookings(Request $request)
    {   
        $query = BrokerUserManagement::where('sales_head_id',$request->sales_head_id)->pluck('broker_id');
        $totalBookings = Bookings::whereIn('broker_id',$query)->where('booking_status',1)->get();

        $totalBookings  = DB::table('tbl_bookings')
                ->leftjoin('tbl_builder_sites','tbl_builder_sites.id','=','tbl_bookings.site_id')
                ->select('tbl_bookings.id','tbl_bookings.broker_id','tbl_bookings.customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'tbl_bookings.final_amount','tbl_builder_sites.site_name','tbl_bookings.bhk')
                ->whereIn('broker_id',$query)->where('booking_status','1')->paginate(10);;

        
        if($totalBookings!=""){
            return Response::json(['status'=>1,'data'=>$totalBookings]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
         
    }

    public function getSaleBookingDetail(Request $request)
    {
        $data  = Bookings::with(['users'=>function($query){
                    $query->addSelect('id','name','phone');
                },'properties','sites'=>function($query){
                $query->addSelect('id','site_name');
            }])->where('id',$request->id)->first();
        if($data!=""){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
    }

    public function getSalesHeadCommissionDetail(Request $request)
    {   
        $query = BrokerUserManagement::where('sales_head_id',$request->sales_head_id)->pluck('broker_id');
        $data['bookings']  = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'properties','sitesoffers'])->whereIn('broker_id',$query)->where('booking_status','1')->paginate(10);


        if($data['bookings']->total()>0){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
         
    }

    /*****City Head Api starts Here **/


    /*** City Head Api Here*/
    public function cityheadcommissiondetail(Request $request)
    {
        
        $matchCase = ['id'=>$request->id,'booking_status'=>'1'];
        $data  = Bookings::with(['users'=>function($query){
                    $query->addSelect('id','name','phone');
                },'properties','sites'=>function($query){
                $query->addSelect('id','site_name');
            }])->where($matchCase)->first();

        if($data!=""){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
    }


    public function cityHeadDashboard(Request $request)
    {
        $query = SalesUserManagement::where('city_head_id',$request->city_head_id)->pluck('sales_head_id');

        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query

        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');
       

        $totalBookings = Bookings::whereIn('broker_id',$brokerUnderSalesHead)->where('booking_status',1)->count();
         

        $checkSalesHeadCommission = Commission::where('city_head',$request->city_head_id)->first();

        
        $commission = $checkSalesHeadCommission->city_head_commission;
        $data = [];
        $data['totalSalesHead']=count($query);
        $data['totalBroker']=count($brokerUnderSalesHead);
        $data['totalBookings']=$totalBookings;
        $data['totalCommission']=count($totalBookings)*$commission;

         //dd($query,$brokerUnderSalesHead,$totalBookings,$totalCommission,$checkSalesHeadCommission,$data);
        if($data!=""){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
         
    }

    public function listTotalCitySalesHead(Request $request)
    {
        $query = SalesUserManagement::where('city_head_id',$request->city_head_id)->pluck('sales_head_id');

        $brokerlist =   User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
                return $query->where('name','=', 'saleshead');
            })->whereIn('id',$query)->orderBy('id','desc')->get();
 
        if($brokerlist!=""){
            return Response::json(['status'=>1,'data'=>$brokerlist]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
    }

    public function listTotalCitySalesBroker(Request $request)
    {
        $query = SalesUserManagement::where('city_head_id',$request->city_head_id)->pluck('sales_head_id');

        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query

        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');


        $brokerlist =   User::with(['roles','cities','states'])->whereHas('roles', function ($brokerUnderSalesHead) {
                return $brokerUnderSalesHead->where('name','=', 'broker');
            })->whereIn('id',$brokerUnderSalesHead)->orderBy('id','desc')->get();
 
        if($brokerlist!=""){
            return Response::json(['status'=>1,'data'=>$brokerlist]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }

    }

    public function listTotalCitySalesBookings(Request $request)
    {
        $query = SalesUserManagement::where('city_head_id',$request->city_head_id)->pluck('sales_head_id');

        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query

        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');
        $totalBookings  = Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','id','site_id','bhk')->whereIn('broker_id',$brokerUnderSalesHead)->where('booking_status','1')->get();
        if($totalBookings!=""){
            return Response::json(['status'=>1,'data'=>$totalBookings]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }

    }

    public function citySalesBrokerDetails(Request $request)
    {   
        $matchMyBookingDetails = ['id'=>$request->id];

         


        $query = BrokerUserManagement::where('sales_head_id',$request->sales_head_id)->pluck('broker_id');
        $brokerlist =   User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
                return $query->where('name','=', 'broker');
            })->whereIn('id',$query)->orderBy('id','desc')->get();


        if($data!=''){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
    }





    public function listTotalCitySalesCommission(Request $request)
    {
        $query = SalesUserManagement::where('city_head_id',$request->city_head_id)->pluck('sales_head_id');

        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query

        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');
        $totalBookings  = Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','id','site_id','bhk')->whereIn('broker_id',$brokerUnderSalesHead)->where('booking_status','1')->get();
        if($totalBookings!=""){
            return Response::json(['status'=>1,'data'=>$totalBookings]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }

    }


    public function citySalesBookingsDetails(Request $request)
    {   
        $matchMyBookingDetails = ['booking_status'=>1,'id'=>$request->id];
        $data = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'properties','sitesoffers'])->where($matchMyBookingDetails)->first();
        if($data!=''){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
    }

    /** Company Representative **/
    
    public function companyRepresentDashboard(Request $request)
    {
        $query = BrokerUserManagement::where('represent_id',$request->represent_id)->pluck('sales_head_id');

        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query

        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');
       
        $totalMyBookings = Bookings::where('company_represent_id',$request->represent_id)->count();

        $totalBookings = Bookings::whereIn('broker_id',$brokerUnderSalesHead)->where('booking_status',1)->count();
         


        
        
        $data = [];
        $data['totalSalesHead']=count($query);
        $data['totalBroker']=count($brokerUnderSalesHead);
        $data['totalMyBookings']=$totalMyBookings;
        $data['totalBrokerBooking']=$totalBookings;
 
        if($data!=""){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
         
    }


    public function companytotalSalesHead(Request $request)
    {
        $query = BrokerUserManagement::where('represent_id',$request->represent_id)->pluck('sales_head_id');


        $totalSalesHead =   User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
                return $query->where('name','=', 'saleshead');
            })->whereIn('id',$query)->orderBy('id','desc')->paginate(10);



        if($totalSalesHead->total()>0){
            return Response::json(['status'=>1,'data'=>$totalSalesHead]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }

    }


    public function companytotalBroker(Request $request)
    {
        $query = BrokerUserManagement::where('represent_id',$request->represent_id)->pluck('sales_head_id');
        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query
        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');
        $totalBroker =   User::with(['roles','cities','states'])->whereHas('roles', function ($query) {
                return $query->where('name','=', 'broker');
            })->whereIn('id',$brokerUnderSalesHead)->orderBy('id','desc')->paginate(10);

        if($totalBroker->total()>0){
            return Response::json(['status'=>1,'data'=>$totalBroker]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
    }

    public function companyTotalMyBookings(Request $request)
    {
        $totalMyBookings = Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','id','site_id','bhk')->where('company_represent_id',$request->represent_id)->where('booking_status','1')->paginate(10);

         
       
        if($totalMyBookings->total()>0){
            return Response::json(['status'=>1,'data'=>$totalMyBookings]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
    }

    public function companyBrokerBookings(Request $request)
    {
        $query = BrokerUserManagement::where('represent_id',$request->represent_id)->pluck('sales_head_id');

        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query
        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');
        $totalBookings = Bookings::select('customer_name',DB::raw("DATE(tbl_bookings.created_at) AS booking_date"),'commission_amount','id','site_id','bhk')->whereIn('broker_id',$brokerUnderSalesHead)->where('booking_status','1')->paginate(10);

        if($totalBookings->total()>0){
            return Response::json(['status'=>1,'data'=>$totalBookings]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }

    }

    public function companyMyBookingsDetails(Request $request)
    {   
        $matchMyBookingDetails = ['booking_status'=>1,'id'=>$request->id];
        $data = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'properties','sitesoffers'])->where($matchMyBookingDetails)->first();
        if($data!=''){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
    }

    public function companyMyBrokerDetails(Request $request)
    {   
        $matchMyBookingDetails = ['booking_status'=>1,'id'=>$request->id];
        $data = Bookings::with(['users'=>function($query){
            $query->addSelect('id','name','phone');
        },'sites'=>function($query){
            $query->addSelect('id','site_name');
        },'properties','sitesoffers'])->where($matchMyBookingDetails)->first();
        if($data!=''){
            return Response::json(['status'=>1,'data'=>$data]);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        }
    }

    public function inquiry(Request $request)
    {
      $inquiry = new Inquiry();
      $inquiry->user_id = $request->user_id;  
      $inquiry->area = $request->area;  
      $inquiry->user_type = $request->user_type;  
      $inquiry->property_type = $request->property_type;  
      $inquiry->min_budget = $request->min_budget;  
      $inquiry->max_budget = $request->max_budget;  
      $result = $inquiry->save();
      if($result==1){
            return Response::json(['status'=>1,'message'=>'Inquiry Successfully Submitted']);
        } else {
            return Response::json(['status'=>0,'message'=>'No Record Found']);
        } 

    }
}
