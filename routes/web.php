<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* ================================Web Routes========================  */

Route::get('/', 'PagesController@index')->name('homepage');
Route::post('/homemodalmail', 'PagesController@homemodalmail')->name('homemodalmail');
Route::post('/bdaymodalmail', 'PagesController@bdaymodalmail')->name('bdaymodalmail');

Route::post('/additemstosinglecart', 'PagesController@additemstosinglecart')->name('additemstosinglecart');
Route::post('/additemstosinglecartpackage', 'PagesController@additemstosinglecartpackage')->name('website.additemstosinglecartpackage');
Route::post('/changepackage', 'PagesController@changepackage')->name('website.changepackage');
Route::post('/changepackagedetail', 'PagesController@changepackagedetail')->name('website.changepackagedetail');
Route::post('/completesinglecart', 'PagesController@completesinglecart')->name('completesinglecart');

Route::post('/additemstocart', 'PagesController@additemstocart')->name('additemstocart');
Route::post('/additemstocartpackage', 'PagesController@additemstocartpackage')->name('additemstocartpackage');
Route::post('/removecartitem', 'PagesController@removecartitem')->name('removecartitem');
Route::post('/completecart', 'PagesController@completecart')->name('completecart');

Route::get('/checkout', 'PagesController@checkout')->name('checkout');
Route::post('/getotp', 'PagesController@getotp')->name('getotp');
Route::post('/resentotp', 'PagesController@resentotp')->name('resentotp');
Route::post('/checkotp', 'PagesController@checkotp')->name('checkotp');
Route::post('/applypromocode', 'PagesController@applypromocode')->name('applypromocode');

Route::get('/payment/{id}', 'PagesController@payment')->name('payment');
Route::post('/updatepayment', 'PagesController@updatepayment')->name('updatepayment');
Route::post('/addeventdate', 'PagesController@addeventdate')->name('addeventdate');

Route::get('/cart/{cart_type}', 'PagesController@cart')->name('cart');

Route::get('/faq', 'PagesController@faq')->name('website.faq');
Route::get('/about-us', 'PagesController@about')->name('website.about');
Route::get('/terms', 'PagesController@terms')->name('website.terms');
Route::get('/food', 'PagesController@food')->name('website.food');
Route::get('/banquents', 'PagesController@banquets')->name('website.banquets');


Route::get('/agentbooking', 'AgentBookingController@index')->name('website.agentbooking');
Route::post('/savecartdetails', 'AgentBookingController@savecartdetails')->name('website.savecartdetails');

Route::post('/completeagentcart', 'PagesController@completeagentcart')->name('website.completeagentcart');

/* Route::any('/equipments', 'PagesController@equipments')->name('website.equipments');
Route::any('/birthdays', 'PagesController@birthdays')->name('website.birthdays');
Route::any('/weddings', 'PagesController@weddings')->name('website.weddings');
Route::any('/packages', 'PagesController@packages')->name('website.packages');
*/

/* ================================Web Routes Ends========================  */
/* ================================Device Based Routes========================  */

$agent = new \Jenssegers\Agent\Agent;
$result = $agent->isMobile();
if ($result)
{
    Route::any('/', 'MobileController@index')->name('website.weddings');
    Route::any('/weddings', 'MobileController@weddings')->name('website.weddings');
    Route::any('/birthdays', 'MobileController@birthdays')->name('website.birthdays');
    Route::any('/equipments', 'MobileController@equipments')->name('website.equipments');
    Route::any('/packages', 'MobileController@packageslist')->name('website.packageslist');
    Route::any('/packages/{id}', 'MobileController@packages')->name('website.packages');
    Route::get('/mobilecart/{cart_type}', 'MobileController@mobilecart')->name('mobilecart');
}
else
{
    Route::any('/equipments', 'PagesController@equipments')->name('website.equipments');
    Route::any('/birthdays', 'PagesController@birthdays')->name('website.birthdays');
    Route::any('/weddings', 'PagesController@weddings')->name('website.weddings');
    Route::any('/packages', 'PagesController@packages')->name('website.packages');
}

