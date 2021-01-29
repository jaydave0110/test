<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Str;
use Request;
use Storage;
use App\Models\Bookings;
use App\Models\BrokerUserManagement;
use App\Models\SalesUserManagement;
use App\Models\Commission;


class Helpers extends Model {

    public static function LoggedIn() {

        if (!session('PpUsrId') ||
            !session('PpUsrEmail')) {

            return false;
        }

        return true;
    }

    public static function getCompanyRepresentativeDashboard($represent_id)
    {

        $query = BrokerUserManagement::where('represent_id',$represent_id)->pluck('sales_head_id');

        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query

        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');
       
        $totalMyBookings = Bookings::where('company_represent_id',$represent_id)->where('booking_status',1)->count();

        $totalBookings = Bookings::whereIn('broker_id',$brokerUnderSalesHead)->where('booking_status',1)->count();
         
        $data = [];
        $data['totalSalesHead']=count($query);
        $data['totalBroker']=count($brokerUnderSalesHead);
        $data['totalMyBookings']=$totalMyBookings;
        $data['totalBrokerBooking']=$totalBookings;


        return json_encode($data);

    }

    public static function getCityHeadDashboard($city_head_id){


        $query = SalesUserManagement::where('city_head_id',$city_head_id)->pluck('sales_head_id');
 
        //after getting sales_head_id get the Broker id from tbl_broker_user_management where sales_head_id is $query

        $brokerUnderSalesHead = BrokerUserManagement::whereIn('sales_head_id',$query)->pluck('broker_id');
       
        
        $totalBookings = Bookings::whereIn('broker_id',$brokerUnderSalesHead)->where('booking_status',1)->count();
         

        $checkSalesHeadCommission = Commission::where('city_head',$city_head_id)->first();

        if($checkSalesHeadCommission!="")
        {
            $commission = $checkSalesHeadCommission->city_head_commission;
        } else {
            $commission=0;
        }
        $data = [];
        $data['totalSalesHead']=count($query);
        $data['totalBroker']=count($brokerUnderSalesHead);
        $data['totalBookings']=$totalBookings;
        $data['totalCommission']=$totalBookings*$commission;

        return json_encode($data);


    }

    public static function salesHeadDashboard($sales_head_id)
    {
        $query = BrokerUserManagement::where('sales_head_id',$sales_head_id)->pluck('broker_id');

        $totalBookings = Bookings::whereIn('broker_id',$query)->where('booking_status',1)->count();
        $totalCommission = Bookings::whereIn('broker_id',$query)->where('booking_status',1)->get();


        $checkSalesHeadCommission = Commission::where('sales_head',$sales_head_id)->first();
        if($checkSalesHeadCommission!="")
        {
            $commission = $checkSalesHeadCommission->sales_head_commission;
        } else {
            $commission =0;
        }

        $data = [];
        $data['totalBroker']=count($query);
        $data['totalBookings']=$totalBookings;
        $data['totalCommission']=count($totalCommission)*$commission;
        return json_encode($data);  
         
    }


    public static function brokerdashboard($user_id)
    {
        
         
            $data = [];
            $matchPendingBooking = ['broker_id'=>$user_id,'booking_status'=>'0'];
            $data['mybooking_pending'] = Bookings::where($matchPendingBooking)->count();
            
            $matchConfirmedBooking = ['broker_id'=>$user_id,'booking_status'=>'1'];
            $data['mybooking_confirmed'] = Bookings::where($matchConfirmedBooking)->count();

            // Collection based on Percentage 
            $matchPercentageBooking = ['broker_id'=>$user_id,'booking_status'=>'1','commission_type'=>'1'];           
            $mycollection_percentage = Bookings::with(['properties'])->where($matchPercentageBooking)->sum('commission_amount');
           
            $data['mycollection_percentage'] =$mycollection_percentage;

            // Collection based on Individual when fix pay           
             
            $matchIndividualBooking = ['broker_id'=>$user_id,'booking_status'=>'1','commission_type'=>'2'];           
            $individualDetails = Bookings::with(['properties'])->where($matchIndividualBooking)->sum('commission_amount');

            $data['mycollection_individual'] =$individualDetails ;
            
            //Collection when fix pay is 0 consider Package
            $matchPackageBooking = ['broker_id'=>$user_id,'booking_status'=>'1','commission_type'=>'3'];           
            $packageDetails = Bookings::with(['properties'])->where($matchPackageBooking)->count();
             
            $data['mycollection_package'] =$packageDetails;
           

             return json_encode($data); 

         
    }


    public static function setFrontUserLoginSession($arrUser) {

        /* set login sessions */
        session([
            'PpUsrId' => $arrUser['id'],
            'PpUsrName' => $arrUser['fullname'],
            'PpUsrTyp' => $arrUser['user_type'],
            'PpUsrEmail' => $arrUser['email'],
            'PpUsrIntrnl' => $arrUser['is_pp_user'],
            'PpUsrVrifid' => $arrUser['sms_verified'],
            'PpUsrPhone' => $arrUser['phone'],
            'PpUsrCity' => $arrUser['city_id']
        ]);
    }

    public static function sendSMS($mobile = '', $message = '') {

        if (strlen($mobile) == 10 && $message != '') {
            \Log::info('---sending sms start ----');
            \Log::info($mobile);
            \Log::info($message);

            // get username and password
            $userName = config('app.sms_user');
            $userPass = config('app.sms_pass');

            if (isset($userName) && isset($userPass) && $userName != '' && $userPass != '') {
                
                // init curl
                $ch = curl_init();
                
                // set curl url
                curl_setopt($ch, CURLOPT_URL,'http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=91'.$mobile.'&msg='.urlencode($message).'&msg_type=TEXT&userid='.$userName.'&auth_scheme=plain&password='.$userPass.'&v=1.1&format=text');
                
                // set curl options
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                // execute curl
                $response = curl_exec($ch);
                
                // insert sms transactions
                return \Helpers::parseSMSResponce($message, $response);

            } else {    
                \Log::info('---sms not sent username password invalid ----');    
                return false;
            }

            \Log::info('---sending sms end ----');
            
            return true;

        } else {
            \Log::info('---mobile no was empty ['.$mobile.']---');
        }

        return false;
    }

    public static function parseSMSResponce($message = '', $response) {
        
        if ($response != '') {
            
            // example responce
            // success | 919876543210 | 3576661876108596614-28961835573954844

            // explode data using pipe
            $arrData = explode('|', $response);
            
            // add it into table
            return SmsTransactions::createSmsTransactions($message, $arrData);
        }
        return false;
    }

    public static function smsTemplate($key = '') {
        $template = [
            'forgotPasswordUser' => 'Verification code is %s for Forgot Passowrd',
            'verifyUser' => 'Property planet verification code is %s',
            'verifyLogin' => 'Property planet login verification code is %s',
            'userVerified' => 'Your property planet acccount is verified',
            'createAgentShare' => '%s is property planet agent commission OTP',
            'createLead' => '%s is property planet OTP',
            'createSiteOffer' => '%s is property planet site offer OTP',
            'createLeadFilterBuilder' => '%s mobile is requested to allow to receive premium leads from PropertyPlanet',
            'createLeadFilterPrimary' => '%s is property planet premium lead OTP',
            'createEnquiry' => '%s is property planet enquiry OTP',
            'ppcLead' => '%s is property planet enquiry OTP',
            'packageExpiration' => 'Your package with PropertyPlanet is going to expire %s, Kindly make payment to continue services.',
            'newLeadNotify' => '%s, Mobile %s is interested in your %s property -PropertyPlanet',
            'leadFilter' => 'Interested buyer, Name: %s Phone: %s Budget: %s - %s -PropertyPlanet',
            'membershipInvoiceCreate' => 'New membership activated of amount %s for %s Months.'
        ];

        return isset($template[$key]) ? $template[$key] : false;
    }
    
    public static function cdnurl($filename = '') {
        if ($filename != '') 
            return config('app.cdnurl') . ($filename != '' ? $filename : '');
        else 
            return url(\Config::get('constants.img_placeholder'));
    }

    public static function  customurl($filename = '') {
        
        
    }

    public static function isSuperAdmin() {
        return self::getAdminRoleType() == 1 ? true : false;
    }

    public static function getAdminUserId() {
        return session('AdmId') > 0 ? session('AdmId') : false;
    }

    public static function getAdminUserRole() {
        return session('AdmRoleId') > 0 ? session('AdmRoleId') : false;
    }

    public static function getAdminRoleDesc() {
        return session('AdmRoleDesc') ? session('AdmRoleDesc') : false;
    }

    public static function getAdminRoleType() {
        

        return session('AdmRoleType') ? session('AdmRoleType') : false;   
    }
    
    public static function formatRoleDesc($desc) {
        return isset($desc) ? json_decode(unserialize($desc)) : [];
    }

    public static function getUserCityAccess() {
        
        if (self::isSuperAdmin()) {
            return false;
        }

        if (\Session::get('AdmRoleCityId')) {
            return explode(',', session('AdmRoleCityId'));
        } else {
            return [0];
        }
    }

    public static function markSessionUserVerified() {
        session(['PpUsrVrifid' => '1']);
    }

    public static function setLeadUserDetails($request) {
        session([
            'LeadFullName' => $request->fullname ? $request->fullname : '',
            'LeadPhone' => $request->phone ? $request->phone : '',
            'LeadEmail' => $request->email ? $request->email : '',
        ]);
    }

    public static function isLeadUserDetailsAvailable() {
        if (session('LeadFullName') != '' && session('LeadPhone') != '' && session('LeadEmail') != '') {
            return true;
        }
        return false;
    }
    
    public static function forgetLeadUserDetails() {
        \Session::forget('LeadFullName');
        \Session::forget('LeadPhone');
        \Session::forget('LeadEmail');
    }

    public static function getLeadUserDetails() {
        return [
            'fullname' => session('LeadFullName') ? session('LeadFullName') : '',
            'phone' => session('LeadPhone') ? session('LeadPhone') : '',
            'email' => session('LeadEmail') ? session('LeadEmail') : ''
        ];
    }

    public static function getLeadUserFullname() {
        return session('LeadFullName') ? session('LeadFullName') : '';
    }

    public static function getLeadUserPhone() {
        return session('LeadPhone') ? session('LeadPhone') : '';
    }

    public static function getLeadUserEmail() {
        return session('LeadEmail') ? session('LeadEmail') : '';
    }

    public static function isUserVerified() {
        
        // check user logged in
        if (self::LoggedIn()) {
            // if loggedin what's session variable not to disturb database
            if (session()->exists('PpUsrVrifid') && session('PpUsrVrifid') === '0') {
                $isVerified = Users::select(['sms_verified'])->where('id', self::getLoginUserId())->first();
                if ($isVerified != null && $isVerified->sms_verified == '1') {
                    return true;
                }
                return false;
            }
        }
        return true;
    }

    public static function getUserCanCreateLeadFilter($user_id) {
        if ($user_id > 0) {
            $userDetails = Users::select('builder_filter_no')->where('id', $user_id)->first();
            if ($userDetails != false) {
                return $userDetails->builder_filter_no;
            }
        }
        return false;
    }

    public static function getFrontUserActiveOTP($user_id = '') {
        if ($user_id > 0) {
            $UserLoginDetails = VerifyUserLogin::where('user_id', $user_id)->first();
            if ($UserLoginDetails != null) {
                return $UserLoginDetails->otp;
            }
        }
        return false;
    }

    public static function isUserAccountTypeSet() {
        $userId = Helpers::getLoginUserId();
        if ($userId > 0) {
            $userDetails = Users::select(['user_type'])->where(['id' => $userId])->first();
            return $userDetails->user_type > 0 ? true : false;
        }
    }

    public static function getFrontLoggedInUserPhone() {
        $userPhone = Users::select(['phone'])->where('id', self::getLoginUserId())->first();
        if ($userPhone) {
            return $userPhone->phone;
        }
        return false;
    }

    public static function getUserCityName() {

        $cityAccess = self::getUserCityAccess();

        if ($cityAccess != false) {
            
            $arrCity = Cities::whereIn('id', $cityAccess)->pluck('name');

            $city = '';
            foreach ($arrCity as $k => $v) {
                $city .= $v.', ';
            }
            
            return ' &nbsp;<i class="fas fa-arrow-right"></i>&nbsp; '.trim($city, ', ');
        }
        
    }

    public static function getUserCities() {

        $cityAccess = self::getUserCityAccess();

        $userCities = [];

        if ($cityAccess != false) {
            
            $userCities = Cities::whereIn('id', $cityAccess)->get(); 


        }elseif (self::isSuperAdmin()) {

            $userCities = Cities::all();
            
        }

        if(!empty($userCities)){
            $currentCity = \Session::get('CURRENT_CITY');
            if(!empty($currentCity)){
                \Session::put('CURRENT_CITY',$currentCity);
            }else{
                // \Session::put('CURRENT_CITY',$userCities[0]->id);
            }            
        }

        return $userCities;
        
    }

    public static function canAccess($role, $returnRedirect = true) {
        
        if (self::isSuperAdmin()) {
            return true;
        }

        if (count(array_intersect($role, \Helpers::formatRoleDesc(\Helpers::getAdminRoleDesc()))) > 0) {
            return true;
        } else {
            if ($returnRedirect == true) {
                redirect()->route('dashboard')->send();
            } else {
                return false;
            }
        }
    }

    public static function canDo($role, $redirect = false) {
        
        if (self::isSuperAdmin()) {
            return true;
        }

        if (count(array_intersect($role, \Helpers::formatRoleDesc(\Helpers::getAdminRoleDesc()))) > 0) {
            return true;
        }
    }

    public static function getLoginUserId($flag=false) {

        if (self::LoggedIn()) {
            if(session('PpUsrTyp') == 5 && !$flag){
                $site_builder_user = \App\Models\Sites::where('handler_user_id',session('PpUsrId'))->select('user_id')->first();
                if($site_builder_user){
                    return $site_builder_user->user_id;
                }else{
                    return session('PpUsrId');                    
                }
            }else{
                return session('PpUsrId');
            }
        }

        return false;
    }

    public static function getLoginUserDetails($userId)
    {
        $userDetails = \App\Models\Users::where('id',$userId)->first();
        
        if($userDetails)
        {
            return $userDetails;
        }
        return false;
    }

    public static function getLoginName() {

        if (self::LoggedIn()) {
            return session('PpUsrName');
        }

        return false;
    }

    public static function getLoginEmail() {

        if (self::LoggedIn()) {
            return session('PpUsrEmail');
        }

        return false;
    }

    public static function getLoginPhone() {

        if (self::LoggedIn()) {
            return session('PpUsrPhone');
        }

        return false;
    }

    public static function getLoginCity() {

        if (self::LoggedIn()) {
            return session('PpUsrCity');
        }

        return false;
    }    

    public static function isFrontPPUser() {

        if (self::LoggedIn()) {
            return session('PpUsrIntrnl') == "1" ? true : false;
        }

        return false;
    }

    public static function isEndUser() {
        if (self::LoggedIn()) {
            return (session('PpUsrTyp') == 1 ? true : false);
        }
        return false;
    }

    public static function isEndUserPaid() {
        if (self::LoggedIn() && session('PpUsrTyp') == 1) {
            $activePackage = \App\Models\UserPayments::where('user_id',self::getLoginUserId())->where(['status' => 1])->with('membershipPackages')->first();
            if($activePackage){
                return true;
            }
        }
        return false;
    }

    public static function isBuilder() {
        if (self::LoggedIn()) {
            return ((session('PpUsrTyp') == 2 || session('PpUsrTyp') == 5) ? true : false);
        }
        return false;
    }

    public static function isAgent() {
        if (self::LoggedIn()) {
            return (session('PpUsrTyp') == 3 ? true : false);
        }
        return false;
    }

    public static function isActiveUser($userId = null)
    {
        if(isset($userId) && !empty($userId))
        {
            $userData = Users::where([['id','=',$userId],['status','=','1']])->first();
        }
        else
        {
            $userData = Users::where([['id','=',self::getLoginUserId()],['status','=','1']])->first();
        }
              
        if(isset($userData) && $userData->count() > 0)
        {
            return true;
        }

        dd($userData);
        return false;
    }

    public static function setUserType($userType) {
        if (self::LoggedIn()) {
            session(['PpUsrTyp' => $userType]);
        }
        return false;   
    }

    public static function isPlatinumAgent($UserId=null)
    {
        if(isset($UserId) && !empty($UserId))
        {
            $brokers = Users::where([['user_type','=',3],['id','=',$UserId]])->with(['agentPayments'=>function($query){
                            return $query->where('status',1);
                        },'agentPayments.membershipPackages'])->get();
        }
        else
        {
            $brokers = Users::where([['user_type','=',3],['id','=',self::getLoginUserId()]])->with(['agentPayments'=>function($query){
                    return $query->where('status',1);
                },'agentPayments.membershipPackages'])->get();
        }
        

        if(isset($brokers) && $brokers->count() > 0)
        {   
            foreach ($brokers as $k => $v)
            {
                if(isset($v->agentPayments[0]->membershipPackages->package_name) && $v->agentPayments[0]->membershipPackages->id == '18')
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        return false;
    }

    public static function subString($str, $limit) {
        if (strlen($str) > $limit) {
            return substr($str,0, $limit)."...";
        } else {
            return $str;
        }
    }

    public static function getFirstElementofArray($arr) {
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                return $v;
            }
        }
    }

    public static function daysRemaining($date) {
        
        $now = strtotime(date('Y-m-d H:i:s'));
        $end_date = strtotime($date);
        $diff = ceil(($end_date - $now) / (60*60*24));

        if ($diff > 0) {
            return $diff.' day' . ($diff > 1 ? 's' : '') . ' remaining';
        } else if ($diff < 0) {
            $diff = abs($diff);
            return 'Expired from '.$diff.' day' . ($diff > 1 ? 's' : '');
        }
        return 'Expired';
    }

    public static function isValidImage($extension = '') {
        if (in_array($extension, array('tiff', 'bmp', 'gif', 'jpe', 'jpeg', 'jpg', 'png'))) {
            return true;
        } 
        return false;
    }

    public static function getSiteBedroomConfig($details) {
        
        if (isset($details->properties) && count($details->properties) > 0) {
            $arr = [];
            foreach ($details->properties as $property) {
                // only consider residential property
                if ($property->cat_id == 1) {
                    $arr[] = $property->propertyFeatures->bedrooms;
                }
            }
            asort($arr);
            return is_array($arr) ? implode(', ', array_unique($arr)).' BHK' : false;
        }
    }

    public static function getPrettyNumber($n, $format = false) {
        $n = (int) $n;
        if ($n < 100000) {
            return ($format == true ? floatval($n) : $n);
        } elseif ($n < 10000000) {
            return ($format == true ? round(floatval($n / 100000),2) : round(($n / 100000),2)) . " Lacs";
        } elseif ($n >= 10000000) {
            return ($format == true ? round(floatval($n / 10000000),2) : round(($n / 10000000),2)) . " CR";
        }
    }

    public static function getNewSiteCounts() {
        $count = [];
        $count['newSites'] = number_format(self::getSiteStatusWiseCount(1, 1), 0, ".", ",");
        $count['newSitesPending'] = number_format(self::getSiteStatusWiseCount(1, 4), 0, ".", ",");
        $count['disabledSites'] = number_format(self::getSiteStatusWiseCount(1, 0), 0, ".", ",");
        return $count;
    }

    public static function getResaleSiteCounts() {
        $count = [];
        $count['resaleSites'] = number_format(self::getSiteStatusWiseCount(2, 1), 0, ".", ",");
        $count['resaleSitesPending'] = number_format(self::getSiteStatusWiseCount(2, 4), 0, ".", ",");
        $count['resaleDisabledSites'] = number_format(self::getSiteStatusWiseCount(2, 0), 0, ".", ",");
        return $count;
    }

    protected static function getSiteStatusWiseCount($property_type = 1, $status) {
        $count = Sites::where(['property_type' => $property_type, 'status' => $status]);
        if(defined('CURRENT_CITY') && !empty(CURRENT_CITY)){
            $count = $count->where('city_id', CURRENT_CITY);
        }
        if (\Helpers::getUserCityAccess() !== false) {
            $count = $count->whereIn('city_id', \Helpers::getUserCityAccess());
        }
        $count = $count->get()->count();
        return $count;
    }

    public static function propertyTypeHtml() {
        
        $propertySubCategory = array(
            'Residentials' => array(
                                'icon-flats' => 'Flats',
                                'icon-villa' => 'House/Villa',
                                'icon-weekendhome' => 'Open Plots / Weekend Homes'
                            ),

            'Commercial' => array(
                                'icon-office' => 'Office',
                                'icon-shop'=> 'Shop',
                                'icon-showroom' => 'Showroom',
                                'icon-warehouse' => 'Warehouse',
                            ),

            'Industrial' => array(
                                'icon-industrial-plot' => 'Industrial Plot',
                                'icon-industrial-shed' => 'Industrial Shed'
                            )
        );
        
        $_output = '';
        foreach ($propertySubCategory as $key => $value) {
            $_output .= '<div class="row">';
            $_output .= '<div class="col-12"><h5 class="property-type">'.$key.'</h5></div>';
            foreach ($value as $k => $v) {
                $_output .= '<div class="col-4">
                        <div class="d-flex list-entity">
                            <div class="w-25">
                                <span class="'.$k.'"></span>
                            </div>
                            <div class="w-75">
                                <div class="search-value">'.$v.'</div>
                                <div class="search-key">'.$key.'</div>
                            </div>
                        </div>
                    </div>';    
            }
            $_output .= '</div>';
        }
        return $_output;
    }

    public static function getSearchedTransaction($data) {
        $request = explode("&", base64_decode(urldecode($data)));
        $transaction = '';
        foreach ($request as $key => $value) {
            if (substr($value, 0, 13) == 'transaction[]') {
                $arr = explode("=", $value);
                return $arr[1];
            }
        }
        return 'buy';
    }

    public static function getSearchedCategory($data) {
        $request = explode("&", base64_decode(urldecode($data)));
        $category = [];
        foreach ($request as $key => $value) {
            if (substr($value, 0, 16) == 'propertycategory') {
                $arr = explode("=", $value);
                $category[] = $arr[1];
            }
        }
        return $category;
    }

    public static function getSearchedTerms($data) {
        $request = explode("&", base64_decode(urldecode($data)));
        $terms = [];
        foreach ($request as $key => $value) {
            if (substr($value, 0, 10) == 'searchterm') {
                $arr = explode("=", $value);
                if ($arr[1] != '') {
                    if (substr($arr[1], 0, 1) == 'A') {
                        $terms['area'][] = substr($arr[1], 2, strlen($arr[1]));
                    }
                    if (substr($arr[1], 0, 1) == 'S') {
                        $terms['sites'][] = substr($arr[1], 2, strlen($arr[1]));
                    }
                }
            }
        }   
        return $terms;
    }

    public static function getSearchedBudget($data) {
        $request = explode("&", base64_decode(urldecode($data)));
        $budget = [];
        if ($request) {
            foreach ($request as $key => $value) {
                if (in_array(substr($value, 0, 9), ['minbudget', 'maxbudget'])) {
                    $arr = explode("=", $value);
                    $budget[substr($value, 0, 9)] = $arr[1];
                }
            }
            return $budget;
        }
        return false;
    }

    public static function validateMobileNo($phone) {
        if (is_numeric($phone) && $phone >= 6000000000 && $phone <= 9999999999) {
            return true;
        }
        return false;
    }

    public static function getStaticValues($key = '') {

        $staticValues = array();

        $staticValues['user_type'] = array(
            '1' => 'Propertyplanet User',
            '2' => 'Builder',
            '3' => 'Agent',
        );

        $staticValues['furnished_status'] = array(
            'Furnished' => 'Furnished',
            'Unfurnished' => 'Unfurnished ',
            'Semi_Furnished' => 'Semi Furnished',
        );

        $staticValues['open_sides'] = array(
            '1' => '1',
            '2' => '2 ',
            '3' => '3',
            '4' => '4',
        );

        $staticValues['possession_status'] = array(
            '1' => 'Ready to move',
            '2' => 'Under construction',
        );

        $staticValues['area_unit'] = array(
            '' => 'Select Unit',
            'sq_yrd' => 'Square Yards',
            'sq_m' => 'Square Meter',
            'foot' => 'Foot',
            'meter' => 'Meter',
            'sqft' => 'Sq. Ft.',
            'inches' => 'Inches',
            'feet' => 'Feet',
            'acres' => 'Acres',
            'vigha' => 'Vigha',
            'hector'=> 'Hector',
            'vaar' => 'Vaar'
        );

        $staticValues['area_type'] = array(
            'sb_area' => 'SB Area',
            'foyer_area' => 'Foyer',
            'parking_area' => 'Parking',
            'carpet_area' => 'Carpet Area',
            'built_area' => 'Built Area',
            'plot_area' => 'Plot Area',
            'plot_area_project' => 'Plot area project',
            'shed_area' => 'Shed area',
        );

        $staticValues['price_status'] = array(
            '' => 'Select option',
            '1' => 'Display original price',
            '2' => 'Negotiable',
            '3' => 'Price on request',
        );

        $staticValues['sample_house'] = array(
            '' => 'Select option',
            '0' => 'Not available',
            '1' => 'Yes available',
            '2' => 'In future',
            '3' => 'Under construction'
        );  

        $staticValues['site_photo_type'] = array(
            '' => 'Other',
            'house_pictures' => 'House Photos',
            'project_pictures' => 'Project Photos',
            'sequence_diagrams' => 'Main Plan Diagram',
            'amenities_pictures' => 'Amenities Photos',
            'living_room' => 'Living Room',
            'bedrooms' => 'Bedrooms',
            'bathrooms' => 'Bathrooms',
            'kitchen' => 'Kitchen',
            'master_plan' => 'Master Plan',
            'common_areas' => 'Common area',
            'washrooms' => 'Washrooms',
            'exterior_view' => 'Exterior View',
            'floor_plan' => 'Floor Plan',
            'location_map' => 'Location Map',
            'site_view' => 'Site View',
        );

        $staticValues['new_site_photo_type'] = array(
            'house_photos' => 'House Photos',
            'living_room' => 'Living Room',
            'bedroom' => 'Bedroom',
            'bathroom' => 'Bathroom',
            'kitchen' => 'Kitchen',
            'master_plan' => 'Master Plan',
            'common_areas' => 'Common area',
            'wash_room' => 'Wash Room',
            'exterior_view' => 'Exterior View',
            'floor_plan' => 'Floor Plan',
            'location_map' => 'Location Map',
            'site_view' => 'Site View',
            '7/12 document'=>'7/12 Document', 
            'map_image'=>'Map Image',
            'other'=>'Other'
        );

        $staticValues['photo_type'] = array(
            'layout_diagrams' => 'Layout Diagrams',
            '' => 'Other'
        );

        $staticValues['transaction_type'] = array(
            '' => 'Select option',
            '1' => 'Sale',
            '2' => 'Rent',
        );

        $staticValues['bedrooms'] = array(
            '' => 'Select BHK',
            '1' => '1 BHK',
            '1.5' => '1.5 BHK',
            '2' => '2 BHK',
            '2.5' => '2.5 BHK',
            '3' => '3 BHK',
            '3.5' => '3.5 BHK',
            '4' => '4 BHK',
            '4.5' => '4.5 BHK',
            '5' => '5 BHK',
            '5.5' => '5.5 BHK',
            '6' => '6 BHK',
        );

        $staticValues['bedrooms_plain'] = array(
            '' => 'Select BHK',
            '1' => '1 BHK',
            '2' => '2 BHK',
            '3' => '3 BHK',
            '4' => '4 BHK',
            '5' => '5 BHK',
            '6' => '6 BHK',
            '7' => '7 BHK',
            '8' => '8 BHK',
            '9' => '9 BHK',
            '10' => '10 BHK'
        );

        $staticValues['bathrooms'] = $staticValues['balconies'] = array(
            '' => 'Select Option',
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6'
        );

        $staticValues['age_of_construction'] = array(
            '' => 'Select Option',
            '<5' => 'Less Than 5 Years',
            '5_10' => '5 To 10 Years',
            '10_15' => '10 To 15 Years',
            '15_20' => '15 To 20 Years',
            '>20' => 'Above 20 Years',
        );

        $staticValues['yn_text'] = array(
            '' => 'Select Option',
            'yes' => 'Yes',
            'no' => 'No'
        );

        $staticValues['yn_boolean'] = array(
            '' => 'Select Option',
            '1' => 'Yes',
            '0' => 'No'
        );

        $staticValues['land_zone'] = array(
            '' => 'Select option',
            'residential' => 'Residential',
            'agricultural' => 'Agricultural',
            'educational' => 'Educational',
            'industrial' => 'Industrial',
            'commercial' => 'Commercial',
            'others' => 'Others'
        );

        $staticValues['land_type'] = array(
            '' => 'Select option',
            'old_condition' => 'Old Condition',
            'new_condition' => 'New Condition',
            'not_agricultural' => 'Not Agricultural',
            'others' => 'others'
        );

        $staticValues['land_location'] = array(
            '' => 'Select option',
            'road_touch' => 'Road touch',
            'interior' => 'Interior',
            'corner_plot' => 'Corner plot'
        );

        $staticValues['package_for'] = array(
            '' => 'Select option',
            '1' => 'Builder',
            '2' => 'Agent',
            '3' => 'User'
        );

        $staticValues['package_type'] = array(
            '' => 'Select option',
            '1' => 'Paid',
            '2' => 'Free',
            '3' => 'Trial'
        );
        
        $staticValues['search_rent_budgets'] = array(
            '0'         =>      '0',
            '2000'      =>      '2000',
            '5000'      =>      '5000',
            '7000'      =>      '7000',
            '10000'     =>      '10000',
            '12000'     =>      '12000',
            '15000'     =>      '15000',
            '20000'     =>      '20000',
            '25000'     =>      '25000',
            '30000'     =>      '30000',
            '35000'     =>      '35000',
            '40000'     =>      '40000',
            '45000'     =>      '45000',
            '50000'     =>      '50000',
            '60000'     =>      '60000',
            '80000'     =>      '80000',
            '100000'    =>      '1 Lac',
            '200000'    =>      '2 Lac',
            '500000'    =>      '5 Lac',
        );

        $staticValues['search_budgets'] = array(
            '0'         =>      '0',
            '500000'    =>      '5 Lac',
            '1000000'   =>      '10 Lac',
            '2000000'   =>      '20 Lac',
            '2500000'   =>      '25 Lac',
            '4000000'   =>      '40 Lac',
            '5000000'   =>      '50 Lac',
            '6000000'   =>      '60 Lac',
            '10000000'  =>      '1 CR',
            '15000000'  =>      '1.5 CR',
            '20000000'  =>      '2 CR',
            '25000000'  =>      '2.5 CR',
            '30000000'  =>      '3 CR',
            '40000000'  =>      '4 CR',
            '50000000'  =>      '5 CR',
            '70000000'  =>      '7 CR',
            '100000000' =>      '10 CR',
            '150000000' =>      '15 CR'
        );

        $staticValues['lead_type'] = array(
            '1' => 'By PropertyPlanet Team',
            '2' => 'Contact Builder',
            '3' => 'I\'m Interested',
            '4' => 'Request Unit details',
            '5' => 'Offer',
            '6' => 'PPC',
            '7' => 'PortalRegistration Popup'
        );

        $staticValues['lead_status'] = array(
            '1' => 'Searching',
            '2' => 'Purchased',
            '3' => 'Cancelled',
            '4' => 'Ringing',
            '5' => 'Fake',
        );

        $staticValues['inquiry_type'] = array(
            '1' => 'Buy Residential', 
            '2' => 'Buy Commercial', 
            '3' => 'Sell Residential'
        );

        $staticValues['interested_in'] = array(
            'new_resale' => 'Both new & Resale', 
            'new' => 'New property', 
            'resale' => 'Resale property'
        );

        $staticValues['inquiry_purpose'] = array(
            'Investment' => 'Investment', 
            'Residence' => 'Residence'
        );

        $staticValues['inquiry_possession'] = array(
            'ready_to_move' => 'Ready to move', 
            'with_in' => 'With In'
        );

        $staticValues['builder_agent_share_payment_type'] = array(
            '1' => '100% brokerage against booking',
            '2' => 'Proportionate brokerage as per the payment'
        );

        $staticValues['user_payment_type'] = $staticValues['agent_payment_type'] = $staticValues['builder_payment_type'] = array(
            '1' => 'Cash payment',
            '2' => 'Cheque payment',
            '3' => 'Online payment'
        );

        $staticValues['site_offer_type'] = array(
            '1' => 'Display to everyone',
            '2' => 'Display after form fill'
        );

        $staticValues['admin_login_day'] = array(
            '0' => 'Allow on all days (Monday to Sunday)',
            '1' => 'Allow on Weekdays (Monday to Saturday)',
            '2' => 'Allow on Weekends (Sunday Only)',
        );

        $staticValues['amenities'] = [
            'lift' => 'Elevator/Lift',
            'security_facility' => 'Security Facility',
            'parking_area' => 'Parking Area',
            'cctv' => 'CCTV',
            'garden' => 'Garden',
            'children_play_area' => 'Children Play Area',
            'intercom' => 'Intercom',
            'swimming_pool' => 'Swimming Pool',
            'gazebo' => 'Gazebo',
            'gymasium' => 'Gymnasium',
            'banquet_hall' => 'Banquet Hall',
            'amphi_theatre' => 'Amphi Theatre',
            'indoor_game_court' => 'Indoor Games Court',
            'outdoor_game_court' => 'Outdoor Games Court',
            'joggers_park' => 'Joggers Park',
            'temple' => 'Temple',
            'wifi' => 'Wi-Fi',
            'senior_citizen_garden' => 'Senior Citizen Garden',
            'gas_line' => 'Gas Line',
            'rainwater_harvest' => 'Rainwater Harvest',
            'badminton' => 'Badminton',
            'banquet_hall' => 'Banquet Hall',
            'butterfly_park' => 'Butterfly Park',
            'dg_set' => 'DG Set',
            'golf' => 'Golf',
            'gymasium' => 'Gymnasium',
            'internal_road' => 'Internal Road',
            'internet' => 'Internet',
            'infinity_swimming_pool' => 'Infinity Swimming Pool',
            'library' => 'Library',
            'power_backup' => 'Power Backup',
            'restaurant' => 'Restaurant',
            'relaxation_room' => 'Relaxation Room',
            'security_facility' => 'Security Facility',
            'squash' => 'Squash',
            'tennis' => 'Tennis',
            'unity_stores' => 'Unity Stores',
            'video_door_phone' => 'Video Door Phone',
            'volleyball' => 'Volleyball', 
            'water_supply' => 'Water supply',
            'washing_machine_area' => 'Washing Machine Area',
            'yoga' => 'Yoga'
        ];

        $staticValues['interior_detail'] = [
            '1' => 'Fully Furnished',
            '2' => 'Semi Furnished',
            '3' => 'Unfurnished'
        ];

        $staticValues['want_to_buy_in'] = [
            'gidc' => 'GIDC',
            'sez' => 'SEZ',
            'industrial_park' => 'Industrial Park',
            'industrial_land' => 'Industrial Land',
            'any' => 'Any'
        ];

        $staticValues['specialized_in'] = [
            '1'=>'Sell',
            '2'=>'Rent'
        ];

        $staticValues['specialized_in_property'] = [
            '1'=>'Residential',
            '2'=>'Commercial',
            '3'=>'Industrial',
            '4'=>'Land'
        ]; 

        $staticValues['news_offer_for'] = [
            '1'=>'End User',
            '2'=>'Builder',
            '3'=>'Agent',
            '4'=>'Site Owner',
            '5'=>'All'
        ];

        $staticValues['road_width'] = [
            '7.5'=>'7.5',
            '9'=>'9',
            '12'=>'12',
            '15'=>'15',
            '18'=>'18',
            '24'=>'24',
            '30'=>'30',
            '50'=>'50',
            '75'=>'75',
            '100'=>'100',
            'other'=>'other'
        ];   

        return isset($staticValues[$key]) ? $staticValues[$key] : false;
    }

