<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


Route::get('/', function () {
    //return redirect()->route('login');
	if(Auth()->user()!=''){
		 if (Auth()->user()->hasAnyRole(['superadmin', 'admin'])) {
           
            return redirect('home');
        }
	} else {
	    return view('auth.login');
	}

});
Auth::routes();
/*Route::get('/register', function () {
	return redirect()->route('login');
});
*/
Route::group(['middleware' => ['auth'],'namespace' => 'App\Http\Controllers'], function() {

   	Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::post('/users/updatestatus','UserController@updateStatus')->name('users.updateStatus');

    Route::post('/sites/changeSiteStatus','SitesController@changeSiteStatus')->name('sites.changeSiteStatus');
    
    Route::resource('sites','SitesController');
    
    Route::get('deleteBuilderImage/{id}', 'SitesController@deleteSiteSingleImage');

    /*** companyrepresentative **/
    Route::post('getRepresentativeList','CompanyRepresentativeController@getRepresentativeList')->name('getRepresentativeList');
    /**** company Representative **/
    Route::get('companysalesHead/{id}/totalsaleshead', 'CompanyRepresentativeController@companyTotalSalesHead')->name('companysalesHead.show');

    Route::get('companyTotalBrokers/{id}/totalbrokers', 'CompanyRepresentativeController@companyTotalBrokers')->name('companyTotalBrokers.show');

    Route::get('companyTotalBookings/{id}/totalbookings', 'CompanyRepresentativeController@companyTotalBookings')->name('companyTotalBookings.show');

    Route::get('companyTotalBrokerBookings/{id}/totalbrokerbookings', 'CompanyRepresentativeController@companyTotalBrokerBookings')->name('companyTotalBrokerBookings.show');
    
    
    Route::resource('companyrepresentative','CompanyRepresentativeController');

    /**** city head */

    Route::get('citysalesHead/{id}/totalsaleshead', 'CityHeadController@citysalesHead')->name('citysalesHead.show');

    Route::get('cityTotalBrokers/{id}/totalbrokers', 'CityHeadController@cityTotalBrokers')->name('cityTotalBrokers.show');

    Route::get('cityTotalBookings/{id}/totalbookings', 'CityHeadController@cityTotalBookings')->name('cityTotalBookings.show');

    Route::get('cityTotalCommission/{id}/totalcommission', 'CityHeadController@cityTotalCommission')->name('cityTotalCommission.show');


    Route::resource('cityhead','CityHeadController');

    /****sales head **/

    Route::get('salesheadtotalbroker/{id}/totalbroker', 'SalesHeadController@salesheadTotalBroker')->name('salesheadtotalbroker.show');

     Route::get('salesheadtotalbooking/{id}/totalbooking', 'SalesHeadController@salesheadTotalBooking')->name('salesheadtotalbooking.show');

    Route::get('salesheadtotalcommission/{id}/totalcommission', 'SalesHeadController@salesheadTotalCommission')->name('salesheadtotalcommission.show'); 

    




    Route::resource('saleshead','SalesHeadController');
    
    /** brokers**/
    Route::get('brokerPending/{id}/pending', 'BrokersController@brokerPending')->name('brokerPending.show');
    Route::get('brokerConfirm/{id}/confirm', 'BrokersController@brokerConfirm')->name('brokerConfirm.show');
    Route::get('brokerPercentage/{id}/percentage', 'BrokersController@brokerPercentage')->name('brokerPercentage.show');
    Route::get('brokerIndividual/{id}/individual', 'BrokersController@brokerIndividual')->name('brokerIndividual.show');
    Route::get('brokerPackage/{id}/package', 'BrokersController@brokerPackage')->name('brokerPackage.show');






    Route::get('brokers','BrokersController@index')->name('brokers.index');
    Route::get('brokers/create','BrokersController@create')->name('brokers.create');
    Route::post('brokers/store','BrokersController@store')->name('brokers.store');
    Route::get('brokers/{id}/edit','BrokersController@edit')->name('brokers.edit');
    Route::patch('brokers/update/{id}','BrokersController@update')->name('brokers.update');


    Route::get('brokers/{id}','BrokersController@show')->name('brokers.show');
    Route::get('brokers/assign/{id}','BrokersController@assignBroker')->name('brokers.assign');
    Route::post('brokers/storeassign','BrokersController@storeassign')->name('broker.storeassign');
    


    Route::get('sites/search/list', 'SitesController@search')->name('sites.search');
    Route::get('getPropertyType', 'SitesController@getPropertyType')->name('getPropertyType');
    Route::get('getAreas', 'SitesController@getAreas')->name('getAreas');
    Route::get('getCities', 'SitesController@getCities')->name('getCities');
    Route::post('siteQuickImageUpload/', 'SitesController@quickUpload')->name('siteQuickImageUpload');
    Route::post('siteChangeImageType', 'SitesController@changeImageType');
    Route::get('/updatesitecover/{imageid}/{siteid}', 'SitesController@updateSiteCover')->name('updatesitecover');
    /* ajax - start */
    Route::post('tempImageUpload', 'TempImageUploadController@upload')->name('tempImageUpload');
    Route::post('tempChangeImageType', 'TempImageUploadController@changeImageType')->name('tempChangeImageType');
    Route::post('tempRemoveImg', 'TempImageUploadController@tempRemoveImg')->name('tempRemoveImg');
    Route::post('tempUpdatesitecover', 'TempImageUploadController@tempUpdatesitecover')->name('tempUpdatesitecover');
    
    Route::post('getPropertiesOfSites', 'SitesController@getPropertiesOfSites')->name('getPropertiesOfSites');

    Route::post('getOffersOfProperty', 'SitesController@getOffersOfProperty')->name('getOffersOfProperty');

    
    
    Route::post('getBrokerList', 'BookingsController@getBrokerList')->name('getBrokerList');

    
    

    
        /* ajax - end */

       
     Route::post('siteChangeImageType', 'SitesController@changeImageType')->name('siteChangeImageType');    
    Route::get('siteoffers/{id}','SiteOffersController@index')->name('siteoffers.index'); 
    Route::get('siteoffers/{id}/view','SiteOffersController@show')->name('siteoffers.view'); 
    Route::post('siteoffers/store','SiteOffersController@store')->name('siteoffers.store'); 
    Route::patch('siteoffers/update/{id}','SiteOffersController@update')->name('siteoffers.update'); 

    Route::get('siteoffers/{id}/edit','SiteOffersController@edit')->name('siteoffers.edit');
    Route::get('/siteoffers/delete/{id}', 'SiteOffersController@destroy')
     ->name('siteoffers.destroy');
     
    //Route::resource('siteoffers', 'SiteOffersController');
    Route::resource('properties', 'PropertiesController');
    Route::get('deletePropertyImage/{id}', 'PropertiesController@deletePropertySingleImage')
            ->name('deletePropertyImage');

             


    /***** Properties Offers ***/        
    /*Route::get('propertiesoffers/{id}','PropertiesOffersController@index')->name('propertiesoffers.index');  
    Route::get('propertiesoffers/{id}/view','PropertiesOffersController@show')->name('propertiesoffers.view');
    Route::post('propertiesoffers/store','PropertiesOffersController@store')->name('propertiesoffers.store'); 
    Route::patch('propertiesoffers/update/{id}','PropertiesOffersController@update')->name('propertiesoffers.update');
     Route::get('propertiesoffers/{id}/edit','PropertiesOffersController@edit')->name('propertiesoffers.edit');
    Route::get('/propertiesoffers/delete/{id}', 'PropertiesOffersController@destroy')
     ->name('propertiesoffers.destroy');*/
     /*---Property Offer Ends----*/

    Route::resource('bookings', 'BookingsController');  

     
    Route::get('bookings/payment/{id}','BookingsController@payments')
            ->name('bookings.payments');
    Route::post('bookings/storetotalamount','BookingsController@storetotalamount')
            ->name('storetotalamount.store');
    Route::post('bookings/storedirectamount','BookingsController@storedirectamount')
            ->name('storedirectamount.store'); 
    Route::post('bookings/storecashamount','BookingsController@storecashamount')
            ->name('storecashamount.store');
    Route::post('bookings/storeloanamount','BookingsController@storeloanamount')
            ->name('storeloanamount.store');
    Route::get('directpaymentinfo/{bookingid}/{id}', 'BookingsController@directpaymentinfo')
            ->name('directpaymentinfo');
    Route::get('cashpaymentinfo/{bookingid}/{id}', 'BookingsController@cashpaymentinfo')
            ->name('cashpaymentinfo');
    Route::get('/deletedirectpayment/{id}', 'BookingsController@deletedirectpayment')
            ->name('deletedirectpayment');
    Route::get('/deletecashpayment/{id}', 'BookingsController@deletecashpayment')
            ->name('deletecashpayment');
    Route::resource('inquiry','InquiryController');

});


    Route::get('propertydetails/{category}/{seourl}/{id}', 'PropertyDetailsController@index')
            ->name('details');
    Route::get('p/{id}', 'PropertyDetailsController@shortUrl')->name('detailshorturl');
    /* site offer popup from search page */
    Route::post('getSiteOffers', 'SiteOffersController@getSiteOffers')->name('getSiteOffers');