/* ================================Device Based Routes Ends========================  */

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('change-password', 'Auth\UpdatePasswordController@index')->name('password.form');
Route::post('change-password', 'Auth\UpdatePasswordController@update')->name('password.update');

Route::group([ 'as'=>'admin.', 'prefix'=>'admin', 'namespace'=>'Admin', 'middleware'=>['auth','admin']],
function() {
    Route::get('dashboard','DashboardController@index')->name('dashboard');

    Route::get('marriagethemes', 'ThemeController@marriagethemes')->name('marriagethemes');
    Route::get('birthdaythemes', 'ThemeController@birthdaythemes')->name('birthdaythemes');
    Route::get('equipthemes', 'ThemeController@equipthemes')->name('equipthemes');

    //Route::get('packagethemes', 'ThemeController@packagethemes')->name('packagethemes');

    /*
    Route::get('marriagesubthemes', 'ThemeController@marriagesubthemes')->name('marriagesubthemes');
    Route::get('birthdaysubthemes', 'ThemeController@birthdaysubthemes')->name('birthdaysubthemes');
    Route::get('equipsubthemes', 'ThemeController@equipsubthemes')->name('equipsubthemes');
    Route::get('packagesubthemes', 'ThemeController@packagesubthemes')->name('packagesubthemes');
    */

    /* Routes Added for subthemes data */

    Route::resource('equipsubthemes', 'EquipmentController');
    Route::post('equipsubthemes/update', 'EquipmentController@update')->name('equipsubthemes.update');
    Route::get('equipsubthemes/destroy/{id}', 'EquipmentController@destroy');
    Route::get('equipsubthemes/delsubimage/{id}', 'EquipmentController@delsubimage');

    Route::resource('bdaysubthemes', 'BirthdayController');
    Route::post('bdaysubthemes/update', 'BirthdayController@update')->name('bdaysubthemes.update');
    Route::get('bdaysubthemes/destroy/{id}', 'BirthdayController@destroy');
    Route::get('bdaysubthemes/delsubimage/{id}', 'BirthdayController@delsubimage');

    Route::resource('marriagesubthemes', 'MarriageController');
    Route::post('marriagesubthemes/update', 'MarriageController@update')->name('marriagesubthemes.update');
    Route::get('marriagesubthemes/destroy/{id}', 'MarriageController@destroy');
    Route::get('marriagesubthemes/delsubimage/{id}', 'MarriageController@delsubimage');

    Route::resource('packagethemes', 'PackageController');
    Route::post('packagethemes/update', 'PackageController@update')->name('packagethemes.update');
    Route::get('packagethemes/destroy/{id}', 'PackageController@destroy');

    Route::resource('packagedetails', 'PackageDetailController');
    Route::post('packagedetails/update', 'PackageDetailController@update')->name('packagedetails.update');
    Route::get('packagedetails/destroy/{id}', 'PackageDetailController@destroy');

    Route::resource('mainvendor', 'MainVendorController');
    Route::post('mainvendor/update', 'MainVendorController@update')->name('mainvendor.update');
    Route::get('mainvendor/destroy/{id}', 'MainVendorController@destroy');

    Route::resource('maincategory', 'MainCategoryController');
    Route::post('maincategory/update', 'MainCategoryController@update')->name('maincategory.update');
    Route::get('maincategory/destroy/{id}', 'MainCategoryController@destroy');

    Route::resource('subdetailsvendor', 'SubdetailsvendorController');
    Route::post('subdetailsvendor/update', 'SubdetailsvendorController@update')->name('subdetailsvendor.update');
    Route::get('subdetailsvendor/destroy/{id}', 'SubdetailsvendorController@destroy');

    Route::resource('vendordet', 'VendordetController');
    Route::post('vendordet/update', 'VendordetController@update')->name('vendordet.update');
    Route::get('vendordet/destroy/{id}', 'VendordetController@destroy');

    Route::resource('coupons', 'CouponController');
    Route::post('coupons/update', 'CouponController@update')->name('coupons.update');
    Route::get('coupons/destroy/{id}', 'CouponController@destroy');

    Route::resource('coupontext', 'CoupontextController');
    Route::post('coupontext/update', 'CoupontextController@update')->name('coupontext.update');
    Route::get('coupontext/destroy/{id}', 'CoupontextController@destroy');

    Route::resource('homeslider', 'HomesliderController');
    Route::post('homeslider/update', 'HomesliderController@update')->name('homeslider.update');
    Route::get('homeslider/destroy/{id}', 'HomesliderController@destroy');

    Route::resource('items', 'ItemsController');
    Route::post('items/update', 'ItemsController@update')->name('items.update');
    Route::get('items/destroy/{id}', 'ItemsController@destroy');

    /* Routes Added for subthemes data Ends */

    Route::get('themesdata/getdata', 'ThemeController@getdata')->name('themesdata.getdata');
    Route::post('themesdata/postdata', 'ThemeController@postdata')->name('themesdata.postdata');
    Route::get('themesdata/fetchdata', 'ThemeController@fetchdata')->name('themesdata.fetchdata');
    Route::get('themesdata/removedata', 'ThemeController@removedata')->name('themesdata.removedata');
    Route::get('themesdata/massremove', 'ThemeController@massremove')->name('themesdata.massremove');

    /* Routes Added for subthemes data */

    Route::post('subthemes/postdata', 'ThemeController@postsubtheme')->name('subthemes.postdata');
    Route::post('subthemes/updatesubtheme', 'ThemeController@updatesubtheme')->name('subthemes.updatesubtheme');
    Route::get('subthemes/fetchdata', 'ThemeController@subfetchdata')->name('subthemes.fetchdata');
    Route::get('subthemes/removedata', 'ThemeController@subthemedelete')->name('subthemes.removedata');
    Route::get('subthemes/muldelete', 'ThemeController@subthememuldel')->name('subthemes.muldelete');

    /* Routes Added for subthemes data ends */

    /* Routes Added for createdusers data */

    Route::get('createdusers', 'CreatedusersController@index')->name('createdusers');
    Route::get('createdusers/getdata', 'CreatedusersController@getdata')->name('createdusers.getdata');
    Route::post('createdusers/postdata', 'CreatedusersController@postdata')->name('createdusers.postdata');
    Route::get('createdusers/fetchdata', 'CreatedusersController@fetchdata')->name('createdusers.fetchdata');
    Route::get('createdusers/removedata', 'CreatedusersController@removedata')->name('createdusers.removedata');
    Route::get('createdusers/massremove', 'CreatedusersController@massremove')->name('createdusers.massremove');

    Route::get('createdusersunreg', 'CreatedusersController@createdusersunreg')->name('createdusersunreg');
    Route::get('createdusersunreg/unregdata', 'CreatedusersController@unregdata')->name('createdusersunreg.unregdata');
    Route::get('createdusersunreg/movedata', 'CreatedusersController@movedata')->name('createdusersunreg.movedata');

    /* Routes Added for createdusers data Ends */

     /* Routes Added for Event co-ordinator data */

     Route::resource('eventcoordinate', 'EventcoordinateController');

      /* Routes Added for Event co-ordinator data Ends */

      /* Routes Added for Specialevent data */

      Route::get('specialevent', 'SpecialeventController@index')->name('specialevent');
      Route::get('specialevent/getdata', 'SpecialeventController@getdata')->name('specialevent.getdata');
      Route::post('specialevent/postdata', 'SpecialeventController@postdata')->name('specialevent.postdata');
      Route::get('specialevent/fetchdata', 'SpecialeventController@fetchdata')->name('specialevent.fetchdata');
      Route::get('specialevent/removedata', 'SpecialeventController@removedata')->name('specialevent.removedata');
      Route::get('specialevent/massremove', 'SpecialeventController@massremove')->name('specialevent.massremove');

      /* Routes Added for Specialevent data Ends */

    /* Routes Added for customer data */

    Route::get('customers', 'CustomerController@index')->name('customers');
    Route::get('customers/getdata', 'CustomerController@getdata')->name('customers.getdata');
    Route::post('customers/postdata', 'CustomerController@postdata')->name('customers.postdata');
    Route::get('customers/fetchdata', 'CustomerController@fetchdata')->name('customers.fetchdata');
    Route::get('customers/removedata', 'CustomerController@removedata')->name('customers.removedata');
    Route::get('customers/massremove', 'CustomerController@massremove')->name('customers.massremove');

    Route::post('customers/import', 'CustomerController@import')->name('customers.import');

    /* Routes Added for customer data Ends */

      /* Routes Added for Banquets data */

    Route::get('banquet', 'BanquetsController@index')->name('banquet');
    Route::get('banquet/getdata', 'BanquetsController@getdata')->name('banquet.getdata');
    Route::post('banquet/postdata', 'BanquetsController@postdata')->name('banquet.postdata');
    Route::get('banquet/fetchdata', 'BanquetsController@fetchdata')->name('banquet.fetchdata');
    Route::get('banquet/removedata', 'BanquetsController@removedata')->name('banquet.removedata');
    Route::get('banquet/massremove', 'BanquetsController@massremove')->name('banquet.massremove');

    /* Routes Added for Banquets data Ends */

    /* Routes Added for Foodbookings data */

    Route::get('foodbooking', 'FoodbookingsController@index')->name('foodbooking');
    Route::get('foodbooking/getdata', 'FoodbookingsController@getdata')->name('foodbooking.getdata');
    Route::post('foodbooking/postdata', 'FoodbookingsController@postdata')->name('foodbooking.postdata');
    Route::get('foodbooking/fetchdata', 'FoodbookingsController@fetchdata')->name('foodbooking.fetchdata');
    Route::get('foodbooking/removedata', 'FoodbookingsController@removedata')->name('foodbooking.removedata');
    Route::get('foodbooking/massremove', 'FoodbookingsController@massremove')->name('foodbooking.massremove');

  /* Routes Added for Foodbookings Ends */

  /* Routes Added for Foodbookings data */

    Route::get('quote', 'QuotesController@index')->name('quote');
    Route::get('quote/getdata', 'QuotesController@getdata')->name('quote.getdata');
    Route::post('quote/postdata', 'QuotesController@postdata')->name('quote.postdata');
    Route::get('quote/fetchdata', 'QuotesController@fetchdata')->name('quote.fetchdata');
    Route::get('quote/removedata', 'QuotesController@removedata')->name('quote.removedata');
    Route::get('quote/massremove', 'QuotesController@massremove')->name('quote.massremove');

    /* Routes Added for Foodbookings Ends */

    /* Routes Added for Custom Bday Bookings data */

    Route::resource('custombdays', 'CustombdayController');
    Route::post('custombdays/update', 'CustombdayController@update')->name('custombdays.update');
    Route::get('custombdays/destroy/{id}', 'CustombdayController@destroy');

    /* Routes Added for Custom Bday Bookings data Ends */

    /* Routes Added for Saved Carts data */

    Route::resource('savedcart', 'SavecartController');
    Route::post('savedcart/update', 'SavecartController@update')->name('savedcart.update');
    Route::get('savedcart/destroy/{id}', 'SavecartController@destroy');
    Route::get('savedcart/saveremarks/{id}/{remarks}', 'SavecartController@saveremarks')->name('savedcart.saveremarks');
    Route::get('savedcart/move/{id}', 'SavecartController@move');

    /* Routes Added for Saved Carts data Ends */

    /* Routes Added for Quotations data */
    Route::get('quotations/confirmed', 'QuotationController@confirmed')->name('quotations.confirmed');
    Route::get('quotations/completed', 'QuotationController@completed')->name('quotations.completed');

    Route::get('quotations/confirmedpop/{id}', 'QuotationController@confirmedpop')->name('quotations.confirmedpop');
    Route::post('quotations/confirmedpop', 'QuotationController@updateconfirmed')->name('quotations.updateconfirmed');

    Route::get('quotations/completedpop/{id}', 'QuotationController@completedpop')->name('quotations.completedpop');
    Route::get('quotations/delcompleteditem/{id}', 'QuotationController@delcompleteditem')->name('quotations.delcompleteditem');
    Route::get('quotations/triggermailcompleted/{id}', 'QuotationController@triggermailcompleted')->name('quotations.triggermailcompleted');
    Route::post('quotations/completedpop', 'QuotationController@updatecompleted')->name('quotations.updatecompleted');
    Route::get('quotations/completedgen/{item_id}/{invoice_number}/{gst_no}', 'QuotationController@completedgen')->name('quotations.completedgen');

    Route::get('reports/completed', 'QuotationController@reportscompleted')->name('reports.completed');
    Route::post('reports/updatecompleted', 'QuotationController@updatecompletedreports')->name('reports.updatecompleted');
    Route::get('reports/consolidated', 'QuotationController@reportsconsolidated')->name('reports.consolidated');

    Route::resource('quotations', 'QuotationController');
    Route::post('quotations/updatequotations', 'QuotationController@updatequotations')->name('quotations.updatequotations');
    Route::get('quotations/destroy/{id}', 'QuotationController@destroy');
    Route::get('quotations/saveremarks/{id}/{remarks}', 'QuotationController@saveremarks')->name('quotations.saveremarks');
    Route::get('quotations/editfields/{id}/{item_id}/{item_value}', 'QuotationController@editfields')->name('quotations.editfields');
    Route::get('quotations/updategst/{id}/{item_id}', 'QuotationController@updategst')->name('quotations.updategst');
    Route::get('quotations/setvalue/{id}/{item_id}', 'QuotationController@setvalue')->name('quotations.setvalue');

    Route::get('quotations/genquote/{id}', 'QuotationController@genquote')->name('quotations.genquote');
    Route::get('quotations/mailgenquote/{id}/{quote_type}', 'QuotationController@mailgenquote')->name('quotations.mailgenquote');

    /* Routes Added for Quotations data Ends */

    /* Routes Added for Saved Carts data */

    Route::resource('cities', 'CityController');
    Route::post('cities/update', 'CityController@update')->name('cities.update');
    Route::get('cities/destroy/{id}', 'CityController@destroy');

    /* Routes Added for Saved Carts data Ends */

});