    public static function filterStaticValues($key = '') {
        $staticValues['filter_bhk'] = array(
            '1' => '1_bhk',
            '2' => '2_bhk',
            '3' => '3_bhk',
            '4' => '4_bhk',
            '5' => '5_bhk',
        );

        return isset($staticValues[$key]) ? $staticValues[$key] : false;
    }

    public static function propertyFeaturesFields() {
        return array('id', 'property_id', 'bedrooms', 'bathrooms','balconies', 'foyer_area', 'store_room', 'pooja_room', 'study_room', 'parking_area', 'no_of_parking', 'open_sides', 'servant_room', 'area_covered', 'area_covered_unit', 'sb_area', 'sb_area_unit', 'carpet_area', 'carpet_area_unit', 'built_area', 'built_area_unit', 'plot_area', 'plot_area_unit', 'plot_area_project', 'plot_area_project_unit', 'commencement', 'vastu', 'furnished_status', 'interior_details', 'shed_area', 'shed_area_unit', 'electricity_connection', 'crane_facility', 'etp', 'shed_height', 'shed_height_unit', 'no_of_towers','no_of_flats', 'no_of_houses', 'total_floors', 'property_on_floor', 'plot_size_range', 'plot_size_range_unit', 'price_sq_ft','is_corner_plot','is_corner_shop','is_main_road_facing','personal_washroom','cafeteria','covered_area','covered_area_unit','width_of_enterance','currently_leased_out','assured_returns','road_approach','road1_width','road1_width_unit','road2_width','road2_width_unit','road_width','road_width_unit','power_capacity','total_price','usp','cabins','workstation','acs','length','width','frontage','facing','ideal_for','length_width_unit','frontage_unit','payment_terms','total_unit');
    }

    /* generate site or property url from category and site details */
    public static function getPropertyUrl($category, $details) {
                 
        // prepare seo friendly string
        $propertyDetails = self::hyphenize($details->sub_title);

        // set property type
        //$property_type = ((int) $property_type == 1 ? 'new' : 'resale');

        // generate route url 
        return route('details', [$category, $propertyDetails, $details->id]);
    }

    public static function getPropertyType($proeprty_type) {
        if ($proeprty_type == 1) {
            return 'New';
        } else if ($proeprty_type == 2) {
            return 'Resale';
        }
        return false;
    }

    public static function imgUrl($image = '', $imageClass = '', $placeholderImage = 'images/placeholder.svg') {

        if (!file_exists($image)) {
            $image = $placeholderImage;
        }

        return url($image);
    }

