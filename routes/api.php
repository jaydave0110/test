<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
//http://localhost/demo/public/api/hello
Route::get('/hello',function(){

	dd('hello dear');
});
/* Get Listing Filters */
 

Route::get('listing-properties', 'SitesController@listProperties');
Route::post('filter-listing-properties', 'SitesController@filterListProperties');
Route::post('properties-details', 'SitesController@propertiesDetails');


// Get property  category
Route::get('getPropertyCategory', 'SitesController@getPropertyCategory');

// Get property sub category
Route::post('getPropertySubCategory', 'SitesController@getPropertySubCategory');

// Get states
Route::post('getStates', 'SitesController@getStates');

// Get cities
Route::post('getCities', 'SitesController@getCities');

// Get areas
Route::post('getAreas', 'SitesController@getAreas');
Route::post('getSiteNames', 'SitesController@getSiteNames');

Route::post('registerBroker','BrokerController@registerBroker');
Route::post('login','BrokerController@login');
Route::post('updateProfile','BrokerController@updateProfile');
Route::post('homepage','HomePageController@homepage');
Route::post('createWishList', 'WishListController@store');
Route::post('removeWishList', 'WishListController@destroy');

Route::post('viewWishList', 'WishListController@index');

Route::post('brokerdashboard', 'BrokerController@brokerdashboard');
Route::post('myPendingBooking', 'BrokerController@myPendingBooking');
Route::post('myConfirmedBooking', 'BrokerController@myConfirmedBooking');
Route::post('myPercentage', 'BrokerController@myPercentage');
Route::post('myCollectionIndividual', 'BrokerController@myCollectionIndividual');
Route::post('myCollectionPackage', 'BrokerController@myCollectionPackage');

Route::post('pendingDetails', 'BrokerController@pendingDetails');
Route::post('confirmedDetails', 'BrokerController@confirmedDetails');
Route::post('percentageDetails', 'BrokerController@percentageDetails');
Route::post('individualDetails', 'BrokerController@individualDetails');
Route::post('collectionPackageDetail', 'BrokerController@collectionPackageDetail');


Route::get('salesHeadDashboard','BrokerController@salesHeadDashboard');
Route::get('getSalesHeadTotalBroker','BrokerController@getSalesHeadTotalBroker');
Route::get('getSaleBookingDetail','BrokerController@getSaleBookingDetail');
Route::get('getSalesHeadTotalBookings','BrokerController@getSalesHeadTotalBookings');
Route::get('getSalesHeadCommissionDetail','BrokerController@getSalesHeadCommissionDetail');


Route::get('cityHeadDashboard','BrokerController@cityHeadDashboard');
Route::get('listTotalCitySalesHead','BrokerController@listTotalCitySalesHead');
Route::get('listTotalCitySalesBroker','BrokerController@listTotalCitySalesBroker');



Route::get('listTotalCitySalesBookings','BrokerController@listTotalCitySalesBookings');
Route::get('cityheadcommissiondetail','BrokerController@cityheadcommissiondetail');
Route::get('citySalesBookingsDetails','BrokerController@citySalesBookingsDetails');



Route::get('listTotalCitySalesCommission','BrokerController@listTotalCitySalesCommission');









Route::get('companyRepresentDashboard','BrokerController@companyRepresentDashboard');
Route::get('companytotalSalesHead','BrokerController@companytotalSalesHead');
Route::get('companytotalBroker','BrokerController@companytotalBroker');
Route::get('companyTotalMyBookings','BrokerController@companyTotalMyBookings');
Route::get('companyMyBookingsDetails','BrokerController@companyMyBookingsDetails');
Route::get('companyBrokerBookings','BrokerController@companyBrokerBookings');
Route::get('companyMyBrokerDetails','BrokerController@companyMyBrokerDetails');

Route::post('inquiry','BrokerController@inquiry');




});

// forget password
Route::post('forget', 'App\Http\Controllers\Auth\ForgotPasswordController@getResetToken');

//reset password
Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset');

//user verification
Route::get('email/verify/{token}', 'App\Http\Controllers\Auth\VerificationController@verify');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