Route::group([ 'as'=>'author.', 'prefix'=>'author', 'namespace'=>'Author', 'middleware'=>['auth','author']],
function(){
 Route::get('dashboard','DashboardController@index')->name('dashboard');

});

Route::group([ 'as'=>'dealer.', 'prefix'=>'dealer', 'namespace'=>'Dealer', 'middleware'=>['auth','dealer']],
function(){
 Route::get('dashboard','DashboardController@index')->name('dashboard');

});

Route::get('ajaxdata', 'AjaxdataController@index')->name('ajaxdata');
Route::get('ajaxdata/getdata', 'AjaxdataController@getdata')->name('ajaxdata.getdata');
Route::post('ajaxdata/postdata', 'AjaxdataController@postdata')->name('ajaxdata.postdata');
Route::get('ajaxdata/fetchdata', 'AjaxdataController@fetchdata')->name('ajaxdata.fetchdata');
Route::get('ajaxdata/removedata', 'AjaxdataController@removedata')->name('ajaxdata.removedata');
Route::get('ajaxdata/massremove', 'AjaxdataController@massremove')->name('ajaxdata.massremove');

Route::resource('ajax-crud', 'AjaxCrudController');
Route::post('ajax-crud/update', 'AjaxCrudController@update')->name('ajax-crud.update');
Route::get('ajax-crud/destroy/{id}', 'AjaxCrudController@destroy');