    public static function isMicroSite($sitePayments) {
        if (isset($sitePayments) && count($sitePayments) > 0) {
            foreach ($sitePayments as $payments) {
                // invoice must be active 
                // micro site must be enabled for invoice
                // invoice must not be expired
                if ($payments->is_microsite == 1 && 
                    $payments->status == 1 && 
                    strtotime(date('d-M-Y H:i:s')) < strtotime($payments->subscription_duration_to)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function getSiteDetailsTemplate($category, $transaction_type, $micro = false) {
        
        if (in_array($transaction_type, array('new', 'resale'))) {
            switch ($category) {
                case 'residential':
                case 'commercial':
                case 'industrial': 
                    if ($micro == true) {
                        return 'Front.PropertyDetails.Micro.'.ucfirst(strtolower($category)).'Details';
                    } else {
                        return 'Front.PropertyDetails.'.ucfirst($transaction_type).'.'.ucfirst(strtolower($category)).'Details';    
                    }
                    break;

                case 'land':
                    return 'Front.PropertyDetails.Resale.LandDetails';
                    break;
                
                default:
                    redirect()->route('homepage')->send();
                    break;
            }
         } else {
            redirect()->route('homepage')->send();
         }
    }

    public static function propertyPaginationMetadata($arrSite) {
        $arr = [];
        $arr['total'] = $arrSite->count();
        $arr['currentPage'] = $arrSite->currentPage();
        $arr['nextPage'] = $arrSite->nextPageUrl();
        return $arr;
    }

    public static function configureSearchSidebar($request) {
        //dd($request);
        return array(
                'transaction_type' => array(
                    'buy'  => 'Buy',
                    'rent'  => 'Rent'
                ),
                'bedrooms' => array(
                    '1_bhk'     => '1 bhk',
                    '2_bhk'     => '2 bhk',
                    '3_bhk'     => '3 bhk',
                    '4_bhk'     => '4 bhk',
                    '5_bhk'     => '5 bhk'
                ),
                'possession' => array(
                    'ready_to_move' => 'Ready to move',
                    'in_1_year'     => 'In 1 year',
                    'in_2_year'     => 'In 2 year',
                    'in_2_plus_year'=> 'In 2+ year',
                ),
                'amenities' => array(
                    'security'          => 'Security',
                    'gym'               => 'Gym',
                    'Amphitheatre'      => 'Amphitheatre',
                    'landscaped_garden' => 'Landscaped Garden'
                ),
                'listed_by' => array(
                    'owner'     => 'Owner',
                    'builder'   => 'Builder',
                    'agent'     => 'Agent'
                ),
                'amenities' => array(
                    'gymasium'                  => 'Gym',
                    'lift'                      => 'Elevator/Lift',
                    'security_facility'         => 'Security facility',
                    'children_play_area'        => 'Children play area',
                    'garden'                    => 'Garden',
                    'parking_area'              => 'Parking area',
                    'swimming_pool'             => 'Swimming pool',
                    'outdoor_game_court'        => 'Outdoor game court',
                    'indoor_game_court'         => 'Indoor game court',
                    'joggers_park'              => 'Joggers park',
                    'gazebo'                    => 'Gazebo',
                    'temple'                    => 'Temple',
                    'butterfly_park'            => 'Butterfly park',
                    'banquet_hall'              => 'Banquet hall',
                    'amphi_theatre'             => 'Amphi theatre'
                )
            );
    }

    public static function configureSearchResponse($arrSite,$isApi=false,$loginUserId=null,$loginUserType=null) {
            
        $sites = array();
        if (isset($arrSite)) {
            
            // get sites in loop
            foreach ($arrSite as $k => $v) {

                // check property available in site
                if (isset($v->properties)) {

                    // Get site properties categories
                    $propertyCategory = self::getSitePropertyCategory($v->properties);
                       
                    // if property category available then proceed
                    if ($propertyCategory !== false) {
                        
                        // Seperate element for each category [Ex: residential, commerical, industrial, land]
                        foreach ($propertyCategory as $category) {
                            //print_r($category);
                            // format sites summary
                            $sites[] = self::configureSiteResponse($v, $category, $isApi,$loginUserId,$loginUserType);
                             
                        }
                    }
                }
            } 
        }

        return $sites;
    }

    public static function hyphenize($string, $separator = '-') {
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = preg_replace("%[^-/+|\w ]%", '', strtolower($string));
        $string = preg_replace("/[\/_|+ -]+/", $separator, $string);
        return trim($string, '-');
    }

    /* format site response */
    public static function configureSiteResponse($details, $category, $isApi=false,$loginUserId=null,$loginUserType=null) {
        

        if (is_array($category) && count($category) > 0) {
            $price = self::getSitePrice($details);

            $total_images = \Helpers::getSiteTotalImages($details);
            $possession = self::getSitePossesionDetails($details);
            $cover_image = url(\Helpers::getCoveredImage($details));

             // Mascot Details
            $mascotDetails = Helpers::getMascotDetails();

            $arr['site_info']['property_id']        = isset($details->properties[0]->id) && !empty($details->properties[0]->id) ? (string)$details->properties[0]->id : ''; 

            if(isset($details->properties[0]->propertyFeatures->sb_area) && isset($details->properties[0]->propertyFeatures->sb_area_unit))
            {
                $arr['site_info']['commercial_area'] = $details->properties[0]->propertyFeatures->sb_area.' '.$details->properties[0]->propertyFeatures->sb_area_unit.' (SBA)';
            }
            elseif(isset($details->properties[0]->propertyFeatures->carpet_area) && isset($details->properties[0]->propertyFeatures->carpet_area_unit))
            {
                $arr['site_info']['commercial_area'] = $details->properties[0]->propertyFeatures->carpet_area.' '.$details->properties[0]->propertyFeatures->carpet_area_unit.' (CA)';
            }
            elseif(isset($details->properties[0]->propertyFeatures->built_area) && isset($details->properties[0]->propertyFeatures->built_area_unit))
            {
                $arr['site_info']['commercial_area'] = $details->properties[0]->propertyFeatures->built_area.' '.$details->properties[0]->propertyFeatures->built_area_unit.' (BA)';
            }

            $arr['site_info']['property_on_floor']            = $details->properties[0]->propertyFeatures->property_on_floor; 
               
            $arr['site_info']['wishlistId']         = (string)WishList::getWishListId($loginUserId,$details->id);   


            $arr['site_info']['id']                 = $details->id;
            $arr['site_info']['property_type']      = $details->property_type;

            $arr['site_info']['user_phone']  = Helpers::isLandSite($details->id) && isset($mascotDetails->phone) ? $mascotDetails->phone : (isset($details->users->phone) ? $details->users->phone : '');
            /*$arr['site_info']['user_phone']         = isset($details->users->phone) ? $details->users->phone : '';*/
            
            $arr['site_info']['category_slug']      = $category['slug'];
            $arr['site_info']['featured']           = $details->is_featured == '1' ? true : false;
            $arr['site_info']['property_type_text'] = ($details->property_type == 1 ? 'New' : (isset($details->properties) ? ($details->properties[0]->transaction_type == 1 ? 'Sell' : 'Rent' ) : ''));
            $arr['site_info']['posted_on']          = \Carbon\Carbon::parse($details->created_at)->diffForHumans();
            $arr['site_info']['category']           = ($category['subcategory'] != null ? $category['name'].' > '.$category['subcategory']['name'] : '');
            $arr['site_info']['url']                = self::getPropertyUrl($category['slug'], $details);

            $arr['site_info']['site_name']          = ($details->site_name ? self::subString($details->site_name, 40) : $details->site_name);
            $arr['site_info']['address']            = $details->address;
            $arr['site_info']['budget']             = $price;
            $arr['site_info']['cover_image']        = ($cover_image ? $cover_image : '');
            $arr['site_info']['total_images']       = ($isApi ? ($total_images == false ? 0 : $total_images ) : $total_images );
            $arr['site_info']['possession']         = ($isApi ? ($possession == false ? "" : $possession ) : $possession );
            $arr['site_info']['sample_house']       = self::getSiteSampleHouseDetails($details);

            $arr['site_info']['latitude']           = isset($details->latitude) && !empty($details->latitude) ? $details->latitude : '';
            $arr['site_info']['longitude']          = isset($details->longitude) && !empty($details->longitude) ? $details->longitude : '';

            $arr['areas']['name']                   = isset($details->areas) && $details->areas->name ? $details->areas->name : '';
            $arr['cities']['name']                  = isset($details->cities) && $details->cities->name ? $details->cities->name : '';
            $arr['states']['name']                  = isset($details->states->name) ? $details->states->name : '';

            $arr['site_info']['offers']             = \Helpers::getSiteOfferCounts($details);
            $arr['site_info']['owner']              = \Helpers::getSiteOwnerDetails($details);
            $arr['site_info']['amenities']          = self::getSiteAmenities($details->siteMetas);
            
            $arr['site_info']['agent_share'] = false;

            //$arr['site_info']['api_property_on_floor'] =self::addOrdinalNumberSuffix($apiPfloor);



            if(isset($loginUserType) && $loginUserType == '3')
            {
                $arr['site_info']['agent_share']    =   isset($details->agentShares) && $details->agentShares !== null ?  $details->agentShares->shares.' %' : false;
            }
            elseif(Helpers::isAgent())
            {

                $arr['site_info']['agent_share']        = (Helpers::isAgent() !== false) ? 
                                                            ($details->agentShares !== null ? 
                                                                $details->agentShares->shares : false)
                                                            : false;
            }

            $arr['site_info']['share_start']        = (Helpers::isAgent() !== false) ? 
                                                            ($details->agentShares !== null ? 
                                                                date('d-M-Y', strtotime($details->agentShares->start_date)) : false)
                                                            : false;
            $arr['site_info']['share_end']          = (Helpers::isAgent() !== false) ? 
                                                            ($details->agentShares !== null ? 
                                                                date('d-M-Y', strtotime($details->agentShares->end_date)) : false)
                                                            : false;
            $arr['summary']                        = self::searchPageSiteSummary($details, $category['id']);

            $siteimg = isset($details->siteImages) && $details->siteImages->count() > 0 ? $details->siteImages->count() : 0;
            $propimg = isset($details->properties[0]->propertyImages) && $details->properties[0]->propertyImages->count() > 0 ? $details->properties[0]->propertyImages->count() : 0;
            
            $arr['site_info']['site_images_count'] = $siteimg+$propimg;

            return $arr;
        }

        return false;
    }

    /* configure properties */
    public static function searchPageSiteSummary($details, $category) {

        switch ($category) {
            case '1':
                return self::residentialSearchSummary($details);
                break;
        
            case '2':
                return self::commercialSearchSummary($details);
                break;

            case '3':
                return self::industrialSearchSummary($details);
                break;

            case '4':
                return self::landSearchSummary($details);
                break;
        }

        return false;
    }

    public static function residentialSearchSummary($details) {
        

        if (isset($details->properties) && count($details->properties) > 0) {
            $return = array();
            $i = 0;

            foreach ($details->properties as $property) {
                
                // consider only residential property
                if ($property->cat_id == 1) {

                    // get property area
                    $area = self::getPropertyArea($property);

                    $area = $area['area'] > 0 ? number_format((int) $area['area']).' '.$area['area_unit'].' ('.$area['type'].')' : 'Area N/A';

                    $return[$i]['area']     = $area;
                    $return[$i]['bedrooms'] = $property->propertyFeatures->bedrooms.' BHK';
                    $return[$i]['category'] = (isset($property->propertySubCategory) ? $property->propertySubCategory->name : '');
                    $return[$i]['for']      = $property->transaction_type;
                    $return[$i]['price']    = self::getPropertyPrice($property, $details->price_status);
                    $i++;
                }
            }

            usort($return, function($a, $b) {
                $retval = $a['bedrooms'] <=> $b['bedrooms'];
                if ($retval == 0) {
                    $retval = $a['area'] <=> $b['area'];
                }
                return $retval;
            });
            
            return array('residential' => $return);
        }
        return false;
    }

    public static function commercialSearchSummary($details) {
        if (isset($details->properties) && count($details->properties) > 0) {
            $return = array();
            foreach ($details->properties as $property) {
                
                // property category
                $category = [];
                if (isset($property->propertiesUnitCategory)) {
                     foreach ($property->propertiesUnitCategory as $unitCategory) {
                        $category[] = $unitCategory->propertySubCategory->name;
                    }
                }

                $features = $property->propertyFeatures;
                if (isset($features->total_floors)) {
                    if (isset($property->propertyMetas)) {
                        for ($i = 0; $i <= $features->total_floors; $i++) {
                            $return[$i]['floor_no'] = self::NumSuffix($i).' Floor';
                            $return[$i]['area'] = self::chkMetas($property->propertyMetas, 'floor_'.$i.'_area', '','-');
                            $return[$i]['min_area'] = self::chkMetas($property->propertyMetas, 'floor_'.$i.'_min_area', '','-');
                            $return[$i]['max_area'] = self::chkMetas($property->propertyMetas, 'floor_'.$i.'_max_area', '','-');
                            $return[$i]['category'] = $category;
                            $return[$i]['price'] = self::chkMetas($property->propertyMetas, 'floor_'.$i.'_price_sq_ft', '', '-');

                            if ($return[$i]['price'] > 0) {
                                $return[$i]['price'] = config('app.currency') .' '. self::getPrettyNumber($return[$i]['price'], true);
                            }

                            /* uncomment this when min and max area is entered corretly along with price per sqft */
                            // $return[$i]['floor_no'] = self::NumSuffix($i).' Floor';
                            // $return[$i]['category'] = $category;
                            // $return[$i]['min_area'] = 
                            //     self::chkMetas($property->propertyMetas, 'floor_'.$i.'_min_area', '','-');
                            // $return[$i]['max_area'] =
                            //      self::chkMetas($property->propertyMetas, 'floor_'.$i.'_max_area', '','-');
                            // $return[$i]['price_persqft'] = 
                            //     self::chkMetas($property->propertyMetas, 'floor_'.$i.'_price_sq_ft', '', '-');
                            // $return[$i]['price'] = config('app.currency') .' '.
                            //     self::getPrettyNumber($return[$i]['min_area'] * $return[$i]['price_persqft']).' - '.
                            //     self::getPrettyNumber($return[$i]['max_area'] * $return[$i]['price_persqft']);
                        }
                    }
                }
            }
        }
        return array('commerical' => $return);
    }

    public static function industrialSearchSummary($details) {
        if (isset($details->properties) && count($details->properties) > 0) {
            $return = array();
            foreach ($details->properties as $property) {
                
                // property category
                $category = [];
                if (isset($property->propertiesUnitCategory)) {
                    foreach ($property->propertiesUnitCategory as $unitCategory) {
                        $category[] = $unitCategory->propertySubCategory->name;
                    }
                }

                // get property area
                $area = self::getPropertyArea($property);
                $area = $area['area'] > 0 ? number_format((int) $area['area']).' '.$area['area_unit'].' ('.$area['type'].')' : 'Area N/A';
                $return[0]['area']         = $area;
                $return[0]['category'] = $category;
                $return[0]['price']        = self::getPropertyPrice($property, $details->price_status);
            }
        }
        return array('industrial' => $return);
    }

    public static function landSearchSummary($details) {
        
        // check property exist
        if (isset($details->properties) && count($details->properties) > 0) {
            
            $min_area_size = $max_area_size = 0;
            $min_area_unit = $max_area_unit = '';
            $area_type = '';

            // get properties in loop
            foreach ($details->properties as $property) {

                // property category
                $category = [];
                if (isset($property->propertiesUnitCategory)) {
                    foreach ($property->propertiesUnitCategory as $unitCategory) {
                        $category[] = $unitCategory->propertySubCategory->name;
                    }
                }
                
                // get property wise area
                $property_area = self::getPropertyArea($property);
                
                // property area > 0
                if ($property_area['area'] > 0) {
                    
                    // if min area available then update
                    if ($min_area_size == 0 || $min_area_size > $property_area['area']) {
                        $min_area_size = $property_area['area'];
                    }

                    // if max area available then update
                    if ($min_area_size == 0 || $max_area_size < $property_area['area']) {
                        $max_area_size = $property_area['area'];
                        $max_area_unit = $property_area['area_unit'];
                    }
                    $area_type = ' ('.$property_area['type'].')';
                }

            }
            
            // area from and to calculate
            if ($min_area_size > 0 && $min_area_size == $max_area_size) {
                $area_size = $min_area_size;
            } else if ($min_area_size == 0 && $max_area_size == 0) {
                $area_size = 'Area N/A';
            } else {
                $area_size = $min_area_size.' to '.$max_area_size;
            }

            // generate final summary for the site
            $return['land']['area']         = $area_size;
            $return['land']['area_unit']    = $max_area_unit;
            $return['land']['area_type']    = $area_type;
            $return['land']['category']     = $category;
            $return['land']['price']        = self::getSitePrice($details);
        }
        return $return;
    }

    private static function getSiteAmenities($amenities) {
        $return = [];
        if ($amenities) {
            $arr = array(
                'lift'                  => ['class' => 'elevator', 'name' => 'Elevator/Lift'],
                'garden'                => ['class' => 'garden', 'name' => 'garden'],
                'security_facility'     => ['class' => 'security', 'name' => 'security'],
                'parking_area'          => ['class' => 'parking', 'name' => 'parking'],
                'children_play_area'    => ['class' => 'childrenplayarea', 'name' => 'Children play area'],
                'restaurant'            => ['class' => 'restaurant', 'name' => 'Restaurant'],
                'gas_line'              => ['class' => 'gaspipe', 'name' => 'Gas Pipe'],
                'internal_road'         => ['class' => 'internalroad', 'name' => 'Internal road'],
                'video_door_phone'      => ['class' => 'videodoor', 'name' => 'Video door'],
                'washing_machine_area'  => ['class' => 'washing-machine', 'name' => 'Washing machine'],
                'library'               => ['class' => 'library', 'name' => 'Library'],
                'internet'              => ['class' => 'internet', 'name' => 'Internet'],
                'intercom'              => ['class' => 'intercom', 'name' => 'Intercom'],
                'rainwater_harvest'     => ['class' => 'rainwater', 'name' => 'Rain Water harvesting'],
                'unity_stores'          => ['class' => 'unitystore', 'name' => 'Unity store'],
                'swimming_pool'         => ['class' => 'swimming-pool', 'name' => 'Swimming Pool'],
                'infinity_swimming_pool' => ['class' => 'infinity-pool', 'name' => 'Infinity Swimming pool'],
                'volleyball'            => ['class' => 'volleyball', 'name' => 'Volleyball'],
                'badminton'             => ['class' => 'badminton', 'name' => 'Badminton'],
                'golf'                  => ['class' => 'golf', 'name' => 'Golf'],
                'tennis'                => ['class' => 'tennis', 'name' => 'Tennis'],
                'squash'                => ['class' => 'squash', 'name' => 'Squash'],
                'yoga'                  => ['class' => 'yoga', 'name' => 'Yoga'],
                'gazebo'                => ['class' => 'gazebo', 'name' => 'Gazebo'],
                'banquet_hall'          => ['class' => 'banquet-hall', 'name' => 'Banquet hall'],
                'amphi_theatre'         => ['class' => 'amphi-theatre', 'name' => 'Amphi theatre'],
                'gymasium'              => ['class' => 'gym', 'name' => 'Gym'],
                'indoor_game_court'     => ['class' => 'indoor-games', 'name' => 'Indoor games court'],
                'outdoor_game_court'    => ['class' => 'outdoor-games', 'name' => 'Outdoor games court'],
                'joggers_park'          => ['class' => 'jogger-park', 'name' => 'Jogger park'],
                'butterfly_park'        => ['class' => 'butterfly-park', 'name' => 'Butterfly park'],
                'temple'                => ['class' => 'temple', 'name' => 'Temple'],
                'senior_citizen_garden' => ['class' => 'senior-citizen-garden', 'name' => 'Senior citizen garden'],
                'wifi'                  => ['class' => 'wifi', 'name' => 'WI-FI'],
                'relaxation_room'       => ['class' => 'relaxation', 'name' => 'Relaxation room']
            );
            foreach ($amenities as $val) {
                if (array_key_exists($val->meta_key, $arr)) {
                    $amenity = $val->meta_value == '1' ? 
                                    array(
                                        'class' => 'amenity-'.$arr[$val->meta_key]['class'],
                                        'name' => $arr[$val->meta_key]['name']
                                    ) : '';
                    if(!empty($amenity)){
                        $return[] = $amenity;
                    }
                }
            }
        }
        return $return;
    }
    /* search page helper - end */

    /* get property category and sub category wise area details */
    public static function getPropertyArea($property) {
        
        $return['area'] = '';
        $return['area_type'] = '';
        $return['area_unit'] = '';
        $return['type'] = '';
        $type = '';

        $cat_id = (int) $property->cat_id;
        $sub_cat_id = (int) $property->sub_cat_id;

        // check sub category available
        if ((isset($cat_id) && $cat_id > 0) || (isset($sub_cat_id) && $sub_cat_id > 0)) {
            // residential properties
            if ($sub_cat_id == 14) {
                // residential - Flats
                if (isset($property->propertyFeatures->carpet_area)) {
                    $return = self::getAreaByType($property->propertyFeatures, 'carpet_area', 'carpet_area_unit');
                    $type = 'CA';
                } else if (isset($property->propertyFeatures->sb_area)) {
                    $return = self::getAreaByType($property->propertyFeatures, 'sb_area', 'built_area_unit');
                    $type = 'SBA';
                }
            } else if ($sub_cat_id == 9) {
                // residential - house/villa
                $return = self::getAreaByType($property->propertyFeatures, 'built_area', 'built_area_unit');
                $type = 'BA';
            } else if ($sub_cat_id == 10) {
                // residential - open plots/weekend home
                $return = self::getAreaByType($property->propertyFeatures, 'plot_area', 'plot_area_unit');
                $type = 'PA';
            }

            /* commercial properties - shop, showroom, office */ 
            else if ($cat_id == 2 || in_array($sub_cat_id, [11, 12, 13])) {
                if (isset($property->propertyFeatures->sb_area)) {
                    $return = self::getAreaByType($property->propertyFeatures, 'sb_area', 'sb_area_unit');
                    $type = 'SBA';
                } else if (isset($property->propertyFeatures->built_area)) {
                    $return = self::getAreaByType($property->propertyFeatures, 'built_area', 'built_area_unit');
                    $type = 'BA';
                }
            }

            /* Industrial properties */ 
            else if (in_array($sub_cat_id, [16, 22])) {
                // Industrial plot / Godown - warehouse
                $return = self::getAreaByType($property->propertyFeatures, 'plot_area', 'plot_area_unit');
                $type = 'PA';
            } else if ($sub_cat_id == 16) {
                // Industrial shed
                $return = self::getAreaByType($property->propertyFeatures, 'shed_area', 'shed_area_unit');
                $type = 'SA';
            }

            // Land properties
            else if ($cat_id == 4) {
                $return = self::getAreaByType($property->propertyFeatures, 'area_covered', 'area_covered_unit');
                $type = 'LA';
            }
        }
        $return['type'] = $type;
        return $return;
    }

    public static function getSiteDetailsForDropdown() {
        
        if(defined('CURRENT_CITY') && !empty(CURRENT_CITY)){
            $Sites = Sites::where('property_type', 1)->where('city_id',CURRENT_CITY);
        }else{
            $Sites = Sites::where('property_type', 1);
        }
        // if (\Helpers::getUserCityAccess() !== false) {
        //     $Sites = $Sites->whereIn('city_id', \Helpers::getUserCityAccess());
        // }
        $Sites = $Sites->orderBy('site_name', 'asc')->pluck('site_name', 'id');

        return $Sites;
    }

    public static function getResaleSiteDetailsForDropdown() {
        
        if(defined('CURRENT_CITY') && !empty(CURRENT_CITY)){
            $Sites = Sites::where('property_type', 2)->where('city_id',CURRENT_CITY);
        }else{
            $Sites = Sites::where('property_type', 2);
        }

        $Sites = $Sites->where('status',1);

        // if (\Helpers::getUserCityAccess() !== false) {
        //     $Sites = $Sites->whereIn('city_id', \Helpers::getUserCityAccess());
        // }
        $Sites = $Sites->orderBy('site_name', 'asc')->pluck('site_name', 'id');

        return $Sites;
    }

    public static function getCompanyDetailsForDropdown() {
        
        return Companies::orderBy('company_name', 'asc')->pluck('company_name', 'id');

    }

    /**
        get area by area type example : for sb_area
        $arrArea = $propertyDetails->propertyFeatures (property features array)
        $arrSize = 'sb_area'
        $arrUnit = 'sb_area_unit'
    **/
    public static function getAreaByType($arrFeatures, $areaSize, $areaUnit) {
    
        // get area unit
        $arrAreaUnit = self::getStaticValues('area_unit');
        $arrAreaType = self::getStaticValues('area_type');

        // area size and unit
        $return['area']         = (int) $arrFeatures->$areaSize;
        $return['area_type']    = isset($arrAreaType[$areaSize]) && array_key_exists($areaSize, $arrAreaType)? $arrAreaType[$areaSize] : 'Area';
        $return['area_unit']    = (isset($arrFeatures->$areaUnit) && $arrFeatures->$areaUnit != '' ? 
                                        $arrAreaUnit[$arrFeatures->$areaUnit] : '');

        return $return;
    }

    /* Get site posssesion details */
    public static function getSitePossesionDetails($details) {
        if ($details->possession_status == 0) {
            if (strtotime($details->possession_date) > 0) {
                if (strtotime($details->possession_date) < time()) {
                    return 'Ready to move';
                } else {
                    return 'In '.date('M Y', strtotime($details->possession_date));
                }
            } else {
                return 'On going';
            }
        } else if ($details->possession_status == 1) {
            return 'Ready to move';
        } else {
            return 'On going';
        }
        return false;
    }

    /* get sample house details */
    public static function getSiteSampleHouseDetails($details) {
        if (isset($details->sample_house)) {
            switch ($details->sample_house) {
                case '1':
                    return 'Available';
                    break;

                case '2':
                    $sample_house_date = strtotime($details->sample_house_date) > 0 ? date('d-M-Y', strtotime($details->sample_house_date)) : '';
                    return 'Available by ' . $sample_house_date;
                    break;

                case '3':
                    return 'Under construction';
                    break;
            }
        }
        return false;
    }

    /* Get site area summary example: 1000 SQFT to 1200 SQFT */
    public static function getSitePropertyCategory($arrProperty) {
        
        
        if (isset($arrProperty) && !empty($arrProperty) > 0) {
            
            $category = [];
            foreach ($arrProperty as $property) {
                  
                // category details 
                $category[$property->propertyCategory->id]['id'] = $property->propertyCategory->id;
                $category[$property->propertyCategory->id]['name'] = $property->propertyCategory->name;
                $category[$property->propertyCategory->id]['slug'] = $property->propertyCategory->slug;

                // subcategory details if available
                $category[$property->propertyCategory->id]['subcategory'] = null;
                if ($property->propertySubCategory) {
                    $category[$property->propertyCategory->id]['subcategory'] = array(
                        'id'    => $property->propertySubCategory->id,
                        'name'  => $property->propertySubCategory->name,
                        'slug'  => $property->propertySubCategory->slug
                    );
                }

            }
            return $category;
        }
        return false;
    }


    public static function propertyInfobar($details = '', $type = '') {
        
        if (isset($details->properties) && count($details->properties) > 0) {

            switch ($type) {
                case 'residential':
                    $config = self::residentialInfoBar($details);
            }

            return '
                <div class="project-info">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="counter_frame blue">
                              
                                <span class="counter-value"> ' . $config[$type]['bhk'] . '</span>
                                <h3 class="title">Configurations</h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="counter_frame blue">
                              
                                <span class="counter-value">  ' . $config[$type]['area'] . ' ' . $config[$type]['area_unit'].'</span>
                                <h3 class="title">Area = '.$config[$type]['area_type'].'</h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="counter_frame blue possession_date_box"  data-id="'.$details->id.'">
                                <span class="counter-value">'.self::getSitePossesionDetails($details).'</span>
                                <h3 class="title">Possession &nbsp;'.(!\Helpers::isEndUser() && ($details->user_id == \Helpers::getLoginUserId()) ? ' 
                                    <span data-id="'.$details->id.'" class="edit-possession-date" style="position: absolute;right: 82px;">
                                        <i class="fa fa-edit"></i>
                                    </span>
                                    <span style="display:none;position: absolute;right: 82px;display: none;"  class="cancel-possession-date"><i class="fa fa-trash"></i></span>' : '' ).'
                                </h3>
                                
                                        <div class="update-possession-date-box" style="display:none;">
                                            <div class="row"  >
                                                <div class="col-md-12">
                                                    <input type="date" id="possession_date" /><span class="update-possession-date">&nbsp;&nbsp;<i class="fa fa-check" style="color:#fff;"></i></span>
                                                </div>
                                            </div>    
                                        </div>
                                 
                            </div>
                        </div>
                      <!--   <div class="col-md-4 mb-2">
                            <div class="info-col">
                                <div class="info-value">
                                    ' . $config[$type]['bhk'] . '
                                </div>
                                <div class="info-desc">Configurations</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="info-col">
                                <div class="info-value">
                                    ' . $config[$type]['area'] . ' ' . $config[$type]['area_unit'].'
                                </div>
                                <div class="info-desc">Area</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="info-col possession_date_box" data-id="'.$details->id.'">
                                <div class="info-value">'.self::getSitePossesionDetails($details).'</div>
                                <div class="info-desc">Possession &nbsp;'.(!\Helpers::isEndUser() && ($details->user_id == \Helpers::getLoginUserId()) ? '<span data-id="'.$details->id.'" class="edit-possession-date"><i class="fa fa-edit"></i></span><span style="display:none;"  class="cancel-possession-date"><i class="fa fa-cancel"></i></span>' : '' ).'</div>
                                <div class="update-possession-date-box" style="display:none;" >
                                    <input type="date" id="possession_date" /><span class="update-possession-date"><i class="fa fa-check"></i></span>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>';
        } 
        return false;
    }

    public static function getResidentialInfoBar($details = '')
    {
        $config = self::residentialInfoBar($details);
        return $config;
    }

    private static function residentialInfoBar($details) {
        
        // check property exist
        if (isset($details)) {
            
            $min_area_size = $max_area_size = 0;
            $min_area_unit = $max_area_unit = '';
            $area_type = '';
            // get properties in loop
           

                // get property wise area
                $property_area = self::getPropertyArea($details);
                 
                if(isset($property_area['type']) && !empty($property_area['type'])) 
                {
                    $area_type = $property_area['type'];
                }

                // property area > 0
                if ($property_area['area'] > 0) {
                    
                    // if min area available then update
                    if ($min_area_size == 0 || $min_area_size > $property_area['area']) {
                        $min_area_size = $property_area['area'];
                    }

                    // if max area available then update
                    if ($min_area_size == 0 || $max_area_size < $property_area['area']) {
                        $max_area_size = $property_area['area'];
                        $max_area_unit = $property_area['area_unit'];
                    }
                }

              
            // area from and to calculate
            if ($min_area_size > 0 && $min_area_size == $max_area_size) {
                $area_size = $min_area_size;
            } else if ($min_area_size == 0 && $max_area_size == 0) {
                $area_size = 'Not Available';
            } else {
                $area_size = $min_area_size.' to '.$max_area_size;
            }

            // generate final summary for the site
            $return['residential']['area']         = $area_size;
            $return['residential']['area_unit']    = $max_area_unit;
            $return['residential']['area_type']    = $area_type;
            $return['residential']['price']        = self::getSitePrice($details);
            $return['residential']['bhk']          = self::getSiteBedroomConfig($details);   
        }
        return $return;
    }

    public static function comparePropertyConfig($details) {
        if (count($details->properties) > 0) {
            $config = '';
            foreach ($details->properties as $key => $value) {
                $type_category = $value->propertyCategory->id;
                switch ($type_category) {
                    case 1:
                        $config .= self::compareResidentialConfig($value, $details);
                        break;
                    
                    case 2:
                        $config .= self::compareCommercialConfig($value, $details);
                        break;

                    case 3:
                        $config .= self::compareIndustrialConfig($value, $details);    
                        break;
                    
                    case 4:
                        $config .= self::compareLandConfig($value, $details);
                        break;
                }
            }
            return $config;
        } 
        return false;
    }

    public static function compareResidentialConfig($propertyDetails, $siteDetails) {

        // get area details
        $area = self::getPropertyArea($propertyDetails);

        // living room area details
        $living_room_area = self::chkMetas($propertyDetails->propertyMetas, 'living_room_area', '', 'n/a');
        $price = config('app.currency') .' '. self::getPrettyNumber($propertyDetails->price, true);
        $price = self::decidePriceVisibility($price, $siteDetails->price_status);
        if ($price == 'Price on request') {
            $price = '';
        } else {
            $price = 'Price - <a href="javascript:void(0)" class="leadModel" data-type="viewNumber" data-category="residential" data-id="'.$siteDetails->id.'">'.$price.'</a>';
        }

        $container = rand(99, 99999);
        $config = '<div class="card mb-3">
                <div class="card-header p-1">
                    <h5 class="mb-0">
                        <button class="btn btn-link btn-block no-shadow" type="button" data-toggle="collapse" data-target="#container-'.$container.'" aria-expanded="true" aria-controls="container-'.$container.'">
                            '.$propertyDetails->propertyFeatures->bedrooms.' BHK - '.$area['area'].' '.$area['area_unit'].' '.(isset($area['type']) && !empty($area['type']) ? '('.$area['type'].')' : '' ).' '.$price.'
                        </button>
                    </h5>
                </div>
                <div id="container-'.$container.'" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion-'.$container.'">
                    <div class="card-body p-0">
                        <table class="m-0 table table-property-details table-responsive table-striped">
                            <tbody>
                                '.self::getPropertySingleConfigFromMeta('Living room area', $propertyDetails->propertyMetas, 'living_room_area', 'table').'

                                '.self::getPropertySingleConfigFromMeta('Kitchen area', $propertyDetails->propertyMetas, 'kitchen_area', 'table').'

                                '.self::getPropertySingleConfigFromMeta('Kitchen wash area', $propertyDetails->propertyMetas, 'kitchen_wash_area', 'table');

                                if ($propertyDetails->propertyFeatures->foyer_area != null) {
                                    $config .= self::formatSingleConfig('Foyer area', $propertyDetails->propertyFeatures->foyer_area, 'table');
                                }

                                if ($propertyDetails->propertyFeatures->store_room != NULL) {
                                    $config .= self::formatSingleConfig('Store room', $propertyDetails->propertyFeatures->store_room, 'table');
                                }

                                if ($propertyDetails->propertyFeatures->pooja_room != NULL) {
                                    $config .= self::formatSingleConfig('Pooja room', $propertyDetails->propertyFeatures->pooja_room, 'table');
                                }

                                if ($propertyDetails->propertyFeatures->study_room != NULL) {
                                    $config .= self::formatSingleConfig('Study room', $propertyDetails->propertyFeatures->study_room, 'table');
                                }

                            $config .= '</tbody>
                        </table>
                    </div>
                </div>
            </div>';

        // $config = '
        //     <div class="row">
        //         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        //             <div class="property-description">
        //                 <div class="property-description-inner">';

        //             /* bedrooms areas */
        //             $config .= self::getPropertyMultipleConfigFromMeta('Bedrooms area', $propertyDetails->propertyFeatures, $propertyDetails->propertyMetas, 'bedrooms', 'bedroom_area_');

        //             /* bathrooms areas */
        //             $config .= self::getPropertyMultipleConfigFromMeta('Bathrooms area', $propertyDetails->propertyFeatures, $propertyDetails->propertyMetas, 'bathrooms', 'bedroom_bathroom_', 'living_room_bathroom');

        //             /* balconies areas */
        //             $config .= self::getPropertyMultipleConfigFromMeta('Balconies area', $propertyDetails->propertyFeatures, $propertyDetails->propertyMetas, 'balconies', 'bedroom_balcony_', 'living_room_balcony');
                 
        //             $config .= '<div class="row">';

                    

        //         $config .= '</div>        
        //                 </div>
        //             </div>
        //         </div>
        //     </div>
        // </div>';
        return $config;
    }

    protected static function compareCommercialConfig($details = '') {
        return false;
    }

    protected static function compareIndustrialConfig($details = '') {
        return false;
    }

    protected static function compareLandConfig($details = '') {
        return false;
    }

    public static function newPropertyConfig($details = '') {
        
        if (count($details->properties) > 0) {
            $config = '';
            foreach ($details->properties as $key => $value) {
                
                $type_category = $value->propertyCategory->id;
                switch ($type_category) {
                    case 1:
                        $config .= self::newPropertyResidentialConfig($value, $details);
                        break;
                    
                    case 2:
                        $config .= self::newPropertyCommercialConfig($value, $details);
                        break;

                    case 3:
                        $config .= self::industrialPropertyConfig($value, $details);    
                        break;
                    
                    case 4:
                        $config .= self::LandPropertyConfig($value, $details);
                        break;
                }
            }
            return $config;
        } 
        return false;
    }

    // Get property configuration for api
    public static function newPropertyConfigApi($details = '',$request=[]) {
        
        if (!empty($details)) {
            $config = [];$i=0;
            
                
                $type_category = $details->propertyCategory->id;
                
                switch ($type_category) {
                    case 1:
                        $config['residential'][$i] = self::newPropertyResidentialConfigAPi($details,$details->sites,$request);
                        break;
                    
                    case 2:
                        $config['commercial'][$i] = self::newPropertyCommercialConfigApi($details,$details->sites,$request);
                        break;

                    case 3:
                        $config['industrial'][$i] = self::industrialPropertyConfigApi($details,$details->sites,$request);    
                        break;
                    
                     
                }$i++;
            
            return $config;
        } 
        return false;
    }

    private static function newPropertyResidentialConfig($propertyDetails, $siteDetails) {
        
        // get area details
        $area = self::getPropertyArea($propertyDetails);

        // living room area details
        $living_room_area = self::chkMetas($propertyDetails->propertyMetas, 'living_room_area', '', 'n/a');
        $price = config('app.currency') .' '. self::getPrettyNumber($propertyDetails->price, true);
        $price = self::decidePriceVisibility($price, $siteDetails->price_status);
        if ($price == 'Price on request'){
            $price = '<a href="javascript:void(0)" class="leadModel" data-type="viewNumber" data-category="residential" data-id="'.$siteDetails->id.'">Contact for price</a>'.((\Helpers::isBuilder() || \Helpers::isAgent()) && ($siteDetails->user_id == \Helpers::getLoginUserId()) ? '<span class="fa fa-edit edit_property_price" data-type="property_price"></span> <br/><input type="number" data-currency="'.config('app.currency').'" property="'.$propertyDetails->id.'" class="edit_price property_price" style="display:none;" placeholder="Price"  />' : '' ).'';
        } else {
            $price = '<a href="javascript:void(0)" class="leadModel property_price_text" data-type="viewNumber" data-category="residential" data-id="'.$siteDetails->id.'">'.$price.'</a>'.((\Helpers::isBuilder() || \Helpers::isAgent()) && ($siteDetails->user_id == \Helpers::getLoginUserId()) ? '<span class="fa fa-edit edit_property_price" data-type="property_price"></span> <br/><input type="number" data-currency="'.config('app.currency').'" property="'.$propertyDetails->id.'" class="edit_price property_price" style="display:none;" placeholder="Price"  />' : '' ).'';
        }

        $config = '
        <div class="property-config-box">
            <div class="property-config-title">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        '.$propertyDetails->propertyFeatures->bedrooms.' BHK - '.$area['area'].' '.$area['area_unit'].' '.$area['type'].'
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <div class="text-right">'.$price.'</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                    <div id="layoutimage-slider">';
                    $layoutImages = self::getLayoutImage($propertyDetails->propertyImages);
                    if (count($layoutImages) > 0) {
                        foreach ($layoutImages as $layoutImage) {
                            $config .= '<div class="property-photo openPropertyGallery" data-image="'.$layoutImage.'">
                                <img src="'.$layoutImage.'" class="img-responsive" />
                            </div>';
                        }
                    } else {
                        $config .= '<div class="property-photo openPropertyGallery" data-image="'.url(\Config::get('constants.img_placeholder')).'">
                                <img src="'.url(\Config::get('constants.img_placeholder')).'" class="img-responsive" />
                            </div>';
                    } 
                    $config .= '</div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                    <div class="property-description">
                        <div class="property-description-inner">';

                    /* living room area */
                    $config .= '<div class="row">
                            '.($propertyDetails->propertyFeatures->total_floors ?
                                self::configBox('Total Floors', $propertpropertyDetails->propertyFeatures->total_floors)
                             : '').'
                            '.($propertyDetails->propertyFeatures->no_of_towers ?
                                self::configBox('No Of Towers', $propertyDetails->propertyFeatures->no_of_towers)
                             : '').'
                            '.($propertyDetails->propertyFeatures->no_of_flats ?
                                self::configBox('No Of Flats', $propertyDetails->propertyFeatures->no_of_flats)
                             : '').'
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                '.self::getPropertySingleConfigFromMeta('Living room area', $propertyDetails->propertyMetas, 'living_room_area').'
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                '.self::getPropertySingleConfigFromMeta('Kitchen area', $propertyDetails->propertyMetas, 'kitchen_area').'
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                '.self::getPropertySingleConfigFromMeta('Kitchen wash area', $propertyDetails->propertyMetas, 'kitchen_wash_area').'
                            </div>
                        </div>';

                    /* bedrooms areas */
                    $config .= self::getPropertyMultipleConfigFromMeta('Bedrooms area', $propertyDetails->propertyFeatures, $propertyDetails->propertyMetas, 'bedrooms', 'bedroom_area_');

                    /* bathrooms areas */
                    $config .= self::getPropertyMultipleConfigFromMeta('Bathrooms area', $propertyDetails->propertyFeatures, $propertyDetails->propertyMetas, 'bathrooms', 'bedroom_bathroom_', 'living_room_bathroom');

                    /* balconies areas */
                    $config .= self::getPropertyMultipleConfigFromMeta('Balconies area', $propertyDetails->propertyFeatures, $propertyDetails->propertyMetas, 'balconies', 'bedroom_balcony_', 'living_room_balcony');
                 
                    $config .= '<div class="row">';

                    if ($propertyDetails->propertyFeatures->foyer_area != null) {
                        $config .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        $config .= self::formatSingleConfig('Foyer area', $propertyDetails->propertyFeatures->foyer_area);
                        $config .= '</div>';
                    }

                    if ($propertyDetails->propertyFeatures->store_room != NULL) {
                        $config .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        $config .= self::formatSingleConfig('Store room', $propertyDetails->propertyFeatures->store_room);
                        $config .= '</div>';
                    }

                    if ($propertyDetails->propertyFeatures->pooja_room != NULL) {
                        $config .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        $config .= self::formatSingleConfig('Pooja room', $propertyDetails->propertyFeatures->pooja_room);
                        $config .= '</div>';
                    }

                    if ($propertyDetails->propertyFeatures->study_room != NULL) {
                        $config .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        $config .= self::formatSingleConfig('Study room', $propertyDetails->propertyFeatures->study_room);
                        $config .= '</div>';
                    }

                    if ($propertyDetails->propertyFeatures->parking_area != NULL) {
                        $config .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        $config .= self::formatSingleConfig('Parking area', $propertyDetails->propertyFeatures->parking_area);
                        $config .= '</div>';
                    }

                    if ($propertyDetails->propertyFeatures->open_sides != NULL) {
                        $config .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        $config .= self::formatSingleConfig('Open sides', $propertyDetails->propertyFeatures->open_sides);
                        $config .= '</div>';
                    }

                    if ($propertyDetails->propertyFeatures->is_corner_plot != NULL) {
                        $config .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        $config .= self::formatSingleConfig('Corner Plot', 'Yes');
                        $config .= '</div>';
                    }

                    if ($propertyDetails->propertyFeatures->furnished_status != NULL) {
                        $config .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        $config .= self::formatSingleConfig('Furnished Status', self::getStaticValues('furnished_status')[$details->propertyFeatures->furnished_status]);
                        $config .= '</div>';
                    }

                    if ($propertyDetails->propertyFeatures->width_of_road_facing_plot != NULL) {
                        $config .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        $config .= self::formatSingleConfig('Width of road facing plot', $propertyDetails->propertyFeatures->width_of_road_facing_plot);
                        $config .= '</div>';
                    }

                    if ($propertyDetails->propertyFeatures->servant_room != NULL) {
                        $config .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        $config .= self::formatSingleConfig('Servant room', $propertyDetails->propertyFeatures->servant_room);
                        $config .= '</div>';
                    }

                $config .= '</div>        
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        return $config;
    }

    // Get residential property configurations
    private static function newPropertyResidentialConfigAPi($propertyDetails, $siteDetails,$request=[]) 
    {
        
        $propertyConfig = [];
        // get area details
        $area = self::getPropertyArea($propertyDetails);

        // living room area details
        $living_room_area = self::chkMetas($propertyDetails->propertyMetas, 'living_room_area', '', 'n/a');
        $price = config('app.currency') .' '. self::getPrettyNumber($propertyDetails->price, true);
        $price = self::decidePriceVisibilityApi($price,$siteDetails->price_status,$request);
        
        if($price == 'Price on request')
        {
            $price = 'Contact for price';
        }
        else
        {
            $price = $price;
        }

        $propertyConfig['property_id'] = isset($propertyDetails->id) && !empty($propertyDetails->id) ? (string)$propertyDetails->id : '';
        $propertyConfig['property_detail'] = $propertyDetails->propertyFeatures->bedrooms.' BHK - '.$area['area'].' '.$area['area_unit'].' '.$area['type'];
        $propertyConfig['price'] = $price;

        $layoutImages = self::getLayoutImage($propertyDetails->propertyImages);
         

        $floorImage = [];    
        if(count($layoutImages) > 0)
        {   $i = 0;
            foreach($layoutImages as $layoutImage)
            {
                $floorImage[$i] = $layoutImage;
                $i++;
            }
        } 
        else
        {
            $floorImage = null;
        }
        $propertyConfig['floor_plan_image'] = $floorImage;  

        // Get living room area
        $livingRoomArea = self::getPropertySingleConfigFromMeta('Living room area', $propertyDetails->propertyMetas, 'living_room_area',null,true);
        
        if(isset($livingRoomArea) && !empty($livingRoomArea))
        {
            $livingRoomData = [];
            $livingRoomData['config_title'] = 'Living room area';
            $livingRoomData['config_value'] = $livingRoomArea;

            // Add living room area into config details
            $propertyConfig['residential_config_detail'][] = $livingRoomData;
        }
        
        // Get kitchen area
        $kitchenArea = self::getPropertySingleConfigFromMeta('Kitchen area', $propertyDetails->propertyMetas, 'kitchen_area',null,true);
        
        if(isset($kitchenArea) && !empty($kitchenArea))
        {   
            $kitchenData = [];
            $kitchenData['config_title'] = 'Kitchen area';
            $kitchenData['config_value'] = $kitchenArea;

            // Add kitchen area into config details
            $propertyConfig['residential_config_detail'][] = $kitchenData;
        }
        
        // Get kitchen wash area
        $kitchenWashArea = self::getPropertySingleConfigFromMeta('Kitchen wash area', $propertyDetails->propertyMetas, 'kitchen_wash_area',null,true);
        
        if(isset($kitchenWashArea) && !empty($kitchenWashArea))
        {   
            $kitchenWashData = [];
            $kitchenWashData['config_title'] = 'Kitchen wash area';
            $kitchenWashData['config_value'] = $kitchenWashArea;

            // Add kitchen wash area into config details
            $propertyConfig['residential_config_detail'][] = $kitchenWashData;
        }

        // Get bedrooms area
        $bedroomArea = self::getPropertyMultipleConfigFromMetaApi('Bedrooms area', $propertyDetails->propertyFeatures, $propertyDetails->propertyMetas, 'bedrooms', 'bedroom_area_');
        
        if(isset($bedroomArea) && !empty($bedroomArea) && count($bedroomArea)>0)
        {
            // Add bedrooms area into config details
            $propertyConfig['residential_config_detail'][] = $bedroomArea; 
        }

        // Get bathrooms area 
        $bathroomArea = self::getPropertyMultipleConfigFromMetaApi('Bathrooms area', $propertyDetails->propertyFeatures, $propertyDetails->propertyMetas, 'bathrooms', 'bedroom_bathroom_', 'living_room_bathroom');
        
        if(isset($bathroomArea) && !empty($bathroomArea) && count($bathroomArea)>0)
        {
            // Add bathroom area into config details
            $propertyConfig['residential_config_detail'][] = $bathroomArea; 
        }

        // Get balconies area
        $balconyArea = self::getPropertyMultipleConfigFromMetaApi('Balconies area', $propertyDetails->propertyFeatures, $propertyDetails->propertyMetas, 'balconies', 'bedroom_balcony_', 'living_room_balcony');
        
        if(isset($balconyArea) && !empty($balconyArea) && count($balconyArea)>0)
        {
            // Add bathroom area into config details
            $propertyConfig['residential_config_detail'][] = $balconyArea;
        }

        // Get foyer area
        if(isset($propertyDetails->propertyFeatures->foyer_area) && $propertyDetails->propertyFeatures->foyer_area != null) 
        {
           $foyarAreaData=[];
           $foyarAreaData['config_title'] = 'Foyer area';
           $foyarAreaData['config_value'] = $propertyDetails->propertyFeatures->foyer_area;

           // Add foyer area into config details
           $propertyConfig['residential_config_detail'][] = $foyarAreaData;
        }

        // Get store room area
        if(isset($propertyDetails->propertyFeatures->store_room) && $propertyDetails->propertyFeatures->store_room != NULL) 
        {
           $storeRoomData=[];
           $storeRoomData['config_title'] = 'Store room area';
           $storeRoomData['config_value'] = $propertyDetails->propertyFeatures->store_room;

           // Add Store room area into config details
           $propertyConfig['residential_config_detail'][] = $storeRoomData;
        }

        // Get pooja room area
        if(isset($propertyDetails->propertyFeatures->pooja_room) && $propertyDetails->propertyFeatures->pooja_room != NULL) 
        {
            $poojaRoomData=[];
            $poojaRoomData['config_title'] = 'Pooja room area';
            $poojaRoomData['config_value'] = $propertyDetails->propertyFeatures->pooja_room;

            // Add pooja room area into config details
            $propertyConfig['residential_config_detail'][] = $poojaRoomData;
        }

        // Get study room area
        if(isset($propertyDetails->propertyFeatures->study_room) && $propertyDetails->propertyFeatures->study_room != NULL) 
        {
            $studyRoomData=[];
            $studyRoomData['config_title'] = 'Study room area';
            $studyRoomData['config_value'] = $propertyDetails->propertyFeatures->study_room;

            // Add study room area into config details
            $propertyConfig['residential_config_detail'][] = $studyRoomData;
        }

        // Get parking area
        if(isset($propertyDetails->propertyFeatures->parking_area) && $propertyDetails->propertyFeatures->parking_area != NULL) 
        {
            $parkingRoomData=[];
            $parkingRoomData['config_title'] = 'Parking area';
            $parkingRoomData['config_value'] = $propertyDetails->propertyFeatures->parking_area;

            // Add parking area into config details
            $propertyConfig['residential_config_detail'][] = $parkingRoomData;
        }

        // Get open side area
        if(isset($propertyDetails->propertyFeatures->open_sides) && $propertyDetails->propertyFeatures->open_sides != NULL) 
        {
            $openSideData=[];
            $openSideData['config_title'] = 'Open side area';
            $openSideData['config_value'] = $propertyDetails->propertyFeatures->open_sides;

            // Add open side area into config details
            $propertyConfig['residential_config_detail'][] = $openSideData;
        }

        // Get is corner plot area
        if(isset($propertyDetails->propertyFeatures->is_corner_plot) && $propertyDetails->propertyFeatures->is_corner_plot != NULL) 
        {
            $isCornerPlotData=[];
            $isCornerPlotData['config_title'] = 'Is corner plot area';
            $isCornerPlotData['config_value'] = 'Yes';

            // Add is corner plot area into config details
            $propertyConfig['residential_config_detail'][] = $isCornerPlotData;
        }

        // Get furnished status
        if(isset($propertyDetails->propertyFeatures->furnished_status) && $propertyDetails->propertyFeatures->furnished_status != NULL) 
        {
            $furnishedStausData=[];
            $furnishedStausData['config_title'] = 'Furnished status';
            $furnishedStausData['config_value'] = self::getStaticValues('furnished_status')[$propertyDetails->propertyFeatures->furnished_status];

            // Add furnished status into config details
            $propertyConfig['residential_config_detail'][] = $furnishedStausData;
        }

        // Get width of road facing plot
        if(isset($propertyDetails->propertyFeatures->width_of_road_facing_plot) && $propertyDetails->propertyFeatures->width_of_road_facing_plot != NULL) 
        {
            $widthOfRoadFacingPlotData=[];
            $widthOfRoadFacingPlotData['config_title'] = 'Width of road facing plot';
            $widthOfRoadFacingPlotData['config_value'] = $propertyDetails->propertyFeatures->width_of_road_facing_plot;

            // Add width of road facing plot area into config details
            $propertyConfig['residential_config_detail'][] = $widthOfRoadFacingPlotData;
        }

        // Get servent room area
        if(isset($propertyDetails->propertyFeatures->servant_room) && $propertyDetails->propertyFeatures->servant_room != NULL) 
        {
            $servantRoomData=[];
            $servantRoomData['config_title'] = 'Servant room area';
            $servantRoomData['config_value'] = $propertyDetails->propertyFeatures->servant_room;

            // Add servent room area into config details
            $propertyConfig['residential_config_detail'][] = $storeRoomData;
        }
                
        return $propertyConfig;
    }

    private static function newPropertyCommercialConfig($details, $siteDetails) {
        
        $return = '';
        
        if (isset($details->propertyFeatures->total_floors) && 
            $details->propertyFeatures->total_floors > 0) {

            if (isset($details->propertyMetas)) {
                $return .= '<table class="table table-responsive table-hover table-striped" id="customers">';
                $return .= '<thead>';
                $return .= '<tr>';
                $return .= '<th class="text-center">Floor</th>';
                $return .= '<th class="text-center">Area</th>';
                $return .= '<th class="text-center">Total Unit</th>';
                $return .= '<th class="text-center">Booked Unit</th>';
                $return .= '<th class="text-center">Available Unit</th>';
                $return .= '<th class="text-center">Price per SQFT</th>';
                $return .= '</tr>';
                $return .= '</thead>';

                $return .= '<tbody>';
                for ($i = 0; $i <= $details->propertyFeatures->total_floors; $i++) {
                    $min_area = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_min_area', '', '');
                    $max_area = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_max_area', '', '');
                    $price = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_price_sq_ft', '', 'n/a');

                    $return .= '<tr>';
                    $return .= '<td class="text-center">'.self::NumSuffix($i).' floor</td>';
                    $return .= '<td class="text-center">'.$min_area.' Sq.Ft. - '.$max_area.' Sq.Ft.</td>';
                    $return .= '<td class="text-center">'. self::chkMetas($details->propertyMetas, 'floor_'.$i.'_total_units', '', '-') .'</td>';
                    $return .= '<td class="text-center">'. self::chkMetas($details->propertyMetas, 'floor_'.$i.'_booked', '', '-') .'</td>';
                    $return .= '<td class="text-center">'. self::chkMetas($details->propertyMetas, 'floor_'.$i.'_available', '', '-') .'</td>';

                    $price = self::decidePriceVisibility($price, $siteDetails->price_status);
                    if ($price == 'Price on request'){
                        $return .= '<td class="text-right"><a href="javascript:void(0)" class="leadModel" data-type="viewNumber" data-category="commercial" data-id="'.$siteDetails->id.'">Contact for price</a>'.((\Helpers::isBuilder() || \Helpers::isAgent()) && ($siteDetails->user_id == \Helpers::getLoginUserId()) ? '<span class="fa fa-edit edit_property_price" data-display-class="floor_'.$i.'_price_sq_ft"></span> <br/><input type="number" data-id="floor_'.$i.'_price_sq_ft" property="'.$details->propertyMetas[0]->property_id.'" key="'.$i.'" class="edit_price floor_'.$i.'_price_sq_ft" style="display:none;" placeholder="Price"  />' : '' ).'</td>';
                    } else {
                        $return .= '<td class="text-right"><a href="javascript:void(0)" class="leadModel per_sqft_price" data-type="viewNumber" data-category="commercial" data-id="'.$siteDetails->id.'">'.$price.'</a> '.((\Helpers::isBuilder() || \Helpers::isAgent()) && ($siteDetails->user_id == \Helpers::getLoginUserId()) ? '<span class="fa fa-edit edit_property_price" data-display-class="floor_'.$i.'_price_sq_ft"></span> <br/><input type="number" data-id="floor_'.$i.'_price_sq_ft" property="'.$details->propertyMetas[0]->property_id.'" key="'.$i.'" class="edit_price floor_'.$i.'_price_sq_ft" style="display:none;" placeholder="Price"  />' : '' ).' </td>';
                    }
                    $return .= '</tr>';
                }
                $return .= '</tbody>';

                $return .= '</table>';
            }
        }
        return $return;
    }


    /**
    *   Get commercial property configurations for api 
    */
    private static function newPropertyCommercialConfigApi($details, $siteDetails,$request=[]) {
        
        $return = '';
        $commercialPropertyConfig['commercial_config_detail'] = []; 
        $startingPrice = [];
        if (isset($details->propertyFeatures->total_floors) && 
            $details->propertyFeatures->total_floors > 0) {

            if (isset($details->propertyMetas)) {
                for ($i = 0; $i <= $details->propertyFeatures->total_floors; $i++) {
                    
                    $min_area = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_min_area', '', '');
                    $max_area = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_max_area', '', '');
                    $price = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_price', '', 'n/a');
                    $price_sq_ft = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_price_sq_ft', '', 'n/a');

                    $commercialPropertyConfig['commercial_config_detail'][$i]['property_id'] = isset($details->id) ? (string)$details->id : '';
                    $commercialPropertyConfig['commercial_config_detail'][$i]['floor'] = self::NumSuffix($i).' floor';
                    $commercialPropertyConfig['commercial_config_detail'][$i]['area'] = $min_area.' Sq.Ft. - '.$max_area.' Sq.Ft.';
                    $commercialPropertyConfig['commercial_config_detail'][$i]['total_unit'] = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_total_units', '', '-');
                    $commercialPropertyConfig['commercial_config_detail'][$i]['booked_unit'] = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_booked', '', '-');
                    $commercialPropertyConfig['commercial_config_detail'][$i]['available_unit'] = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_available', '', '-');

                    $price = self::decidePriceVisibilityApi($price, $siteDetails->price_status,$request);
                    if ($price == 'Price on request'){
                        $commercialPropertyConfig['commercial_config_detail'][$i]['price'] = 'Contact for price';
                    } else {
                        $commercialPropertyConfig['commercial_config_detail'][$i]['price'] = $price;
                        array_push($startingPrice,$price);
                    }
                    $commercialPropertyConfig['commercial_config_detail'][$i]['price_per_sqft'] = isset($price_sq_ft) ? $price_sq_ft : '';
                }
            }
        }
        if(isset($startingPrice) && count($startingPrice)>0)
        {
            $commercialPropertyConfig['starting_price'] = min($startingPrice);    
        }
        else
        {
            $commercialPropertyConfig['starting_price'] = 'Contact for price';
        }
        
        return $commercialPropertyConfig;
    }

    private static function industrialPropertyConfig($details, $siteDetails) {
        
        // get area details
        $areaUnit = self::getStaticValues('area_unit');

        $return = '<div class="property-description">
                        <div class="property-description-inner">
                            <div class="row">';

        $price = config('app.currency') .' '. self::getPrettyNumber($details->price, true);
        $price = self::decidePriceVisibility($price, $siteDetails->price_status);
        if ($price == 'Price on request'){
            $price = '<a href="javascript:void(0)" class="leadModel" data-type="viewNumber" data-category="residential" data-id="'.$siteDetails->id.'">Contact for price</a>'.((\Helpers::isBuilder() || \Helpers::isAgent()) && ($siteDetails->user_id == \Helpers::getLoginUserId()) ? '<span class="fa fa-edit edit_property_price" data-type="property_price"></span> <br/><input type="number" data-currency="'.config('app.currency').'" property="'.$details->id.'" class="edit_price property_price" style="display:none;" placeholder="Price"  />' : '' ).'';
        } else {
            $price = '<a href="javascript:void(0)" class="leadModel property_price_text" data-type="viewNumber" data-category="residential" data-id="'.$siteDetails->id.'">'.$price.'</a>'.((\Helpers::isBuilder() || \Helpers::isAgent()) && ($siteDetails->user_id == \Helpers::getLoginUserId()) ? '<span class="fa fa-edit edit_property_price" data-type="property_price"></span> <br/><input type="number" data-currency="'.config('app.currency').'" property="'.$details->id.'" class="edit_price property_price" style="display:none;" placeholder="Price"  />' : '' ).'';
        }

        $return .= self::configBox('Price',$price);
        
        if ($details->propertyFeatures->plot_area != '') {
            $return .= self::configBox('Plot Area', ($details->propertyFeatures->plot_area.' '.$areaUnit[$details->propertyFeatures->plot_area_unit]));
        }

        if ($details->propertyFeatures->shed_area != '') {
            $return .= self::configBox('Shed Area', ($details->propertyFeatures->shed_area.' '.$areaUnit[$details->propertyFeatures->shed_area_unit]));
        }

        if ($details->propertyFeatures->shed_height != '') {
            $return .= self::configBox('Shed height', ($details->propertyFeatures->shed_height.' '.$areaUnit[$details->propertyFeatures->shed_height_unit]));
        }

        if ($details->propertyFeatures->road_approach != '') {
            if($details->propertyFeatures->road_approach == 'road_touch')
            {
                $roadApproach = 'Road Touch';
            }elseif($details->propertyFeatures->road_approach == 'interior')
            {
                $roadApproach = 'Interior';
            }elseif ($details->propertyFeatures->road_approach == 'corner_plot') {
                $roadApproach = 'Corner Plot';
            }

            $return .= self::configBox('Road Approach', ($roadApproach));
        }

        if ($details->propertyFeatures->road1_width != '') {
            $return .= self::configBox('Road 1 Width', ($details->propertyFeatures->road1_width.' '.$areaUnit[$details->propertyFeatures->road1_width_unit]));
        }

        if ($details->propertyFeatures->road2_width != '') {
            $return .= self::configBox('Road 2 Width', ($details->propertyFeatures->road2_width.' '.$areaUnit[$details->propertyFeatures->road2_width_unit]));
        }

        if ($details->propertyFeatures->road_width != '') {
            $return .= self::configBox('Road Width', ($details->propertyFeatures->road_width.' '.$areaUnit[$details->propertyFeatures->road_width_unit]));
        }        

        if ($details->propertyFeatures->crane_facility != '') {
            $return .= self::configBox('Crane Facility', ucfirst($details->propertyFeatures->crane_facility));
        }

        if ($details->propertyFeatures->electricity_connection != '') {
            $return .= self::configBox('Electricity connection', ucfirst($details->propertyFeatures->electricity_connection));
        }

        if ($details->propertyFeatures->power_capacity != '') {
            $return .= self::configBox('Power Capacity', $details->propertyFeatures->power_capacity.' HP');
        }

        if ($details->propertyFeatures->etp != '') {
            $return .= self::configBox('ETP', $details->propertyFeatures->etp);
        }


           
        if ($details->propertyFeatures->payment_terms != '') {
            $return .= self::configBox('Payment Terms',$details->propertyFeatures->payment_terms);
        }

        if ($details->propertyFeatures->price_sq_ft != '') {
         
            $return .= self::configBox('Price Per Sq. Ft.', $details->propertyFeatures->price_sq_ft);
           
        }

        if(isset($details->propertyFeatures->total_price) && !empty($details->propertyFeatures->total_price))
        {
            $return .= self::configBox('Total Price', (string)self::getPrettyNumber($details->propertyFeatures->total_price));
        }

        $getRecord = Properties::where('site_id',$details->site_id)->first();
        
        if($getRecord!=''){
            if ($getRecord->price != '') {
                $return .= self::configBox('Price ',(string)self::getPrettyNumber($getRecord->price)) ;
            }

            if ($getRecord->booking_amount != '') {
                $return .= self::configBox('Token ',(string)self::getPrettyNumber($getRecord->booking_amount)) ;
            }  

            if ($getRecord->maintenance != '') {
                $return .= self::configBox('Maintenance ',(string)self::getPrettyNumber($getRecord->maintenance)) ;
            } 
        }







        if ($siteDetails->water_supply == 1) {
            $return .= self::configBox('Water supply', 'Available');
        }




        $return .= '</div></div></div>';
        return $return;
    }

    /**
    * Get industrial property configuration for api
    */
    private static function industrialPropertyConfigApi($details, $siteDetails,$request=[]) {
        
        $industrialPropertyConfig['industrial_config_detail'] = [];

        // get area details
        $areaUnit = self::getStaticValues('area_unit');

        $price = config('app.currency') .' '. self::getPrettyNumber($details->price, true);
        $price = self::decidePriceVisibilityApi($price,$siteDetails->price_status,$request);
        $ic = 0;
        if ($price == 'Price on request'){
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Price';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = 'Contact for price';
            $ic++;
        } else {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Price';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = $price;
            $ic++;
        }

        if (isset($details->propertyFeatures->plot_area) && $details->propertyFeatures->plot_area != '') {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Plot Area';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = $details->propertyFeatures->plot_area.' '.$areaUnit[$details->propertyFeatures->plot_area_unit];
            $ic++;
        }

        if (isset($details->propertyFeatures->shed_area) && $details->propertyFeatures->shed_area != '') {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Shed Area';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = $details->propertyFeatures->shed_area.' '.$areaUnit[$details->propertyFeatures->shed_area_unit];
            $ic++;
        }

        if (isset($details->propertyFeatures->shed_height) && $details->propertyFeatures->shed_height != '') {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Shed Height';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = $details->propertyFeatures->shed_height.' '.$areaUnit[$details->propertyFeatures->shed_height_unit];
            $ic++;
        }

        if (isset($details->propertyFeatures->road_approach) && $details->propertyFeatures->road_approach != '') {
            if($details->propertyFeatures->road_approach == 'road_touch')
            {
                $roadApproach = 'Road Touch';
            }elseif($details->propertyFeatures->road_approach == 'interior')
            {
                $roadApproach = 'Interior';
            }elseif ($details->propertyFeatures->road_approach == 'corner_plot') {
                $roadApproach = 'Corner Plot';
            }

            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Road Approach';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = $roadApproach;
            $ic++;
        }

        if (isset($details->propertyFeatures->road1_width) && $details->propertyFeatures->road1_width != '') {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Road 1 Width';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = $details->propertyFeatures->road1_width.' '.$areaUnit[$details->propertyFeatures->road1_width_unit];
            $ic++;
        }

        if (isset($details->propertyFeatures->road2_width) && $details->propertyFeatures->road2_width != '') {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Road 2 Width';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = $details->propertyFeatures->road2_width.' '.$areaUnit[$details->propertyFeatures->road2_width_unit];
            $ic++;
        }

        if (isset($details->propertyFeatures->road_width) && $details->propertyFeatures->road_width != '') {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Road Width';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = $details->propertyFeatures->road_width.' '.$areaUnit[$details->propertyFeatures->road_width_unit];
            $ic++;
        }        

        if (isset($details->propertyFeatures->crane_facility) && $details->propertyFeatures->crane_facility != '') {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Crane Facility';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = ucfirst($details->propertyFeatures->crane_facility);
            $ic++;
        }

        if (isset($details->propertyFeatures->electricity_connection) && $details->propertyFeatures->electricity_connection != '') {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Electricity Connection';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = ucfirst($details->propertyFeatures->electricity_connection);
            $ic++;
        }

        if (isset($details->propertyFeatures->power_capacity) && $details->propertyFeatures->power_capacity != '') {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Power Capacity';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = $details->propertyFeatures->power_capacity.' HP';
            $ic++;
        }

        if (isset($siteDetails->water_supply) && $siteDetails->water_supply == 1) {
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_title'] = 'Water Supply';
            $industrialPropertyConfig['industrial_config_detail'][$ic]['config_value'] = 'Available';
            $ic++;
        }

        return $industrialPropertyConfig;
    }

    private static function LandPropertyConfig($details, $siteDetails) {

        // get property area
        $detailsAvailable = false;
        $area = self::getPropertyArea($details);
        $areaUnit = self::getStaticValues('area_unit');

        $return = '<div class="property-description">
                        <div class="property-description-inner">
                            <div class="row">';

        $price = config('app.currency') .' '. self::getPrettyNumber($details->price, true);
        $price = self::decidePriceVisibility($price, $siteDetails->price_status);
        if ($price == 'Price on request'){
            $price = '<a href="javascript:void(0)" class="leadModel" data-type="viewNumber" data-category="residential" data-id="'.$siteDetails->id.'">Contact for price</a>'.((\Helpers::isBuilder() || \Helpers::isAgent()) && ($siteDetails->user_id == \Helpers::getLoginUserId()) ? '<span class="fa fa-edit edit_property_price" data-type="property_price"></span> <br/><input type="number" data-currency="'.config('app.currency').'" property="'.$details->id.'" class="edit_price property_price" style="display:none;" placeholder="Price"  />' : '' ).'';
        } else {
            $price = '<a href="javascript:void(0)" class="leadModel property_price_text" data-type="viewNumber" data-category="residential" data-id="'.$siteDetails->id.'">'.$price.'</a>'.((\Helpers::isBuilder() || \Helpers::isAgent()) && ($siteDetails->user_id == \Helpers::getLoginUserId()) ? '<span class="fa fa-edit edit_property_price" data-type="property_price"></span> <br/><input type="number" data-currency="'.config('app.currency').'" property="'.$details->id.'" class="edit_price property_price" style="display:none;" placeholder="Price"  />' : '' ).'';
        }

        $return .= self::configBox('Price',$price);

        
        if ($area['area'] != '') {
            $return .= self::configBox($area['area_type'], ($area['area'].' '.$area['area_unit']));
            $detailsAvailable = true;
        }

        if ($details->propertyFeatures->area_covered != '') {
            $return .= self::configBox('Total Land Area', ($details->propertyFeatures->area_covered.' '.$areaUnit[$details->propertyFeatures->area_covered_unit]));
            $detailsAvailable = true;
        }

        if ($details->propertyFeatures->price_sq_ft != '') {
            $return .= self::configBox('Price per SQFT', $details->propertyFeatures->price_sq_ft);
            $detailsAvailable = true;
        }

        $land_zone = self::chkMetas($details->propertyMetas, 'land_zone', '','');      
        if ($land_zone != '') {
            $arr_land_zone = self::getStaticValues('land_zone');
            $return .= self::configBox('Land Declared Zone', (array_key_exists($land_zone, $arr_land_zone) ? $arr_land_zone[$land_zone] : $land_zone));
            $detailsAvailable = true;
        }

        $land_type = self::chkMetas($details->propertyMetas, 'land_type', '','');      
        if ($land_type != '') {
            $arr_land_type = self::getStaticValues('land_type');
            $return .= self::configBox('Land type', (array_key_exists($land_type, $arr_land_type) ? $arr_land_type[$land_type] : $land_type));
            $detailsAvailable = true;
        }

        $no_of_owners = self::chkMetas($details->propertyMetas, 'no_of_owners', '','');      
        if ($no_of_owners != '') {
            $return .= self::configBox('No of owners', $no_of_owners);
            $detailsAvailable = true;
        }

        $land_location = self::chkMetas($details->propertyMetas, 'land_location', '','');      
        if ($land_location != '') {
            $arr_land_location = self::getStaticValues('land_location');
            $return .= self::configBox('Land location', (array_key_exists($land_location, $arr_land_location) ? $arr_land_location[$land_location] : $land_location));
            $detailsAvailable = true;
        }

        $good_for = self::chkMetas($details->propertyMetas, 'good_for', '','');      
        if ($good_for != '') {
            $arr_good_for = self::getStaticValues('land_zone');
            $return .= self::configBox('Land good for', (array_key_exists($good_for, $arr_good_for) ? $arr_good_for[$good_for] : $good_for));
            $detailsAvailable = true;
        }

        if ($details->propertyFeatures->road_approach != '') {
            if($details->propertyFeatures->road_approach == 'road_touch')
            {
                $roadApproach = 'Road Touch';
            }elseif($details->propertyFeatures->road_approach == 'interior')
            {
                $roadApproach = 'Interior';
            }elseif ($details->propertyFeatures->road_approach == 'corner_plot') {
                $roadApproach = 'Corner Plot';
            }

            $return .= self::configBox('Road Approach', ($roadApproach));
        }

         

        if ($details->propertyFeatures->length != '' && $details->propertyFeatures->width != '') {
            $return .= self::configBox('Size', $details->propertyFeatures->length.' * '.$details->propertyFeatures->width.' '.$details->propertyFeatures->length_width_unit);
        }
        if ($details->propertyFeatures->frontage != '') {
           
            $return .= self::configBox('Frontage', $details->propertyFeatures->frontage);
        }
        if ($details->propertyFeatures->facing != '') {
           
            $return .= self::configBox('Facing', $details->propertyFeatures->facing);
        }

        if ($details->propertyFeatures->area_covered != '') {
           
            $return .= self::configBox('Total Size', $details->propertyFeatures->area_covered.' '.$details->propertyFeatures->area_covered_unit);
        }

        $getRecord = Properties::where('site_id',$details->site_id)->first();
        if($getRecord!=''){
            if ($getRecord->price != '') {
                $return .= self::configBox('Price ',(string)self::getPrettyNumber($getRecord->price)) ;
            }

            if ($getRecord->booking_amount != '') {
                $return .= self::configBox('Token ',(string)self::getPrettyNumber($getRecord->booking_amount)) ;
            }  

            if ($getRecord->maintenance != '') {
                $return .= self::configBox('Maintenance',(string)self::getPrettyNumber($getRecord->maintenance)) ;
            } 

            if ($getRecord->village != '') {
                $return .= self::configBox('Village Name',$getRecord->village) ;
            } 

            if ($getRecord->fp_no != '') {
                $return .= self::configBox('Fp No',$getRecord->fp_no) ;
            }

            if ($getRecord->survey_no != '') {
                $return .= self::configBox('Survey No',$getRecord->survey_no) ;
            }

            if ($getRecord->tp_scheme_no != '') {
                $return .= self::configBox('Tp Scheme No',$getRecord->tp_scheme_no) ;
            }

        }

        if ($details->propertyFeatures->payment_terms != '') {
            $return .= self::configBox('Payment Terms',$details->propertyFeatures->payment_terms);
        }

        if ($details->propertyFeatures->price_sq_ft != '') {
         
            $return .= self::configBox('Price Per Sq. Ft.', $details->propertyFeatures->price_sq_ft);
           
        }
        if ($details->propertyFeatures->usp != '') {
         
            $return .= self::configBox('USP.', $details->propertyFeatures->usp);
        }
        

        if(isset($details->propertyFeatures->total_price) && !empty($details->propertyFeatures->total_price))
        {
            $return .= self::configBox('Total Price', (string)self::getPrettyNumber($details->propertyFeatures->total_price));
        }

        if ($details->propertyFeatures->road1_width != '') {
            $return .= self::configBox('Road 1 Width', ($details->propertyFeatures->road1_width.' '.$areaUnit[$details->propertyFeatures->road1_width_unit]));
        }

        if ($details->propertyFeatures->road2_width != '') {
            $return .= self::configBox('Road 2 Width', ($details->propertyFeatures->road2_width.' '.$areaUnit[$details->propertyFeatures->road2_width_unit]));
        }

        if ($details->propertyFeatures->road_width != '') {
            $return .= self::configBox('Road Width', ($details->propertyFeatures->road_width.' '.$areaUnit[$details->propertyFeatures->road_width_unit]));
        }

        if ($detailsAvailable == false) {
            $return .= '<div class="col-md-12">No details are available.</div>';
        }

        $return .= '</div>
            </div>
        </div>';

        return $return;
    }

    /**
    * Get land property configuration 
    */
    private static function LandPropertyConfigApi($details,$siteDetails,$request=[]) {

        $landPropertyConfig['land_config_detail'] = [];

        // get property area
        $area = self::getPropertyArea($details);
        $areaUnit = self::getStaticValues('area_unit');

        $price = config('app.currency') .' '. self::getPrettyNumber($details->price, true);
        $price = self::decidePriceVisibilityApi($price,$siteDetails->price_status,$request);
        $l=0;
        if ($price == 'Price on request'){
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Price';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = 'Contact for price';
            $l++;
        } else {
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Price';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = $price;
            $l++;
        }

        if (isset($area['area']) && $area['area'] != '') {
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Area';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = $area['area'].' '.$area['area_unit'];
            $l++;
        }

        if (isset($details->propertyFeatures->area_covered) && $details->propertyFeatures->area_covered != '') {
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Total Land Area';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = $details->propertyFeatures->area_covered.' '.$areaUnit[$details->propertyFeatures->area_covered_unit];
            $l++;
        }

        if (isset($details->propertyFeatures->price_sq_ft) && $details->propertyFeatures->price_sq_ft != '') {

            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Price per sq.ft.';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = config('app.currency') .' '. self::getPrettyNumber($details->propertyFeatures->price_sq_ft, true);
            $l++;
        }

        $land_zone = self::chkMetas($details->propertyMetas, 'land_zone', '','');      
        if (isset($land_zone) && $land_zone != '') {
            $arr_land_zone = self::getStaticValues('land_zone');
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Land Zone';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = array_key_exists($land_zone, $arr_land_zone) ? $arr_land_zone[$land_zone] : $land_zone;
            $l++;
        }

        $land_type = self::chkMetas($details->propertyMetas, 'land_type', '','');      
        if (isset($land_type) && $land_type != '') {
            $arr_land_type = self::getStaticValues('land_type');
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Land Type';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = array_key_exists($land_type, $arr_land_type) ? $arr_land_type[$land_type] : $land_type;
            $l++;
        }

        $no_of_owners = self::chkMetas($details->propertyMetas, 'no_of_owners', '','');      
        if (isset($no_of_owners) && $no_of_owners != '') {
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'No Of Owners';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = $no_of_owners;
            $l++;
        }

        $land_location = self::chkMetas($details->propertyMetas, 'land_location', '','');      
        if (isset($land_location) && $land_location != '') {
            $arr_land_location = self::getStaticValues('land_location');
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Land Location';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = array_key_exists($land_location, $arr_land_location) ? $arr_land_location[$land_location] : $land_location;
            $l++;
        }
        
        $good_for = self::chkMetas($details->propertyMetas, 'good_for', '','');      
        if (isset($good_for) && $good_for != '') {
            $arr_good_for = self::getStaticValues('land_zone');
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Good For';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = array_key_exists($good_for, $arr_good_for) ? $arr_good_for[$good_for] : $good_for;
            $l++;
        }

        if (isset($details->propertyFeatures->road_approach) && $details->propertyFeatures->road_approach != '') {
            if($details->propertyFeatures->road_approach == 'road_touch')
            {
                $roadApproach = 'Road Touch';
            }elseif($details->propertyFeatures->road_approach == 'interior')
            {
                $roadApproach = 'Interior';
            }elseif ($details->propertyFeatures->road_approach == 'corner_plot') {
                $roadApproach = 'Corner Plot';
            }

            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Road Approach';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = $roadApproach;
            $l++;
        }

        if (isset($details->propertyFeatures->road1_width) && $details->propertyFeatures->road1_width != '') {
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Road 1 Width';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = $details->propertyFeatures->road1_width.' '.$areaUnit[$details->propertyFeatures->road1_width_unit];
            $l++;
        }

        if (isset($details->propertyFeatures->road2_width) && $details->propertyFeatures->road2_width != '') {
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Road 2 Width';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = $details->propertyFeatures->road2_width.' '.$areaUnit[$details->propertyFeatures->road2_width_unit];
            $l++;
        }

        if (isset($details->propertyFeatures->road_width) && $details->propertyFeatures->road_width != '') {
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'Road Width';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = $details->propertyFeatures->road_width.' '.$areaUnit[$details->propertyFeatures->road_width_unit];
            $l++;
        }

        if (isset($details->propertyFeatures->usp) && $details->propertyFeatures->usp != '') {
            $landPropertyConfig['land_config_detail'][$l]['config_title'] = 'USP';
            $landPropertyConfig['land_config_detail'][$l]['config_value'] = $details->propertyFeatures->usp;
            $l++;
        }
        

        return $landPropertyConfig;
    }

    protected static function configBox($key, $value, $class= 'col-xs-4 col-sm-4 col-md-4 col-lg-4') {
        return '<div class="'.$class.'">
                <div class="config-entity">
                    <div class="config-header">'.$key.'</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; '.$value.'
                        </div>
                    </div>
                </div>
            </div>';
    }

    public static function resalePropertyConfig($details = '') {

        
        if (count($details->properties) > 0) {
            $config = '';
            foreach ($details->properties as $key => $value) {
                 
                $type_category = $value->propertyCategory->id;
                switch ($type_category) {
                    case 1:
                        $config .= self::resalePropertyResidentialConfig($value, $details);
                        break;
                    
                    case 2:
                        $config .= self::resalePropertyCommercialConfig($value, $details);
                        break;

                    case 3:
                        $config .= self::industrialPropertyConfig($value, $details);    
                        break;
                    
                    case 4:
                        $config .= self::LandPropertyConfig($value, $details);
                        break;
                }
            }
            return $config;
        } 
        return false;
    }

    private static function resalePropertyResidentialConfig($details, $siteDetails) {
        $return = '';

        $areaUnit = self::getStaticValues('area_unit');
        $yn_text = self::getStaticValues('yn_text');

        $return .= '<div class="property-description">
                        <div class="property-description-inner">
                            <div class="row">';
        
        
        if($details->site_id!=''){
            $getRecord = Properties::where('site_id',$details->site_id)->first();
            
            if($getRecord->society_name!=''){

            $return .= self::configBox('Society Name', (isset($getRecord->society_name) ? $getRecord->society_name : ''));
            }

            

            // dd($details->propertyFeatures->price);
            if ($getRecord->price != '') {
                $return .= self::configBox('Price ',(string)self::getPrettyNumber($getRecord->price)) ;
            }

            if ($getRecord->booking_amount != '') {
                $return .= self::configBox('Token ',(string)self::getPrettyNumber($getRecord->booking_amount)) ;
            }  

            if ($getRecord->maintenance != '') {
                $return .= self::configBox('Maintenance ',(string)self::getPrettyNumber($getRecord->maintenance)) ;
            }     


        }
                             

         
        if ($details->propertyFeatures->sb_area != '') {
            $return .= self::configBox('SB Area', ($details->propertyFeatures->sb_area.' '.(isset($areaUnit[$details->propertyFeatures->sb_area_unit]) ? $details->propertyFeatures->sb_area_unit : '')));
        }

        
        if ($details->propertyFeatures->carpet_area != '') {
            $return .= self::configBox('Carpet Area', ($details->propertyFeatures->carpet_area.' '.(isset($areaUnit[$details->propertyFeatures->carpet_area_unit]) ? $details->propertyFeatures->carpet_area_unit : '')));
        }

        if ($details->propertyFeatures->built_area != '') {
            $return .= self::configBox('Builtup Area', ($details->propertyFeatures->built_area.' '.(isset($areaUnit[$details->propertyFeatures->built_area_unit]) ? $details->propertyFeatures->built_area_unit : '')));
        }
        
        if ($details->propertyFeatures->bedrooms != '') {
            $return .= self::configBox('Bedrooms', $details->propertyFeatures->bedrooms);
        }
        
        if ($details->propertyFeatures->bathrooms != '') {
            $return .= self::configBox('Bathrooms', $details->propertyFeatures->bathrooms);
        }

      /*  if ($details->propertyFeatures->balconies != '') {
            $return .= self::configBox('Balconies', $details->propertyFeatures->balconies);
        }*/
        
        if ($details->propertyFeatures->foyer_area != '') {
            $return .= self::configBox('Foyer area', $details->propertyFeatures->foyer_area);
        }

        if ($details->propertyFeatures->store_room != '') {
            $return .= self::configBox('Store room', $details->propertyFeatures->store_room);
        }

        if ($details->propertyFeatures->pooja_room != '') {
            $return .= self::configBox('Pooja room', $details->propertyFeatures->pooja_room);
        }

        if ($details->propertyFeatures->balconies != '') {
            $return .= self::configBox('Balconies', $details->propertyFeatures->balconies);
        }

        if ($details->propertyFeatures->total_floors) {
            $return .= self::configBox('Total Floors', $details->propertyFeatures->total_floors);
        }

        if ($details->propertyFeatures->open_sides) {
            $return .= self::configBox('Open Sides', $details->propertyFeatures->open_sides);
        }

        if ($details->propertyFeatures->property_on_floor) {
            $return .= self::configBox('Property on floor', $details->propertyFeatures->property_on_floor);
        }

        if ($details->propertyFeatures->furnished_status) {
            $return .= self::configBox('Furnished Status', self::getStaticValues('furnished_status')[$details->propertyFeatures->furnished_status]);
        }

        if ($details->propertyFeatures->is_corner_plot) {
            $return .= self::configBox('Corner Plot','yes');
        }

        if ($details->propertyFeatures->width_of_road_facing_plot != NULL) {
            $return .= self::configBox('Property on floor', $details->propertyFeatures->width_of_road_facing_plot);
        }


        if ($details->propertyFeatures->parking_area != '') {
            $return .= self::configBox('Parking Area', array_key_exists($details->propertyFeatures->parking_area, $yn_text) ? $yn_text[$details->propertyFeatures->parking_area] : $details->propertyFeatures->parking_area);
        }


        if ($details->propertyFeatures->no_of_parking != '') {
            $return .= self::configBox('No Of Parking ', array_key_exists($details->propertyFeatures->no_of_parking, $yn_text) ? $yn_text[$details->propertyFeatures->no_of_parking] : $details->propertyFeatures->no_of_parking);
        }

        if ($details->propertyFeatures->interior_details != '') {
            $return .= self::configBox('Interior Details', array_key_exists($details->propertyFeatures->interior_details, $yn_text) ? $yn_text[$details->propertyFeatures->interior_details] : $details->propertyFeatures->interior_details);
        }

        if ($details->propertyFeatures->payment_terms != '') {
            $return .= self::configBox('Payment Terms', array_key_exists($details->propertyFeatures->payment_terms, $yn_text) ? $yn_text[$details->propertyFeatures->payment_terms] : $details->propertyFeatures->payment_terms);
        }

        if ($details->propertyFeatures->price_sq_ft != '') {
         
            $return .= self::configBox('Price Per Sq. Ft.', $details->propertyFeatures->price_sq_ft);
           
        }
        if(isset($details->propertyFeatures->total_price) && !empty($details->propertyFeatures->total_price))
        {
            $return .= self::configBox('Total Price', (string)self::getPrettyNumber($details->propertyFeatures->total_price));

        }
         

        







        $furniture_details = self::chkMetas($details->propertyMetas, 'furniture_details', '','');      
        if ($furniture_details != '') {
            $return .= self::configBox('Furniture details', $furniture_details);
        }

        $interior_details = self::chkMetas($details->propertyMetas, 'interior_details', '','');      
        if ($interior_details != '') {
            $return .= self::configBox('Interior details', $interior_details);
        }

        $return .= self::configBox('Elevator / Lift', (self::chkAmenity($siteDetails->siteMetas, 'lift', '1','Yes')));
        $return .= self::configBox('Power backup', ($siteDetails->power_backup  == 1 ? 'Yes' : 'No'));
        $return .= self::configBox('Possession', self::getSitePossesionDetails($siteDetails));

        $return .= '</div></div></div>';

        return $return;
    }

    private static function resalePropertyCommercialConfig($details, $siteDetails) {
        
        $return = '';

        // get property area
        $area = self::getPropertyArea($details);
        $areaUnit = self::getStaticValues('area_unit');

        $return .= '<div class="property-description">
                        <div class="property-description-inner">
                            <div class="row">';
        
        if ($details->propertyFeatures->carpet_area != '') {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Carpet area</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$details->propertyFeatures->carpet_area.'
                            '.(isset($areaUnit[$details->propertyFeatures->carpet_area_unit]) ? $details->propertyFeatures->carpet_area_unit : '').'
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->covered_area != '') {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Covered area</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$details->propertyFeatures->covered_area.'
                            '.(isset($areaUnit[$details->propertyFeatures->covered_area_unit]) ? $details->propertyFeatures->covered_area_unit : '').'
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        if ($area['area'] != '') {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">'.$area['area_type'].'</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$area['area'].' '.$area['area_unit'].'
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->no_of_parking != '') {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">No Of Parking</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$details->propertyFeatures->no_of_parking.'
                            '.'
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->cabins != '') {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">No. Of Cabins</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$details->propertyFeatures->cabins.'
                            '.'
                        </div>
                    </div>
                </div>
            </div>';
        }
        if ($details->propertyFeatures->workstation != '') {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">No. Of Workstation</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$details->propertyFeatures->workstation.'
                            '.'
                        </div>
                    </div>
                </div>
            </div>';
        }
        if ($details->propertyFeatures->acs != '') {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">No. Of Acs</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$details->propertyFeatures->acs.'
                            '.'
                        </div>
                    </div>
                </div>
            </div>';
        }
        if ($details->propertyFeatures->interior_details != '') {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Interior Detail</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$details->propertyFeatures->interior_details.'
                            '.'
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->payment_terms != '') {
            $return .= self::configBox('Payment Terms',$details->propertyFeatures->payment_terms);
        }

        if ($details->propertyFeatures->price_sq_ft != '') {
         
            $return .= self::configBox('Price Per Sq. Ft.', $details->propertyFeatures->price_sq_ft);
           
        }

        if(isset($details->propertyFeatures->total_price) && !empty($details->propertyFeatures->total_price))
        {
            $return .= self::configBox('Total Price', (string)self::getPrettyNumber($details->propertyFeatures->total_price));
        }

        $getRecord = Properties::where('site_id',$details->site_id)->first();
        
        if($getRecord!=''){
            if ($getRecord->price != '') {
                $return .= self::configBox('Price ',(string)self::getPrettyNumber($getRecord->price)) ;
            }

            if ($getRecord->booking_amount != '') {
                $return .= self::configBox('Token ',(string)self::getPrettyNumber($getRecord->booking_amount)) ;
            }  

            if ($getRecord->maintenance != '') {
                $return .= self::configBox('Maintenance ',(string)self::getPrettyNumber($getRecord->maintenance)) ;
            } 
        }




        if ($details->propertyFeatures->furnished_status) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Furnished Status</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.self::getStaticValues('furnished_status')[$details->propertyFeatures->furnished_status].'
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->is_corner_shop) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Corner Shop/Office/Showroom</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            Yes
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->width_of_enterance) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Width Of Enterence</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$details->propertyFeatures->width_of_enterance.'
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->currently_leased_out) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Currently Leased Out</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            Yes
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->assured_returns) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Assured Returns</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            Yes
                        </div>
                    </div>
                </div>
            </div>';
        }


        if ($details->propertyFeatures->is_main_road_facing) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Main Road Facing</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            Yes
                        </div>
                    </div>
                </div>
            </div>';
        }


        if ($details->propertyFeatures->personal_washroom) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Personal Washroom</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            Yes
                        </div>
                    </div>
                </div>
            </div>';
        }


        if ($details->propertyFeatures->cafeteria) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Cafeteria</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            Yes
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->total_floors) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Total Floors</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$details->propertyFeatures->total_floors.'
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->property_on_floor) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Property on floor</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$details->propertyFeatures->property_on_floor.'
                        </div>
                    </div>
                </div>
            </div>';
        }

        $furniture_details = self::chkMetas($details->propertyMetas, 'furniture_details', '','');      
        if ($furniture_details != '') {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Furniture details</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$furniture_details.'
                        </div>
                    </div>
                </div>
            </div>';
        }

        $interior_details = self::chkMetas($details->propertyMetas, 'interior_details', '','');      
        if ($interior_details != '') {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Interior details</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.$interior_details.'
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->crane_facility) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Crane facility</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.ucfirst($details->propertyFeatures->crane_facility).'
                        </div>
                    </div>
                </div>
            </div>';
        }

        if ($details->propertyFeatures->electricity_connection) {
            $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="config-entity">
                    <div class="config-header">Electricity connection</div>
                    <div class="config-body">
                        <div class="config-value">
                            <i class="fas fa-check-circle"></i>&nbsp; 
                            '.ucfirst($details->propertyFeatures->electricity_connection).'
                        </div>
                    </div>
                </div>
            </div>';
        }

        $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
            <div class="config-entity">
                <div class="config-header">Elevator / Lift</div>
                <div class="config-body">
                    <div class="config-value">
                        <i class="fas fa-check-circle"></i>&nbsp; 
                        '.self::chkAmenity($siteDetails->siteMetas, 'lift', '1','Yes').'
                    </div>
                </div>
            </div>
        </div>';

        $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
            <div class="config-entity">
                <div class="config-header">Power backup</div>
                <div class="config-body">
                    <div class="config-value">
                        <i class="fas fa-check-circle"></i>&nbsp; 
                        '.($siteDetails->power_backup  == 1 ? 'Yes' : 'No').'
                    </div>
                </div>
            </div>
        </div>';

        $return .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
            <div class="config-entity">
                <div class="config-header">Possession</div>
                <div class="config-body">
                    <div class="config-value">
                        <i class="fas fa-check-circle"></i>&nbsp; 
                        '.self::getSitePossesionDetails($siteDetails).'
                    </div>
                </div>
            </div>
        </div>';

        $return .= '</div>
            </div>
        </div>';

        return $return;
    }

    public static function getPropertySingleConfigFromMeta($title, $arrMeta, $metaKey, $type = 'div',$isApi=false) {
        $metaEntity = self::chkMetas($arrMeta, $metaKey, '', '');
        if ($metaEntity != '') {

            if($isApi)
            {
                return $metaEntity; 
            }
            else
            {
                return self::formatSingleConfig($title, $metaEntity, $type, [$arrMeta,$metaKey]);
            }
        }
        return false;
    }

    public static function formatSingleConfig($title, $value, $type = 'div',$metaDetails = '') {
        $metaId = 0;
        if(!empty($metaDetails)){
            $metaId = self::getMetaId($metaDetails[0],$metaDetails[1]);            
        }
        if ($type == 'table') {
            return '<tr class="meta_entity" data-id="'.$metaId.'" data-value="'.$value.'">
                <td>'.$title.'</td>
                <td>'.$value.'</td>
            </tr>';
        } else {
            return '<div class="config-entity meta_entity" data-id="'.$metaId.'" data-value="'.$value.'">
                <div class="config-header">'.$title.'</div>
                <div class="config-body">
                    <div class="config-value">
                        <i class="fas fa-check-circle"></i>&nbsp; '.$value.'
                    </div>
                </div>
            </div>';
        }
    }

    private static function getPropertyMultipleConfigFromMeta ($title, $arrFeatures, $arrMeta, $metaKey, $loopKey, $extrakey = '') {

        if ($arrFeatures->$metaKey != '') {

            // icon before key value
            $icon = '<i class="fas fa-check-circle"></i>&nbsp; ';

            $config = '<div class="config-entity">
            <div class="config-header">'.$title.' ('.$arrFeatures->$metaKey.')</div>
                <div class="config-body">
                    <div class="row">';

                if ($metaKey == 'bedrooms') {
                    $master_bedroom = self::chkMetas($arrMeta, 'master_bedroom', '', '');
                }

                for ($i = 1; $i <= $arrFeatures->$metaKey; $i++) {
                    $loopValue = self::chkMetas($arrMeta, $loopKey.$i, '', '');
                    if ($loopValue != '') {
                        $config .= '
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                                <div class="config-value">
                                    '.$icon.' '.$loopValue.'
                                    '.($metaKey == 'bedrooms' && $master_bedroom == $i ? '(Master bed)' : '').'
                                </div>
                            </div>';
                    }
                }

                /* extra key */
                if ($extrakey != '') {
                    $extraValue = self::chkMetas($arrMeta, $extrakey, '', '');
                    if ($extraValue != '') {
                        $config .= '
                        <div class="col-md-4">
                            <div class="config-value">'.$icon.' '.$extraValue.'</div>
                        </div>';
                    }
                }

            $config .= '</div>
                    </div>
                </div>';

            return $config;
        }
        return false;
    }

    public static function getPropertyMultipleConfigFromMetaData ($title, $arrFeatures, $arrMeta, $metaKey, $loopKey, $extrakey = '',$isApi=false) {

        if ($arrFeatures->$metaKey != '') {

            if($isApi){

                // icon before key value
                $icon = '';

                $config = ''; //('.$arrFeatures->$metaKey.')

                    if ($metaKey == 'bedrooms') {
                        $master_bedroom = self::chkMetas($arrMeta, 'master_bedroom', '', '');
                    }

                    for ($i = 1; $i <= $arrFeatures->$metaKey; $i++) {
                        if(!empty($config)){
                            $config.=', ';
                        }
                        $loopValue = self::chkMetas($arrMeta, $loopKey.$i, '', '');
                        if ($loopValue != '') {
                            $config .= $loopValue.' '.($metaKey == 'bedrooms' && $master_bedroom == $i ? '(Master bed)' : '');
                        }
                    }

                    /* extra key */
                    if ($extrakey != '') {
                        $extraValue = self::chkMetas($arrMeta, $extrakey, '', '');
                        if ($extraValue != '') {                            
                            $config .= $extraValue;
                        }
                    }

                $config .= '';

            }else{
                // icon before key value
                $icon = '<i class="fas fa-check-circle"></i>&nbsp; ';

                $config = '<div class="config-entity">
                <div class="config-header">'.$title.' ('.$arrFeatures->$metaKey.')</div>
                    <div class="config-body">
                        <div class="row">';

                    if ($metaKey == 'bedrooms') {
                        $master_bedroom = self::chkMetas($arrMeta, 'master_bedroom', '', '');
                    }

                    for ($i = 1; $i <= $arrFeatures->$metaKey; $i++) {
                        $loopValue = self::chkMetas($arrMeta, $loopKey.$i, '', '');
                        if ($loopValue != '') {
                            $config .= '
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                                    <div class="config-value">
                                        '.$icon.' '.$loopValue.'
                                        '.($metaKey == 'bedrooms' && $master_bedroom == $i ? '(Master bed)' : '').'
                                    </div>
                                </div>';
                        }
                    }

                    /* extra key */
                    if ($extrakey != '') {
                        $extraValue = self::chkMetas($arrMeta, $extrakey, '', '');
                        if ($extraValue != '') {
                            $config .= '
                            <div class="col-md-4">
                                <div class="config-value">'.$icon.' '.$extraValue.'</div>
                            </div>';
                        }
                    }

                $config .= '</div>
                        </div>
                    </div>';
            }

            

            return $config;
        }
        return false;
    }


    // Get property multiple configuration from meta for api
    private static function getPropertyMultipleConfigFromMetaApi ($title, $arrFeatures, $arrMeta, $metaKey, $loopKey, $extrakey = '') {

        if ($arrFeatures->$metaKey != '') {

            $multipleConfig = [];
            $titleMultipleConfig = $title.' ('.$arrFeatures->$metaKey.')';
            
            if ($metaKey == 'bedrooms')
            {
                $master_bedroom = self::chkMetas($arrMeta, 'master_bedroom', '', '');
            }

            $j = 0;
            for ($i = 1; $i <= $arrFeatures->$metaKey; $i++) 
            {
                $loopValue = self::chkMetas($arrMeta, $loopKey.$i, '', '');
                if ($loopValue != '') 
                {
                    $multipleConfig[$titleMultipleConfig][$j] = $loopValue.' '.($metaKey == 'bedrooms' && $master_bedroom == $i ? '(Master bed)' : '');
                    $j++;
                }
            }

            /* extra key */
            if ($extrakey != '')
            {
                $extraValue = self::chkMetas($arrMeta, $extrakey, '', '');
                if ($extraValue != '')
                {
                    $multipleConfig[$titleMultipleConfig][$j] = $extraValue;
                }
            }
            
            // Set data format
            $multipleConfigData = [];
            foreach($multipleConfig as $k => $v)
            {
                $multipleConfigData['config_title'] = isset($k) && !empty($k) ? $k : null;

                if(isset($v) && !empty($v) && count($v)>0)
                {   $multipleConfigArea = ''; 
                    $c = 1;
                    $metaCount = count($v);
                    foreach($v as $key => $val)
                    {
                        $multipleConfigArea .= $metaCount==$c ? $val.'' : $val.',';
                        $c++;
                    }
                }

                $multipleConfigData['config_value'] = isset($multipleConfigArea) && !empty($multipleConfigArea) ? $multipleConfigArea : null;
            }

            return $multipleConfigData;
        }
        return false;
    }

    
    public static function cmsPropertyDetails($details) {
        $return = '';
        
        if (isset($details->propertyCategory)) {

            switch ($details->propertyCategory->id) {
                 case '1':
                        $return = self::cmsResidentialPropertyDetails($details);
                     break;

                case '2':
                        $return = self::cmsCommercialPropertyDetails($details);
                    break;

             }
        }
        return $return;
    }

    private static function cmsResidentialPropertyDetails($details = '') {
        
        $areaUnit = self::getStaticValues('area_unit');
        $areaDetails = self::getPropertyArea($details);
        
        $return = isset($details->propertyFeatures->bedrooms) ? $details->propertyFeatures->bedrooms .' BHK - ' : '';
        $return .= $areaDetails['area'].' '.$areaDetails['area_unit'].' '.$areaDetails['type'];

        return $return;

    }

    private static function cmsCommercialPropertyDetails($details = '') {

        $return = '';

        if (isset($details->propertyFeatures->total_floors) && 
            $details->propertyFeatures->total_floors > 0) {

            if (isset($details->propertyMetas)) {
                $return .= '<table class="table table-responsive box-shadow">';
                $return .= '<thead>';
                $return .= '<tr>';
                $return .= '<th>Floor</th>';
                $return .= '<th>Area</th>';
                $return .= '<th>Price SQFT</th>';
                $return .= '</tr>';
                $return .= '</thead>';

                $return .= '<tbody>';
                for ($i = 0; $i <= $details->propertyFeatures->total_floors; $i++) {
                    $price = self::chkMetas($details->propertyMetas, 'floor_'.$i.'_price_sq_ft', '', 'n/a');
                    $return .= '<tr>';
                    $return .= '<td>'.self::NumSuffix($i).' floor</td>';
                    $return .= '<td>'.
                        self::chkMetas($details->propertyMetas, 'floor_'.$i.'_area', '', 'n/a')
                        .'</td>';
                    $return .= '<td>'.($price > 0 ? config('app.currency') .' '. Helpers::getPrettyNumber($price, true) : '-').'</td>';
                    $return .= '</tr>';
                }
                $return .= '</tbody>';

                $return .= '</table>';
            }
        }

        return $return;
    }

    public static function getPropertyStatus($details) {
        if ( isset($details->status)) {
            if ($details->status == '0') {
                return '<span class="text-success">Inactive</label>';
            } else if ($details->status == '1') {
                return '<span class="text-success">Active</label>';
            } else if ($details->status == '2') {
                return '<span class="text-danger">Soldout</label>';
            } else if ($details->status == '4') {
                return '<span class="text-danger">Pending Verification</label>';
            }
        }
        return false;
    }

    public static function getAdminMenu() {
        return array(

            'Catalog' => array(
                'icon' => 'icon icon-hierarchy',
                'submenu' => array( 
                    'New Properties' => array(
                        'route' => route('sites.index'),
                        'uri' => 'sites',
                        'extra_uri' => array('properties', 'verifysites', 'disabledsites'),
                        'icon' => 'icon icon-building',
                        'roles' => array(
                            'ADD_SITES' => 'Add Sites',
                            'EDIT_SITES' => 'Edit Sites',
                            'ADD_NEW_PROPERTIES' => 'Add New Properties',
                            'EDIT_NEW_PROPERTIES' => 'Edit New Properties',
                            'VERIFY_SITES' => 'Verify sites & properties',
                            'DISABLED_SITES' => 'View disabled sites & properties',
                        )
                    ),
                    'Resale Properties' => array(
                        'route' => route('resalesites.index'),
                        'uri' => 'resalesites',
                        'extra_uri' => array('verifyresalesites', 'disabledresalesites'),
                        'icon' => 'icon icon-home',
                        'roles' => array(
                            'EDIT_RESALE_PROPERTIES' => 'Edit Resale Properties',
                            'DELETE_RESALE_PROPERTIES' => 'Delete Resale Properties'
                        )
                    ),
                    'Site Offers' => array(
                        'route' => route('siteoffers.index'),
                        'uri' => 'siteoffers',
                        'icon' => 'icon icon-files',
                        'roles' => array(
                            'ADD_SITE_OFFERS' => 'Add Site Offers',
                            'EDIT_SITE_OFFERS' => 'Edit Site Offers',
                            'DELETE_SITE_OFFERS' => 'Delete Site Offers'
                        )
                    ),
                    'Featured Properties' => array(
                        'route' => route('featuredproperty.index'),
                        'uri' => 'featuredproperty',
                        'icon' => 'fa fa-adjust',
                        'roles' => array(
                            'ADD_SITE_FEATURED' => 'Make site featured',
                            'DELETE_SITE_FEATURED' => 'Remove featured site'
                        )
                    ),
                    'Featured Companies' => array(
                        'route' => route('featuredcompany.index'),
                        'uri' => 'featuredcompany',
                        'icon' => 'fa fa-adjust',
                        'roles' => array(
                            'ADD_COMPANY_FEATURED' => 'Make company featured',
                            'DELETE_COMPANY_FEATURED' => 'Remove featured company'
                        )
                    ),
                    'Resale Featured Properties' => array(
                        'route' => route('featuredresaleproperty.index'),
                        'uri' => 'featuredresaleproperty',
                        'icon' => 'fa fa-adjust',
                        'roles' => array(
                            'ADD_SITE_FEATURED' => 'Make resale site featured',
                            'DELETE_SITE_FEATURED' => 'Remove featured resale site'
                        )
                    ),
                    'Campanies' => array(
                        'route' => route('companies.index'),
                        'uri' => 'companies',
                        'icon' => 'fas fa-building',
                        'roles' => array(
                            'ADD_COMPANIES' => 'Add Companies',
                            'EDIT_COMPANIES' => 'Edit Companies',
                            'VIEW_COMPANIES' => 'View Companies',
                        )
                    ),
                    'Front Users' => array(
                        'route' => route('frontusers.index'),
                        'uri' => 'frontusers',
                        'icon' => 'fa fa-users',
                        'roles' => array(
                            'EDIT_FRONT_USERS' => 'Edit Users',
                            'VIEW_FRONT_USERS' => 'View Users',
                            'CAN_ADD_FRONT_USERS' => 'Add Users',
                            'CAN_SEE_FRONT_USER_OTP' => 'CAN see user OTP',
                            'CAN_SEE_FRONT_USER_PHONE' => 'Can see user phone no',
                        )
                    ),
                    'Guest Users' => array(
                        'route' => route('viewGuestUsers'),
                        'uri' => 'guestUsers',
                        'icon' => 'fa fa-user',
                        'roles' => array(
                            'VIEW_GUEST_USERS' => 'View Guest Users'
                        )
                    ),
                    'News - Offers' => array(
                        'route' => route('newsoffers.index'),
                        'uri' => 'newsoffers',
                        'icon' => 'far fa-newspaper',
                        'roles' => array(
                            'VIEW_NEWS_OFFERS_USERS' => 'News/Offers Notification'
                        )
                    ),
                    'Completed Projects' => array(
                        'route' => route('completedProject.index'),
                        'uri' => 'completedProject',
                        'icon' => 'icon icon-files',
                        'roles' => array(
                            'VIEW_COMPLETED_USERS' => 'Completed Projects'
                        ) 
                    ),
                    'Testimonials' => array(
                        'route' => route('testimonials.index'),
                        'uri' => 'testimonials',
                        'icon' => 'icon icon-files',
                        'roles' => array(
                            'ADD_TESTIMONIALS' => 'Add Testimonial',
                            'EDIT_TESTIMONIALS' => 'Edit Testimonial',
                            'DELETE_TESTIMONIALS' => 'Delete Testimonial'
                        )
                    ),
                    'Manage Agent Shares' => array(
                        'route' => route('manageShares.index'),
                        'uri' => 'manageShares',
                        'icon' => 'icon icon-users',
                        'roles' => array(
                            'MANAGE_ADD_SHARES' => 'Manage Add Shares',
                            'MANAGE_EDIT_SHARES' => 'Manage Edit Shares',
                            'EDIT_AGENT_SHARES' => 'Edit Agents Shares'

                        )
                    )
                )
            ),

            'Marketing' => array(
                'icon' => 'icon icon-target',
                'submenu' => array( 
                    'Portal Registrations' => array(
                        'route' => route('portalregistration.index'),
                        'uri' => 'portalregistration',
                        'icon' => 'icon icon-files',
                        'roles' => array(
                            'ADD_PORTAL_REGISTRATION' => 'View registration details',
                            'EDIT_PORTAL_REGISTRATION' => 'Edit registration details',
                            'DELETE_PORTAL_LEAD_RECORDING' => 'Delete recording',
                            'CAN_SEND_LEADS_TO_BUILDER' => 'Can send leads to builder',
                            'CAN_SEE_PORTAL_PHONE' => 'Can see user phone no',
                            'CAN_SEE_PORTAL_EMAIL' => 'Can see user email',
                            'CAN_SEE_PORTAL_CHART' => 'Can see registration chart',
                        )
                    ),
                    'Manage Leads' => array(
                        'route' => route('leads.index'),
                        'uri' => 'leads',
                        'icon' => 'fa fa-align-justify',
                        'roles' => array(
                            'SEND_LEADS' => 'Send Leads',
                            'EDIT_LEADS' => 'Edit Leads',
                            'VIEW_LEADS' => 'View Leads',
                            'CAN_SEE_LEAD_PHONE' => 'Can see lead phone no',
                            'CAN_SEE_LEAD_EMAIL' => 'Can see lead email',
                            'CAN_SEE_LEAD_MATCHES_PHONE' => 'Can see matches phone no',
                            'CAN_SEE_LEAD_MATCHES_EMAIL' => 'Can see matches email',
                        )
                    ),
                    'Site Leads' => array(
                        'route' => route('sites.leads'),
                        'uri' => 'site-leads-details',
                        'icon' => 'fas fa-asterisk',
                        'roles' => array(
                            'VIEW_LEADS' => 'View Leads',
                        )
                    ),
                    'Site Leads' => array(
                        'route' => route('sites.leads.report'),
                        'uri' => 'site-leads-report',
                        'icon' => 'fas fa-asterisk',
                        'roles' => array(
                            'VIEW_LEADS' => 'View Leads',
                        )
                    ),
                    'Builder Lead Filters' => array(
                        'route' => route('builderleadfilters.index'),
                        'uri' => 'builderleadfilters',
                        'icon' => 'fas fa-filter',
                        'roles' => array(
                            'ADD_LEAD_FILTERS' => 'Add lead filter',
                            'EDIT_LEAD_FILTERS' => 'Edit lead filter',
                            'DELETE_LEAD_FILTERS' => 'Delete lead filter',
                            'CAN_SEE_LEAD_FILTER_PHONE' => 'Can see phone',
                            'CAN_SEE_LEAD_FILTER_EMAIL' => 'Can see email',
                            'CAN_SEE_LEAD_FILTER_MATCHES' => 'Can see matches',
                        )
                    ),
                )
            ),

            'Enquiries' => array(
                'icon' => 'icon icon-files',
                'submenu' => array( 
                    'Agent Enquiries' => array(
                        'route' => route('admin_enquiry.index',['type'=>3]),
                        'uri' => 'admin_enquiry',
                        'icon' => 'fas fa-id-badge'
                    ),
                    'Builder Enquiries' => array(
                        'route' => route('admin_enquiry.index',['type'=>2]),
                        'uri' => 'admin_enquiry',
                        'icon' => 'fas fa-street-view'
                    ),
                    'User Enquiries' => array(
                        'route' => route('admin_enquiry.index',['type'=>1]),
                        'uri' => 'admin_enquiry',
                        'icon' => 'fas fa-users'
                    ),
                )
            ),

            'Misc Ops' => array(
                'icon' => 'icon icon-paperplane',
                'submenu' => array( 
                    'Manage Pages' => array(
                        'route' => \URL::to('/admin/pages'),
                        'uri' => 'pages',
                        'icon' => 'icon icon-files',
                        'roles' => array(
                            'ADD_PAGES' => 'Add Pages',
                            'EDIT_PAGES' => 'Edit Pages',
                            'VIEW_PAGES' => 'View Pages',
                        )
                    ),
                    'Work' => array(
                        'route' => route('works.index'),
                        'uri' => 'works',
                        'icon' => 'icon icon-hammer',
                        'roles' => array(
                            'ADD_WORK' => 'Add Work',
                            'EDIT_WORK' => 'Edit Work',
                            'VIEW_WORK' => 'View Work',
                        )
                    ),
                    'Workers' => array(
                        'route' => route('workers.index'),
                        'uri' => 'workers',
                        'icon' => 'icon icon-paintcan',
                        'roles' => array(
                            'ADD_WORKER' => 'Add Worker',
                            'EDIT_WORKER' => 'Edit Worker',
                            'VIEW_WORKER' => 'View Worker',
                        )
                    ),
                    'Banks' => array(
                        'route' => route('banks.index'),
                        'uri' => 'banks',
                        'icon' => 'icon icon-truck',
                        'roles' => array(
                            'ADD_BANK' => 'Add Bank',
                            'EDIT_BANK' => 'Edit Bank',
                            'VIEW_BANK' => 'View Bank',
                        )
                    ),
                    'SMS History' => array(
                        'route' => route('smshistory.index'),
                        'uri' => 'smshistory',
                        'icon' => 'icon icon-package',
                        'roles' => array(
                            'VIEW_SMS_HISTORY' => 'View SMS History',
                        )
                    ),
                )
            ),

            'Accounts' => array(
                'icon' => 'icon icon-files',
                'submenu' => array( 
                    'Membership Package' => array(
                        'route' => route('packages.index'),
                        'uri' => 'packages',
                        'icon' => 'icon icon-package',
                        'roles' => array(
                            'ADD_PACKAGE_LIST' => 'Add Package',
                            'EDIT_PACKAGE_LIST' => 'Edit Package',
                            'VIEW_PACKAGE_LIST' => 'View Package',
                        )
                    ),
                    'Builder Payments' => array(
                        'route' => route('invoices.index'),
                        'uri' => 'invoices',
                        'extra_uri' => array('pendinginvoices', 'expiredinvoices', 'rejectedinvoices', 'upcomingrenewalinvoices'),
                        'icon' => 'fas fa-street-view',
                        'roles' => array(
                            'CAN_GENERATE_BUILDER_INVOICE' => 'Can generate builder invoice',
                            'CAN_APPROVE_BUILDER_INVOICE' => 'Can approve builder invoice',
                            'CAN_MAKE_BUILDER_PAYMENT' => 'Can make builder payment',
                        )
                    ),
                    'Agent Payments' => array(
                        'route' => route('agentinvoices.index'),
                        'uri' => 'agentinvoices',
                        'extra_uri' => array('agentpendinginvoices', 'agentexpiredinvoices', 'agentrejectedinvoices', 'upcomingagentrenewalinvoices'),
                        'icon' => 'fas fa-id-badge',
                        'roles' => array(
                            'CAN_GENERATE_AGENT_INVOICE' => 'Can generate agent invoice',
                            'CAN_APPROVE_AGENT_INVOICE' => 'Can approve agent invoice',
                            'CAN_MAKE_AGENT_PAYMENT' => 'Can make agent payment',
                        )
                    ),
                    'User Payments' => array(
                        'route' => route('userinvoices.index'),
                        'uri' => 'userinvoices',
                        'extra_uri' => array('userpendinginvoices', 'userexpiredinvoices', 'userrejectedinvoices', 'upcominguserrenewalinvoices'),
                        'icon' => 'fa fa-user',
                        'roles' => array(
                            'CAN_GENERATE_USER_INVOICE' => 'Can generate user invoice',
                            'CAN_APPROVE_USER_INVOICE' => 'Can approve user invoice',
                            'CAN_MAKE_USER_PAYMENT' => 'Can make user payment',
                        )
                    ),
                )
            ),

            'Locations' => array(
                'icon' => 'icon icon-globe',
                'submenu' => array( 
                    'Areas' => array(
                        'route' => route('areas.index'),
                        'uri' => 'areas',
                        'icon' => 'fas fa-map-marker-alt',
                        'roles' => array(
                            'ADD_AREAS' => 'Add Areas',
                            'EDIT_AREAS' => 'Edit Areas',
                            'VIEW_AREAS' => 'View Areas',
                        )
                    ),
                    'City' => array(
                        'route' => route('cities.index'),
                        'uri' => 'cities',
                        'icon' => 'fas fa-building',
                        'roles' => array(
                            'ADD_CITY' => 'Add City',
                            'EDIT_CITY' => 'Edit City',
                            'VIEW_CITY' => 'View City',
                        )
                    ),
                    'State' => array(
                        'route' => route('states.index'),
                        'uri' => 'states',
                        'icon' => 'far fa-sun',
                        'roles' => array(
                            'ADD_STATE' => 'Add State',
                            'EDIT_STATE' => 'Edit State',
                            'SHOW_STATE' => 'View State',
                        )
                    ),
                    'Country' => array(
                        'route' => route('countries.index'),
                        'uri' => 'countries',
                        'icon' => 'icon icon-globe',
                        'roles' => array(
                            'ADD_COUNTRY' => 'Add Country',
                            'EDIT_COUNTRY' => 'Edit Country',
                            'VIEW_COUNTRY' => 'View Country',
                        )
                    ),
                )
            ),

            'Settings' => array(
                'icon' => 'icon icon-settings',
                'submenu' => array( 
                    'Admin Roles' => array(
                        'route' => route('roles.index'),
                        'uri' => 'roles',
                        'icon' => 'icon icon-check',
                        'roles' => array(
                            'ADD_ADMIN_ROLES' => 'Add Admin Role',
                            'EDIT_ADMIN_ROLES' => 'Edit Admin Role',
                            'VIEW_ADMIN_ROLES' => 'View Admin Role',
                        )
                    ),
                    'Admin Users' => array(
                        'route' => route('users.index'),
                        'uri' => 'users',
                        'icon' => 'icon icon-users',
                        'roles' => array(
                            'ADD_ADMIN_USERS' => 'Add admin user',
                            'EDIT_ADMIN_USERS' => 'Edit admin user',
                            'DELETE_ADMIN_USERS' => 'Delete admin user',
                            'VIEW_ADMIN_USERS' => 'View admin user',
                        )
                    ),
                    'My Profile' => array(
                        'route' => route('myprofile.index'),
                        'uri' => 'myprofile',
                        'icon' => 'icon icon-profile',
                        'roles' => array(
                            'CAN_MANAGE_OWN_PROFILE' => 'User can manage his/her profile',
                        )
                    ),
                    'Change Password' => array(
                        'route' => \URL::to('/admin/changePassword'),
                        'uri' => 'changePassword',
                        'icon' => 'icon icon-key',
                        'roles' => array(
                            'CAN_CHANGE_PASSWORD' => 'User can change his/her password',
                        )
                    ),
                    'Admin Login History' => array(
                        'route' => route('usershistory.index'),
                        'uri' => 'usershistory',
                        'icon' => 'icon icon-users',
                        'roles' => array(
                            'VIEW_ADMIN_USERS_LOGIN_HISTORY' => 'View admin user login history',
                        )
                    ),
                )
            ),
        );
    }

    public static function getAdminLoginTimings($userTimings) {
        
        switch (count($userTimings)) {
            case '7':
                return 0;
                break;

            case '6':
                return 1;
                break;

            case '1':
                return 2;
                break;
        }
        
        return '';
    }

    public static function getCities(){
        return \App\Models\Cities::select(['id','name'])->get();
    }

    public static function openPermissionRoutes() {
        return [
            'dashboard', 
            'logout', 
            'getStates', 'getCities', 'getAreas', 'getPropertyType',
            'getSiteByCompany', 'revealPhoneNo', 'siteQuickImageUpload', 
            'changeSiteStatus', 'siteChangeImageType', 'getSiteSmsHistory', 
            'tempImageUpload', 'tempChangeImageType', 'tempRemoveImg',
            'deletePropertyImage', 'getSitePackageRate', 'getSitePackageDiscount', 
            'updatesitecover', 'deleteBuilderImage', 'tempUpdatesitecover',
            'getFrontUserSuggestion', 'getFrontUserDetails', 'checkCompanyHasUser'
        ];
    }

    public static function chkSpecs($metas, $key, $return = false) {
        if (isset($metas)) {
             
            foreach ($metas as $k => $v) {
                if ($v->meta_key == $key) {
                    return $v->meta_value;
                }
            }    
        }
        return $return;
    }

    public static function chkMetas($metas, $key, $value = '', $empty = '') {
        if (isset($metas)) {
            foreach ($metas as $k => $v) {
                if ($v->meta_key == $key) {
                    if ($v->meta_key == 'master_bedroom' && $value != '') {
                        if ($v->meta_value == $value) {
                            return 'checked';
                        }
                    } else if ($v->meta_value != 'no') {
                        return $v->meta_value != '' ? $v->meta_value : $empty;
                    } else {
                        return $empty;
                    }
                }
            }    
        }
        return $empty != '' ? $empty : false;
    }

    public static function getMetaId($metas,$key) {
        if (isset($metas)) {
            foreach ($metas as $k => $v) {
                if ($v->meta_key == $key) {
                    return $v->id;
                    // if ($v->meta_key == 'master_bedroom' && $value != '') {
                    //     if ($v->meta_value == $value) {
                    //         return 'checked';
                    //     }
                    // } else if ($v->meta_value != 'no') {
                    //     return $v->meta_value != '' ? $v->meta_value : $empty;
                    // } else {
                    //     return $empty;
                    // }
                }
            }    
        }
        return 0;
    }

    public static function chkAmenity($metas, $key, $val, $return  = 'checked') {
        if (isset($metas)) {
            foreach ($metas as $k => $v) {
                if ($v->meta_key == $key  && $v->meta_type == $val) {
                    return $return;
                }
            }
        }
        return false;
    }

    public static function hasMenuPerm($currentMenu, $menuType = 'main') {
        
        if (self::isSuperAdmin()) {
            return true;
        }

        $roleDesc = self::formatRoleDesc(self::getAdminRoleDesc());

        if (isset($roleDesc) && is_array($roleDesc)) {
            if ($menuType == 'main') {
                foreach ($currentMenu as $key => $value) {
                    if (isset($value['roles']) && is_array($value['roles'])) {
                        foreach ($value['roles'] as $k => $v) {
                            if (in_array($k, $roleDesc)) {
                                return true;
                            }
                        }
                    }
                }    
            } else {
                if (isset($currentMenu['roles'])) {
                    foreach ($currentMenu['roles'] as $key => $value) {
                        if (isset($key)) {
                            if (in_array($key, $roleDesc)) {
                                return true;
                            }
                        }
                    }    
                }
            }
        }
        return false;
    }

    public static function showRoleDetails($roleDetails) {

        $roleHtml = '';
        $staticRoles = self::getAdminMenu();

        if (isset($staticRoles) && isset($roleDetails)) {
            
            $roleHtml .= '<ul class="list-group">';
            foreach ($staticRoles as $rk => $staticRole) {
                foreach ($staticRole['submenu'] as $k => $v) {
                    
                    $available = false;
                    foreach ($v['roles'] as $kr => $vr) {
                        if (in_array($kr, $roleDetails)) {
                            $available = true;
                        }
                    }

                    if ($available == true) {
                        $roleHtml .= '<li class="list-group-item">
                        <h4>'.$k.'</h4>
                        <div class="row">';

                        foreach ($v['roles'] as $kr => $vr) {
                            if (in_array($kr, $roleDetails)) {
                               $roleHtml .= '<div class="col-md-4">
                                        <label> 
                                            <i class="icon icon-check text-success"></i>&nbsp;&nbsp;'.$vr.'
                                        </label>&nbsp;&nbsp;
                                    </div>';
                            }
                        }
                        $roleHtml .= '</div></li>';
                    }           
                }
            }
            $roleHtml .= '</ul>';
        } else {
            $roleHtml .= '<div class="text-danger">No roles has been assigned</div>';
        }
        return $roleHtml;
    }

    /* select sidebar current page element */
    public static function selectPage($arr, string $return = 'active') {
        
        // declare blank array
        $arrPages = [];

        // prepare pages array
        if (isset($arr['submenu']) && is_array($arr['submenu'])) {
            foreach ($arr['submenu'] as $key => $value) {
                $arrPages[] = $value['uri'];
                if (isset($value['extra_uri'])) {
                    foreach ($value['extra_uri'] as $k => $v) {
                        $arrPages[] = $v;        
                    }
                }
            }
        } else {
            foreach ($arr as $key => $value) {
                if ($key == 'uri') {
                    $arrPages[] = $value;
                } else if ($key == 'extra_uri') {
                    foreach ($value as $k => $v) {
                        $arrPages[] = $v;        
                    }
                }
            }
        }

        // get current page 
        $arrRequest = explode('/', \Request::getRequestUri());

        // if parameter set then check
        if ($arrRequest[2]) {

            // current uri
            $currentUri = explode('?', $arrRequest[2])[0];

            // check if current page is exist in array
            if (in_array($currentUri, $arrPages)) {

                // return result
                return $return;
            
            }
        }
        return false;
    }

    public static function trimSpecialChars($str, $keepspace = false) {
        if ($keepspace == true) {
            return preg_replace("/[^A-Za-z0-9 ]/", '', $str);
        } else {
            return preg_replace("/[^A-Za-z0-9]/", '', $str);
        }
    }

    public static function NumSuffix($number) {

        if ($number == 0) {
            return 'Ground';
        }

        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

    public static function typePhotoAvailable($arrImages, $type) {
        if (count($arrImages) > 0) {
            foreach ($arrImages as $i => $image) {
                if ($image->image_type == $type) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function displaySiteImages($image, $i, $id) {
        
        $checked = '';
        if ($image->is_covered == 1) {
            $checked = 'checked';
        }

        $imageType = array(
            'project_pictures' => 'Project Pictures',
            'house_pictures' => 'House Pictures',
            'amenities_pictures' => 'Amenities Pictures',
            'sequence_diagrams' => 'Main Plan Diagram',
        );

        $return = '<div class="col-md-4" id="imageContainer'.$image->id.'">
                    <div class="card propertyImageCard">
                <img class="card-img-top" src="'.url('/public/'.$image->image_name) .'" />
                <div class="card-body">
                    <div class="dropdown siteImageDropdown" data-siteid="'.$id.'" data-imageid="'.$image->id.'">
                        <label>
                            <input type="radio" name="coverImage" class="updateCoverImage" data-siteid="'.$id.'" value="'.$image->id.'" '.$checked.' /> &nbsp;Make Cover
                        </label><br />
                        <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="siteImages" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.($image->image_type != '' ? $imageType[$image->image_type] : 'Select type').'
                        </button>
                        <div class="dropdown-menu" aria-labelledby="siteImages">';

                        foreach ($imageType as $key => $value) {
                            $return .= '<button class="dropdown-item" type="button" data-image-type="'.$key.'">'.$value.'</button>';
                        }

            $return .= '</div>
                        <button type="button" title="Delete this image" class="btn btn-sm btn-danger deleteSiteImage" data-imageid="'.$image->id.'"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            </div>
        </div>';
        return $return;
    }

    public static function displayPropertiesImages($image, $i) {
        
        $return = '<div class="col-3" id="imageContainer'.$image->id.'">
                    <div class="card propertyImageCard">
                        <img src="'.self::getPropertyThumbUrl($image->image_name, 400).'" class="card-img-top" />
                        <div class="card-body">
                            <button type="button"  title="Delete this image" class="btn btn-danger btn-sm deletePropertyImage" data-imageid="'.$image->id.'"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                </div>';
        return $return;
    }

    public static function getLayoutImage($propertyImages) {
        if (isset($propertyImages)) {
            $arrImg = array();
            if (isset($propertyImages)) {
                foreach ($propertyImages as $image) {
                    if ($image->image_type == 'layout_diagrams') {
                        $arrImg[] = $image;
                    }
                }
            }

            $image = [];
            if (count($arrImg) > 0) {
                foreach ($arrImg as $value) {
                    if (isset($value->image_name) && $value->image_name != '') {
                        $image[] = self::getPropertyThumbUrl($value->image_name);
                    } else {
                        $image[] = \Config::get('constants.img_placeholder');
                    }
                }
            }
            return $image;
        }
        return array(\Config::get('constants.img_placeholder'));
    }

    public static function getSiteOfferCounts($site) {
        if ($site->siteOffers) {
            return count($site->siteOffers);
        } else {
            return false;
        }
    }

    public static function getSiteOwnerDetails($site) {
        if ($site->users != null) {
            $arr = array();
            $userType           = \Helpers::getStaticValues('user_type');
            
            // get property uploader name
            if ($site->companies != null) {
                $arr['name'] = $site->companies->company_name != '' ? $site->companies->company_name : config('app.name') . ' User';
            } else {
                $arr['name'] = $site->users->fullname;
            }

            // get property company or user logo
            if ($site->companies != null) {
                if (trim($site->companies->company_logo) != '') {
                    $arr['logo'] = \Storage::disk(config('app.cdn'))->url($site->companies->company_logo);
                } else {
                    $arr['logo'] = url('images/male.svg');
                }
            } else {
                $arr['logo'] = url('images/male.svg');
            }

            // get property uploader name
            $arr['type'] = array_key_exists($site->users->user_type, $userType) ? $userType[$site->users->user_type] : 'User';

            return $arr;
        }
        return false;
    }

    public static function getSiteTotalImages($site) {
        
        if ( isset($site)) {

            $totalImages = 0;

            // site images
            $totalImages = count($site->siteImages);

            if (isset($site->properties)) {
                foreach ($site->properties as $property) {
                    if (isset($property->propertyImages)) {
                        $totalImages += count($property->propertyImages);
                    }
                }
            }
            return $totalImages > 0 ? $totalImages : false;
        }
        return false;
    }

    public static function getCoveredImage($site, $size = 1200) {
        
        if ( isset($site)) {
            
            $coveredImage = array();
            $firstImage = '';
            $i = 0;

            if(isset($site->siteImages))
            {
                foreach ($site->siteImages as $image) {
                    if ($i == 0) {
                        $coveredImage = $image;
                    }

                    if ($image->is_covered == '1') {
                        $coveredImage = $image;
                    }
                    $i++;
                }
            }

            $image = config('app.url').''.\Config::get('constants.img_placeholder');
            if ($coveredImage) {
                $image =  url($coveredImage->image_name);  
            }
            return $image;
        }
        return config('app.url').''.\Config::get('constants.img_placeholder');
    }

    public static function getFeaturedImage($site) {
        if (isset($site)) {
            $featuredImage = array();
            foreach ($site->properties as $property) {
                if (isset($property->propertyImages)) {
                    foreach ($property->propertyImages as $image) {
                        if ($image->is_featured == '1') {
                            $featuredImage[] = $image;
                        }
                    }
                }
            }

            $image = \Config::get('constants.img_placeholder');
            if (count($featuredImage) > 0) {
                if (file_exists($featuredImage[0]->image_name)) {
                    $image = $featuredImage[0]->image_name;
                }
            }
            return $image;
        }
        return \Config::get('constants.img_placeholder');
    }

    public static function resizeImage($dir, $path, $desireHW = 100, $quality = 80) {
        
        // open image file
        $img = \Intervention\Image\Facades\Image::make($path);

        // check height and width for crop
        $width = $img->width();
        $height = $img->height();

        $desireWidth = $desireHeight = $desireHW;

        if ($width > $height) {
            $percent = ($desireWidth * 100) / $width;
            $newHeight = ($height * $percent) / 100;
            $img->resize($desireWidth, $newHeight)->stream('jpg', $quality);
        } else {
            $percent = ($desireHeight * 100) / $height;
            $newWidth = ($width * $percent) / 100;
            $img->resize($newWidth, $desireHeight)->stream('jpg', $quality);
        }

        // add watermark to image
        $watermark = \Intervention\Image\Facades\Image::make('images/watermark-'.$desireHW.'.png');
        $img->insert($watermark, 'bottom-right', 10, 10);
        $img = $img->__toString();

        if (parse_url($path)) {

            // get file name
            $fileName = basename(parse_url($path)['path']);
            
            // store in image s3 after resize
            \Storage::disk(config('app.cdn'))->put($dir . '/'.$desireHW . '/' . $fileName, $img, 'public');
        }

        //$img->save($path . $desireHW);
    }

    public static function SiteMinimalDetails($site) {

        $coverImage = self::getCoveredImage($site);
        $price = self::getSitePrice($site);

        $details = '<div class="pp-property-box box-border b-radius">
            <div class="pp-image-container">
                <div class="image" style="background-image: url('.url($coverImage).')"></div>
            </div>
            <div class="pp-property-details">
                <div class="property-name h4">
                    '.self::subString($site->site_name, 35).'
                </div>
                <div class="property-location">
                    <i class="fas fa-map-marker-alt"></i>&nbsp;
                    '.($site->areas ? $site->areas->name : '-').', '.
                    ($site->cities ? $site->cities->name : '-').'
                </div><hr class="mt-1 mb-2" />
                <div class="small">
                    <ul>';
                    foreach ($site->properties as $property) {
                        $propertyArea = self::getPropertyArea($property);
                        $details .= '<li class="ml-3 mr-0">
                            <div class="d-flex w-100 mt-1 mb-1">
                                <div class="text-left text-info w-50">';
                                    if ($property->cat_id == 1) {
                                        $details .= $property->propertyFeatures->bedrooms.' BHK - ';
                                    }
                                    $details .= $propertyArea['area'].' '.$propertyArea['area_unit'].' '.$propertyArea['type'].'
                                </div>
                                <div class="text-right w-50 text-success">
                                    '.Helpers::getPrettyNumber($property->price).'
                                </div>
                            </div>
                        </li>';
                    }
                    $details .= '</ul>
                </div>
                <a href="'.route('detailshorturl', [$site->id]).'" class="btn small btn-primary mt-2 btn-block btn-sm">
                    View property &nbsp;<i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>';
        return $details;
    }

    public static function pdfSiteMinimalDetails($site) {

        $coverImage = self::getCoveredImage($site);
        $price = self::getSitePrice($site);

        $details = '<div class="pp-property-box box-border b-radius">
            <div class="pp-property-details">
                <div class="property-name h4">
                    '.self::subString($site->site_name, 35).'
                </div>
                <div class="property-location">
                    <i class="fas fa-map-marker-alt"></i>&nbsp;
                    '.($site->areas ? $site->areas->name : '-').', '.
                    ($site->cities ? $site->cities->name : '-').'
                </div><hr class="mt-1 mb-2" />
                <div class="small">
                    <ul>';
                    foreach ($site->properties as $property) {
                        $propertyArea = self::getPropertyArea($property);
                        $details .= '<li class="ml-3 mr-0">
                            <div class="d-flex w-100 mt-1 mb-1">
                                <div class="text-left text-info w-50">';
                                    if ($property->cat_id == 1) {
                                        $details .= $property->propertyFeatures->bedrooms.' BHK - ';
                                    }
                                    $details .= $propertyArea['area'].' '.$propertyArea['area_unit'].' '.$propertyArea['type'].'
                                </div>
                                <div class="text-right w-50 text-success">
                                    '.Helpers::getPrettyNumber($property->price).'
                                </div>
                            </div>
                        </li>';
                    }
                    $details .= '</ul>
                </div>
            </div>
        </div>';
        return $details;
    }

    public static function siteGridView($site, $class="", $categoryWise=false,$isHomePage=false) {

        $activeStatus = '';
        if(!empty(\Session::get('active-site')))
        {
            if(\Session::get('active-site') == $site->id)
            {
                $activeStatus = '<a href="javascript:;" class="mb-2 solid_active">Active</a>';
            }
        }
        else
        {
            if(self::isAgent())
            {
                $activeStatus = '<a href="javascript:;" class="mb-2 sale_rent_status">'.(isset($site->properties[0]->transaction_type) && $site->properties[0]->transaction_type == 1 ? 'Sale' : 'Rent').'</a>';
            }
            else
            {
                $activeStatus = '';
            }
        }

        $propCatSubCat = '';
        //$propCatSubCat = isset($site->properties[0]->propertyCategory->name) ? $site->properties[0]->propertyCategory->name : '';
        $propCatSubCat = isset($site->properties[0]->propertySubCategory->name) ? $site->properties[0]->propertySubCategory->name : '';

        $property_area = [];
        $property_area = self::searchPageSiteSummary($site,$site->properties[0]->cat_id);

        if(isset($site->properties[0]->cat_id) && $site->properties[0]->cat_id == 1)
        {
           $propCatSubCat .= isset($property_area['residential']) && isset($property_area['residential'][0]['area']) ? ' - '.$property_area['residential'][0]['area'] : ''; 
        }
        elseif(isset($site->properties[0]->cat_id) && $site->properties[0]->cat_id == 2)
        {
            $commArea = self::getPropertyArea($site->properties[0]);
            $propCatSubCat .= isset($commArea['area']) && isset($commArea['area_unit']) && isset($commArea['type']) ? ' - '.$commArea['area'].' '.$commArea['area_unit'].' '.$commArea['type'] : '';
        }
        elseif(isset($site->properties[0]->cat_id) && $site->properties[0]->cat_id == 3)
        {
           $propCatSubCat .= isset($property_area['industrial']) && isset($property_area['industrial'][0]['area']) ? ' - '.$property_area['industrial'][0]['area'] : ''; 
        }
        elseif(isset($site->properties[0]->cat_id) && $site->properties[0]->cat_id == 4)
        {
           $propCatSubCat .= isset($property_area['land'][0]['area']) && isset($property_area['land'][0]['area_unit']) && isset($property_area['land'][0]['area_type']) ? ' - '.$property_area['land'][0]['area'].' '.$property_area['land'][0]['area_unit'].' '.$property_area['land'][0]['area_type'] : ''; 
        }
                    
        //$isSoldoutClass = isset($site->is_soldout) && $site->is_soldout == 1 ? 'solid' : 'solid_active'; 
        $coverImage = self::getCoveredImage($site);
        $price = self::getSitePrice($site);
        $price_status = self::getStaticValues('price_status');

        $price_status_string = '';
        if($price_status){
            foreach ($price_status as $key => $value) {
                $price_status_string .= '<option value="'.$key.'" '.($site->price_status == $key ? 'selected' : '').' >'.$value.'</option>';
            }
        }        
        // '.(!$isHomePage ? '<div class="property-price"><b>Code : '.$site->code.'</b></div>' : '').'
           

        $details = '<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-property-box" data-status="'.($site->is_soldout == 1 ? $site->is_soldout : 0 ).'" '.($site->is_soldout == 1 ? 'style="display:none;"' : '' ).'><div class="pp-property-box box-border b-radius '.$class.'" data-status="'.($site->is_soldout == 1 ? $site->is_soldout : 0 ).'" '.($site->is_soldout == 1 ? 'style=""' : '' ).'>
                '.($site->status == 4 ? '<div class="pp-waiting-approval btn-danger box-shadow"><i class="far fa-clock"></i>&nbsp; Waiting for approval</div>' : '').'
                <div class="pp-image-container">
                    <div class="image" style="background-image: url('.url($coverImage).')">
                    '.$activeStatus.'        
                    </div>
                </div>
                <div class="pp-property-details">
                    <div class="property-name h4">
                        '.self::subString($site->site_name, 35).'
                    </div>
                    <div class="property-location">
                        <i class="fas fa-map-marker-alt"></i>&nbsp;
                        '.($site->areas ? $site->areas->name : '-').', '.
                        ($site->cities ? $site->cities->name : '-').'
                    </div><div>'.$propCatSubCat.'</div>
                    <div class="property-price">'.$price.'</div>
                    
                    '.(Request::get('activeOnly') ? '<div class="row p-l-15">status : '.($site->is_soldout == 0 ? 'Available' : 'Sold').'</div>' : '').'
                    '.((self::isAgent() || self::isEndUser() || self::isBuilder()) && !Request::get('activeOnly') && !$isHomePage ? '<div class="row p-t-10 p-l-15"><b >status : <select class="select-style is_soldout" data-id="'.$site->id.'" data-current-id="'.$site->is_soldout.'" ><option value="0" '.($site->is_soldout == 0 ? 'selected' : '' ).' >Available</option><option value="1" '.($site->is_soldout == 1 ? 'selected' : '' ).' >Sold</option></select></b></div>' : '' ).'<div class="">';

                    $details .= (isset($site->properties[0]->code) && !empty($site->properties[0]->code) ? '<div>Code : <span class="property-price">'.$site->properties[0]->code.'</span></div>' : '');


                    $details .= 'Posted Date : <span class="">'.(isset($site->properties[0]->created_at) ? date('d-m-Y',strtotime($site->properties[0]->created_at)).' ('.\Carbon\Carbon::parse($site->properties[0]->created_at)->diffForHumans().')' : '').'</span>';


                        if ($categoryWise == true) {
                            $propertyCategory = self::getSitePropertyCategory($site->properties);
                            if ($propertyCategory !== false) {
                                foreach ($propertyCategory as $category) {
                                    $url = self::getPropertyUrl($site->property_type, $category['slug'], $site);
                                    $details .= '<a href="'.$url.'" target="_blank" class="mb-2 btn btn-sm btn-primary width_50 ">Edit '.$category['name'].' details &nbsp;<i class="fas fa-arrow-right"></i></a>';
                                }
                            }
                        } else {
                            $url = Request::url();
                            $chkString ="frontSites";
                            $contains = Str::contains($url, $chkString);
                            if($contains==true){
                            $details .= '<a href="'.route('detailshorturl', [$site->id]).'" target="_blank" style="width:100% !important ;display:block;" class="mb-2 btn btn-primary width_50 btn-sm">View details &nbsp;<i class="fas fa-arrow-right" ></i></a>';
                            } else {
                               $details .= '<a href="'.route('detailshorturl', [$site->id]).'" target="_blank" class="mb-2 btn btn-primary width_50 btn-sm">View details &nbsp;<i class="fas fa-arrow-right"></i></a>'; 
                            }
                        }
                        $url = Request::url();
                        $chkString ="frontSites";
                        $contains = Str::contains($url, $chkString);
                        if($contains!=true){
                            if(self::isAgent() || self::isEndUser()){
                                $details .= '<a href="'.route('property.edit', [$site->id]).'" target="_blank" class="mb-2 btn btn-primary width_50 btn-sm">Edit property &nbsp;<i class="fas fa-edit"></i></a>';
                            }

                        }

                        if(self::isBuilder() || self::isCompanyOwner()){
                            $details .= '<a href="'.route('propertyInterestList', [$site->id]).'" target="_blank" class="mb-2 btn  btn-primary width_50 btn-sm">Interested Users &nbsp;<i class="fas fa-heart"></i></a>';
                        }

                        /*if(self::isAgent())
                        {
                            $details .= '<a href="'.route('siteleads').'?site_id='.$site->id.'&type=3'.'" target="_blank" class="mb-2 btn  btn-primary width_50 btn-sm ">Leads &nbsp;<i class="fas fa-arrow-right"></i></a>';

                            $details .= '<a href="'.route('sitePropEnquiries').'?site_id='.$site->id.'&user_type=3'.'" target="_blank" class="mb-2 btn btn-primary width_50 btn-sm ">Matches &nbsp;<i class="fas fa-arrow-right"></i></a>';
                        }*/

                        // if user is logged in then only show leads link
                        // if (self::LoggedIn()) {

                           
                        //     if (self::getLoginUserId() == $site->user_id) {
                                                        
                        //         $details .= ($site->status == 1 ? '<a title="Only you can see this link because this property is listed from your account." href="siteleads/search/list?site_id='.$site->id.'" target="_blank" class="btn btn-sm btn-block btn-success">View Leads &nbsp;<i class="fa fa-arrow-right"></i></a>' : '<a href="#" class="btn btn-block btn-danger btn-sm">Site is disabled.</a>');
                        //     } 
                        // }

                        $details .= '
                    </div>
                </div>
            </div></div>';

        return $details;
    }


    // Get site grid view data for api
    public static function siteGridViewApi($site, $request=[]) 
    {
        $siteData = [];
        $siteData['id'] = $site->id;

        $siteData['property_for'] = isset($site->properties[0]->transaction_type) && $site->properties[0]->transaction_type == 1 ? 'Sale' : 'Rent';
        //$siteData['property_type'] = isset($site->properties[0]->propertyCategory->name) ? $site->properties[0]->propertyCategory->name : '';
        $siteData['property_type'] = isset($site->properties[0]->propertySubCategory->name) ? $site->properties[0]->propertySubCategory->name : '';
        
        $siteData['code'] = isset($site->properties[0]->code) ? $site->properties[0]->code : '';
        $siteData['cover_image'] = self::getCoveredImage($site);
        $siteData['name'] = $site->site_name;
        $siteData['area'] = isset($site->areas) ? $site->areas->name : '-';
        $siteData['area'] .= isset($site->cities) ? ', '.$site->cities->name : '';
        $siteData['price'] = self::getSitePriceApi($site,$request->userType);
        $siteData['leads'] = ($request->userType == 1 || $request->userType == 3) && isset($site->properties[0]->propertyLeads) ? count($site->properties[0]->propertyLeads) : '';
        $siteData['soldout'] = ($request->userType == 1 || $request->userType == 2 || $request->userType == 3) && isset($site->is_soldout) ? (string)$site->is_soldout : '';
        $siteData['price_status'] = $request->userType == 1 || $request->userType == 2 || $request->userType == 3 ? (string)$site->price_status : '';
        $siteData['site_status'] = isset($site->status) ? (string)$site->status : '';
        $siteData['posted_date'] = isset($site->properties[0]->created_at) ? date('d-m-Y',strtotime($site->properties[0]->created_at)) : '';
        $siteData['duration'] = isset($site->properties[0]->created_at) ? \Carbon\Carbon::parse($site->properties[0]->created_at)->diffForHumans() : '';
        $siteData['config'] = self::searchPageSiteSummary($site,$site->properties[0]->cat_id);
        
        return $siteData;
    }

    public static function borkerPropertyGridView($site, $class="", $categoryWise=false,$isHomePage=false)
    {
        $coverImage = self::getCoveredImage($site);
        $price = self::getSitePrice($site);
        
        $details = '<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-property-box" data-status="'.($site->is_soldout == 1 ? $site->is_soldout : 0 ).'" '.($site->is_soldout == 1 ? 'style="display:none;"' : '' ).'><div class="pp-property-box box-border  b-radius '.$class.'">
                '.($site->status == 4 ? '<div class="pp-waiting-approval btn-danger box-shadow"><i class="far fa-clock"></i>&nbsp; Waiting for approval</div>' : '').'
                <div class="pp-image-container">
                    <div class="image" style="background-image: url('.url($coverImage).')">
                    </div>
                </div>
                <div class="pp-property-details">
                    <div class="property-name h4">
                        '.self::subString($site->site_name, 35).'
                    </div>
                    <div class="property-location">
                        <i class="fas fa-map-marker-alt"></i>&nbsp;
                        '.($site->areas ? $site->areas->name : '-').', '.
                        ($site->cities ? $site->cities->name : '-').'
                    </div>
                    <div class="property-price">'.$price.'</div>
                    
                    '.((self::isAgent() || self::isEndUser()) && !$isHomePage ? '<div class="property-price"><b>Leads : '.(isset($site->properties[0]->propertyLeads) ? count($site->properties[0]->propertyLeads) : 0 ).'</b></div>' : '' ).'
                    '.((self::isAgent() || self::isEndUser() || self::isBuilder()) && !$isHomePage ? '<div class="row p-t-10 p-l-15"><b >status : '.($site->is_soldout == 0 ? 'Available' : 'Sold' ).'</b></div>' : '' ).'<div class="mt-3">';

                    if ($categoryWise == true) {
                        $propertyCategory = self::getSitePropertyCategory($site->properties);
                        if ($propertyCategory !== false) {
                            foreach ($propertyCategory as $category) {
                                $url = self::getPropertyUrl($site->property_type, $category['slug'], $site);
                                $details .= '<a href="'.$url.'" target="_blank" class="mb-2 btn btn-sm btn-block btn-primary">Edit '.$category['name'].' details &nbsp;<i class="fas fa-arrow-right"></i></a>';
                            }
                        }
                    } else {
                        $details .= '<a href="'.route('detailshorturl', [$site->id]).'" target="_blank" class="mb-2 btn btn-block btn-primary">View details &nbsp;<i class="fas fa-arrow-right"></i></a>';
                    }
                    
                        $details .= '
                    </div>
                </div>
            </div></div>';

        return $details;
    }

    public static function getSiteDataOnly($site){
        $coverImage = self::getCoveredImage($site);
        $price = self::getSitePrice($site);
        $details = '<div class="pp-property-box box-border b-radius" style="cursor:pointer;">
                '.($site->status == 4 ? '<div class="pp-waiting-approval btn-danger box-shadow"><i class="far fa-clock"></i>&nbsp; Waiting for approval</div>' : '').'
                <div class="pp-image-container property_item heading_space">
                    <div class="image" style="background-image: url('.url($coverImage).')"></div>
                </div>
                <div class="pp-property-details">
                    <div class="property-name h4">
                        '.self::subString($site->site_name, 35).'
                    </div>
                    <div class="property-location">
                        <i class="icon-icons74"></i>&nbsp;
                        '.($site->areas ? $site->areas->name : '-').', '.
                        ($site->cities ? $site->cities->name : '-');
                if(\Session::get('active-site') == $site->id){
                     $details .= '<a href="javascript:;" class="mb-2 solid">Active</a>';
                }
                    $details .= '</div>
                </div>
            </div>';
        return $details;
    }

    public static function siteEnquiryView($site, $class="",$mascotDetails=null) {
        
        $coverImage = self::getCoveredImage($site);
        $price = self::getSitePrice($site);

        $share_percentage_of_pp_type = ($site->property_type == 1 ? 'New' : 'Resale' );
        if(self::isAgent()){
            $paymentDetails = \Helpers::getPaymentDetails();
            if(\Helpers::isMembershipPaid($paymentDetails)){  
                $agent_share = \App\Models\AgentShares::where('agent_package',$paymentDetails->package_id)->where('site_id',$site->id)->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(start_date) AND DATE(end_date)')->first();
                if($agent_share){
                    $share_percentage_of_pp_type = $agent_share->shares.' %';
                }else{
                    $share_percentage_of_pp_type = 'N/A';
                }                
            }
        }

        $details = '<div class="property-enquiry-card box-shadow b-radius pp-property-box box-border b-radius '.$class.'">
                '.($site->status == 4 ? '<div class="pp-waiting-approval btn-danger box-shadow"><i class="far fa-clock"></i>&nbsp; Waiting for approval</div>' : '').'
                <div class="pp-image-container">
                    <div class="image" style="background-image: url('.url($coverImage).')">
                    <a href="javascript:;" class="mb-2 solid agent_share_percentage" style="background:'.($site->property_type == 1 ? '#3ca8f4' : '#f4883c' ).'">'.$share_percentage_of_pp_type.'</a>  
                    </div>

                </div>
                <div class="pp-property-details">
                    <div class="property-name h4 text-center">
                        '.self::subString($site->site_name, 35).'
                    </div>
                    <div class="property-location text-center">
                        <i class="fas fa-map-marker-alt"></i>&nbsp;
                        '.($site->areas ? $site->areas->name : '-').', '.
                        ($site->cities ? $site->cities->name : '-').'
                    </div>';

        if(isset($site->property_type) && $site->property_type == '1')
        {

            $area_details = isset($site->properties[0]->cat_id) ? Helpers::searchPageSiteSummary($site,$site->properties[0]->cat_id) : [];    
            if(isset($site->properties[0]->cat_id) && $site->properties[0]->cat_id == '1')
            {
                if(isset($area_details['residential']) && count($area_details['residential'])>0)
                {
                    $details .= '<div class="col-md-12 p-l-0 p-r-0"  id="bkk">
                            <div class="scrollmenu">';

                            $area = [];
                            foreach ($area_details['residential'] as $k => $v) {
                                $details .= '<a class="counter_block">
                                                <p class="count-text no-padding no-margin" align="center">'.(isset($v['bedrooms']) && !empty($v['bedrooms']) ? $v['bedrooms'] : '').'</p>
                                                <hr style="margin:0;padding:0;border-color:lightgray;">
                                                <h2 class="count-text m-t-5"> '.(isset($v['area']) && !empty($v['area']) ? $v['area'] : '').'</h2>
                                            </a>';
                            }
                    $details .= '</div></div>';
                }
            }elseif(isset($site->properties[0]->cat_id) && $site->properties[0]->cat_id == '2'){
                if(isset($area_details['commerical']) && count($area_details['commerical'])>0)
                {
                    $details .= '<div class="col-md-12 p-l-0 p-r-0"  id="bkk">
                            <div class="scrollmenu">';

                            $area = [];
                            foreach ($area_details['commerical'] as $k => $v) {
                                $details .= '<a class="counter_block">
                                                <p class="count-text no-padding no-margin" align="center">'.(isset($v['floor_no']) && !empty($v['floor_no']) ? $v['floor_no'] : '').'</p>
                                                <hr style="margin:0;padding:0;border-color:lightgray;">
                                                <h2 class="count-text m-t-5"> '.(isset($v['area']) && !empty($v['area']) ? $v['area'] : '').'</h2>
                                            </a>';
                            }
                    $details .= '</div></div>';
                }
            }
        }                      

        $details .= '<div class="property-price text-center">'.$price.'</div>';



                    // if user is logged in then only show leads link
                    if (self::LoggedIn()) {

                        // if login user own this property then show lead link
                        if (self::getLoginUserId() == $site->user_id) {
                        
                            $details .= ($site->status == 1 ? '<div class="badge">It\'s your property</div>' : '');
                        }
                    }
                    
                    $details .= '<div class="pp-property-owner-details">
                        <span class="owner-name">'.(isset($site->properties[0]->cat_id) && $site->properties[0]->cat_id == '4' ? $mascotDetails->fullname : $site->users->fullname).'</span>
                        <span class="owner-phone"> ('.(isset($site->properties[0]->cat_id) && $site->properties[0]->cat_id == '4' ? $mascotDetails->phone : $site->users->phone).')</span><br />
                        <a href="'.route('detailshorturl', [$site->id]).'" target="_blank" class="btn btn-sm btn-primary">View details &nbsp;<i class="fas fa-arrow-right"></i></a>
                        '.(self::isAgent() ? (\App\Models\PropertyInterests::checkPropertyInterest($site->id,self::getLoginUserId()) ? '<a href="javascript:;" target="_blank" class="btn btn-sm btn-primary">Interested &nbsp;<i class="fas fa-heart"></i></a>' : '<a href="javascript:;" data-href="/property-interest/'.$site->id.'" class="btn btn-sm btn-danger send_interest"><i class="fas fa-heart"></i></a>') : '' ).'
                    </div>
                </div>
            </div>';

        return $details;
    }

    public static function mySiteListView($site) {
        return '<div class="searched-property-box">
                    <div class="d-flex upper-details">
                        <div class="property-photo">
                            <img src="https://pp.dev/images/placeholder.svg" class="placeholder" alt="Sun Residency">
                        </div>

                        <div class="property-description">
                            <div class="property-description-inner">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="property-name">Sun Residency</div>
                                        <div class="builder-name">In
                                            Amroli, 
                                            Surat
                                        </div>
                                    </div>
                                </div>
                                <div class="property-details">
                                    <ul>
                                        <li></li>
                                    </ul>
                                    <div class="property-amenities">
                                        <span><i class="fa fa-bed"></i>3 BHK</span>
                                        <span><i class="fa fa-bath"></i>Bathrooms</span>
                                    </div>
                                    <div class="property-price" title="0">Price on request</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="down-details additional-details">
                        <div class="row">
                            <div class="col-4">
                                <div class="developer-details">
                                    <div class="developer-logo">
                                        <div class="inner">
                                            <img src="https://pp.dev/images/placeholder.svg" alt="">
                                        </div>
                                    </div>
                                    <div class="developer-name">
                                        <div>Sun Residency</div>
                                        <div>Developer</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="property-action text-right">
                                    <a href="https://pp.dev/details/3762" class="btn btn-success btn-lg"><i class="fa fa-inr"></i>&nbsp; View price</a>

                                    <a href="https://pp.dev/details/2390" class="btn btn-info btn-lg">Details &nbsp; <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }

    public static function getSitePrice($site) {

       
            
        // if price is not hidden then display
        if ($site->price_status !== 3 || self::isFrontPPUser()) {



            if (isset($site->properties)) {    
                $arrPrice = array();
                foreach ($site->properties as $property) {
                    $arrPrice[] = $property->price;
                }

                // remove zero price
                $arrPrice = array_filter($arrPrice);


                // if price still available after removing zero then proceed
                if (count($arrPrice) > 0) {
                    if (count($arrPrice) == 1) {
                        $arrPrice = min($arrPrice);
                        return config('app.currency') .' '. self::getPrettyNumber($arrPrice, true);
                    } else if (min($arrPrice) > 0 && max($arrPrice) > 0) {
                        return config('app.currency') .' '. self::getPrettyNumber(min($arrPrice), true).' to '.
                                                            self::getPrettyNumber(max($arrPrice), true);
                    }
                } else {
                    if (self::isFrontPPUser()) {
                        return 'Price not specified';
                    }
                }
            } 
        }
        return 'Price on request';
    }

    // Get Site price for api
    public static function getSitePriceApi($site,$userType) {
            
        // if price is not hidden then display
         
        if($site->price_status !== 3 || $userType == "1")
        {
            if(isset($site->properties))
            {    
                $arrPrice = array();
                foreach($site->properties as $property)
                {
                    $arrPrice[] = $property->price;
                }

                // remove zero price
                $arrPrice = array_filter($arrPrice);
                
                // if price still available after removing zero then proceed
                if (count($arrPrice) > 0) 
                {
                    if (count($arrPrice) == 1) 
                    {
                        $arrPrice = min($arrPrice);
                        return self::getPrettyNumber($arrPrice, true);
                    }
                    else if (min($arrPrice) > 0 && max($arrPrice) > 0) 
                    {
                        return self::getPrettyNumber(min($arrPrice), true).' to '.
                                                            self::getPrettyNumber(max($arrPrice), true);
                    }
                }
                else
                {
                    if ($userType == "1") 
                    {
                        return 'Price not specified';
                    }
                }
            } 
        }
        return 'Price on request';
    }

    public static function getSiteMinMaxPrice($properties) {
        if (isset($properties)) {
            
            $arrPrice = [];
            
            // check all property for prices
            foreach ($properties as $property) {
                
                // price for residential and land
                if (in_array($property->cat_id, [1, 4])) {
                    $arrPrice[] = $property->price;
                }

                // ned to write code for commerical and industrial property
                if (in_array($property->cat_id, [2, 3])) {
                    // code goes here
                }                
            }

            if (count($arrPrice) > 0) {
                // get min and max price from array
                return array(
                    'min_price' => min($arrPrice), 
                    'max_price' => max($arrPrice)
                );
            } else {
                return array(
                    'min_price' => 0, 
                    'max_price' => 0
                );
            }
        }
    }

    public static function getSiteCategory($properties) {
        if ($properties) {
            $arrCategory = [];
            foreach ($properties as $value) {
                $arrCategory['cat_id'][] = $value->cat_id;
                $arrCategory['sub_cat_id'][] = $value->sub_cat_id;
            }
            
            if (count($arrCategory) > 0) {
                return array(
                    'cat_id' => array_unique(array_filter($arrCategory['cat_id'])), 
                    'sub_cat_id' => array_unique(array_filter($arrCategory['sub_cat_id'])), 
                );
            } else {
                return array(
                    'cat_id' => [], 
                    'sub_cat_id' => [], 
                );
            }
        }
        return false;
    }

    public static function getPropertyPrice($property, $price_status) {
        if ($property) {
            
            // if price still available after removing zero then proceed
            if ($property->price > 0) {
                $price = $property->price;
            } else {
                $price = 0;
            }

            // send property price
            return \Helpers::decidePriceVisibility($price, $price_status);

        }
    }
    
    public static function decidePriceVisibility($price, $price_status) {
        /*if (self::LoggedIn()) { */   
            if (self::isFrontPPUser()) {
                if (is_int($price)) {
                    if ($price > 0) {
                        return config('app.currency') .' '. self::getPrettyNumber($price, true);
                    } else {
                        return 'Price not set';
                    }
                } else {
                    return $price;
                }
            } else {
                if ($price_status == 1) {
                    if (is_int($price)) {
                        return config('app.currency') .' '. self::getPrettyNumber($price, true);
                    } else {
                        return $price;
                    }
                } else if ($price_status == 2) {
                    if (is_int($price)) {
                        return config('app.currency') .' '. self::getPrettyNumber($price, true);
                    } else {
                        return $price;
                    }
                } else if ($price_status == 3) {
                    return 'Price on request';
                }
            }
        /*} else {
            return 'Price on request';
        }*/
    }

    // Get decide price visibility for api
    public static function decidePriceVisibilityApi($price,$price_status,$request=[]) {
        if ($request->userId) {    
            if ($request->userType == "1") {
                if (is_int($price)) {
                    if ($price > 0) {
                        return config('app.currency') .' '. self::getPrettyNumber($price, true);
                    } else {
                        return 'Price not set';
                    }
                } else {
                    return $price;
                }
            } else {
                if ($price_status == 1) {
                    if (is_int($price)) {
                        return config('app.currency') .' '. self::getPrettyNumber($price, true);
                    } else {
                        return $price;
                    }
                } else if ($price_status == 2) {
                    if (is_int($price)) {
                        return config('app.currency') .' '. self::getPrettyNumber($price, true);
                    } else {
                        return $price;
                    }
                } else if ($price_status == 3) {
                    return 'Price on request';
                }
            }
        } else {
            return 'Price on request';
        }
    }

    public static function getSiteGalleryImages($details = '') {

        $oldSiteImageType = self::getStaticValues('site_photo_type');
        $newSiteImageType = self::getStaticValues('new_site_photo_type');
        $propertyImageType = self::getStaticValues('photo_type');
        

        // merge both array 
        $arrImageType = array_merge($oldSiteImageType,$propertyImageType, $newSiteImageType);
        ksort($arrImageType);

        $imgArr = array();
        $i = 0;
        foreach ($arrImageType as $key => $imageType) {

            // check in site images

            if (isset($details->sites->siteImages)) {

                foreach ($details->sites->siteImages as $k => $v) {
                    if ($v->image_type == $key) {
                        $imgArr[$key][$i] = array(
                            'id' => $v->id,
                            'src' => $v->image_name,
                            'thumb' => $v->image_name,
                            'subHtml' => '<p>'.$arrImageType[$v->image_type].'</p>'
                        );
                    }$i++;
                }
            }
             
            // check in property images
         
                    if (isset($v->propertyImages)) {
                        foreach ($details->propertyImages as $ik => $iv) {
                            if ($iv->image_type == $key) {
                                $imgArr[$key][$i] = array(
                                    'id' => $iv->id,
                                    'src' => $iv->image_name,
                                    'thumb' => $iv->image_name,
                                    'subHtml' => '<p>'.$arrImageType[$iv->image_type].'</p>'
                                );
                            }$i++;
                        }
                    }
                

        }
        return array('image_type' => $arrImageType, 'images' => $imgArr);
    }

    public static function isResidential($arr) {
        if (in_array($arr->cat_id, [1]) || 
            in_array($arr->property_type, [3,8,9,10,13,14])) {
            return true;
        }
        return false;
    }
    
    public static function isCommercial($arr) {
        if (in_array($arr->cat_id, [2]) || 
            in_array($arr->property_type, [5,11,12])) {
            return true;
        }
        return false;
    }
    
    public static function isIndustrial($arr) {
        if (in_array($arr->cat_id, [3]) || 
            in_array($arr->property_type, [16,17,22])) {
            return true;
        }
        return false;
    }

    public static function isLand($arr) {
        if (in_array($arr->cat_id, [4]) || 
            in_array($arr->property_type, [20])) {
            return true;
        }
        return false;
    }

    public static function isLandSite($siteId) 
    {
        $isLandSite = Properties::where([['site_id','=',$siteId],['cat_id','=','4'],['status','=','1']])->get();

        if(isset($isLandSite) && $isLandSite->count() > 0)
        {
            return true;
        }
        return false;
    }

    public static function isMembershipPaid($paymentDetails) {

        if (isset($paymentDetails) && isset($paymentDetails->membershipPackages)) {
             
            if ($paymentDetails->membershipPackages->package_type == 1 || $paymentDetails->membershipPackages->package_type == 2  && 
                strtotime($paymentDetails->subscription_duration_to) > 
                    strtotime(date('Y-m-d H:i:s'))) {
                return true;
            }
        }
        return false;
    }

    public static function getLeadsInfo($paymentDetails, $value) {
        if ($paymentDetails != null && self::isMembershipPaid($paymentDetails) == true) {
            return $value;
        }
        return '<span class="text-danger" title="Information is not available in free package">
                    Not available in Free package.
                </span>';
    }

    public static function getLeadsPropertyLink($value) {
    
        if (isset($value->sites->id) && $value->sites->id > 0) { 
            
            // get property details
            $propertySelected = '';
            if ($value->propertyFeatures) {
                $area_unit = self::getStaticValues('area_unit');
                
                // if property is residential then add area related details
                if (Helpers::isResidential($value)) {
                    $propertySelected = ' (Interested in '.$value->propertyFeatures->bedrooms . ' BHK ' . 
                    ($value->propertyFeatures->sb_area ? ' - '.$value->propertyFeatures->sb_area : '') .  
                    ($value->propertyFeatures->sb_area_unit ? ' '.$area_unit[$value->propertyFeatures->sb_area_unit] : '').")";
                }
            }

            // generate property url
            if ($value->propertyCategory) {
                $url = route('detailshorturl', [$value->sites->id]);
                return '<a href="'.$url.'" target="_blank">'.$value->sites->site_name . $propertySelected.' <i class="fas fa-external-link-alt"></i></a>';
            }
        }
        return 'n/a';
    }

    public static function parseSearchParams($request) {

        if (isset($request->data)) {

            // convert string to array and store in $requestData
            parse_str(base64_decode($request->data), $requestData);

            // convert request data array to object
            $requestData = (array) $requestData;    

            // format searched data
            if (isset($requestData['searchterm'])) {
                
                $searchedData = array();
                
                // get searched data loop through
                foreach ($requestData['searchterm'] as $k => $v) {
                    
                    // it will get "A-" (Area), "B-" (Builder) 
                    // in $tkey and $tVal will be value
                    $tKey = substr($v, 0, 2);
                    $tVal = substr($v, 2);

                    if ($tKey == 'A-') {
                        $requestData['areas'][] = (int) $tVal;
                    } else if ($tKey == 'S-') {
                        $requestData['sites'][] = (int) $tVal;
                    }    
                }

                // remove unformatted data
                unset($requestData['searchterm']);
            }
            // send request data back
            return (object) $requestData;   
        }
        return false;
    }

    public static function parseCompareParams($request) {
        if (isset($request->data)) {

            // convert string to array and store in $requestData
            $requestData = explode(',', base64_decode(urldecode($request->data)));

            // convert request data array to object
            $requestData = (array) $requestData;
            
            // if valid site array set then return else false
            return isset($requestData) && count($requestData) > 0 ? $requestData : false;
        }
        return false;
    }

    public static function propertyConfigDropdown($properties) {
        $select = '';
        if ($properties) {
            $area_unit = self::getStaticValues('area_unit');
            $select = '<select name="property_id[]" class="cselect form-control" multiple="multiple"  data-placeholder="Select Configuration">';
            foreach ($properties as $property) {
                
                // for residential
                if ($property->propertyCategory) {
                    if (in_array($property->propertyCategory->id, [1, 4])) {
                        if ($property->propertyFeatures) {
                            $select .= '<option value="'.$property->id.'">' . 
                                $property->propertyFeatures->bedrooms . ' BHK ' . 
                                ($property->propertyFeatures->sb_area ? ' - '.$property->propertyFeatures->sb_area : '') .  
                                ($property->propertyFeatures->sb_area_unit ? ' '.$area_unit[$property->propertyFeatures->sb_area_unit] : '') . '</option>';
                        }
                    } else if ($property->propertyCategory->id == 2) {
                        $select .= '<option value="'.$property->id.'">Commercial Varient</option>';
                    } else if ($property->propertyCategory->id == 3) {
                        $select .= '<option value="'.$property->id.'">Industrial Varient</option>';
                    }
                }

            }
            $select .= '</select>';
            return $select;
        }
        return false;
    }

    public static function getUserSidebarDetails() {

        if ( ! self::isEndUser()) {
            if(session('PpUsrTyp') == 5){
                return false;
            }else{
                $userId = self::getLoginUserId();
                if ($userId) {
                    $UserCompany = UserCompanies::where('user_id', $userId)->pluck('company_id');
                    if ($UserCompany->count() > 0) {
                        $company = Companies::select(['company_name', 'company_logo'])
                                        ->where('id', $UserCompany)
                                        ->first();
                        return $company;
                    }
                }
            }
        }
        return false;
    }

    public static function getCompanySites() {

        if ( ! self::isEndUser()) {
            $userId = self::getLoginUserId();
            if ($userId) {
                $UserCompany = UserCompanies::where('user_id', $userId)->first();
                if ($UserCompany) {
                    $UserCompany->load(['sites','sites.areas','sites.cities','sites.siteImages']);
                    if(empty(\Session::get('active-site'))){
                        if(isset($UserCompany->sites[0]->id)){
                            \Session::put('active-site',$UserCompany->sites[0]->id);
                        }
                    }
                    return $UserCompany;
                }else{
                    \Session::forget('active-site');
                }
            }
        }
        return false;
    }

    public static function isCompanyOwner() {
        $company = self::getUserSidebarDetails();
        if($company){
            return true;
        }
        return false;
    }

    public static function getSiteThumbUrl($name, $size = 400) {

        $name = self::prepareThumbDirName($name, $size);

        //if (file_exists(\Helpers::cdnurl($name))) {
            return \Helpers::cdnurl($name);
        // } else {
        //     return url('images/placeholder.svg');
        // }
    }

    public static function prepareThumbDirName($name, $size) {
        
        // explode using slash
        $arrUrl = explode('/', $name);

        // get last element which is filename
        $filename = $arrUrl[count($arrUrl) - 1];
        
        // prepare size folder name before filename
        $resizeFileName =  $size .'/' . $filename; 
        
        // replace size folder before filename
        return str_replace($filename, $resizeFileName, $name);

    }

    public static function getPropertyThumbUrl($name, $size = 400) {
        
        //$name = self::prepareThumbDirName($name, $size);

        //if (file_exists(\Helpers::cdnurl($name))) {
            return url('/public/'.$name);
        // } else {
        //     return url('images/placeholder.svg');
        // }
    }

    public static function getUserPropertyCount($userId) {
        // count user uploaded property 
        return Sites::where(['user_id' => $userId, 'status' => 1,'is_soldout'=>1])->pluck('id')->count();
    }

    public static function getUserSubscriptionDetails() {
        
        // if agent than fetch payment details
        if (self::isAgent()) {

            // count user uploaded property 
            $UserProperty = self::getUserPropertyCount(self::getLoginUserId());
            
            // count user added enquiry
            $UserEnquiry = Enquiries::where(['user_id' => self::getLoginUserId()])->where('status','1')->pluck('id')->count();
            
            $UserPackageDetails = AgentPayments::with(['membershipPackages'])->where([
                'user_id' => self::getLoginUserId(),
                'status' => '1'
            ])->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();

            if ($UserPackageDetails) {
                $discountAmount = ((($UserPackageDetails->package_cost * $UserPackageDetails->subscription_duration_month) * $UserPackageDetails->discount_percentage) / 100);

                $content = '<div class="card box-shadow mb-4">
                    <div class="card-header">Subscription details<hr class="mb-0" /></div>
                    <div class="card-body pt-2 pb-2">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="alert alert-success">
                                    <div class="h6">Subscription status</div><hr class="p-0 mt-2 mb-2" />
                                    <div class="bold">'.($UserPackageDetails->status == 1 ? '<i class="fas fa-check-circle"></i>&nbsp;Active' : '<i class="fas fa-times-circle"></i>&nbsp;Expired').'
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-warning">
                                    <div class="h6">Subscription starts on</div><hr class="p-0 mt-2 mb-2" />
                                    <div class="bold">
                                        '.date('d-M-Y', strtotime($UserPackageDetails->subscription_duration_from)).'
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-warning">
                                    <div class="h6">Subscription ends on</div><hr class="p-0 mt-2 mb-2" />
                                    <div class="bold">
                                        '.date('d-M-Y', strtotime($UserPackageDetails->subscription_duration_to)).'
                                    </div>
                                </div>
                            </div>
                        </div>  

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <table class="table table-striped border">
                                    <tbody>
                                        <tr>
                                            <th colspan="2" class="text-center alert-primary">Subscription details</th>
                                        </tr>
                                        <tr>
                                            <th>Package Name</th>
                                            <td>'.$UserPackageDetails->membershipPackages->package_name.'</td>
                                        </tr>
                                        <tr>
                                            <th>Property Limit</th>
                                            <td>'.$UserPackageDetails->add_property_limit_no.' Properties ('.($UserPackageDetails->add_property_limit_no - $UserProperty).' remaining)</td>
                                        </tr>
                                        <tr>
                                            <th>Enquiry Limit</th>
                                            <td>'.$UserPackageDetails->enquiry_limit_no.' Enquiry ('.($UserPackageDetails->enquiry_limit_no - $UserEnquiry).' remaining)</td>
                                        </tr>
                                        <tr>
                                            <th>Subscription start on</th>
                                            <td>'.date('d-M-Y', strtotime($UserPackageDetails->subscription_duration_from)).'</td>
                                        </tr>
                                        <tr>
                                            <th>Subscription end on</th>
                                            <td>'.date('d-M-Y', strtotime($UserPackageDetails->subscription_duration_to)).'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-striped border">
                                    <tbody>
                                        <tr>
                                            <th colspan="2" class="text-center alert-primary">
                                                Billing details</th>
                                        </tr>
                                        <tr>
                                            <th>Invoice Amount</th>
                                            <td>'.config('app.currency').' '.(number_format($UserPackageDetails->package_cost * $UserPackageDetails->subscription_duration_month)).' ('.config('app.currency').' '.number_format($UserPackageDetails->package_cost).' x '.$UserPackageDetails->subscription_duration_month.' Months)</td>
                                        </tr>
                                        <tr>
                                            <th>Discount (-)</th>
                                            <td>'.($discountAmount > 0 ? $UserPackageDetails->discount_percentage.'% &nbsp;('.config('app.currency').' '.number_format($discountAmount).')' : 'Not Applicable').'</td>
                                        </tr>
                                        <tr>
                                          <th>CGST @ 9% (+)</th>
                                          <td>'.config('app.currency').' '.number_format($UserPackageDetails->cgst_total, 2).'</td>
                                        </tr>
                                        <tr>
                                          <th>SGST @ 9% (+)</th>
                                          <td>'.config('app.currency').' '.number_format($UserPackageDetails->sgst_total, 2).'</td>
                                        </tr>
                                        <tr>
                                            <th>Total Paid</th>
                                            <td>'.config('app.currency').' '.number_format($UserPackageDetails->invoice_amount).'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>';

                return $content;
            }
        } else if (self::isEndUser() && self::isEndUserPaid()) {
            // count user uploaded property 
            $UserProperty = self::getUserPropertyCount(self::getLoginUserId());
            
            // count user added enquiry
            $UserEnquiry = Enquiries::where(['user_id' => self::getLoginUserId()])->pluck('id')->count();
            
            $UserPackageDetails = UserPayments::with(['membershipPackages'])->where([
                'user_id' => self::getLoginUserId(),
                'status' => '1'
            ])->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();

            if ($UserPackageDetails) {
                $discountAmount = ((($UserPackageDetails->package_cost * $UserPackageDetails->subscription_duration_month) * $UserPackageDetails->discount_percentage) / 100);

                $content = '<div class="card box-shadow mb-4">
                    <div class="card-header">Subscription details<hr class="mb-0" /></div>
                    <div class="card-body pt-2 pb-2">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="alert alert-success">
                                    <div class="h6">Subscription status</div><hr class="p-0 mt-2 mb-2" />
                                    <div class="bold">'.($UserPackageDetails->status == 1 ? '<i class="fas fa-check-circle"></i>&nbsp;Active' : '<i class="fas fa-times-circle"></i>&nbsp;Expired').'
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-warning">
                                    <div class="h6">Subscription starts on</div><hr class="p-0 mt-2 mb-2" />
                                    <div class="bold">
                                        '.date('d-M-Y', strtotime($UserPackageDetails->subscription_duration_from)).'
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-warning">
                                    <div class="h6">Subscription ends on</div><hr class="p-0 mt-2 mb-2" />
                                    <div class="bold">
                                        '.date('d-M-Y', strtotime($UserPackageDetails->subscription_duration_to)).'
                                    </div>
                                </div>
                            </div>
                        </div>  

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <table class="table table-striped border">
                                    <tbody>
                                        <tr>
                                            <th colspan="2" class="text-center alert-primary">Subscription details</th>
                                        </tr>
                                        <tr>
                                            <th>Package Name</th>
                                            <td>'.$UserPackageDetails->membershipPackages->package_name.'</td>
                                        </tr>
                                        <tr>
                                            <th>Property Limit</th>
                                            <td>'.$UserPackageDetails->add_property_limit_no.' Properties ('.($UserPackageDetails->add_property_limit_no - $UserProperty).' remaining)</td>
                                        </tr>
                                        <tr>
                                            <th>Enquiry Limit</th>
                                            <td>'.$UserPackageDetails->enquiry_limit_no.' Enquiry ('.($UserPackageDetails->enquiry_limit_no - $UserEnquiry).' remaining)</td>
                                        </tr>
                                        <tr>
                                            <th>Subscription start on</th>
                                            <td>'.date('d-M-Y', strtotime($UserPackageDetails->subscription_duration_from)).'</td>
                                        </tr>
                                        <tr>
                                            <th>Subscription end on</th>
                                            <td>'.date('d-M-Y', strtotime($UserPackageDetails->subscription_duration_to)).'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-striped border">
                                    <tbody>
                                        <tr>
                                            <th colspan="2" class="text-center alert-primary">
                                                Billing details</th>
                                        </tr>
                                        <tr>
                                            <th>Invoice Amount</th>
                                            <td>'.config('app.currency').' '.(number_format($UserPackageDetails->package_cost * $UserPackageDetails->subscription_duration_month)).' ('.config('app.currency').' '.number_format($UserPackageDetails->package_cost).' x '.$UserPackageDetails->subscription_duration_month.' Months)</td>
                                        </tr>
                                        <tr>
                                            <th>Discount (-)</th>
                                            <td>'.($discountAmount > 0 ? $UserPackageDetails->discount_percentage.'% &nbsp;('.config('app.currency').' '.number_format($discountAmount).')' : 'Not Applicable').'</td>
                                        </tr>
                                        <tr>
                                          <th>CGST @ 9% (+)</th>
                                          <td>'.config('app.currency').' '.number_format($UserPackageDetails->cgst_total, 2).'</td>
                                        </tr>
                                        <tr>
                                          <th>SGST @ 9% (+)</th>
                                          <td>'.config('app.currency').' '.number_format($UserPackageDetails->sgst_total, 2).'</td>
                                        </tr>
                                        <tr>
                                            <th>Total Paid</th>
                                            <td>'.config('app.currency').' '.number_format($UserPackageDetails->invoice_amount).'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>';

                return $content;
            }
        } else if (self::isBuilder()) {

            $SitePackageDetails = SitePayments::with(['sites', 'membershipPackages'])
                    ->where('status', '1')
                    ->whereIn('site_id', Sites::where(
                        'user_id', \Helpers::getLoginUserId()
                    )->pluck('id'))
                    ->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')
                    ->get();
                    
            if ($SitePackageDetails) {
                $content = '<div class="card box-shadow mb-4">
                    <div class="card-header">Subscription details<hr class="mb-0" /></div>
                    <div class="card-body pt-0 pb-2">
                        <table class="table table-striped">
                            <tbody>
                                <thead>
                                    <tr>
                                        <th>Site</th>
                                        <th>Package Name</th>
                                        <th>Subscription start from</th>
                                        <th>Subscription end on</th>
                                        <th>Status</th>
                                        <th>Paid Amount</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    if (count($SitePackageDetails) > 0) {
                                        foreach ($SitePackageDetails as $Payment) {
                                            $content .= '<tr>
                                                <td><a href="'.route('detailshorturl', [$Payment->sites->id]).'" target="_blank">'.$Payment->sites->site_name.'</a></td>
                                                <td>'.$Payment->membershipPackages->package_name.'</td>
                                                <td>'.date('d-M-Y', strtotime($Payment->subscription_duration_from)).'</td>
                                                <td>'.date('d-M-Y', strtotime($Payment->subscription_duration_to)).'</td>
                                                <td>'.($Payment->status == '1' ? '<i class="fas fa-check-circle text-success"></i>&nbsp; Active' : '<i class="fas fa-times-circle text-danger"></i>&nbsp; Disabled').'</td>
                                                <td>'.config('app.currency').' '.number_format($Payment->invoice_amount).'</td>
                                            </tr>';
                                        }
                                    } else {
                                        $content .= '<tr><td colspan="6" class="text-center">Subscription details not available.</td></tr>';
                                    }

                                    $content .= '
                                </tbody>
                            </tbody>
                        </table>
                    </div>
                </div>';
                return $content;
            }
        }
    }

    public static function canUserAddProperty() {
        
        if (self::LoggedIn()) {
            
            if (self::isAgent()) {
                $UserPackageDetails = AgentPayments::where([
                    'user_id' => self::getLoginUserId(),
                    'status' => '1'
                ])->first();

                // get users added property
                $TotalUserProperty = Sites::where([
                    'user_id' => self::getLoginUserId(),
                    'status' => 1,
                    'is_soldout' => '0'
                ])->pluck('id')->count();

                // if user purchased package then give limits accordingly
                if ($UserPackageDetails) {
                    if ($TotalUserProperty < $UserPackageDetails->add_property_limit_no) {
                        return array('status' => true, 'can_add' => $UserPackageDetails->add_property_limit_no);
                    } else {
                        return array('status' => false, 'can_add' => $UserPackageDetails->add_property_limit_no);
                    }
                } else {
                    // as a free member they can add 2 free property
                    return array('status' => false, 'can_add' => config('app.can_add_total_free_property'));
                }

            } else if (self::isBuilder()) {

                // builder can't add property on their own
                return array('status' => false, 'can_add' => '0');

            }elseif(self::isEndUser() && self::isEndUserPaid()) {
                $UserPackageDetails = UserPayments::where([
                    'user_id' => self::getLoginUserId(),
                    'status' => '1'
                ])->first();

                // get users added property
                $TotalUserProperty = Sites::where([
                    'user_id' => self::getLoginUserId(),
                    'status' => 1,
                    'is_soldout' => '0'
                ])->pluck('id')->count();

                // if user purchased package then give limits accordingly
                if ($UserPackageDetails) {
                    if ($TotalUserProperty < $UserPackageDetails->add_property_limit_no) {
                        return array('status' => true, 'can_add' => $UserPackageDetails->add_property_limit_no);
                    } else {
                        return array('status' => false, 'can_add' => $UserPackageDetails->add_property_limit_no);
                    }
                } else {
                    // as a free member they can add 2 free property
                    if ($TotalUserProperty < config('app.can_add_total_free_property')) 
                    {
                        return array('status' => true, 'can_add' => config('app.can_add_total_free_property'));
                    } else {
                        return array('status' => false, 'can_add' => config('app.can_add_total_free_property'));
                    }
                }
            }else {

                // get users added property
                $TotalUserProperty = Sites::where([
                    'user_id' => self::getLoginUserId(),
                    'status' => 1,
                    'is_soldout' => '0'
                ])->pluck('id')->count();

                if ($TotalUserProperty < config('app.can_add_total_free_property')) {
                    return array('status' => true, 'can_add' => config('app.can_add_total_free_property'));
                } else {
                    return array('status' => false, 'can_add' => config('app.can_add_total_free_property'));
                }
            }
        }
        return array('status' => false);
    }

    /**
    *   To check user can add property or not through api 
    */
    public static function canUserAddPropertyApi($request) {
       


        if (isset($request->userId))
        {
            if (isset($request->userType) && $request->userType == '3')  // Is agent
            {


                $UserPackageDetails = AgentPayments::where([
                    'user_id' => $request->userId,
                    'status' => '1'
                ])->first();

                // get users added property
                $TotalUserProperty = Sites::where([
                    'user_id' => $request->userId,
                    'status' => 1,
                    'is_soldout' => '0'
                ])->pluck('id')->count();
                
                // if user purchased package then give limits accordingly
                if ($UserPackageDetails) 
                {
                    if ($TotalUserProperty < $UserPackageDetails->add_property_limit_no) 
                    {
                        return array('status' => true, 'can_add' => $UserPackageDetails->add_property_limit_no);
                    }
                    else
                    {
                        return array('status' => false, 'can_add' => $UserPackageDetails->add_property_limit_no);
                    }
                } 
                else
                {
                    // as a free member they can add 2 free property
                    return array('status' => false, 'can_add' => config('app.can_add_total_free_property'));
                }
            }
            /*else if (isset($request->userType) && $request->userType == '2') // Is builder
            { 
  

                // builder can't add property on their own
                return array('status' => false, 'can_add' => '0',"message" => "builder can't add property on their own");

            }*/
            else if(isset($request->userType) && $request->userType == '1') 
            {
                $UserPackageDetails = UserPayments::where([
                    'user_id' => $request->userId,
                    'status' => '1'
                ])->first();

                // get users added property
                $TotalUserProperty = Sites::where([
                    'user_id' => $request->userId,
                    'status' => 1,
                    'is_soldout' => '0'
                ])->pluck('id')->count();

                // if user purchased package then give limits accordingly
                if ($UserPackageDetails) {
                    if ($TotalUserProperty < $UserPackageDetails->add_property_limit_no) {
                        return array('status' => true, 'can_add' => $UserPackageDetails->add_property_limit_no);
                    } else {
                        return array('status' => false, 'can_add' => $UserPackageDetails->add_property_limit_no);
                    }
                } else {
                    // as a free member they can add 2 free property
                    if ($TotalUserProperty < config('app.can_add_total_free_property')) 
                    {
                        return array('status' => true, 'can_add' => config('app.can_add_total_free_property'));
                    }
                    else
                    {
                        return array('status' => false, 'can_add' => config('app.can_add_total_free_property'));
                    }
                }
            }
            else
            { // Other user

                // get users added property
                $TotalUserProperty = Sites::where([
                    'user_id' => $request->userId,
                    'status' => 1,
                    'is_soldout' => '0'
                ])->pluck('id')->count();

                
                if ($TotalUserProperty < config('app.can_add_total_free_property')) 
                {
                    return array('status' => true, 'can_add' => config('app.can_add_total_free_property'));
                }
                else
                {
                    return array('status' => false, 'can_add' => config('app.can_add_total_free_property'));
                }
            }
        }
        return array('status' => false);
    }

    // User can add enquiry or not
    public static function canUserAddEnquiry()
    {
        if (self::LoggedIn()) {
            
            if (self::isAgent()) {
                $UserPackageDetails = AgentPayments::where([
                    'user_id' => self::getLoginUserId(),
                    'status' => '1'
                ])->first();

                // get users added enquiry
                $TotalUserEnquiry = Enquiries::where([
                    'user_id' => self::getLoginUserId(),
                    'status' => 1
                ])->pluck('id')->count();

                // if user purchased package then give limits accordingly
                if ($UserPackageDetails) {
                    if ($TotalUserEnquiry < $UserPackageDetails->enquiry_limit_no) {
                        return array('status' => true, 'can_add' => $UserPackageDetails->enquiry_limit_no);
                    } else {
                        return array('status' => false, 'can_add' => $UserPackageDetails->enquiry_limit_no);
                    }
                } else {
                    // as a free member they can add 2 free property
                    return array('status' => false, 'can_add' => '50');
                }

            }elseif (self::isBuilder()) {

                // builder can add multiple enquiry on their own
                return array('status' => true);

            }elseif(self::isEndUser() && self::isEndUserPaid()) {
                $UserPackageDetails = UserPayments::where([
                    'user_id' => self::getLoginUserId(),
                    'status' => '1'
                ])->first();

                // get users added property
                $TotalUserEnquiry = Enquiries::where([
                    'user_id' => self::getLoginUserId(),
                    'status' => 1
                ])->pluck('id')->count();

                // if user purchased package then give limits accordingly
                if ($UserPackageDetails) {
                    if ($TotalUserEnquiry < $UserPackageDetails->enquiry_limit_no) {
                        return array('status' => true, 'can_add' => $UserPackageDetails->enquiry_limit_no);
                    } else {
                        return array('status' => false, 'can_add' => $UserPackageDetails->enquiry_limit_no);
                    }
                } else {
                    
                    // as a free member they can add 2 free property
                    return array('status' => false, 'can_add' => config('app.can_add_total_free_enquiry'));
                }
            }else {

                // get users added property
                $TotalUserEnquiry = Enquiries::where([
                    'user_id' => self::getLoginUserId(),
                    'status' => 1
                ])->pluck('id')->count();

                if ($TotalUserEnquiry < config('app.can_add_total_free_enquiry')) {
                    return array('status' => true, 'can_add' => config('app.can_add_total_free_enquiry'));
                } else {
                    return array('status' => false, 'can_add' => config('app.can_add_total_free_enquiry'));
                }
            }
        }
        return array('status' => false);
    }

    // User can add enquiry or not
    public static function canUserAddEnquiryApi($request)
    {
        if (isset($request->userId) && isset($request->userType)) {
            
            if ($request->userType == '3') {
                $UserPackageDetails = AgentPayments::where([
                    'user_id' => $request->userId,
                    'status' => '1'
                ])->first();

                // get users added enquiry
                $TotalUserEnquiry = Enquiries::where([
                    'user_id' => $request->userId,
                    'status' => 1
                ])->pluck('id')->count();

                // if user purchased package then give limits accordingly
                if ($UserPackageDetails) {
                    if ($TotalUserEnquiry < $UserPackageDetails->enquiry_limit_no) {
                        return array('status' => true, 'can_add' => $UserPackageDetails->enquiry_limit_no);
                    } else {
                        return array('status' => false, 'can_add' => $UserPackageDetails->enquiry_limit_no);
                    }
                } else {
                    // as a free member they can add 2 free property
                    return array('status' => false, 'can_add' => '0');
                }

            }elseif ($request->userType == '2') {

                // builder can add multiple enquiry on their own
                return array('status' => true);

            }elseif($request->userType == '1') {
                $UserPackageDetails = UserPayments::where([
                    'user_id' => $request->userId,
                    'status' => '1'
                ])->first();

                // get users added property
                $TotalUserEnquiry = Enquiries::where([
                    'user_id' => $request->userId,
                    'status' => 1
                ])->pluck('id')->count();

                // if user purchased package then give limits accordingly
                if ($UserPackageDetails) {
                    if ($TotalUserEnquiry < $UserPackageDetails->enquiry_limit_no) {
                        return array('status' => true, 'can_add' => $UserPackageDetails->enquiry_limit_no);
                    } else {
                        return array('status' => false, 'can_add' => $UserPackageDetails->enquiry_limit_no);
                    }
                } else {
                    if ($TotalUserEnquiry < config('app.can_add_total_free_enquiry')) {
                        return array('status' => true, 'can_add' => config('app.can_add_total_free_enquiry'));
                    } else {
                    // as a free member they can add 2 free property
                    return array('status' => false, 'can_add' => config('app.can_add_total_free_enquiry'));
                    }
                }
            }else {

                // get users added property
                $TotalUserEnquiry = Enquiries::where([
                    'user_id' => $request->userId,
                    'status' => 1
                ])->pluck('id')->count();

                if ($TotalUserEnquiry < config('app.can_add_total_free_enquiry')) {
                    return array('status' => true, 'can_add' => config('app.can_add_total_free_enquiry'));
                } else {
                    return array('status' => false, 'can_add' => config('app.can_add_total_free_enquiry'));
                }
            }
        }
        return array('status' => false);
    }

    public static function getUserTypeFromSiteId($site_id = 0) {
        $userDetails = Users::select('id', 'user_type')
                            ->where('id', Sites::where('id', $site_id)->pluck('user_id'))
                            ->first();
        return $userDetails;
    }

    public static function isPaymentValid($paymentDetails) {
        if ($paymentDetails != null) {
            
            // consider only paid package free or trial user will not be considerable
            if ($paymentDetails->membershipPackages->package_type == 1 && 
                strtotime($paymentDetails->subscription_duration_to) > strtotime(date('Y-m-d H:i:s'))) {

                // payment is valid
                return true;
            }
        }

        // default to invalid
        return false;
    }

    public static function getPaymentDetailsBySiteId($siteId = '') {
        $paymentDetails = array();
        $isBuilder = $isAgent = $isUser = false;

        $userDetails = self::getUserTypeFromSiteId($siteId);
       
        if ($userDetails->user_type == 2) {
            $isBuilder = true;
        } else if ($userDetails->user_type == 3) {
            $isAgent = true;
        } else if ($userDetails->user_type == 1) {
            $isUser = true;
        }

        if ($isBuilder === true) {
            // get select site payment details
            $paymentDetails = SitePayments::with(['membershipPackages'])
                                ->where('site_id', $siteId)
                                ->where('status', '1')
                                ->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();
        } else if ($isAgent === true) {
            // get select agent payment details
            $paymentDetails = AgentPayments::with(['membershipPackages'])->where([
                                    'user_id' => $userDetails->id,
                                    'status' => '1'
                                ])->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();
        } else if ($isUser === true) {
            // get select user payment details
            $paymentDetails = UserPayments::with(['membershipPackages'])->where([
                                    'user_id' => $userDetails->id,
                                    'status' => '1'
                                ])->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();
        }
        return $paymentDetails;
    }
    
    public static function getPaymentDetails($request = '') {
        $paymentDetails = array();


        if (\Helpers::isBuilder()) {
            // get select site payment details
            $paymentDetails = SitePayments::with(['membershipPackages'])
                                ->whereIn('site_id', ($request != '' && $request->site_id != null ? array($request->site_id) : Sites::where('user_id', \Helpers::getLoginUserId())->pluck('id')))
                                ->where('status', '1')
                                ->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();
                                
        } else if (\Helpers::isAgent()) {
            // get select site payment details
            $paymentDetails = AgentPayments::with(['membershipPackages'])->where([
                                    'user_id' => \Helpers::getLoginUserId(),
                                    'status' => '1'
                                ])->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();
        } else if (\Helpers::isEndUser()) {
            // get select site payment details
            $paymentDetails = \App\Models\UserPayments::with(['membershipPackages'])->where([
                                    'user_id' => \Helpers::getLoginUserId(),
                                    'status' => '1'
                                ])->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();
        }
        
        return $paymentDetails;
    }

    /**
    * Get payment details for api
    */
    public static function getPaymentDetailsApi($request = '') {
        $paymentDetails = array();

        if (isset($request->userType) && $request->userType == '2') {
            // get select site payment details
            $paymentDetails = SitePayments::with(['membershipPackages'])
                                ->whereIn('site_id', ($request != '' && $request->site_id != null ? array($request->site_id) : Sites::where('user_id', $request->userId)->pluck('id')))
                                ->where('status', '1')
                                ->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();
        } else if (isset($request->userType) && $request->userType == '3') {
            // get select site payment details
            $paymentDetails = AgentPayments::with(['membershipPackages'])->where([
                                    'user_id' => $request->userId,
                                    'status' => '1'
                                ])->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();
        } else if (isset($request->userType) && $request->userType == '1') {
            // get select site payment details
            $paymentDetails = \App\Models\UserPayments::with(['membershipPackages'])->where([
                                    'user_id' => $request->userId,
                                    'status' => '1'
                                ])->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')->first();
        }
        return $paymentDetails;
    }
    
    public static function sendNewLeadMessageToPropertyOwner($leadPhone, $leadName, $siteId,$leadId = 0) {
        
        if ($siteId > 0) {

            // get site details
            $SiteDetails = Sites::select(['id', 'user_id', 'site_name'])
                ->where('id', $siteId)
                ->with(['users' => function($query) {
                    $query->addSelect('id', 'user_type', 'phone', 'device_token');
                }])
                ->get();


            if ($SiteDetails->count() > 0) {

                foreach ($SiteDetails as $Site) {
                    
                    // get site payment details
                    $paymentDetails = self::getPaymentDetailsBySiteId($Site->id);

                    // lead user fullname
                    $leadName = ($leadName != '' ? $leadName : 'One user');

                    // lead user phone 
                    $leadPhone = (Helpers::isPaymentValid($paymentDetails) ? $leadPhone : 'Free package');

                    // send otp for verification of offer to builder
                    Helpers::sendSMS($Site->users->phone, 
                        sprintf(
                            Helpers::smsTemplate('newLeadNotify'), 
                            \Helpers::subString($leadName, 12), 
                            $leadPhone,
                            \Helpers::subString($Site->site_name, 12)
                        )
                    );

                    // Send Notification To owner
                    if(!empty($Site->users->device_token)){

                        $body_data = $Site->site_name;
                        $properties = $Site->properties;
                        if($properties && $properties->count() > 0){
                            $body_data = $properties[0]->code;
                        }

                        if($leadId > 0){
                            $leads = \App\Models\Leads::where('id',$leadId)->with('user')->get();
                            $lead_user_details = $leads->pluck('user');
                            if($lead_user_details){
                                $userLeadCount = $lead_user_details->where('user_type',1)->count();
                                $builderLeadCount = $lead_user_details->where('user_type',2)->count();
                                $agentLeadCount = $lead_user_details->where('user_type',3)->count();
                            }    
                            $userLeadCount = $userLeadCount && $userLeadCount > 0 ? (string)$userLeadCount : '0';
                            $agentLeadCount = $agentLeadCount && $agentLeadCount > 0 ? (string)$agentLeadCount : '0';
                            $builderLeadCount = $builderLeadCount && $builderLeadCount > 0 ? (string)$builderLeadCount : '0';

                            $response = \Helpers::notify()
                            ->to([$Site->users->device_token]) // $recipients must an array
                            ->priority('high')
                            ->notification([
                                // 'title' => $leadName.' : '.config('app.name'),
                                'title' => 'Your property has got a lead',
                                'body' => $body_data,
                                'key' => 1,
                                'siteId' => $Site->id,
                                'type' => (isset($leads[0]->user) && $leads[0]->user ? $leads[0]->user->user_type : 0),
                                'agentLeadsCount' => $agentLeadCount,
                                'builderLeadsCount' => $builderLeadCount,
                                'userLeadsCount' => $userLeadCount,
                            ])
                            ->send();

                        }else{

                            $response = \Helpers::notify()
                            ->to([$Site->users->device_token]) // $recipients must an array
                            ->priority('high')
                            ->notification([
                                // 'title' => $leadName.' : '.config('app.name'),
                                'title' => 'Your property has got a lead',
                                'body' => $body_data,
                                'key' => 1,
                                'siteId' => $Site->id,
                                'type' => 0,
                            ])
                            ->send();

                        }
                    }
                }
            }
        }
    }

    public static function getAgentInvoiceDetailsTable($invoice) {
        $return = '<table class="table box-shadow table-striped">
            <tr>
                <td colspan="2" class="text-center">This invoice is ';
                    if ($invoice->status == 0) {
                        $return .= '<span class="pp-badge badge-warning p-1 pr-2 pl-2">Pending for approval</span>';
                    } else if ($invoice->status == 1) {
                        $return .= '<span class="pp-badge badge-success p-1 pr-2 pl-2">Active</span>';
                    } else if ($invoice->status == 2) {
                        $return .= '<span class="pp-badge badge-danger p-1 pr-2 pl-2">Rejected</span>';
                    } else if ($invoice->status == 3) {
                        $return .= '<span class="pp-badge badge-danger p-1 pr-2 pl-2">Expired</span>';
                    }
                
                $return .= '
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Subscription from <span class="pp-badge pp-badge-primary p-2">'.($invoice->subscription_duration_from > 0 ? date('d-M-Y', strtotime($invoice->subscription_duration_from)) : '-').'</span> to <span class="pp-badge pp-badge-primary p-2">'.($invoice->subscription_duration_to > 0 ? date('d-M-Y', strtotime($invoice->subscription_duration_to)) : '-').'</span>
                </td>
            </tr>
            <tr>
                <td width="50%">Package Name</td>
                <td width="50%">'.$invoice->membershipPackages->package_name.'</td>
            </tr>
            <tr>
                <td>Package cost</td>
                <td>
                    '.config('app.currency').' '.
                    ($invoice->package_cost * $invoice->subscription_duration_month).'
                    <br />(
                    '.config('app.currency').' '.
                    ($invoice->package_cost*$invoice->subscription_duration_month).' Months)
                </td>
            </tr>
            <tr>
                <td>Discount(-)</td>
                <td>'.$invoice->discount_percentage.'%</td>
            </tr>
            <tr>
                <td>CGST @ 9%(+)</td>
                <td>'.config('app.currency').' '.number_format($invoice->cgst_total, 2).'</td>
            </tr>
            <tr>
                <td>SGST @ 9%(+)</td>
                <td>'.config('app.currency').' '.number_format($invoice->sgst_total, 2).'</td>
            </tr>
            <tr>
                <td>Payable Amount</td>
                <td>'.config('app.currency').' '.number_format($invoice->invoice_amount, 2).'</td>
            </tr>
        </table>';

        return $return;
    }

    public static function getUserInvoiceDetailsTable($invoice) {
        $return = '<table class="table box-shadow table-striped">
            <tr>
                <td colspan="2" class="text-center">This invoice is ';
                    if ($invoice->status == 0) {
                        $return .= '<span class="pp-badge badge-warning p-1 pr-2 pl-2">Pending for approval</span>';
                    } else if ($invoice->status == 1) {
                        $return .= '<span class="pp-badge badge-success p-1 pr-2 pl-2">Active</span>';
                    } else if ($invoice->status == 2) {
                        $return .= '<span class="pp-badge badge-danger p-1 pr-2 pl-2">Rejected</span>';
                    } else if ($invoice->status == 3) {
                        $return .= '<span class="pp-badge badge-danger p-1 pr-2 pl-2">Expired</span>';
                    }
                
                $return .= '
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Subscription from <span class="pp-badge pp-badge-primary p-2">'.($invoice->subscription_duration_from > 0 ? date('d-M-Y', strtotime($invoice->subscription_duration_from)) : '-').'</span> to <span class="pp-badge pp-badge-primary p-2">'.($invoice->subscription_duration_to > 0 ? date('d-M-Y', strtotime($invoice->subscription_duration_to)) : '-').'</span>
                </td>
            </tr>
            <tr>
                <td width="50%">Package Name</td>
                <td width="50%">'.$invoice->membershipPackages->package_name.'</td>
            </tr>
            <tr>
                <td>Package cost</td>
                <td>
                    '.config('app.currency').' '.
                    ($invoice->package_cost * $invoice->subscription_duration_month).'
                    <br />(
                    '.config('app.currency').' '.
                    ($invoice->package_cost*$invoice->subscription_duration_month).' Months)
                </td>
            </tr>
            <tr>
                <td>Discount(-)</td>
                <td>'.$invoice->discount_percentage.'%</td>
            </tr>
            <tr>
                <td>CGST @ 9%(+)</td>
                <td>'.config('app.currency').' '.number_format($invoice->cgst_total, 2).'</td>
            </tr>
            <tr>
                <td>SGST @ 9%(+)</td>
                <td>'.config('app.currency').' '.number_format($invoice->sgst_total, 2).'</td>
            </tr>
            <tr>
                <td>Payable Amount</td>
                <td>'.config('app.currency').' '.number_format($invoice->invoice_amount, 2).'</td>
            </tr>
        </table>';

        return $return;
    }

    public static function getBuilderInvoiceDetailsTable($invoice) {
        $return = '<table class="table box-shadow table-striped">
            <tr>
                <td colspan="2" class="text-center">This invoice is ';
                    if ($invoice->status == 0) {
                        $return .= '<span class="pp-badge badge-warning p-1 pr-2 pl-2">Pending for approval</span>';
                    } else if ($invoice->status == 1) {
                        $return .= '<span class="pp-badge badge-success p-1 pr-2 pl-2">Active</span>';
                    } else if ($invoice->status == 2) {
                        $return .= '<span class="pp-badge badge-danger p-1 pr-2 pl-2">Rejected</span>';
                    } else if ($invoice->status == 3) {
                        $return .= '<span class="pp-badge badge-danger p-1 pr-2 pl-2">Expired</span>';
                    }
                
                $return .= '
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Subscription from <span class="pp-badge pp-badge-primary p-2">'.($invoice->subscription_duration_from > 0 ? date('d-M-Y', strtotime($invoice->subscription_duration_from)) : '-').'</span> to <span class="pp-badge pp-badge-primary p-2">'.($invoice->subscription_duration_to > 0 ? date('d-M-Y', strtotime($invoice->subscription_duration_to)) : '-').'</span>
                </td>
            </tr>
            <tr>
                <td width="50%">Package Name</td>
                <td width="50%">'.$invoice->membershipPackages->package_name.'</td>
            </tr>
            <tr>
                <td>Microsite</td>
                <td>'.($invoice->is_microsite == '1' ? 'Enabled' : 'Not enabled').'</td>
            </tr>
            <tr>
                <td>Package cost</td>
                <td>'.config('app.currency').' '.($invoice->package_cost * $invoice->subscription_duration_month).'
                <br />('.config('app.currency').' '.($invoice->package_cost.' * '.$invoice->subscription_duration_month).' Months)
                </td>
            </tr>
            <tr>
                <td>(-) Discount</td>
                <td>'.$invoice->discount_percentage.' %</td>
            </tr>
            <tr>
                <td>(+) CGST @ 9%</td>
                <td>'.config('app.currency').' '.number_format($invoice->cgst_total, 2).'</td>
            </tr>
            <tr>
                <td>(+) SGST @ 9%</td>
                <td>'.config('app.currency').' '.number_format($invoice->sgst_total, 2).'</td>
            </tr>
            <tr>
                <td>Payable Amount</td>
                <td>'.config('app.currency').' '.number_format($invoice->invoice_amount, 2).'</td>
            </tr>
        </table>';

        return $return;
    }

    public static function getBuilderInvoiceDetailsFront($invoice) {
        $return = '<table class="table box-shadow table-striped">
            <tr>
                <td colspan="2" class="text-center">This invoice is ';
                    if ($invoice->status == 0) {
                        $return .= '<span class="pp-badge badge-warning p-1 pr-2 pl-2">Pending for approval</span>';
                    } else if ($invoice->status == 1) {
                        $return .= '<span class="pp-badge badge-success p-1 pr-2 pl-2">Active</span>';
                    } else if ($invoice->status == 2) {
                        $return .= '<span class="pp-badge badge-danger p-1 pr-2 pl-2">Rejected</span>';
                    } else if ($invoice->status == 3) {
                        $return .= '<span class="pp-badge badge-danger p-1 pr-2 pl-2">Expired</span>';
                    }
                
                $return .= '
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;">
                    <p>Subscription</p>
                    <span class="pp-badge pp-badge-primary p-2">'.($invoice->subscription_duration_from > 0 ? date('d-M-Y', strtotime($invoice->subscription_duration_from)) : '-').'</span> to <span class="pp-badge pp-badge-primary p-2">'.($invoice->subscription_duration_to > 0 ? date('d-M-Y', strtotime($invoice->subscription_duration_to)) : '-').'</span>
                </td>
            </tr>
            <tr>
                <td width="50%">Package Name</td>
                <td width="50%">'.$invoice->membershipPackages->package_name.'</td>
            </tr>
            <tr>
                <td>Microsite</td>
                <td>'.($invoice->is_microsite == '1' ? 'Enabled' : 'Not enabled').'</td>
            </tr>
            <tr>
                <td>Package cost</td>
                <td>'.config('app.currency').' '.($invoice->package_cost * $invoice->subscription_duration_month).'
                <br />('.config('app.currency').' '.($invoice->package_cost.' * '.$invoice->subscription_duration_month).' Months)
                </td>
            </tr>
            <tr>
                <td>(-) Discount</td>
                <td>'.$invoice->discount_percentage.' %</td>
            </tr>
            <tr>
                <td>(+) CGST @ 9%</td>
                <td>'.config('app.currency').' '.number_format($invoice->cgst_total, 2).'</td>
            </tr>
            <tr>
                <td>(+) SGST @ 9%</td>
                <td>'.config('app.currency').' '.number_format($invoice->sgst_total, 2).'</td>
            </tr>
            <tr>
                <td>Payable Amount</td>
                <td>'.config('app.currency').' '.number_format($invoice->invoice_amount, 2).'</td>
            </tr>
        </table>';

        return $return;
    }

    public static function AdminGetSitePropertyTable($Site) {
        $return = '
        <div class="site-property">
            <div class="card">
                <div class="card-header align-items-center">
                    <h3 class="pull-left h5">Properties in '.$Site->site_name.'</h3>
                </div>
                <div class="card-body">';
                    if (isset($Site->properties) && count($Site->properties) > 0) {
                        $return .= '<table class="table table-striped table-hover table-responsive">
                        <thead>
                            <tr>
                                <th width="2%">#</th>
                                <th width="10%">for</th>
                                <th width="13%">Type</th>
                                <th width="30%">Property details</th>
                                <th width="15%">Price</th>
                                <th width="10%">Status</th>
                                <th width="20%" class="text-center">Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>';
                            $i = 1;
                             
                            foreach ($Site->properties as $property) {
                                $return .= '<tr>
                                    <td>'.$i.'</td>
                                    <td>';
                                        if (isset($property->sub_title)) {
                                                 
                                                $return .= $property->sub_title;
                                             
                                        }
                                    $return .= '</td>
                                    <td>';
                                        if (isset($property->propertyCategory->name)) {
                                            $return .= $property->propertyCategory->name;
                                        }
                                        if (isset($property)) {
                                            
                                            if (isset($property->propertySubCategory->name)) {
                                                $return .= ' <i class="fa fa-arrow-right"></i> '.$property->propertySubCategory->name;
                                            }
                                        }
                                    $return .= '</td>
                                    <td>';
                                    $return .= Helpers::cmsPropertyDetails($property);
                                     
                                    $return .= '</td>
                                    <td>'.config('app.currency') .' '. Helpers::getPrettyNumber($property->price, true).'<br />';
                                        if ($Site->price_status == 1) {
                                            $return .= '<div class="badge badge-primary">Price visible</div>';
                                        } elseif ($Site->price_status == 2) {
                                            $return .= '<div class="badge badge-success">Price negotiable</div>';
                                        } elseif ($Site->price_status == 3) {
                                            $return .= '<div class="badge badge-danger">Price hidden</div>';
                                        }
                                    $return .= '</td>
                                    <td>';
                                    $return .= Helpers::getPropertyStatus($property).'</td>';
                                    $return .= '<td class="text-center">';
                                    
                                        $return .= '<a href="'.route('properties.edit', $property->id).'" class="btn btn-sm btn-info"><span class="fas fa-pencil-alt"></span>&nbsp; Edit</a>&nbsp;&nbsp;';
                                    
                                    
                                        $return .= '<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal'.$property->id.'"><i class="fa fa-trash"></i></button>

                                          <div id="myModal'.$property->id.'" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                              <div class="modal-content">
                                                  <div class="modal-header">
                                                      <h3 class="modal-title">Warning</h3>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                    <div class="modal-body">
                                                      Are you sure you want to delete this property varient ?
                                                    </div>
                                                    <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                                                  $return .= \Form::open(["method" => "DELETE", "route" => ["properties.destroy", $property->id], "style"=>"display:inline"]);
                                                  $return .= \Form::submit("Delete", ["class" => "btn btn-danger"]);
                                                  $return .= \Form::close();
                                                $return .= '</div>
                                              </div>
                                            </div>
                                          </div>';
                                    
                                    $return .= '</td>'; 
                                $i++;
                            }
                        $return .= '</tbody>
                    </table>';
                    }
                    
                     
                        $return .= '<div class="text-center mb-3"><a href="'.route('properties.create', ['site_id' => $Site->id, 'company_id' => $Site->company_id]).'" class="btn btn-sm btn-success"><span class="fa fa-plus"></span> Add Property to '.$Site->site_name.'</a></div>';
                    
                $return .= '</div>
            </div>
        </div>';

        return $return;
    }

    public static function getFrontMenuSelected($route, $page) {
        $arrRoute = explode('.', $route);
        if (is_array($page)) {
            return in_array($arrRoute[0], $page) ? 'selected' : '';
        } else {
            return ($arrRoute[0] === $page) ? 'selected' : '';
        }
    }

    public static function __date($date) {
        return date('d-M-Y h:i:s A', strtotime($date));
    }

    public static function getRandomChars($length = 8) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public static function getAgentDetails() {

        $agent              = new \Jenssegers\Agent\Agent();
        $data['platform']   = $agent->platform();
        $data['browser']    = $agent->browser();
        if ($agent->isDesktop()) {
            $data['device_type'] = $agent->device().' - Desktop / Laptop';
        } else if ($agent->isPhone()) {
            if ($agent->isMobile()) {
                $data['device_type'] = 'Mobile - '.$agent->device();
            } else if ($agent->isTablet()) {
                $data['device_type'] = 'Tablet - '.$agent->device();
            }
        } else if ($agent->isRobot()) {
            $data['device_type'] = $agent->robot();
        } else {
            $data['device_type'] = '';
        }
        
        $data['platform']   = $agent->device() . ' ' . $data['platform'] . ' ' . $agent->version($data['platform']);
        $data['browser']    = $data['browser'] . ' ' . $agent->version($data['browser']);

        return $data;
    }

    public static function getTodayLeads(){
        $query = \App\Models\PortalRegistration::select(['id', 'lead_type', 'name', 'phone', 'email', 'budget_from', 'budget_to', 'is_verified', 'created_at']);

        // city wise access where clause
        if (self::getUserCityAccess() !== false) {
            $query = $query->where(function($query) {
                
                $query = $query->whereIn('id', 
                    \App\Models\PortalRegistrationCities::whereIn('city_id', 
                        self::getUserCityAccess()
                    )->pluck('lead_id')
                );

                $query = $query->orWhereIn('id', 
                    \App\Models\PortalRegistrationAreas::whereIn('city_id', 
                        self::getUserCityAccess()
                    )->pluck('lead_id')
                );
            });
        }else{
            if(defined('CURRENT_CITY') && !empty(CURRENT_CITY)){
                $query = $query->where('city_id',CURRENT_CITY);
            }
        }
        
        // get today's lead only
        $query = $query->whereBetween('CREATED_AT', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')]);
        
        return $query->orderBy('id', 'desc')->get();
    }

    public static function getPendingVerifications(){
        $query = \App\Models\Sites::where('status',4)->where('property_type',1);
        if(defined('CURRENT_CITY') && !empty(CURRENT_CITY)){
            $query = $query->where('city_id',CURRENT_CITY);
        }
        $query = $query->get();
        return $query;
    }

    public static function getPendingBuilderInvoices(){
        return \App\Models\SitePayments::where([['payment_type','=',0],['status','=',0]])->get();
    }

    public static function getPendingAgentInvoices(){
        return \App\Models\AgentPayments::where([['status','=',0]])->get();
    }

    public static function getThisWeekRenewals($page=0){
        if(defined('CURRENT_CITY') && !empty(CURRENT_CITY)){
            $query = \App\Models\SitePayments::with(['sites','sites.users'])->where('status',1)->where('site_city_id',CURRENT_CITY)->whereRaw('YEARWEEK(date(`subscription_duration_to`), 1) = YEARWEEK("'.date('Y-m-d').'", 1) and MONTH(subscription_duration_to) = "'.date('m').'" ');
            if($page > 0){
                $query = $query->paginate($page);
            }else{
                $query = $query->get();
            }
            return $query;
        }else{
            $query = \App\Models\SitePayments::with(['sites','sites.users'])->where('status',1)->whereRaw('YEARWEEK(date(`subscription_duration_to`), 1) = YEARWEEK("'.date('Y-m-d').'", 1) and MONTH(subscription_duration_to) = "'.date('m').'" ');
            if($page > 0){
                $query = $query->paginate($page);
            }else{
                $query = $query->get();
            }
            return $query;
        }
    }


    public static function getDirectLeadCounter(){
        $direct_leads_dates = \App\Models\UserNotifications::getUserNotification('direct_leads_checked_on');
        if(!$direct_leads_dates){
            $leads = \App\Models\Leads::getFrontUserLeadList();
            return $leads->count();
        }else{
            $leads = \App\Models\Leads::getFrontUserLeadList();
            return $leads->where('created_at','>=',$direct_leads_dates->direct_leads_checked_on)->count();
        }
    }

    public static function getBrokerPremiumLeadCounter(){
        $broker_premium_lead_dates = \App\Models\UserNotifications::getUserNotification('broker_premium_leads_checked_on');        
        $premium_leads = \App\Models\PortalRegistration::brokerPremiumLeads();
        if(!$broker_premium_lead_dates){
            return $premium_leads->count();
        }else{
            return $premium_leads->where('created_at','>=',$broker_premium_lead_dates->broker_premium_leads_checked_on)->count();
        }
    }

    public static function getUserPremiumLeadCounter(){
        $user_premium_lead_dates = \App\Models\UserNotifications::getUserNotification('user_premium_leads_checked_on');        
        $premium_leads = \App\Models\PortalRegistration::userPremiumLeads();
        if(!$user_premium_lead_dates){
            return $premium_leads->count();
        }else{
            return $premium_leads->where('created_at','>=',$user_premium_lead_dates->user_premium_leads_checked_on)->count();
        }
    }

    public static function getSiteOfferExpiryCounter(){
        if(!empty(\Session::get('active-site'))){
            $siteoffers = \App\Models\SiteOffers::where('site_id',\Session::get('active-site'))->where('is_verified','1')->whereBetween('end_date',[date('Y-m-d H:i:s'),date('Y-m-d H:i:s',strtotime('+15 Days'))])->get();  
            return $siteoffers->count(); 
        }else{
            $siteoffers = \App\Models\SiteOffers::whereIn('site_id',\App\Models\Sites::where('user_id', \Helpers::getLoginUserId())->get()->pluck('id'))->where('is_verified',1)->whereBetween('end_date',[date('Y-m-d H:i:s'),date('Y-m-d H:i:s',strtotime('+15 Days'))])->get();
            return $siteoffers->count();
        }        
    }

    public static function getAgentShareExpiryCounter(){
        if(!empty(\Session::get('active-site'))){
            $agentShares = \App\Models\AgentShares::where('site_id',\Session::get('active-site'))->where('is_verified','1')->whereBetween('end_date',[date('Y-m-d H:i:s'),date('Y-m-d H:i:s',strtotime('+15 Days'))])->get();  
            return $agentShares->count(); 
        }else{
            $agentShares = \App\Models\AgentShares::whereIn('site_id',\App\Models\Sites::where('user_id', \Helpers::getLoginUserId())->get()->pluck('id'))->where('is_verified',1)->whereBetween('end_date',[date('Y-m-d H:i:s'),date('Y-m-d H:i:s',strtotime('+15 Days'))])->get();
            return $agentShares->count();
        }        
    }

    public static function getPackageExpiryCounter(){

        $userType = self::getLoginUserType();

        if(isset($userType) && $userType == 1)
        {
            $packageExpiry = \App\Models\UserPayments::where('user_id',\Helpers::getLoginUserId())->where('status',1)->whereBetween('subscription_duration_to',[date('Y-m-d H:i:s'),date('Y-m-d H:i:s',strtotime('+15 Days'))])->get();
                return $packageExpiry->count();
        }   

        if(isset($userType) && $userType == 2)
        {
            if(!empty(\Session::get('active-site'))){
            $packageExpiry = \App\Models\SitePayments::where('site_id',\Session::get('active-site'))->where('status','1')->whereBetween('subscription_duration_to',[date('Y-m-d H:i:s'),date('Y-m-d H:i:s',strtotime('+15 Days'))])->get();  
            return $packageExpiry->count(); 
            }else{
                $packageExpiry = \App\Models\SitePayments::whereIn('site_id',\App\Models\Sites::where('user_id', \Helpers::getLoginUserId())->get()->pluck('id'))->where('status',1)->whereBetween('subscription_duration_to',[date('Y-m-d H:i:s'),date('Y-m-d H:i:s',strtotime('+15 Days'))])->get();
                return $packageExpiry->count();
            }
        }

        if(isset($userType) && $userType == 3)
        {
            $packageExpiry = \App\Models\AgentPayments::where('user_id',\Helpers::getLoginUserId())->where('status',1)->whereBetween('subscription_duration_to',[date('Y-m-d H:i:s'),date('Y-m-d H:i:s',strtotime('+15 Days'))])->get();
                return $packageExpiry->count();
        }
    }

    public static function getPropertyPossessionCounter()
    {
        $propertyPossessionData = \App\Models\UserNotifications::getUserNotification('property_possession_checked_on');
        
        $dataArray = [];

        if(isset($propertyPossessionData) && !empty($propertyPossessionData))
        {
            $dataArray = [
                'property_possession_date' => date('Y-m-d', strtotime('15 day')),
                'property_possession_checked_on' => $propertyPossessionData->property_possession_checked_on
            ];
        }
        else
        {
            $dataArray = [
                'property_possession_date' => date('Y-m-d', strtotime('15 day'))
            ];
        }

        $propertyData = \App\Models\Sites::frontMyProperties(10,$dataArray,false,true);
        
        return $propertyData->total();
    }

    public static function getPropertyShareCounter()
    {
       $propertyShareData = \App\Models\UserNotifications::getUserNotification('property_share_checked_on');
        
        $dataArray = [];

        if(isset($propertyShareData) && !empty($propertyShareData))
        {
            $dataArray = [
                'property_share_checked_on' => $propertyShareData->property_share_checked_on
            ];
        }
        
        // get agent payment details
        $PaymentDetails = Helpers::getPaymentDetails();

        // agent property shares details
        $propertyShares = AgentShares::getFrontPropertyShares($PaymentDetails,15,$dataArray);
        
        if(isset($propertyShares) && !empty($propertyShares))
        {
            return $propertyShares->total();
        }
        return 0;
    } 

    public static function getPropertyLeadCounter()
    {
        $propertyLeadCount = \App\Models\Leads::whereIn('id',\App\Models\LeadsProperty::whereIn('site_id',\App\Models\Sites::where('user_id', self::getLoginUserId())->pluck('id'))->pluck('lead_id'));

        $propertyLeadData = \App\Models\UserNotifications::getUserNotification('direct_leads_checked_on');
        
        if(isset($propertyLeadData) && !empty($propertyLeadData) && isset($propertyLeadData->direct_leads_checked_on) && !empty($propertyLeadData->direct_leads_checked_on))
        {
            $propertyLeadCount = $propertyLeadCount->where('created_at','>=',$propertyLeadData->direct_leads_checked_on);
        }
       
        return $propertyLeadCount->count();
    }

    // Get site visit enquiry count for notification 
    public static function getSiteVisitCounter()
    {
        //$siteVisitCount = \App\Models\Leads::whereIn('id',\App\Models\LeadsProperty::whereIn('site_id',\App\Models\Sites::where('user_id', self::getLoginUserId())->pluck('id'))->pluck('lead_id'));

        $userSites = Sites::where([['status','=','1'],['is_soldout','=','0'],['is_suspended','=','0'],['user_id','=',self::getLoginUserId()]])->pluck('id');

        $siteVisitCheckedOn = \App\Models\UserNotifications::getUserNotification('site_visit_checked_on');
        
        if(isset($siteVisitCheckedOn->site_visit_checked_on) && !empty($siteVisitCheckedOn->site_visit_checked_on) && isset($userSites) && count($userSites) > 0)
        {
            $siteVisitCount = SiteVisitEnquiry::where('created_at','>=',$siteVisitCheckedOn->site_visit_checked_on)->whereIn('site_id',$userSites)->count();

            return $siteVisitCount;
        }
       
        return 0;
    }

    // Get new broker counter for notification
    public static function getNewBrokerCounter()
    {
        $newBrokerLastChecked = UserNotifications::getUserNotification('new_broker_checked_on');

        $newBrokerCount = 0;
        if(isset($newBrokerLastChecked->new_broker_checked_on) && !empty($newBrokerLastChecked->new_broker_checked_on))
        {
            $newBrokerCount = Users::where([['user_type','=','3'],['status','=','1']])->where('created_at','>=',$newBrokerLastChecked->new_broker_checked_on)->count();
        }

        return $newBrokerCount;
    }

    public static function getLoginUserType() {
        $userDetails = \App\Models\Users::select('id', 'user_type')
                            ->where('id', self::getLoginUserId())
                            ->first();

        return $userDetails->user_type;
    }

    public static function getUserPackageExpiryNotification()
    {
        if(!self::getLoginUserType())
        {
            return false;
        }

        $userPackageExpiryChecked = \App\Models\UserNotifications::getUserNotification('user_package_expiry_checked_on');

        $userType = self::getLoginUserType();
        
        if($userType == 1){

            $activePackage = \App\Models\UserPayments::where([['user_id','=',\Helpers::getLoginUserId()],['status','=',1],['subscription_duration_to','=',date('Y-m-d', strtotime('15 day'))]])->with('membershipPackages');

            if(isset($userPackageExpiryChecked->user_package_expiry_checked_on) && !empty($userPackageExpiryChecked->user_package_expiry_checked_on))
            {
                $activePackage = $activePackage->where('created_at','>=',$userPackageExpiryChecked->user_package_expiry_checked_on);
            }

            $activePackage = $activePackage->first();

            return $activePackage;
        
        }elseif($userType == 2 || $userType == 4){            

            $activePackage = \App\Models\SitePayments::where([['site_id','=',self::loginUserSite()],['status','=',1],['subscription_duration_to','=',date('Y-m-d', strtotime('15 day'))]])->get();
            
            if(isset($userPackageExpiryChecked->user_package_expiry_checked_on) && !empty($userPackageExpiryChecked->user_package_expiry_checked_on))
            {
                $activePackage = $activePackage->where('created_at','>=',$userPackageExpiryChecked->user_package_expiry_checked_on);
            }

            $activePackage = $activePackage->first();

            return $activePackage;
        
        }elseif($userType == 3){

            $activePackage = \App\Models\AgentPayments::where([['user_id','=',\Helpers::getLoginUserId()],['status','=',1],['subscription_duration_to','=',date('Y-m-d', strtotime('15 day'))]])->with('membershipPackages');

            if(isset($userPackageExpiryChecked->user_package_expiry_checked_on) && !empty($userPackageExpiryChecked->user_package_expiry_checked_on))
            {
               $activePackage = $activePackage->where('created_at','>=',$userPackageExpiryChecked->user_package_expiry_checked_on);
            }

            $activePackage = $activePackage->first();

            return $activePackage;
        }
        return false;
    }

    // get news offers count
    public static function getNewsOffersCount()
    {
        $newsOfferLastChecked = \App\Models\UserNotifications::getUserNotification('news_offer_checked_on');
        $newsOfferLastChecked = isset($newsOfferLastChecked->news_offer_checked_on) && !empty($newsOfferLastChecked->news_offer_checked_on) ? $newsOfferLastChecked->news_offer_checked_on : '';

        $newsOffers = \App\Models\Admin\NewsOffersNotification::WhereIn('news_offer_for',[self::getLoginUserType(),5]);
        
        if(isset($newsOfferLastChecked) && !empty($newsOfferLastChecked))
        {
            $newsOffers = $newsOffers->where('created_at','>=',$newsOfferLastChecked);
        }
        
        $newsOfferCount = $newsOffers->orderBy('id','DESC')->get();    

        return $newsOfferCount->count();
    }

    // get news offers
    public static function getNewsOffers()
    {
        // $newsOfferLastChecked = \App\Models\UserNotifications::getUserNotification('news_offer_checked_on');
        // $newsOfferLastChecked = isset($newsOfferLastChecked->news_offer_checked_on) && !empty($newsOfferLastChecked->news_offer_checked_on) ? $newsOfferLastChecked->news_offer_checked_on : '';

        $newsOffers = \App\Models\Admin\NewsOffersNotification::WhereIn('news_offer_for',[self::getLoginUserType(),5])->where('end_date','>=',date('Y-m-d H:i:s'));
        
        // if(isset($newsOfferLastChecked) && !empty($newsOfferLastChecked))
        // {
        //     $newsOffers = $newsOffers->where('created_at','>=',$newsOfferLastChecked);
        // }
        
        $newsOffers = $newsOffers->orderBy('id','DESC')->paginate(5);    

        return $newsOffers;
    }

    public static function loginUserSite(){
        $site_id = 0;
        // Get front web site user data
        if(empty(\Session::get('active-site'))){
            $site = \App\Models\Sites::where('user_id',\Helpers::getLoginUserId())->first();
            if($site){
                $site_id = $site->id;
            }
        }else{
            $site = \App\Models\Sites::where('id',\Session::get('active-site'))->first();
            if($site){
                $site_id = $site->id;
            }
        }

        return $site_id;
    }

    // Get road approach details
    public static function getRoadApproachDetails($data)
    {
        $roadApproachData = [];
        $areaUnit = self::getStaticValues('area_unit');
        $c=0;
        if (isset($data->propertyFeatures->road_approach) && $data->propertyFeatures->road_approach != '') 
        {
            if($data->propertyFeatures->road_approach == 'road_touch')
            {
                $roadApproach = 'Road Touch';
            }
            elseif($data->propertyFeatures->road_approach == 'interior')
            {
                $roadApproach = 'Interior';
            }
            elseif ($data->propertyFeatures->road_approach == 'corner_plot') {
                $roadApproach = 'Corner Plot';
            }

            $roadApproachData[$c]['title'] = 'Road Approach';
            $roadApproachData[$c]['value'] = $roadApproach;
            $c++;
        }

        if (isset($data->propertyFeatures->road1_width) && $data->propertyFeatures->road1_width != '') {
            $roadApproachData[$c]['title'] = 'Road 1 Width';
            $roadApproachData[$c]['value'] = $data->propertyFeatures->road1_width.' '.$areaUnit[$data->propertyFeatures->road1_width_unit];
            $c++;
        }

        if (isset($data->propertyFeatures->road2_width) && $data->propertyFeatures->road2_width != '') {
            $roadApproachData[$c]['title'] = 'Road 2 Width';
            $roadApproachData[$c]['value'] = $data->propertyFeatures->road2_width.' '.$areaUnit[$data->propertyFeatures->road2_width_unit];
            $c++;
        }

        if (isset($data->propertyFeatures->road_width) && $data->propertyFeatures->road_width != '') {
            $roadApproachData[$c]['title'] = 'Road Width';
            $roadApproachData[$c]['value'] = $data->propertyFeatures->road_width.' '.$areaUnit[$data->propertyFeatures->road_width_unit];
            $c++;
        }
        return $roadApproachData;
    }

    // Get resale price details
    public static function getResalePriceDetails($data)
    {
        $priceData = [];
        $i=0;

        if(isset($data->price) && !empty($data->price))
        {
            $priceData[$i]['title'] = 'Price';
            $priceData[$i]['value'] = (string)self::getPrettyNumber($data->price);
            $i++;
        }

        if(isset($data->booking_amount) && !empty($data->booking_amount))
        {
            $priceData[$i]['title'] = 'Token';
            $priceData[$i]['value'] = (string)self::getPrettyNumber($data->booking_amount);
            $i++;
        }

        if(isset($data->propertyFeatures->price_sq_ft) && !empty($data->propertyFeatures->price_sq_ft))
        {
            $priceData[$i]['title'] = isset($data->propertyFeatures->area_covered_unit) && !empty($data->propertyFeatures->area_covered_unit) ? 'Price Per '.strtoupper($data->propertyFeatures->area_covered_unit) : 'Price Per Sq. Ft.';
            $priceData[$i]['value'] = (string)self::getPrettyNumber($data->propertyFeatures->price_sq_ft).'/'.strtoupper($data->propertyFeatures->area_covered_unit);
            $i++;
        }

        if(isset($data->propertyFeatures->total_price) && !empty($data->propertyFeatures->total_price))
        {
            $priceData[$i]['title'] = 'Total Price';
            $priceData[$i]['value'] = (string)self::getPrettyNumber($data->propertyFeatures->total_price);
            $i++;
        }

        if(isset($data->propertyFeatures->payment_terms) && !empty($data->propertyFeatures->payment_terms))
        {
            $priceData[$i]['title'] = 'Payment Terms';
            $priceData[$i]['value'] = (string)$data->propertyFeatures->payment_terms;
            $i++;
        }

        if(isset($data->maintenance) && !empty($data->maintenance))
        {
            $priceData[$i]['title'] = 'Maintenance';
            $priceData[$i]['value'] = (string)self::getPrettyNumber($data->maintenance);
            $i++;
        }

        return $priceData;
    }

    // Get property code for suggestions
    public static function getPropertyCodeSuggestions($userId=null)
    {
        if(isset($userId) && !empty($userId))
        {
            $propertiesCode = Properties::where('code','!=',null)->whereIn('site_id',Sites::where('user_id',$userId)->pluck('id'))->pluck('code');

            if(isset($propertiesCode) && $propertiesCode->count() > 0)
            {
                return $propertiesCode;
            }
            return false;  
        }
        else
        {
           $propertiesCode = Properties::where('code','!=',null)->pluck('code');

            if(isset($propertiesCode) && $propertiesCode->count() > 0)
            {
                return $propertiesCode;
            } 
            return false;
        }
    }

    // Get enquiry code for suggestions
    public static function getEnquiryCodeSuggestions($userId)
    {
        if(isset($userId) && !empty($userId))
        {
            $enquiriesCode = Enquiries::where([['code','!=',null],['user_id','=',$userId]])->pluck('code');

            if(isset($enquiriesCode) && $enquiriesCode->count() > 0)
            {
                return $enquiriesCode;
            }
            return false; 
        }
    }

    // Get mascot broker details
    public static function getMascotDetails()
    {
        $mascotDetails = User::find(2214);
        return $mascotDetails;
    }

    /* Get fcm object */
    public static function notify(){
        return new \App\Libraries\Fcm;
    }

    public static function addOrdinalNumberSuffix($num) {
        if (!in_array(($num % 100),array(11,12,13))){
          switch ($num % 10) {
            // Handle 1st, 2nd, 3rd
            case 1:  return $num.'st';
            case 2:  return $num.'nd';
            case 3:  return $num.'rd';
          }
        }
        return $num.'th';
    }

    public static function getBankDetails($bankid)
    {
        $banks = Banks::where('id',$bankid)->first();
        return $banks;
    }


    

}
