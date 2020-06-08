<?php

namespace App\Http\Controllers;

use PDF;
use App\Item;
use App\Theme;

use Validator;
use App\Coupon;
use App\Remark;
use DataTables;
use App\Package;
use App\Customer;
use App\Payorder;
use App\Subtheme;
use App\Quotation;
use App\Coupontext;
use App\Homeslider;
use App\OnlineCoupon;
use App\OnlineInquiry;

use App\PackageDetail;
use App\Mail\BdayModalMail;
use App\Mail\HomemodalMail;
use App\CustomBdayRequested;
use Illuminate\Http\Request;
use App\Mail\CustomerQuotation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class MobileController extends Controller
{
    private function moneyFormatIndia($num){
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i < sizeof($expunit);  $i++){
                // creates each of the 2's group and adds a comma to the end
                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash; // writes the final format where $currency is the currency symbol.
    }

    public function index(Request $request)
    {
        $homesliders = Homeslider::latest()->get();
        $coupon_texts = Coupontext::where('trans_type','homepage')->get();
        $coupon_texts = $coupon_texts->toArray();

        $cart_items = []; 
        $cart_items_count = 0;
        if ($request->session()->has('generalcart')) {
            $cart_items = $request->session()->get('generalcart');
            $cart_items_count = count($cart_items);
        }

        return view('website.index', compact(['homesliders','coupon_texts','cart_items_count']));
    }

    public function weddings(Request $request)
    {
        $homesliders = Homeslider::latest()->get();
        $theme_names = Theme::has('subthemes')->with('subthemes')->where('theme_type','marriage')->get();
        $page_items = Item::where('type','marriage')->first();
        $coupon_texts = Coupontext::where('trans_type','marriage')->get();
        $coupon_texts = $coupon_texts->toArray();

        $cart_items = [];       

        if ($request->session()->has('weddingcart')) {
            $cart_items = $request->session()->get('weddingcart');
        }
        
        if(request()->ajax())
        {
            if(!empty($_REQUEST["filter_id"]))
            {
                $subthemes_array = $request->input('filter_id');

                /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array); */

                if(!empty($_REQUEST["searchtext"]))
                {
                    $searchtext = $_REQUEST["searchtext"];
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%");
                }
                else
                {
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array);
                }
            }
            else
            {
                $themes = Theme::has('subthemes')->with('subthemes')->where('theme_type','marriage')->get();

                foreach($themes as $theme)
                {
                    $subthemes_array[] = $theme->id;
                }

                /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array); */

                if(!empty($_REQUEST["searchtext"]))
                {
                    $searchtext = $_REQUEST["searchtext"];
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%");
                }
                else
                {
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array);
                }

            }

            return datatables()->of($subthemes_data)
                ->addColumn('action', function($data) {

                    $pwdid = $data->id;
                    $pwid = $data->theme->id;
                    $classname = preg_replace('/\s+/', '', $data->type);
                    $fillid = $pwdid.$pwid;
                    $label = $data->label ? $data->label : 'Hot Selling';
                    $chosetype = '';

                    $price_to_use = $data->discounted_price > 0 ? $data->discounted_price : $data->actual_price;

                    if($data->type == 'Q')
                    {
                        $classname = preg_replace('/\s+/', '', 'quantitycls');
                        $msgdata = ' &nbsp;1 Qty is &nbsp;&nbsp;';
                    }
                    else if($data->type == 'S')
                    {
                        if($data->theme->theme_name == 'LED Wall') 
                        {
                            $classname = preg_replace('/\s+/', '', $data->theme->theme_name);
                            $msgdata = ' &nbsp;Per Sq Ft is &nbsp;&nbsp;';
                        }
                        else
                        {
                            $classname = preg_replace('/\s+/', '', 'sqftcls');
                            $msgdata = ' &nbsp;Per Sq Ft is &nbsp;&nbsp;';
                        }
                    }
                    
                    $dataRow = '
                    <h1 class="fulltext_head">'.$data->theme->theme_name.'</h1>
                    <div class="flash-sale-card">
                        <div class="card card-pop">
                            <div class="card-body">
                                <div class="card-img">
                                    <a href="'.Storage::url($data->file).'" data-lity="">
                                        <img src="'.Storage::url($data->file).'" class=" lazyloaded">
                                    </a>
                                </div>';
                                
                            if($data->type == 'Q')
                            {    
                                $dataRow .= '<div class="card-contents" id="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                    <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                    <div class="row choose">
                                        <div class="col s6 lefttest">
                                            <p>Select Qty </p>
                                            <div class="cntscountequip">
                                                <div class="qty">
                                                    <div class="bord quantity buttons_added"> 
                                                        <div class="row">
                                                            <div class="col s3">
                                                                <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                            <div class="col s6">
                                                                <input id="additem-'.$fillid.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="">
                                                            </div>
                                                            <div class="col s3">
                                                                <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s6 dayspadding">
                                            <p>Select days </p>
                                            <div class="cntscountequip">
                                                <div class="qty">
                                                    <div class="bord quantity buttons_added"> 
                                                        <div class="row">
                                                            <div class="col s3">
                                                                <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                            <div class="col s6">
                                                                <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            </div>
                                                            <div class="col s3">
                                                                <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                }
                                else if($data->type == 'S')
                                {
                                    if($data->type == 'LED Wall')
                                    {
                                        $dataRow .= '<div class="card-contents" id="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                            <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                            <div class="row choose">
                                               <div class="col s8 lefttest p-0">
                                                  <p>Select Sqft </p>
                                                    <div class="sqftcountequip">
                                                        <div class="col s6 text-center width">
                                                            <div class="qty">
                                                                <div class="bord quantity buttons_added"> 
                                                                    <div class="row">
                                                                        <div class="col s3">
                                                                            <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                        <div class="col s6 numbercounters">
                                                                            <input id="addsq1-'.$fillid.'" type="number" step="2" min="2" max="" name="width" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                        </div>
                                                                        <div class="col s3">
                                                                            <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="wd">width </p>
                                                        </div>
                                                        <div class="col s6 text-center width">
                                                            <div class="qty">
                                                                <div class="bord quantity buttons_added"> 
                                                                    <div class="row">
                                                                        <div class="col s3">
                                                                            <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                        <div class="col s6 numbercounters">
                                                                            <input id="addsq2-'.$fillid.'" type="number" step="2" min="2" max="" name="height" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                        </div>
                                                                        <div class="col s3">
                                                                            <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="wd">Height </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col s4 width">
                                                    <p>Select  days </p>
                                                    <div class="sqftcountequip">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>';
                                    }
                                    else
                                    {
                                        $dataRow .= '<div class="card-contents" id="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                        <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                        <div class="row choose">
                                           <div class="col s8 lefttest p-0">
                                              <p>Select Sqft </p>
                                                <div class="sqftcountequip">
                                                    <div class="col s6 text-center width">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="addsq1-'.$fillid.'" type="number" step="4" min="4" max="" name="width" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="wd">width</p>
                                                    </div>
                                                    <div class="col s6 text-center width">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="addsq2-'.$fillid.'" type="number" step="4" min="4" max="" name="height" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="wd">Height </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s4 width">
                                                <p>Select  days </p>
                                                <div class="sqftcountequip">
                                                    <div class="qty">
                                                        <div class="bord quantity buttons_added"> 
                                                            <div class="row">
                                                                <div class="col s3">
                                                                    <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                </div>
                                                                <div class="col s6 numbercounters">
                                                                    <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                                </div>
                                                                <div class="col s3">
                                                                    <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                        </div>';
                                    }
                                }

                                    $dataRow .= '<div style="clear: both;"></div>
                                    <div class="btndatas">
                                        <div class="row final">
                                            <div class="col s12">
                                                <a class="" data-id="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-bookingtype="cart_wedding" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstocartsmobile(this)">
                                                 <div class="col s6 first_col">
                                                    <div id="quick-inquiry" class="marg">
                                                        Add to Cart
                                                    </div> 
                                                 </div>
                                                </a> 
                                                <a class="" data-id="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstosinglecarts(this)">
                                                    <div class="col s6 second_col">
                                                        <div id="book-inquiry" class="marg">
                                                            Enquire Now
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="cartfix">
                                        <div class="added-to-cart" id="result-wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'"></div>
                                    </div> ';

                                    if(!empty($data->description) || $data->subthemeimages_count > 0)
                                    {
                                        $dataRow .= '<div id="bottom-links"><div><ul id="ul-'.$data->id.'" class="fr bottom-panel d-color">';
                                        
                                        if(!empty($data->description))
                                        {
                                            $dataRow .= '<li id="'.$data->id.'map" class="bottom-list list-map_'.$data->id.' itemdesc_link"><span id="text-map-'.$data->id.'" class="txt-val" title="Description">Description</span></li>';
                                        }

                                        if($data->subthemeimages_count > 0)
                                        {
                                            $dataRow .= '<li id="'.$data->id.'photos" class="bottom-list list-photos_'.$data->id.' itemimage_link"><span id="text-photos-'.$data->id.'" class="txt-val" title="Photos">More Photos</span></li>';
                                        }
                                        
                                        $dataRow .= '</ul></div></div>'; 
                                    }

                                $dataRow .= '</div>
                            </div>
                        </div>
                    </div>';

                    if(!empty($data->description) || !empty($data->what_included) || !empty($data->need_to_know))
                    {   
                        $dataRow .= 
                        '<div id="mapshow_list_'.$data->id.'" class="map_list 2" style="display: none;">
                            <div class="desctitle box_style_6">
                                <h1 class="responsive-head-font" style="font-weight: bold;">'.$data->sub_theme_name.' ('.$data->label .')</h1>
                            </div>
                            <div class="full-complete col-md-12">
                            <div class="map-show" style="" id="map_list_'.$data->id.'">
                                <div class="box_style_6 boxes1">
                                    <h2 class="responsive-head2-font" style="margin-top: 0px;">Description</h2>
                                    <div id="line-trugh"></div>
                                    <p class="teaser">'.$data->description.'</p>
                                </div>
                            </div>
                            <div class="near-landmarks" style="" id="nearest_landmarks_'.$data->id.'">
                                <div style="margin-top: 20px;">
                                    <div class="row mar-top-12">
                                        <div class="box_style_6 single_desc boxes2">
                                            <h3>What&#39;s included</h3>
                                            <div id="line-trugh"></div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12">
                                                <ul class="list_ok">';
        
                            foreach(explode('#', $data->what_included) as $list_item)
                            {
                                $dataRow .= '<li>'.$list_item.'</li>';
                            }
        
                            $dataRow .= '</ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>';
        
                            if(!empty($data->need_to_know))
                            {
                                    $dataRow .= '<div class="near-landmarks" style="width:98%;padding-left: 7px;" id="nearest_landmarks_'.$data->id.'">
                                        <div style="margin-top: 20px;">
                                            <div class="row mar-top-12">
                                                <div class="box_style_6 boxes3">
                                                    <div class="single_desc_heading ">
                                                        <h3>Need to know</h3>
                                                    </div>
                                                    <div id="line-trugh1"></div>
                                                    <p></p>
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12">
                                                            <ul class="list_note">';

                                                            foreach(explode('#', $data->need_to_know) as $list_item)
                                                            {
                                                                $dataRow .= '<li>'.$list_item.'</li>';
                                                            }            

                                                            $dataRow .= '</ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                            }
        
                            $dataRow .= '</div>';
                    }
    
                    if($data->subthemeimages_count > 0)
                    {
                        $dataRow .= '<div id="photo_list_'.$data->id.'" class="photos_list" style="display: none;">
                            <div id="photos_list_'.$data->id.'">
                                <div id="media_slider" class="space_img_slider_14207">
                                    <div class="MS-content">';

                                        foreach($data->subthemeimages as $image)
                                        {
                                            $dataRow .= '<div class="item">
                                                <div class="imgTitle">
                                                    <a href="'.Storage::url($image->path).'" data-lity="">
                                                         <img style="height: 130px;" class=" lazyloaded" data-src="'.Storage::url($image->path).'" alt="photo of gallery1" src="'.Storage::url($image->path).'">
                                                    </a>
                                                </div>
                                            </div>';
                                        }
                                        
                                    $dataRow .= '</div>
                                </div>
                            </div>
                        </div>';
                    }   
            
                return $dataRow;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('website.weddings',compact(['theme_names','page_items','coupon_texts','cart_items','homesliders']));
    }

    public function birthdays(Request $request)
    {
        $homesliders = Homeslider::latest()->get();
        $theme_names = Theme::has('subthemes')->with('subthemes')->where('theme_type','birthday')->get();
        $page_items = Item::where('type','birthday')->first();
        $coupon_texts = Coupontext::where('trans_type','birthday')->get();
        $coupon_texts = $coupon_texts->toArray();

        $cart_items = [];       

        if ($request->session()->has('generalcart')) {
            $cart_items = $request->session()->get('generalcart');
        }
        
        if(request()->ajax())
        {
            if(!empty($_REQUEST["filter_id"]))
            {
                $subthemes_array = $request->input('filter_id');

                /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array); */

                if(!empty($_REQUEST["searchtext"]))
                {
                    $searchtext = $_REQUEST["searchtext"];
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%");
                }
                else
                {
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array);
                }
            }
            else
            {
                $themes = Theme::has('subthemes')->with('subthemes')->where('theme_type','birthday')->get();

                foreach($themes as $theme)
                {
                    $subthemes_array[] = $theme->id;
                }

                /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array); */

                if(!empty($_REQUEST["searchtext"]))
                {
                    $searchtext = $_REQUEST["searchtext"];
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%");
                }
                else
                {
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array);
                }

            }

            return datatables()->of($subthemes_data)
                ->addColumn('action', function($data) {

                    $pwdid = $data->id;
                    $pwid = $data->theme->id;
                    $classname = preg_replace('/\s+/', '', $data->type);
                    $fillid = $pwdid.$pwid;
                    $label = $data->label ? $data->label : 'Hot Selling';
                    $chosetype = '';

                    $price_to_use = $data->discounted_price > 0 ? $data->discounted_price : $data->actual_price;

                    if($data->type == 'Q')
                    {
                        $classname = preg_replace('/\s+/', '', 'quantitycls');
                        $msgdata = ' &nbsp;1 Qty is &nbsp;&nbsp;';
                    }
                    else if($data->type == 'S')
                    {
                        if($data->theme->theme_name == 'LED Wall') 
                        {
                            $classname = preg_replace('/\s+/', '', $data->theme->theme_name);
                            $msgdata = ' &nbsp;Per Sq Ft is &nbsp;&nbsp;';
                        }
                        else
                        {
                            $classname = preg_replace('/\s+/', '', 'sqftcls');
                            $msgdata = ' &nbsp;Per Sq Ft is &nbsp;&nbsp;';
                        }
                    }
                    
                    $dataRow = '
                    <div class="flash-sale-card">
                        <div class="card card-pop">
                            <div class="card-body">
                                <div class="card-img">
                                    <a href="'.Storage::url($data->file).'" data-lity="">
                                        <img src="'.Storage::url($data->file).'" class=" lazyloaded">
                                    </a>
                                </div>';
                                
                            if($data->type == 'Q')
                            {    
                                $dataRow .= '<div class="card-contents"  id="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                    <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                    <div class="row choose">
                                        <div class="col s6 lefttest">
                                            <p>Select Qty </p>
                                            <div class="cntscountequip">
                                                <div class="qty">
                                                    <div class="bord quantity buttons_added"> 
                                                        <div class="row">
                                                            <div class="col s3">
                                                                <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                            <div class="col s6">
                                                                <input id="additem-'.$fillid.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="">
                                                            </div>
                                                            <div class="col s3">
                                                                <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s6 dayspadding">
                                            <p>Select days </p>
                                            <div class="cntscountequip">
                                                <div class="qty">
                                                    <div class="bord quantity buttons_added"> 
                                                        <div class="row">
                                                            <div class="col s3">
                                                                <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                            <div class="col s6">
                                                                <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            </div>
                                                            <div class="col s3">
                                                                <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                }
                                else if($data->type == 'S')
                                {
                                    if($data->type == 'LED Wall')
                                    {
                                        $dataRow .= '<div class="card-contents" id="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                            <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                            <div class="row choose">
                                               <div class="col s8 lefttest p-0">
                                                  <p>Select Sqft </p>
                                                    <div class="sqftcountequip">
                                                        <div class="col s6 text-center width">
                                                            <div class="qty">
                                                                <div class="bord quantity buttons_added"> 
                                                                    <div class="row">
                                                                        <div class="col s3">
                                                                            <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                        <div class="col s6 numbercounters">
                                                                            <input id="addsq1-'.$fillid.'" type="number" step="2" min="2" max="" name="width" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                        </div>
                                                                        <div class="col s3">
                                                                            <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="wd">width </p>
                                                        </div>
                                                        <div class="col s6 text-center width">
                                                            <div class="qty">
                                                                <div class="bord quantity buttons_added"> 
                                                                    <div class="row">
                                                                        <div class="col s3">
                                                                            <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                        <div class="col s6 numbercounters">
                                                                            <input id="addsq2-'.$fillid.'" type="number" step="2" min="2" max="" name="height" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                        </div>
                                                                        <div class="col s3">
                                                                            <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="wd">Height </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col s4 width">
                                                    <p>Select  days </p>
                                                    <div class="sqftcountequip">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>';
                                    }
                                    else
                                    {
                                        $dataRow .= '<div class="card-contents" id="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                        <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                        <div class="row choose">
                                           <div class="col s8 lefttest p-0">
                                              <p>Select Sqft </p>
                                                <div class="sqftcountequip">
                                                    <div class="col s6 text-center width">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="addsq1-'.$fillid.'" type="number" step="4" min="4" max="" name="width" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="wd">width</p>
                                                    </div>
                                                    <div class="col s6 text-center width">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="addsq2-'.$fillid.'" type="number" step="4" min="4" max="" name="height" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="wd">Height </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s4 width">
                                                <p>Select  days </p>
                                                <div class="sqftcountequip">
                                                    <div class="qty">
                                                        <div class="bord quantity buttons_added"> 
                                                            <div class="row">
                                                                <div class="col s3">
                                                                    <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                </div>
                                                                <div class="col s6 numbercounters">
                                                                    <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                                </div>
                                                                <div class="col s3">
                                                                    <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                        </div>';
                                    }
                                }

                                    $dataRow .= '<div style="clear: both;"></div>
                                    <div class="row price">
                                        <div class="col s12 text-center">
                                            <div id="quick-inquiry-text" class="marg">
                                                <a class="" rel="nofollow" style="text-decoration:none" href="#">
                                                    <p class="total">Total Amount : 
                                                        <span class="inrupess">₹</span>';

                                                        if(!empty($data->discounted_price))
                                                        {
                                                            $dataRow .= '<span class="strikeamt"><s>'.$this->moneyFormatIndia($data->actual_price).'</s></span>
                                                            <span class="totamonnt">'.$this->moneyFormatIndia($data->discounted_price).'</span>';
                                                        }   
                                                        else
                                                        {
                                                            $dataRow .= '<span class="totamonnt">'.$this->moneyFormatIndia($data->actual_price).'</span>';
                                                        } 
                                                                                        
                                                        $dataRow .= '<span class="lastvalue">/-</span>
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btndatas">
                                        <div class="row final">
                                            <div class="col s12">
                                              <a class="" data-id="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-bookingtype="cart_general" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstocartsmobile(this)">
                                                <div class="col s6 first_col">
                                                    <div id="quick-inquiry" class="marg">
                                                        Add to Cart
                                                    </div>
                                                </div>
                                               </a>
                                               <a class="" data-id="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstosinglecarts(this)">
                                                <div class="col s6 second_col">
                                                    <div id="book-inquiry" class="marg">
                                                        Book Now
                                                    </div>
                                                </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cartfix">
                                        <div class="added-to-cart" id="result-birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'"></div>
                                    </div> ';
                                     
                                    
                                    if(!empty($data->description) || $data->subthemeimages_count > 0)
                                    {
                                        $dataRow .= '<div id="bottom-links"><div><ul id="ul-'.$data->id.'" class="fr bottom-panel d-color">';
                                        
                                        if(!empty($data->description))
                                        {
                                            $dataRow .= '<li id="'.$data->id.'map" class="bottom-list list-map_'.$data->id.' itemdesc_link"><span id="text-map-'.$data->id.'" class="txt-val" title="Description">Description</span></li>';
                                        }

                                        if($data->subthemeimages_count > 0)
                                        {
                                            $dataRow .= '<li id="'.$data->id.'photos" class="bottom-list list-photos_'.$data->id.' itemimage_link"><span id="text-photos-'.$data->id.'" class="txt-val" title="Photos">More Photos</span></li>';
                                        }
                                        
                                        $dataRow .= '</ul></div></div>'; 
                                    }

                                $dataRow .= '</div>
                            </div>
                        </div>
                    </div>';

                    if(!empty($data->description) || !empty($data->what_included) || !empty($data->need_to_know))
                    {   
                        $dataRow .= 
                        '<div id="mapshow_list_'.$data->id.'" class="map_list 3" style="display: none;">
                            <div class="desctitle box_style_6">
                                <h1 class="responsive-head-font" style="font-weight: bold;">'.$data->sub_theme_name.' ('.$data->label .')</h1>
                            </div>
                            <div class="full-complete col-md-12">
                            <div class="map-show" style="" id="map_list_'.$data->id.'">
                                <div class="box_style_6 boxes1">
                                    <h2 class="responsive-head2-font" style="margin-top: 0px;">Description</h2>
                                    <div id="line-trugh"></div>
                                    <p class="teaser">'.$data->description.'</p>
                                </div>
                            </div>
                            <div class="near-landmarks" style="" id="nearest_landmarks_'.$data->id.'">
                                <div style="margin-top: 20px;">
                                    <div class="row mar-top-12">
                                        <div class="box_style_6 single_desc boxes2">
                                            <h3>What&#39;s included</h3>
                                            <div id="line-trugh"></div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12">
                                                <ul class="list_ok">';
        
                            foreach(explode('#', $data->what_included) as $list_item)
                            {
                                $dataRow .= '<li>'.$list_item.'</li>';
                            }
        
                            $dataRow .= '</ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>';
        
                            if(!empty($data->need_to_know))
                            {
                                    $dataRow .= '<div class="near-landmarks" style="width:98%;padding-left: 7px;" id="nearest_landmarks_'.$data->id.'">
                                        <div style="margin-top: 20px;">
                                            <div class="row mar-top-12">
                                                <div class="box_style_6 boxes3">
                                                    <div class="single_desc_heading ">
                                                        <h3>Need to know</h3>
                                                    </div>
                                                    <div id="line-trugh1"></div>
                                                    <p></p>
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12">
                                                            <ul class="list_note">';

                                                            foreach(explode('#', $data->need_to_know) as $list_item)
                                                            {
                                                                $dataRow .= '<li>'.$list_item.'</li>';
                                                            }            

                                                            $dataRow .= '</ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                            }
        
                            $dataRow .= '</div>';
                    }
    
                    if($data->subthemeimages_count > 0)
                    {
                        $dataRow .= '<div id="photo_list_'.$data->id.'" class="photos_list" style="display: none;">
                            <div id="photos_list_'.$data->id.'">
                                <div id="media_slider" class="space_img_slider_14207">
                                    <div class="MS-content">';

                                        foreach($data->subthemeimages as $image)
                                        {
                                            $dataRow .= '<div class="item">
                                                <div class="imgTitle">
                                                    <a href="'.Storage::url($image->path).'" data-lity="">
                                                            <img style="height: 130px;" class=" lazyloaded" data-src="'.Storage::url($image->path).'" alt="photo of gallery1" src="'.Storage::url($image->path).'">
                                                     </a>
                                                </div>
                                            </div>';
                                        }
                                        
                                    $dataRow .= '</div>
                                </div>
                            </div>
                        </div>';
                    }   
            
                return $dataRow;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

		return view('website.birthdays',compact(['theme_names','page_items','coupon_texts','cart_items','homesliders']));
    }

    public function equipments(Request $request)
    {
        $homesliders = Homeslider::latest()->get();
        $theme_names = Theme::has('subthemes')->with('subthemes')->where('theme_type','equipment')->get();
        $page_items = Item::where('type','equipment')->first();
        $coupon_texts = Coupontext::where('trans_type','equipment')->get();
        $coupon_texts = $coupon_texts->toArray();

        $cart_items = [];       

        if ($request->session()->has('generalcart')) {
            $cart_items = $request->session()->get('generalcart');
        }
        
        if(request()->ajax())
        {
            if(!empty($_REQUEST["filter_id"]))
            {
                $subthemes_array = $request->input('filter_id');

                /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array); */

                if(!empty($_REQUEST["searchtext"]))
                {
                    $searchtext = $_REQUEST["searchtext"];
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%");
                }
                else
                {
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array);
                }

            }
            else
            {
                $themes = Theme::has('subthemes')->with('subthemes')->where('theme_type','equipment')->get();

                foreach($themes as $theme)
                {
                    $subthemes_array[] = $theme->id;
                }

                /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array); */

                if(!empty($_REQUEST["searchtext"]))
                {
                    $searchtext = $_REQUEST["searchtext"];
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%");
                }
                else
                {
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array);
                }
            }

            return datatables()->of($subthemes_data)
                ->addColumn('action', function($data) {

                    $pwdid = $data->id;
                    $pwid = $data->theme->id;
                    $classname = preg_replace('/\s+/', '', $data->type);
                    $fillid = $pwdid.$pwid;
                    $label = $data->label ? $data->label : 'Hot Selling';
                    $chosetype = '';

                    $price_to_use = $data->discounted_price > 0 ? $data->discounted_price : $data->actual_price;

                    if($data->type == 'Q')
                    {
                        $classname = preg_replace('/\s+/', '', 'quantitycls');
                        $msgdata = ' &nbsp;1 Qty is &nbsp;&nbsp;';
                    }
                    else if($data->type == 'S')
                    {
                        if($data->theme->theme_name == 'LED Wall') 
                        {
                            $classname = preg_replace('/\s+/', '', $data->theme->theme_name);
                            $msgdata = ' &nbsp;Per Sq Ft is &nbsp;&nbsp;';
                        }
                        else
                        {
                            $classname = preg_replace('/\s+/', '', 'sqftcls');
                            $msgdata = ' &nbsp;Per Sq Ft is &nbsp;&nbsp;';
                        }
                    }
                    
                    $dataRow = '
                    <div class="flash-sale-card">
                        <div class="card card-pop">
                            <div class="card-body">
                                <div class="card-img">
                                    <a href="'.Storage::url($data->file).'" data-lity="">
                                        <img src="'.Storage::url($data->file).'">
                                    </a>
                                </div>';
                                
                            if($data->type == 'Q')
                            {    
                                $dataRow .= '<div class="card-contents"  id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                    <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                    <div class="row choose">
                                        <div class="col s6 lefttest">
                                            <p>Select Qty </p>
                                            <div class="cntscountequip">
                                                <div class="qty">
                                                    <div class="bord quantity buttons_added"> 
                                                        <div class="row">
                                                            <div class="col s3">
                                                                <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                            <div class="col s6">
                                                                <input id="additem-'.$fillid.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="">
                                                            </div>
                                                            <div class="col s3">
                                                                <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s6 dayspadding">
                                            <p>Select days </p>
                                            <div class="cntscountequip">
                                                <div class="qty">
                                                    <div class="bord quantity buttons_added"> 
                                                        <div class="row">
                                                            <div class="col s3">
                                                                <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                            <div class="col s6">
                                                                <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            </div>
                                                            <div class="col s3">
                                                                <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                }
                                else if($data->type == 'S')
                                {
                                    if($data->type == 'LED Wall')
                                    {
                                        $dataRow .= '<div class="card-contents" id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                            <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                            <div class="row choose">
                                               <div class="col s8 lefttest p-0">
                                                  <p>Select Sqft </p>
                                                    <div class="sqftcountequip">
                                                        <div class="col s6 text-center width">
                                                            <div class="qty">
                                                                <div class="bord quantity buttons_added"> 
                                                                    <div class="row">
                                                                        <div class="col s3">
                                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                        <div class="col s6 numbercounters">
                                                                            <input id="addsq1-'.$fillid.'" type="number" step="2" min="2" max="" name="width" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                        </div>
                                                                        <div class="col s3">
                                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="wd">width </p>
                                                        </div>
                                                        <div class="col s6 text-center width">
                                                            <div class="qty">
                                                                <div class="bord quantity buttons_added"> 
                                                                    <div class="row">
                                                                        <div class="col s3">
                                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                        <div class="col s6 numbercounters">
                                                                            <input id="addsq2-'.$fillid.'" type="number" step="2" min="2" max="" name="height" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                        </div>
                                                                        <div class="col s3">
                                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="wd">Height </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col s4 width">
                                                    <p>Select  days </p>
                                                    <div class="sqftcountequip">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>';
                                    }
                                    else
                                    {
                                        $dataRow .= '<div class="card-contents" id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                        <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                        <div class="row choose">
                                           <div class="col s8 lefttest p-0">
                                              <p>Select Sqft </p>
                                                <div class="sqftcountequip">
                                                    <div class="col s6 text-center width">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="addsq1-'.$fillid.'" type="number" step="4" min="4" max="" name="width" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="wd">width</p>
                                                    </div>
                                                    <div class="col s6 text-center width">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="addsq2-'.$fillid.'" type="number" step="4" min="4" max="" name="height" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="wd">Height </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s4 width">
                                                <p>Select  days </p>
                                                <div class="sqftcountequip">
                                                    <div class="qty">
                                                        <div class="bord quantity buttons_added"> 
                                                            <div class="row">
                                                                <div class="col s3">
                                                                    <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                </div>
                                                                <div class="col s6 numbercounters">
                                                                    <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                                </div>
                                                                <div class="col s3">
                                                                    <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                        </div>';
                                    }
                                }

                                    $dataRow .= '<div style="clear: both;"></div>
                                    <div class="row price">
                                        <div class="col s12 text-center">
                                            <div id="quick-inquiry-text" class="marg">
                                                <a class="" rel="nofollow" style="text-decoration:none" href="#">
                                                    <p class="total">Total Amount : 
                                                        <span class="inrupess">₹</span>';

                                                        if(!empty($data->discounted_price))
                                                        {
                                                            $dataRow .= '<span class="strikeamt"><s>'.$this->moneyFormatIndia($data->actual_price).'</s></span>
                                                            <span class="totamonnt">'.$this->moneyFormatIndia($data->discounted_price).'</span>';
                                                        }   
                                                        else
                                                        {
                                                            $dataRow .= '<span class="totamonnt">'.$this->moneyFormatIndia($data->actual_price).'</span>';
                                                        } 
                                                                                        
                                                        $dataRow .= '<span class="lastvalue">/-</span>
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btndatas">
                                        <div class="row final">
                                            <div class="col s12">
                                              <a class="" data-id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-bookingtype="cart_general" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstocartsmobile(this)">
                                                <div class="col s6 first_col">
                                                    <div id="quick-inquiry" class="marg">
                                                        Add to Cart
                                                    </div>
                                                </div>
                                              </a>
                                              <a class="" data-id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstosinglecarts(this)">
                                                <div class="col s6 second_col">
                                                    <div id="book-inquiry" class="marg">
                                                        Book Now
                                                    </div>
                                                </div>
                                              </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cartfix">
                                        <div class="added-to-cart" id="result-equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'"></div>
                                    </div>';
                                    
                                    if(!empty($data->description))
                                    {
                                        $dataRow .= '<div class="col-md-12 desci"><p><span class="bold">Note: </span><span>'.$data->description.'</span></p></div>';
                                    }
                                    

                                    if(!empty($data->description) || $data->subthemeimages_count > 0)
                                    {/*
                                        $dataRow .= '<div id="bottom-links"><div><ul id="ul-'.$data->id.'" class="fr bottom-panel d-color">';
                                        
                                        if(!empty($data->description))
                                        {
                                            $dataRow .= '<li id="'.$data->id.'map" class="bottom-list list-map_'.$data->id.' itemdesc_link"><span id="text-map-'.$data->id.'" class="txt-val" title="Description">Description</span></li>';
                                        }

                                        if($data->subthemeimages_count > 0)
                                        {
                                            $dataRow .= '<li id="'.$data->id.'photos" class="bottom-list list-photos_'.$data->id.' itemimage_link"><span id="text-photos-'.$data->id.'" class="txt-val" title="Photos">More Photos</span></li>';
                                        }
                                        
                                        $dataRow .= '</ul></div></div>'; 
                                        */
                                    }

                                $dataRow .= '</div>
                            </div>
                        </div>
                    </div>';

                    if(!empty($data->description) || !empty($data->what_included) || !empty($data->need_to_know))
                    {   
                        /*
                        $dataRow .= 
                        '<div id="mapshow_list_'.$data->id.'" class="map_list 4" style="display: none;">
                            <div class="desctitle box_style_6">
                                <h1 class="responsive-head-font" style="font-weight: bold;">'.$data->sub_theme_name.' ('.$data->label .')</h1>
                            </div>
                            <div class="map-show" style="" id="map_list_'.$data->id.'">
                                <div class="box_style_6 boxes1">
                                    <h2 class="responsive-head2-font" style="margin-top: 0px;">Description</h2>
                                    <div id="line-trugh"></div>
                                    <p class="teaser">'.$data->description.'</p>
                                </div>
                            </div>
                            <div class="near-landmarks" style="" id="nearest_landmarks_'.$data->id.'">
                                <div style="margin-top: 20px;">
                                    <div class="row mar-top-12">
                                        <div class="box_style_6 single_desc boxes2">
                                            <h3>What&#39;s included</h3>
                                            <div id="line-trugh"></div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12">
                                                <ul class="list_ok">';
        
                            foreach(explode('#', $data->what_included) as $list_item)
                            {
                                $dataRow .= '<li>'.$list_item.'</li>';
                            }
        
                            $dataRow .= '</ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
        
                            if(!empty($data->need_to_know))
                            {
                                    $dataRow .= '<div class="near-landmarks" style="width:100%;" id="nearest_landmarks_'.$data->id.'">
                                        <div style="margin-top: 20px;">
                                            <div class="row mar-top-12">
                                                <div class="box_style_6 boxes3">
                                                    <div class="single_desc_heading ">
                                                        <h3>Need to know</h3>
                                                    </div>
                                                    <div id="line-trugh1"></div>
                                                    <p></p>
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12">
                                                            <ul class="list_note">';

                                                            foreach(explode('#', $data->need_to_know) as $list_item)
                                                            {
                                                                $dataRow .= '<li>'.$list_item.'</li>';
                                                            }            

                                                            $dataRow .= '</ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                            }
        
                            $dataRow .= '</div>';
                            */
                    }
    
                    if($data->subthemeimages_count > 0)
                    {
                        /*
                        $dataRow .= '<div id="photo_list_'.$data->id.'" class="photos_list" style="display: none;">
                            <div id="photos_list_'.$data->id.'">
                                <div id="media_slider" class="space_img_slider_14207">
                                    <div class="MS-content">';

                                        foreach($data->subthemeimages as $image)
                                        {
                                            $dataRow .= '<div class="item">
                                                <div class="imgTitle">
                                                    <img style="height: 130px;" class=" lazyloaded" data-src="'.Storage::url($image->path).'" alt="photo of gallery1" src="'.Storage::url($image->path).'">
                                                </div>
                                            </div>';
                                        }
                                        
                                    $dataRow .= '</div>
                                </div>
                            </div>
                        </div>';
                        */
                    }   
            
                return $dataRow;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

		return view('website.equipments',compact(['theme_names','page_items','coupon_texts','cart_items','homesliders']));
    }

    public function packageslist(Request $request)
    {
        $homesliders = Homeslider::latest()->get();
        $packages = Package::has('packagedetails')->with('packagedetails')->whereCity('1')->get();
        $page_items = Item::where('type','package')->first();
        return view('website.packages',compact(['packages','page_items','homesliders']));
    }

    public function packages(Request $request, $id)
    {
        $homesliders = Homeslider::latest()->get();
        $packages = Package::has('packagedetails')->whereId($id)->first();
        $equipments = Theme::has('subthemes')->with('subthemes')->where('theme_type','equipment')->get();
        $page_items = Item::where('type','package')->first();

        $package_first = Package::has('packagedetails')->whereId($id)->first();

        $min_price = $package_first->packagedetails->min('price');
        $filtered = $package_first->packagedetails->where('price', $min_price);

        //$filtered->first();

        $package_include = $filtered->first()->package_include;

        $coupon_texts = Coupontext::where('trans_type','package')->get();
        $coupon_texts = $coupon_texts->toArray();

        $cart_items = [];

        if ($request->session()->has('generalcart')) {
            $cart_items = $request->session()->get('generalcart');
        }

        $package_theme_id = '';$package_detail_id = '';$package_event_date = '';$package_partidays = '';$cart_items_package = '';

        if ($request->session()->has('generalcart.package')) 
        {
            if ($request->session()->has('event_date')) 
            {
                $package_event_date = $request->session()->get('event_date');
                $package_event_date =  $package_event_date['0']; 
                //$package_event_date = \Carbon\Carbon::parse($package_event_date)->format('d/m/Y');
            }
            
            $cart_items_package = $request->session()->get('generalcart.package');

            $package_theme_id = $cart_items_package['0']['package_theme_id'];
            $package_detail_id = $cart_items_package['0']['package_detail_id'];
            $package_partidays = $cart_items_package['0']['partidays'];
        }

        if(request()->ajax())
        {
            if(!empty($_REQUEST["filter_id"]))
            {
                $subthemes_array = $request->input('filter_id');

                /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array); */

                if(!empty($_REQUEST["searchtext"]))
                {
                    $searchtext = $_REQUEST["searchtext"];
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%");
                }
                else
                {
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array);
                }

            }
            else
            {
                $themes = Theme::has('subthemes')->with('subthemes')->where('theme_type','equipment')->get();

                foreach($themes as $theme)
                {
                    $subthemes_array[] = $theme->id;
                }

                /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array); */

                if(!empty($_REQUEST["searchtext"]))
                {
                    $searchtext = $_REQUEST["searchtext"];
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%");
                }
                else
                {
                    $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array);
                }
            }

            return datatables()->of($subthemes_data)
                ->addColumn('action', function($data) {

                    $pwdid = $data->id;
                    $pwid = $data->theme->id;
                    $classname = preg_replace('/\s+/', '', $data->type);
                    $fillid = $pwdid.$pwid;
                    $label = $data->label ? $data->label : 'Hot Selling';
                    $chosetype = '';

                    $price_to_use = $data->discounted_price > 0 ? $data->discounted_price : $data->actual_price;

                    if($data->type == 'Q')
                    {
                        $classname = preg_replace('/\s+/', '', 'quantitycls');
                        $msgdata = ' &nbsp;1 Qty is &nbsp;&nbsp;';
                    }
                    else if($data->type == 'S')
                    {
                        if($data->theme->theme_name == 'LED Wall') 
                        {
                            $classname = preg_replace('/\s+/', '', $data->theme->theme_name);
                            $msgdata = ' &nbsp;Per Sq Ft is &nbsp;&nbsp;';
                        }
                        else
                        {
                            $classname = preg_replace('/\s+/', '', 'sqftcls');
                            $msgdata = ' &nbsp;Per Sq Ft is &nbsp;&nbsp;';
                        }
                    }
                    
                    $dataRow = '
                    <div class="flash-sale-card">
                        <div class="card card-pop">
                            <div class="card-body">
                                <div class="card-img">
                                    <a href="'.Storage::url($data->file).'" data-lity="">
                                        <img src="'.Storage::url($data->file).'">
                                    </a>
                                </div>';
                                
                            if($data->type == 'Q')
                            {    
                                $dataRow .= '<div class="card-contents"  id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                    <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                    <div class="row choose">
                                        <div class="col s6 lefttest">
                                            <p>Select Qty </p>
                                            <div class="cntscountequip">
                                                <div class="qty">
                                                    <div class="bord quantity buttons_added"> 
                                                        <div class="row">
                                                            <div class="col s3">
                                                                <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                            <div class="col s6">
                                                                <input id="additem-'.$fillid.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="">
                                                            </div>
                                                            <div class="col s3">
                                                                <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s6 dayspadding">
                                            <p>Select days </p>
                                            <div class="cntscountequip">
                                                <div class="qty">
                                                    <div class="bord quantity buttons_added"> 
                                                        <div class="row">
                                                            <div class="col s3">
                                                                <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                            <div class="col s6">
                                                                <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            </div>
                                                            <div class="col s3">
                                                                <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                }
                                else if($data->type == 'S')
                                {
                                    if($data->type == 'LED Wall')
                                    {
                                        $dataRow .= '<div class="card-contents" id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                            <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                            <div class="row choose">
                                               <div class="col s8 lefttest p-0">
                                                  <p>Select Sqft </p>
                                                    <div class="sqftcountequip">
                                                        <div class="col s6 text-center width">
                                                            <div class="qty">
                                                                <div class="bord quantity buttons_added"> 
                                                                    <div class="row">
                                                                        <div class="col s3">
                                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                        <div class="col s6 numbercounters">
                                                                            <input id="addsq1-'.$fillid.'" type="number" step="2" min="2" max="" name="width" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                        </div>
                                                                        <div class="col s3">
                                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="wd">width </p>
                                                        </div>
                                                        <div class="col s6 text-center width">
                                                            <div class="qty">
                                                                <div class="bord quantity buttons_added"> 
                                                                    <div class="row">
                                                                        <div class="col s3">
                                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                        <div class="col s6 numbercounters">
                                                                            <input id="addsq2-'.$fillid.'" type="number" step="2" min="2" max="" name="height" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                        </div>
                                                                        <div class="col s3">
                                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="wd">Height </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col s4 width">
                                                    <p>Select  days </p>
                                                    <div class="sqftcountequip">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>';
                                    }
                                    else
                                    {
                                        $dataRow .= '<div class="card-contents" id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                        <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                        <div class="row choose">
                                           <div class="col s8 lefttest p-0">
                                              <p>Select Sqft </p>
                                                <div class="sqftcountequip">
                                                    <div class="col s6 text-center width">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="addsq1-'.$fillid.'" type="number" step="4" min="4" max="" name="width" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="wd">width</p>
                                                    </div>
                                                    <div class="col s6 text-center width">
                                                        <div class="qty">
                                                            <div class="bord quantity buttons_added"> 
                                                                <div class="row">
                                                                    <div class="col s3">
                                                                        <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                    <div class="col s6 numbercounters">
                                                                        <input id="addsq2-'.$fillid.'" type="number" step="4" min="4" max="" name="height" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="wd">Height </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s4 width">
                                                <p>Select  days </p>
                                                <div class="sqftcountequip">
                                                    <div class="qty">
                                                        <div class="bord quantity buttons_added"> 
                                                            <div class="row">
                                                                <div class="col s3">
                                                                    <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                </div>
                                                                <div class="col s6 numbercounters">
                                                                    <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                                </div>
                                                                <div class="col s3">
                                                                    <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                        </div>';
                                    }
                                }

                                    $dataRow .= '<div style="clear: both;"></div>
                                    <div class="row price">
                                        <div class="col s12 text-center">
                                            <div id="quick-inquiry-text" class="marg">
                                                <a class="" rel="nofollow" style="text-decoration:none" href="#">
                                                    <p class="total">Total Amount : 
                                                        <span class="inrupess">₹</span>';

                                                        if(!empty($data->discounted_price))
                                                        {
                                                            $dataRow .= '<span class="strikeamt"><s>'.$this->moneyFormatIndia($data->actual_price).'</s></span>
                                                            <span class="totamonnt">'.$this->moneyFormatIndia($data->discounted_price).'</span>';
                                                        }   
                                                        else
                                                        {
                                                            $dataRow .= '<span class="totamonnt">'.$this->moneyFormatIndia($data->actual_price).'</span>';
                                                        } 
                                                                                        
                                                        $dataRow .= '<span class="lastvalue">/-</span>
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btndatas_pcks">
                                        <div class="row final">
                                            <div class="col s12">
                                                <a class="" data-id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-bookingtype="cart_general" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstocartsmobile(this)">
                                                    <div class="col s12 pckequ">
                                                        <div id="quick-inquiry" class="marg">
                                                            Add to Cart
                                                        </div>
                                                    </div>
                                               </a>     
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="cartfix">
                                       <div class="added-to-cart" id="result-equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'"></div>
                                    </div> ';
                                    
                                    if(!empty($data->description))
                                    {
                                        $dataRow .= '<div class="col-md-12 desci"><p><span class="bold">Note: </span><span>'.$data->description.'</span></p></div>';
                                    }

                                    if(!empty($data->description) || $data->subthemeimages_count > 0)
                                    {
                                        /*$dataRow .= '<div id="bottom-links"><div><ul id="ul-'.$data->id.'" class="fr bottom-panel d-color">';
                                        
                                        if(!empty($data->description))
                                        {
                                            $dataRow .= '<li id="'.$data->id.'map" class="bottom-list list-map_'.$data->id.' itemdesc_link"><span id="text-map-'.$data->id.'" class="txt-val" title="Description">Description</span></li>';
                                        }

                                        if($data->subthemeimages_count > 0)
                                        {
                                            $dataRow .= '<li id="'.$data->id.'photos" class="bottom-list list-photos_'.$data->id.' itemimage_link"><span id="text-photos-'.$data->id.'" class="txt-val" title="Photos">More Photos</span></li>';
                                        }
                                        
                                        $dataRow .= '</ul></div></div>'; 
                                        */
                                    }

                                $dataRow .= '</div>
                            </div>
                        </div>
                    </div>';

                    if(!empty($data->description) || !empty($data->what_included) || !empty($data->need_to_know))
                    {/*   
                        $dataRow .= 
                        '<div id="mapshow_list_'.$data->id.'" class="map_list 1" style="display: none;">
                            <div class="desctitle box_style_6">
                                <h1 class="responsive-head-font" style="font-weight: bold;">'.$data->sub_theme_name.' ('.$data->label .')</h1>
                            </div>
                            <div class="map-show" style="" id="map_list_'.$data->id.'">
                                <div class="box_style_6 boxes1">
                                    <h2 class="responsive-head2-font" style="margin-top: 0px;">Description</h2>
                                    <div id="line-trugh"></div>
                                    <p class="teaser">'.$data->description.'</p>
                                </div>
                            </div>
                            <div class="near-landmarks" style="" id="nearest_landmarks_'.$data->id.'">
                                <div style="margin-top: 20px;">
                                    <div class="row mar-top-12">
                                        <div class="box_style_6 single_desc boxes2">
                                            <h3>What&#39;s included</h3>
                                            <div id="line-trugh"></div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12">
                                                <ul class="list_ok">';
        
                            foreach(explode('#', $data->what_included) as $list_item)
                            {
                                $dataRow .= '<li>'.$list_item.'</li>';
                            }
        
                            $dataRow .= '</ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
        
                            if(!empty($data->need_to_know))
                            {
                                    $dataRow .= '<div class="near-landmarks" style="width:100%;" id="nearest_landmarks_'.$data->id.'">
                                        <div style="margin-top: 20px;">
                                            <div class="row mar-top-12">
                                                <div class="box_style_6 boxes3">
                                                    <div class="single_desc_heading ">
                                                        <h3>Need to know</h3>
                                                    </div>
                                                    <div id="line-trugh1"></div>
                                                    <p></p>
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12">
                                                            <ul class="list_note">';

                                                            foreach(explode('#', $data->need_to_know) as $list_item)
                                                            {
                                                                $dataRow .= '<li>'.$list_item.'</li>';
                                                            }            

                                                            $dataRow .= '</ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                            }
        
                            $dataRow .= '</div>';
                            */
                    }
    
                    if($data->subthemeimages_count > 0)
                    {/*
                        $dataRow .= '<div id="photo_list_'.$data->id.'" class="photos_list" style="display: none;">
                            <div id="photos_list_'.$data->id.'">
                                <div id="media_slider" class="space_img_slider_14207">
                                    <div class="MS-content">';

                                        foreach($data->subthemeimages as $image)
                                        {
                                            $dataRow .= '<div class="item">
                                                <div class="imgTitle">
                                                    <img style="height: 130px;" class=" lazyloaded" data-src="'.Storage::url($image->path).'" alt="photo of gallery1" src="'.Storage::url($image->path).'">
                                                </div>
                                            </div>';
                                        }
                                        
                                    $dataRow .= '</div>
                                </div>
                            </div>
                        </div>';
                        */
                    }   
            
                return $dataRow;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        //dd($packages->id);
        return view('website.packageform',compact(['equipments','packages','page_items','package_first','package_include','coupon_texts','cart_items','package_theme_id','package_detail_id','package_partidays','package_event_date','homesliders']));
    }

    public function mobilecart(Request $request, $cart_type)
    {
        $homesliders = Homeslider::latest()->get();
        $event_date = '';
        $cart_wedding = $request->session()->get('weddingcart.items'); 
        $cart_general_package = $request->session()->get('generalcart.package');
        $cart_general_equipment = $request->session()->get('generalcart.equipment');
        $cart_general_birthday = $request->session()->get('generalcart.birthday');
        
        if(is_null($cart_wedding))
        {
            $cart_wedding_count = 0;
        }
        else
        {
            $cart_wedding_count = count($cart_wedding); 
        }
        
        if(is_null($cart_general_package))
        {
            $cart_general_package_count = 0;
        }
        else
        {
            $cart_general_package_count = count($cart_general_package); 
        }
        
        if(is_null($cart_general_equipment))
        {
            $cart_general_equipment_count = 0;
        }
        else
        {
            $cart_general_equipment_count = count($cart_general_equipment); 
        }
                
        if(is_null($cart_general_birthday))
        {
            $cart_general_birthday_count  = 0;
        }
        else
        {
            $cart_general_birthday_count = count($cart_general_birthday); 
        }
        
        if ($request->session()->has('event_date')) 
        {
            $event_date = $request->session()->get('event_date');
            $event_date = $event_date['0'];
        }
        
        if($cart_type == 'wedding' && $cart_wedding_count > 0)
        {
            return view('website.cartmobile',compact(['event_date','homesliders']));
        }
        else if($cart_type == 'general' &&  ($cart_general_package_count > 0 || $cart_general_equipment_count > 0  || $cart_general_birthday_count > 0))
        {
            return view('website.cartmobile',compact(['event_date','homesliders']));
        }
        else
        {
            return redirect()->route('homepage');
        }  
    } 

}
