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
use Illuminate\Support\Arr;
use App\CustomBdayRequested;
use Illuminate\Http\Request;
use App\Mail\CustomerQuotation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
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
    
    public function index()
    {
        $homesliders = Homeslider::latest()->get();
        $coupon_texts = Coupontext::where('trans_type','homepage')->get();
        $coupon_texts = $coupon_texts->toArray();

        return view('website.index', compact(['homesliders','coupon_texts']));
    }

    public function equipments(Request $request)
    {
        /* $theme_names = Theme::where('theme_type','equipment')->get();
        return view('website.equipments',compact('theme_names')); */

        $homesliders = Homeslider::latest()->get();

        $page_items = Item::where('type','equipment')->first();
        $theme_names = Theme::has('subthemes')->with('subthemes')->where('theme_type','equipment')->get();

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
                /*             
                $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array); 
                */

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
                    
                    $dataRow = 
                    '<div id="featured-list'.$data->id.'" class="listing-normal">
                        <div class="row" itemprop="itemListElement" itemscope="" itemtype="">
                        <meta itemprop="position" content="0">
                        <div class="img-space">
                            <a href="'.Storage::url($data->file).'" data-lity>
                                <div itemprop="image" class="image-box lazy example-image" style="display:block; background-image: url(\''.Storage::url($data->file).'\');" title="'.$data->sub_theme_name.'"></div>
                            </a>
                        </div>
                        <div class="content-box">
                            <div class="row nms">
                                <div class="col-md-12 space-desc">
                                    <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                </div>
                                <div class="col-md-2 pad-right nonetable">
                                    <div class="top-right">
                                        <div class="shortbtn shortlist-space">
                                            <a rtype="list" class="shortlisted-venue dnone removeinquirycart  mm-btn-yellow" sid="'.$data->id.'" id="rem-'.$data->id.'"> <i style="font-size: 20px;" class="fa fa-heart" aria-hidden="true"></i></a>
                                            <a rtype="list" class="shortlist-venue addinquirycart  mm-btn-yellow" sid="'.$data->id.'" id="enc-'.$data->id.'"> <i style="font-size: 20px;" class="fa fa-heart-o fa-2" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>';

                            if($data->type == 'Q')
                            {    
                                $dataRow .= '<div class="row mar-top-12 nofoqt cntscountequip" id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" style="margin-bottom:10px;margin-top: 5px;margin-left: 30px !important;">
                                    <div class="col-md-6 movelft">
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px;">
                                                <span style="color:#000 !important" class="review-rating col-md-12">
                                                    <p>Select Qty</p>
                                                </span>
                                            </div>
                                            <div class="col-md-6 inpnum" style="padding:0px;">
                                                <div class="qty mt-5">
                                                    <div class="bord quantity buttons_added">
                                                        <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        <input id="additem-'.$fillid.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="">
                                                        <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'"  data-price="'.$price_to_use.'">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 movergt" style="padding-left: 0;">
                                        <div class="row nofoqt">
                                            <div class="col-md-6" style="padding:0px;">
                                                <span style="color:#000 !important" class="review-rating col-md-12">
                                                        <p class="">Select  days</p>
                                                </span>
                                            </div>
                                            <div class="col-md-6 inpnum" style="padding:0px;">
                                                <div class="qty mt-5">
                                                    <div class="bord quantity buttons_added pd-10">
                                                        <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'"  data-price="'.$price_to_use.'">
                                                        <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                        <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'"  data-price="'.$price_to_use.'">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }
                            else if($data->type == 'S')
                            {    
                                if($data->type == 'LED Wall')
                                {
                                    $dataRow .= '<div class="row mar-top-12 nofoqt sqftcountequip" id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" style="margin-bottom:10px;margin-top: 5px;margin-left: 30px !important;">
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-md-4" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating">
                                                        <p style="float:right;margin-left: 15px;">Select Sqft</p>
                                                    </span>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'"  data-price="'.$price_to_use.'">
                                                            <input id="addsq1-'.$fillid.'" type="number" step="2" min="2" max="" name="width" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'"  data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq2-'.$fillid.'" type="number" step="2" min="2" max="" name="height" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 movergt" style="padding-left: 0;">
                                            <div class="row nofoqt">
                                                <div class="col-md-6" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating col-md-12">
                                                            <p class="">Select  days </p>
                                                    </span>
                                                </div>
                                                <div class="col-md-6 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added pd-10">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        '; 
                                }
                                else
                                {
                                    $dataRow .= '<div class="row mar-top-12 nofoqt sqftcountequip" id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" style="margin-bottom:10px;margin-top: 5px;margin-left: 30px !important;">
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-md-4" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating">
                                                        <p style="float:right;margin-left: 15px;">Select Sqft</p>
                                                    </span>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq1-'.$fillid.'" type="number" step="4" min="4" max="" name="width" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq2-'.$fillid.'" type="number" step="4" min="4" max="" name="height" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 movergt" style="padding-left: 0;">
                                            <div class="row nofoqt">
                                                <div class="col-md-6" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating col-md-12">
                                                            <p class="">Select  days </p>
                                                    </span>
                                                </div>
                                                <div class="col-md-6 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added pd-10">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        '; 
                                }
                            }

                            $dataRow .= '<div id="bottom-links" class="row mar-top-10">
                                <div class="clearfix m-top-16">
                                    <div id="" class="quick-inquiry">
                                        <a class="" data-id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstosinglecarts(this)">Book Now</a>
                                    </div>
                                    <div id="" class="quick-inquiry marg">
                                        <a class="" data-id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-bookingtype="cart_general" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'"onclick="additemstocarts(this)">Add to Cart</a>
                                    </div>
                                    <div id="" class="quick-inquiry-text marg">
                                        <a class="open-inquiry" rel="nofollow" style="text-decoration:none" href="#">
                                        <p class="total">Total Amount : 
                                                <span class="inrupess">₹</span>
                                                <span class="totamonnt">'.$this->moneyFormatIndia($price_to_use).'</span>
                                                <span class="lastvalue">/-</span>
                                        </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="cartfix">
                                <div class="added-to-cart" id="result-equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'"></div>
                            </div>
                        </div>
                    </div>
                </div>';
        
                    if(!empty($data->description))
                    {
                        $dataRow .= '
                            <div class="col-md-12 desci equipstxtdata">
                                <div class="col-md-12">
                                    <p><span class="bold">Note: </span><span>'.$data->description.'</span></p></div>
                                </div>
                            </div>';
                    }
                    $dataRow .= '</div>
                            </div>';
    
                    if(!empty($data->description) || !empty($data->what_included) || !empty($data->need_to_know))
                    {   
                        $dataRow .= 
                        '<div id="mapshow_list_'.$data->id.'" class="map_list" style="display: none;">
                            <div class="desctitle box_style_6">
                                <h1 class="responsive-head-font" style="font-weight: bold;">'.$data->sub_theme_name.' ('.$data->label .')</h1>
                            </div>
                            <div class="map-show" style="width:50%;" id="map_list_'.$data->id.'">
                                <div class="box_style_6 boxes1">
                                    <h2 class="responsive-head2-font" style="margin-top: 0px;">Description</h2>
                                    <div id="line-trugh"></div>
                                    <p class="teaser">'.$data->description.'</p>
                                </div>
                            </div>
                            <div class="near-landmarks" style="width:50%;" id="nearest_landmarks_'.$data->id.'">
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
                                                        <img style="height: 130px;" class="lazyloaded" data-src="'.Storage::url($image->path).'" alt="photo of gallery1" src="'.Storage::url($image->path).'">
                                                    </a>
                                                </div>
                                            </div>';
                                        }
                                        
                                    $dataRow .= '</div>
                                </div>
                            </div>
                        </div>';
                    }   
    
                    $dataRow .= '</div>';   
            
                return $dataRow;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('website.equipments',compact(['theme_names','page_items','coupon_texts','cart_items','homesliders']));
    }

    public function birthdays(Request $request)
    {
        /* $theme_names = Theme::where('theme_type','birthday')->get();
        return view('website.birthdays',compact('theme_names')); */

        $homesliders = Homeslider::latest()->get();

        $page_items = Item::where('type','birthday')->first();
        $theme_names = Theme::has('subthemes')->with('subthemes')->where('theme_type','birthday')->get();

        $coupon_texts = Coupontext::where('trans_type','birthday')->get();
        $coupon_texts = $coupon_texts->toArray();

        $cart_items = [];

        if ($request->session()->has('generalcart')) {
            $cart_items = $request->session()->get('generalcart');
        }

        if(request()->ajax())
        {
            if(!empty($_REQUEST["filter_id"]) && empty($_REQUEST["filter_price"]))
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
            else if(empty($_REQUEST["filter_id"]) && !empty($_REQUEST["filter_price"]))
            {
                $themes = Theme::has('subthemes')->with('subthemes')->where('theme_type','birthday')->get();

                foreach($themes as $theme)
                {
                    $subthemes_array[] = $theme->id;
                }
                
                $filter_price_array = $request->input('filter_price');

                $set_1 = false;
                $set_2 = false;
                $set_3 = false;
                $set_4 = false;

                if(in_array('1', $filter_price_array))
                {
                    $set_1 = true;
                }
                if(in_array('2', $filter_price_array))
                {
                    $set_2 = true;
                }
                if(in_array('3', $filter_price_array))
                {
                    $set_3 = true;
                }
                if(in_array('4', $filter_price_array))
                {
                    $set_4 = true;
                }                

                if($set_1 == false && $set_2 == false && $set_3 == false && $set_4 == false) 
                {
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
                else if($set_1 == true && $set_2 == false && $set_3 == false && $set_4 == false) 
                {
                    /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '<=', 3500); */

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '<=', 3500)->where('sub_theme_name', 'like', "%$searchtext%");
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '<=', 3500);
                    }
                }
                else if($set_1 == false && $set_2 == true && $set_3 == false && $set_4 == false) 
                {
                    /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->whereBetween('actual_price', [3500, 5000]); */

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->whereBetween('actual_price', [3500, 5000])->where('sub_theme_name', 'like', "%$searchtext%");
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->whereBetween('actual_price', [3500, 5000]);
                    }
                }
                else if($set_1 == false && $set_2 == false && $set_3 == true && $set_4 == false) 
                {
                    /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->whereBetween('actual_price', [5000, 10000]); */

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->whereBetween('actual_price', [5000, 10000])->where('sub_theme_name', 'like', "%$searchtext%");
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->whereBetween('actual_price', [5000, 10000]);
                    }

                }
                else if($set_1 == false && $set_2 == false && $set_3 == false && $set_4 == true) 
                {
                    /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '>=', 10000); */

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '>=', 10000)->where('sub_theme_name', 'like', "%$searchtext%");
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '>=', 10000);
                    }
                }
                else if($set_1 == true && $set_2 == true && $set_3 == false && $set_4 == false) 
                {
                    /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                        $query->where('actual_price', '<=', 3500)
                              ->orWhereBetween('actual_price', [3500, 5000]);
                    }); */

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [3500, 5000]);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [3500, 5000]);
                        });
                    }                    
                }
                else if($set_1 == true && $set_2 == false && $set_3 == true && $set_4 == false) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    }       
                }
                else if($set_1 == true && $set_2 == false && $set_3 == false && $set_4 == true) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                            ->orWhere('actual_price', '>=', 10000);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                            ->orWhere('actual_price', '>=', 10000);
                        });
                    }                    
                }
                else if($set_1 == false && $set_2 == true && $set_3 == true && $set_4 == false) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    }    
                }
                else if($set_1 == false && $set_2 == true && $set_3 == false && $set_4 == true) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    }
                }
                else if($set_1 == false && $set_2 == false && $set_3 == true && $set_4 == true) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where('actual_price', '>=', 5000);
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '>=', 5000);
                    }
                }
                else if($set_1 == true && $set_2 == true && $set_3 == true && $set_4 == false) 
                {

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    }

                }
                else if($set_1 == true && $set_2 == true && $set_3 == false && $set_4 == true) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [5000, 10000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [5000, 10000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    }
                }
                else if($set_1 == false && $set_2 == true && $set_3 == true && $set_4 == true) 
                {

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    }       

                }
                else if($set_1 == true && $set_2 == true && $set_3 == true && $set_4 == true) 
                {
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
            }
            else if(!empty($_REQUEST["filter_id"]) && !empty($_REQUEST["filter_price"]))
            {               
                $filter_price_array = $request->input('filter_price');
                $subthemes_array = $request->input('filter_id');

                $set_1 = false;
                $set_2 = false;
                $set_3 = false;
                $set_4 = false;

                if(in_array('1', $filter_price_array))
                {
                    $set_1 = true;
                }
                if(in_array('2', $filter_price_array))
                {
                    $set_2 = true;
                }
                if(in_array('3', $filter_price_array))
                {
                    $set_3 = true;
                }
                if(in_array('4', $filter_price_array))
                {
                    $set_4 = true;
                }                

                if($set_1 == false && $set_2 == false && $set_3 == false && $set_4 == false) 
                {
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
                else if($set_1 == true && $set_2 == false && $set_3 == false && $set_4 == false) 
                {
                    /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '<=', 3500); */

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where('actual_price', '<=', 3500);
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '<=', 3500);
                    } 

                }
                else if($set_1 == false && $set_2 == true && $set_3 == false && $set_4 == false) 
                {
                    /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->whereBetween('actual_price', [3500, 5000]); */

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->whereBetween('actual_price', [3500, 5000]);
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->whereBetween('actual_price', [3500, 5000]);
                    } 

                }
                else if($set_1 == false && $set_2 == false && $set_3 == true && $set_4 == false) 
                {
                    /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->whereBetween('actual_price', [5000, 10000]); */

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->whereBetween('actual_price', [5000, 10000]);
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->whereBetween('actual_price', [5000, 10000]);
                    } 

                }
                else if($set_1 == false && $set_2 == false && $set_3 == false && $set_4 == true) 
                {
                    /* $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '>=', 10000); */

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where('actual_price', '>=', 10000);
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '>=', 10000);
                    } 

                }
                else if($set_1 == true && $set_2 == true && $set_3 == false && $set_4 == false) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [3500, 5000]);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [3500, 5000]);
                        });
                    } 

                }
                else if($set_1 == true && $set_2 == false && $set_3 == true && $set_4 == false) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    } 

                }
                else if($set_1 == true && $set_2 == false && $set_3 == false && $set_4 == true) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                            ->orWhere('actual_price', '>=', 10000);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                            ->orWhere('actual_price', '>=', 10000);
                        });
                    } 
                }
                else if($set_1 == false && $set_2 == true && $set_3 == true && $set_4 == false) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    } 
                }
                else if($set_1 == false && $set_2 == true && $set_3 == false && $set_4 == true) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    } 
                }
                else if($set_1 == false && $set_2 == false && $set_3 == true && $set_4 == true) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where('actual_price', '>=', 5000);
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('actual_price', '>=', 5000);
                    } 
                }
                else if($set_1 == true && $set_2 == true && $set_3 == true && $set_4 == false) 
                {
                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000]);
                        });
                    } 

                }
                else if($set_1 == true && $set_2 == true && $set_3 == false && $set_4 == true) 
                {

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [5000, 10000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->where('actual_price', '<=', 3500)
                                  ->orWhereBetween('actual_price', [5000, 10000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    } 

                }
                else if($set_1 == false && $set_2 == true && $set_3 == true && $set_4 == true) 
                {

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where('sub_theme_name', 'like', "%$searchtext%")->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array)->where(function ($query) {
                            $query->whereBetween('actual_price', [3500, 5000])
                                  ->orWhereBetween('actual_price', [5000, 10000])
                                  ->orWhere('actual_price', '>=', 10000);
                        });
                    } 

                }
                else if($set_1 == true && $set_2 == true && $set_3 == true && $set_4 == true) 
                {                    

                    if(!empty($_REQUEST["searchtext"]))
                    {
                        $searchtext = $_REQUEST["searchtext"];

                        $subthemes_data = Subtheme::withCount('subthemeimages')->where('sub_theme_name', 'like', "%$searchtext%")->whereIn('theme_id',$subthemes_array);
                    }
                    else
                    {
                        $subthemes_data = Subtheme::withCount('subthemeimages')->whereIn('theme_id',$subthemes_array);
                    } 

                }
            }
            else if(empty($_REQUEST["filter_id"]) && empty($_REQUEST["filter_price"]))
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
                    $chosetype = '';
                    $label = $data->label ? $data->label : 'Hot Selling';
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
                    
                    $dataRow = 
                    '<div id="featured-list'.$data->id.'" class="listing-normal">
                        <div class="row" itemprop="itemListElement" itemscope="" itemtype="">
                        <meta itemprop="position" content="0">
                        <div class="img-space">
                            <a  class="bdaysimg" itemprop="url" href="'.Storage::url($data->file).'" data-lity>
                                <div itemprop="image" class="image-box lazy" style="display:block; background-image: url(\''.Storage::url($data->file).'\');" title="'.$data->sub_theme_name.'"></div>
                            </a>
                        </div>
                        <div class="content-box">
                            <div class="row nms">
                                <div class="col-md-12 space-desc">
                                    <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                </div>
                                <div class="col-md-2 pad-right" style="display:none;">
                                    <div class="top-right">
                                        <div class="shortbtn shortlist-space">
                                            <a rtype="list" class="shortlisted-venue dnone removeinquirycart  mm-btn-yellow" sid="'.$data->id.'" id="rem-'.$data->id.'"> <i style="font-size: 20px;" class="fa fa-heart" aria-hidden="true"></i></a>
                                            <a rtype="list" class="shortlist-venue addinquirycart  mm-btn-yellow" sid="'.$data->id.'" id="enc-'.$data->id.'"> <i style="font-size: 20px;" class="fa fa-heart-o fa-2" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mar-top-12">
                                <div class="col-md-4 pr-0">
                                    <span class="map-mark" style="font-size: 13px!important;"><img src="'.asset('/frontend/images/2.svg').'">'.$data->label.'</span>
                                </div>
                                <div class="col-md-2">
                                    <span style="color:#5CA47A!important" class="review-rating">
                                        <img src="'.asset('/frontend/images/green-star.svg').'"> '.$data->rating.'
                                    </span>
                                </div>
                                <div class="col-md-3 pr-0 cods">
                                    <span class="space-capacity"><span><img src="'.asset('/frontend/images/capacity.svg').'"> '.$data->particular.'</span></span>
                                </div>
                                <div class="col-md-3 pad-right vws">
                                    <div class="page_views">
                                        <div class="page-viewscount">
                                            <span aria-hidden="true" data-toggle="tooltip" data-placement="top" title="" data-original-title="Total Views">'.$data->views.' Views </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ';

                            if($data->type == 'Q')
                            {    
                                $dataRow .= '<div class="row mar-top-6 mar-bottom-17 qtts" id="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                    <div class="col-md-4 mt10b rgtqty">
                                        <div class="row">
                                            <div class="row" style="padding:0px;">
                                                <span style="color:#000 !important" class="review-rating qtssq col-md-12">
                                                    <p>Select Qty</p>
                                                </span>
                                            </div>
                                            <div class="col-md-12 inpnum" style="padding:0px;">
                                                <div class="qty mt-5">
                                                    <div class="bord quantity buttons_added">
                                                        <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        <input id="additem-'.$fillid.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="count input-text qty text '.$classname.'" size="4" pattern="" inputmode="">
                                                        <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt10b leftqty">
                                        <div class="row">
                                            <div class="row" style="padding:0px;">
                                                <span style="color:#000 !important" class="review-rating qtssq col-md-12">
                                                        <p class="">Select  days</p>
                                                </span>
                                            </div>
                                            <div class="col-md-12 inpnum" style="padding:0px;">
                                                <div class="qty mt-5">
                                                    <div class="bord quantity buttons_added pd-10">
                                                        <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                        <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="" class="quick-inquiry-text marg manualclass">
                                            <a class="" rel="nofollow" style="text-decoration:none" href="#">
                                                <p class="total">
                                                    <span class="inrupess">₹</span>';

                                                    if(!empty($data->discounted_price))
                                                    {
                                                        $dataRow .= '<span class="strikeamt">'.$this->moneyFormatIndia($data->actual_price).'</span>
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
                                    <div class="col-md-4 pad-right mar-top-10"></div>
                                </div>';
                            }
                            else if($data->type == 'S')
                            {    
                                if($data->type == 'LED Wall')
                                {
                                    $dataRow .= '<div class="row mar-top-17 mar-bottom-17" id="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                        <div class="col-md-5 mt10b">
                                            <div class="row">
                                                <div class="row" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating qtssq col-md-12">
                                                        <p class="msb0 text-center" style="">Select Sqft </p>
                                                    </span>
                                                </div>
                                                <div class="col-md-12 inpnum sqftdys" style="padding:0px;">
                                                    <div class="col-md-6">
                                                        <div class="qty mt-5">
                                                            <div class="bord quantity buttons_added">
                                                                <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                <input id="addsq1-'.$fillid.'" type="number" step="2" min="2" max="" name="width" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="qty mt-5">
                                                            <div class="bord quantity buttons_added">
                                                                <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                <input id="addsq2-'.$fillid.'" type="number" step="2" min="2" max="" name="height" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="padding:0px;">
                                                    <div class="col-md-6">
                                                        <span style="color:#000 !important" class="review-rating qtssq col-md-12">
                                                            <p class="msb0 text-center" style="">Width</p>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span style="color:#000 !important" class="review-rating qtssq col-md-12">
                                                            <p class="msb0 text-center" style="">Height </p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt10b">
                                            <div class="row">
                                                <div class="col-md-12" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating qtssq">
                                                            <p class="msb0">Select  days</p>
                                                    </span>
                                                </div>
                                                <div class="col-md-12 inpnum sqdays" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 btnsquck">
                                            <div id="" class="quick-inquiry-text marg manualclass">
                                                <a class="" rel="nofollow" style="text-decoration:none" href="#">
                                                    <p class="total">
                                                        <span class="inrupess">₹</span>';

                                                        if(!empty($data->discounted_price))
                                                        {
                                                            $dataRow .= '<span class="strikeamt">'.$this->moneyFormatIndia($data->actual_price).'</span>
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
                                        <div class="col-md-4 pad-right mar-top-10"></div>
                                    </div>'; 
                                }
                                else
                                {
                                    $dataRow .= '<div class="row mar-top-17 mar-bottom-17" id="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                        <div class="col-md-5 mt10b">
                                            <div class="row">
                                                <div class="row" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating qtssq col-md-12">
                                                        <p class="msb0 text-center" style="">Select Sqft </p>
                                                    </span>
                                                </div>
                                                <div class="col-md-12 inpnum sqftdys" style="padding:0px;">
                                                    <div class="col-md-6">
                                                        <div class="qty mt-5">
                                                            <div class="bord quantity buttons_added">
                                                                <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                <input id="addsq1-'.$fillid.'" type="number" step="4" min="4" max="" name="width" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="qty mt-5">
                                                            <div class="bord quantity buttons_added">
                                                                <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                                <input id="addsq2-'.$fillid.'" type="number" step="4" min="4" max="" name="height" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                                <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                 <div class="row" style="padding:0px;">
                                                    <div class="col-md-6">
                                                        <span style="color:#000 !important" class="review-rating qtssq col-md-12">
                                                            <p class="msb0 text-center" style="">Width</p>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span style="color:#000 !important" class="review-rating qtssq col-md-12">
                                                            <p class="msb0 text-center" style="">Height </p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt10b">
                                            <div class="row">
                                                <div class="col-md-12" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating qtssq">
                                                            <p class="msb0">Select  days</p>
                                                    </span>
                                                </div>
                                                <div class="col-md-12 inpnum sqdays" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            <input type="button" value="+" class="plus" data-mainid="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 btnsquck">
                                            <div id="" class="quick-inquiry-text marg manualclass">
                                                <a class="" rel="nofollow" style="text-decoration:none" href="#">
                                                    <p class="total">
                                                        <span class="inrupess">₹</span>';

                                                        if(!empty($data->discounted_price))
                                                        {
                                                            $dataRow .= '<span class="strikeamt">'.$this->moneyFormatIndia($data->actual_price).'</span>
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
                                        <div class="col-md-4 pad-right mar-top-10"></div>
                                    </div>'; 
                                }
                            }

                            $dataRow .= '<div id="bottom-links" class="row mar-top-10">
                                <div class="clearfix m-top-16">
                                    <div class="quick-inquiry">
                                        <a class="" data-id="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstosinglecarts(this)">Book Now</a>
                                    </div>
                                    <div id="" class="quick-inquiry marg">
                                        <a class="" data-id="birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-bookingtype="cart_general" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstocarts(this)">Add to Cart</a>
                                    </div>
                                ';
        
                                if(!empty($data->description) || $data->subthemeimages_count > 0)
                                {
                                    $dataRow .= '<div><ul id="ul-'.$data->id.'" class="fr bottom-panel d-color">';
                                    if(!empty($data->description))
                                    {
                                        $dataRow .= '<li id="'.$data->id.'map" class="bottom-list list-map_'.$data->id.' itemdesc_link"><span id="text-map-'.$data->id.'" class="txt-val " title="Description">Description</span></li>';
                                    }
        
                                    if($data->subthemeimages_count > 0)
                                    {
                                        $dataRow .= '<li id="'.$data->id.'photos" class="bottom-list list-photos_'.$data->id.' itemimage_link"><span id="text-photos-'.$data->id.'" class="txt-val " title="Photos">More Photos</span></li>';
                                    }
                                    $dataRow .= '</ul></div>';
                                }
        
                                $dataRow .= '</div>
                                <div class="cartfix" style="margin-bottom: 0px;margin-top: 5px;">
                                   <div class="added-to-cart" id="result-birthday-'.$data->type.'-'.$data->id.'-'.$fillid.'"></div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    </div>';
    
                    if(!empty($data->description) || !empty($data->what_included) || !empty($data->need_to_know))
                    {   
                        $dataRow .= 
                        '<div id="mapshow_list_'.$data->id.'" class="map_list" style="display: none;">
                            <div class="desctitle box_style_6">
                                <h1 class="responsive-head-font" style="font-weight: bold;">'.$data->sub_theme_name.' ('.$data->label .')</h1>
                            </div>
                            <div class="full-complete col-md-12">
                                <div class="col-md-6 map-show" style="width:50%;" id="map_list_'.$data->id.'">
                                    <div class="boxes1">
                                        <h2 class="responsive-head2-font" style="margin-top: 0px;">Description</h2>
                                        <div id="line-trugh"></div>
                                        <p class="teaser">'.$data->description.'</p>
                                    </div>
                                </div>
                                <div class="col-md-6 near-landmarks" style="width:50%;" id="nearest_landmarks_'.$data->id.'">
                                    <div style="margin-top: 20px;">
                                        <div class="row mar-top-12">
                                            <div class="single_desc boxes2">
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
                                                        <img style="height: 130px;" class="lazyloaded" data-src="'.Storage::url($image->path).'" alt="photo of gallery1" src="'.Storage::url($image->path).'">
                                                        </a>
                                                </div>
                                            </div>';
                                        }
                                        
                                    $dataRow .= '</div>
                                </div>
                            </div>
                        </div>';
                    }   
    
                    $dataRow .= '</div>';   
            
                return $dataRow;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('website.birthdays',compact(['theme_names','page_items','coupon_texts','cart_items','homesliders']));

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
                    
                    $dataRow = 
                    '<div id="featured-list'.$data->id.'" class="listing-normal">
                        <div class="row" itemprop="itemListElement" itemscope="" itemtype="">
                        <meta itemprop="position" content="0">
                        <div class="img-space">
                            <a  class="wdsimg" itemprop="url" href="'.Storage::url($data->file).'" data-lity>
                                <div itemprop="image" class="image-box lazy s" style="display:block; background-image: url(\''.Storage::url($data->file).'\');" title="'.$data->sub_theme_name.'"></div>
                            </a>
                        </div>
                        <div class="content-box">
                            <div class="row nms">
                                <div class="col-md-12 space-desc">
                                    <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                </div>
                                <div class="col-md-2 pad-right" style="display:none;">
                                    <div class="top-right">
                                        <div class="shortbtn shortlist-space">
                                            <a rtype="list" class="shortlisted-venue dnone removeinquirycart  mm-btn-yellow" sid="'.$data->id.'" id="rem-'.$data->id.'"> <i style="font-size: 20px;" class="fa fa-heart" aria-hidden="true"></i></a>
                                            <a rtype="list" class="shortlist-venue addinquirycart  mm-btn-yellow" sid="'.$data->id.'" id="enc-'.$data->id.'"> <i style="font-size: 20px;" class="fa fa-heart-o fa-2" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mar-top-12">
                                <div class="col-md-4 pr-0">
                                    <span class="map-mark" style="font-size: 13px!important;"><img src="'.asset('/frontend/images/2.svg').'">'.$label.'</span>
                                </div>
                                <div class="col-md-2">
                                    <span style="color:#5CA47A!important" class="review-rating">
                                        <img src="'.asset('/frontend/images/green-star.svg').'"> '.$data->rating.'
                                    </span>
                                </div>
                                <div class="col-md-3 pr-0">
                                    <span class="space-capacity"><span><img src="'.asset('/frontend/images/capacity.svg').'"> '.$data->particular.'</span></span>
                                </div>
                                <div class="col-md-3 pad-right">
                                    <div class="page_views">
                                        <div class="page-viewscount">
                                            <span aria-hidden="true" data-toggle="tooltip" data-placement="top" title="" data-original-title="Total Views">'.$data->views.' Views </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ';

                            if($data->type == 'Q')
                            {    
                                $dataRow .= '<div class="row mar-top-12 nofoqt cntscount" id="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" style="margin-bottom:20px;margin-top: 20px;margin-left: 20px !important;">
                                    <div class="col-md-6 movelft">
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px;">
                                                <span style="color:#000 !important" class="review-rating col-md-12">
                                                    <p>Select Qty</p>
                                                </span>
                                            </div>
                                            <div class="col-md-6 inpnum" style="padding:0px;">
                                                <div class="qty mt-5">
                                                    <div class="bord quantity buttons_added">
                                                        <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        <input id="additem-'.$fillid.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="">
                                                        <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 movergt" style="padding-left: 0;">
                                        <div class="row nofoqt">
                                            <div class="col-md-6" style="padding:0px;">
                                                <span style="color:#000 !important" class="review-rating col-md-12">
                                                        <p class="">Select  days </p>
                                                </span>
                                            </div>
                                            <div class="col-md-6 inpnum" style="padding:0px;">
                                                <div class="qty mt-5">
                                                    <div class="bord quantity buttons_added pd-10">
                                                        <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                        <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
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
                                    $dataRow .= '<div class="row mar-top-12 nofoqt sqftcount" style="" id="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-md-4" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating">
                                                        <p style="float:right;margin-left: 15px;">Select Sqft</p>
                                                    </span>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq1-'.$fillid.'" type="number" step="2" min="2" max="" name="width" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq2-'.$fillid.'" type="number" step="2" min="2" max="" name="height" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 movergt" style="padding-left: 0;">
                                            <div class="row nofoqt">
                                                <div class="col-md-6" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating col-md-12">
                                                            <p class="">Select  days</p>
                                                    </span>
                                                </div>
                                                <div class="col-md-6 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>'; 
                                }
                                else
                                {
                                    $dataRow .= '<div class="row mar-top-12 nofoqt sqftcount" style="" id="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'">
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-md-4" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating">
                                                        <p style="float:right;margin-left: 15px;">Select Sqft</p>
                                                    </span>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq1-'.$fillid.'" type="number" step="4" min="4" max="" name="width" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq2-'.$fillid.'" type="number" step="4" min="4" max="" name="height" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 movergt" style="padding-left: 0;">
                                            <div class="row nofoqt">
                                                <div class="col-md-6" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating col-md-12">
                                                            <p class="">Select  days </p>
                                                    </span>
                                                </div>
                                                <div class="col-md-6 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            <input type="button" value="+" class="plus" data-mainid="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        '; 
                                }
                            }

                            $dataRow .= '<div id="bottom-links" class="row mar-top-10">
                                <div class="clearfix m-top-16">
                                    <div class="quick-inquiry">
                                        <a class="" data-id="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstosinglecarts(this)">Enquire Now</a>
                                    </div>
                                    <div id="" class="quick-inquiry marg">
                                        <a class="" data-id="wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-bookingtype="cart_wedding" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstocarts(this)">Add to Cart</a>
                                    </div>
                                ';
        
                                if(!empty($data->description) || $data->subthemeimages_count > 0)
                                {
                                    $dataRow .= '<div><ul id="ul-'.$data->id.'" class="fr bottom-panel d-color">';
                                    if(!empty($data->description))
                                    {
                                        $dataRow .= '<li id="'.$data->id.'map" class="bottom-list list-map_'.$data->id.' itemdesc_link"><span id="text-map-'.$data->id.'" class="txt-val" title="Description">Description</span></li>';
                                    }
        
                                    if($data->subthemeimages_count > 0)
                                    {
                                        $dataRow .= '<li id="'.$data->id.'photos" class="bottom-list list-photos_'.$data->id.' itemimage_link"><span id="text-photos-'.$data->id.'" class="txt-val" title="Photos">More Photos</span></li>';
                                    }
                                    $dataRow .= '</ul></div>';
                                }
        
                                $dataRow .= '</div>
                                
                            </div>
                                <div class="cartfix" style="margin-bottom: 5px;margin-top: 5px;">
                                    <div class="added-to-cart" id="result-wedding-'.$data->type.'-'.$data->id.'-'.$fillid.'"></div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>';
    
                    if(!empty($data->description) || !empty($data->what_included) || !empty($data->need_to_know))
                    {   
                        $dataRow .= 
                        '<div id="mapshow_list_'.$data->id.'" class="map_list" style="display: none;">
                            <div class="desctitle box_style_6">
                                <h1 class="responsive-head-font" style="font-weight: bold;">'.$data->sub_theme_name.' ('.$data->label .')</h1>
                            </div>
                            <div class="full-complete col-md-12">
                                <div class="col-md-6 map-show" style="width:50%;" id="map_list_'.$data->id.'">
                                    <div class=" boxes1">
                                        <h2 class="responsive-head2-font" style="margin-top: 0px;">Description</h2>
                                        <div id="line-trugh"></div>
                                        <p class="teaser">'.$data->description.'</p>
                                    </div>
                                </div>
                            <div class="col-md-6 near-landmarks" style="width:50%;" id="nearest_landmarks_'.$data->id.'">
                                <div style="margin-top: 20px;">
                                    <div class="row mar-top-12">
                                        <div class=" single_desc boxes2">
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
                            </div></div>';
        
                            if(!empty($data->need_to_know))
                            {
                                    $dataRow .= '<div class="near-landmarks pdnglft" style="width:100%;" id="nearest_landmarks_'.$data->id.'">
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
    
                    $dataRow .= '</div>';   
            
                return $dataRow;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('website.weddings',compact(['theme_names','page_items','coupon_texts','cart_items','homesliders']));
    }

    public function changepackage(Request $request)
    {
        $id =  $request->id;
        $package_first = Package::whereId($id)->first();
        $package_name = $package_first->package_name;
        $package_description = $package_first->description;
        $package_image = $package_first->package_image;

        $packagedetails = $package_first->packagedetails;
        $min_price = $package_first->packagedetails->min('price');
        $filtered = $package_first->packagedetails->where('price', $min_price);
        $filtered->first();
        $package_include = $filtered->first()->package_include;

        $resultString = [];

        $resultString['package_include'] = $package_include;
        $resultString['min_price'] = $min_price;
        $resultString['package_name'] = $package_name;
        $resultString['package_description'] = $package_description;
        $resultString['packagedetails'] = $packagedetails;
        $resultString['package_image'] = Storage::url($package_image);
        echo(json_encode($resultString));
    }

    public function changepackagedetail(Request $request)
    {
        $id =  $request->id;
        $packagedetail = PackageDetail::whereId($id)->first();
        $package_price = $packagedetail->price;
        $package_include = $packagedetail->package_include;

        $resultString = [];

        $resultString['package_price'] = $package_price;
        $resultString['package_include'] = $package_include;
        echo(json_encode($resultString));
    }

    public function packages(Request $request)
    {
        $homesliders = Homeslider::latest()->get();
        $packages = Package::has('packagedetails')->whereCity('1')->get();
        $equipments = Theme::has('subthemes')->with('subthemes')->where('theme_type','equipment')->get();
        $page_items = Item::where('type','package')->first();

        $package_first = Package::has('packagedetails')->whereCity('1')->first();

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
                    
                    $dataRow = 
                    '<div id="featured-list'.$data->id.'" class="listing-normal">
                        <div class="row" itemprop="itemListElement" itemscope="" itemtype="">
                        <meta itemprop="position" content="0">
                        <div class="img-space">
                            <a itemprop="url" href="'.Storage::url($data->file).'" data-lity>
                                <div itemprop="image" class="image-box lazy venue-image" style="display:block; background-image: url(\''.Storage::url($data->file).'\');" title="'.$data->sub_theme_name.'"></div>
                            </a>
                        </div>
                        <div class="content-box">
                            <div class="row nms">
                                <div class="col-md-12 space-desc">
                                    <h2><a href="#">'.$data->sub_theme_name.'</a></h2>
                                </div>
                                <div class="col-md-2 pad-right" style="display:none;">
                                    <div class="top-right">
                                        <div class="shortbtn shortlist-space">
                                            <a rtype="list" class="shortlisted-venue dnone removeinquirycart  mm-btn-yellow" sid="'.$data->id.'" id="rem-'.$data->id.'"> <i style="font-size: 20px;" class="fa fa-heart" aria-hidden="true"></i></a>
                                            <a rtype="list" class="shortlist-venue addinquirycart  mm-btn-yellow" sid="'.$data->id.'" id="enc-'.$data->id.'"> <i style="font-size: 20px;" class="fa fa-heart-o fa-2" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>';

                            if($data->type == 'Q')
                            {    
                                $dataRow .= '<div class="row mar-top-12 nofoqt cntscountequip" id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" style="margin-bottom:10px;margin-top: 5px;margin-left: 30px !important;">
                                    <div class="col-md-6 movelft">
                                        <div class="row">
                                            <div class="col-md-6" style="padding:0px;">
                                                <span style="color:#000 !important" class="review-rating col-md-12">
                                                    <p>Select Qty</p>
                                                </span>
                                            </div>
                                            <div class="col-md-6 inpnum" style="padding:0px;">
                                                <div class="qty mt-5">
                                                    <div class="bord quantity buttons_added">
                                                        <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        <input id="additem-'.$fillid.'" type="number" step="1" min="1" max="" name="quantity" value="1" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="">
                                                        <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 movergt" style="padding-left: 0;">
                                        <div class="row nofoqt">
                                            <div class="col-md-6" style="padding:0px;">
                                                <span style="color:#000 !important" class="review-rating col-md-12">
                                                        <p class="">Select  days</p>
                                                </span>
                                            </div>
                                            <div class="col-md-6 inpnum" style="padding:0px;">
                                                <div class="qty mt-5">
                                                    <div class="bord quantity buttons_added pd-10">
                                                        <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                        <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }
                            else if($data->type == 'S')
                            {    
                                if($data->type == 'LED Wall')
                                {
                                    $dataRow .= '<div class="row mar-top-12 nofoqt sqftcountequip" id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" style="margin-bottom:10px;margin-top: 5px;margin-left: 30px !important;">
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-md-4" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating">
                                                        <p style="float:right;margin-left: 15px;">Select Sqft</p>
                                                    </span>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq1-'.$fillid.'" type="number" step="2" min="2" max="" name="width" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq2-'.$fillid.'" type="number" step="2" min="2" max="" name="height" value="2" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 movergt" style="padding-left: 0;">
                                            <div class="row nofoqt">
                                                <div class="col-md-6" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating col-md-12">
                                                            <p class="">Select  days </p>
                                                    </span>
                                                </div>
                                                <div class="col-md-6 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added pd-10">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    '; 
                                }
                                else
                                {
                                    $dataRow .= '<div class="row mar-top-12 nofoqt sqftcountequip" id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" style="margin-bottom:10px;margin-top: 5px;margin-left: 30px !important;">
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-md-4" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating">
                                                        <p style="float:right;margin-left: 15px;">Select Sqft</p>
                                                    </span>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq1-'.$fillid.'" type="number" step="4" min="4" max="" name="width" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="addsq2-'.$fillid.'" type="number" step="4" min="4" max="" name="height" value="4" title="Qty" class="count1 input-text qty text '.$classname.'" size="4" pattern="" inputmode="" readonly>
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 movergt" style="padding-left: 0;">
                                            <div class="row nofoqt">
                                                <div class="col-md-6" style="padding:0px;">
                                                    <span style="color:#000 !important" class="review-rating col-md-12">
                                                            <p class="">Select  days </p>
                                                    </span>
                                                </div>
                                                <div class="col-md-6 inpnum" style="padding:0px;">
                                                    <div class="qty mt-5">
                                                        <div class="bord quantity buttons_added pd-10">
                                                            <input type="button" value="-" class="minus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                            <input id="additem_partidays-'.$fillid.'" type="number" step="1" min="1" max="" name="partidays" value="1" title="Days" class="count1 input-text qty text partidays" size="4" pattern="" inputmode="">
                                                            <input type="button" value="+" class="plus" data-mainid="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" data-price="'.$price_to_use.'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        ';
                                }
                            }

                            $dataRow .= '<div id="bottom-links" class="row mar-top-10">
                                <div class="clearfix m-top-16">
                                    <div id="" class="quick-inquiry" style="display:none;">
                                        <a class="" data-id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'" onclick="additemstosinglecarts(this)">Book Now</a>
                                    </div>
                                    <div id="" class="quick-inquiry marg">
                                        <a class="" data-id="equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'" rel="nofollow" style="text-decoration:none" href="javascript:void(0)" data-itemtype="'.$data->type.'" data-bookingtype="cart_general" data-itemdetails="add^'.$pwdid.'^'.$pwid.'^'.$data->sub_theme_name.'^'.$data->type.'^'.$price_to_use.'^'.$data->particular.'^cartaddedstatus'.$fillid.'^'.$data->theme->theme_name.'"onclick="additemstocarts(this)">Add to Cart</a>
                                    </div>
                                    <div id="" class="quick-inquiry-text marg">
                                        <a class="open-inquiry" rel="nofollow" style="text-decoration:none" href="#">
                                        <p class="total">Total Amount : 
                                                <span class="inrupess">₹</span>
                                                <span class="totamonnt">'.$this->moneyFormatIndia($price_to_use).'</span>
                                                <span class="lastvalue">/-</span>
                                        </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="cartfix">
                                    <div class="added-to-cart" id="result-equipment-'.$data->type.'-'.$data->id.'-'.$fillid.'"></div>
                                </div>
                            </div>
                        </div>';
                        if(!empty($data->description))
                        {
                            $dataRow .= ' <div class="col-md-12 desci equipstxtdata">
                                <div class="col-md-12">
                                    <p><span class="bold">Note: </span><span>'.$data->description.'</span></p></div>
                                </div>
                            </div>';
                        }
                        $dataRow .= '</div>
                                </div>
                            </div>';
    
                    if(!empty($data->description) || !empty($data->what_included) || !empty($data->need_to_know))
                    {   
                        $dataRow .= 
                        '<div id="mapshow_list_'.$data->id.'" class="map_list" style="display: none;">
                            <div class="desctitle box_style_6">
                                <h1 class="responsive-head-font" style="font-weight: bold;">'.$data->sub_theme_name.' ('.$data->label .')</h1>
                            </div>
                            <div class="map-show" style="width:50%;" id="map_list_'.$data->id.'">
                                <div class="box_style_6 boxes1">
                                    <h2 class="responsive-head2-font" style="margin-top: 0px;">Description</h2>
                                    <div id="line-trugh"></div>
                                    <p class="teaser">'.$data->description.'</p>
                                </div>
                            </div>
                            <div class="near-landmarks" style="width:50%;" id="nearest_landmarks_'.$data->id.'">
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
    
                    $dataRow .= '</div>';   
            
                return $dataRow;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('website.packages',compact(['equipments','packages','page_items','package_first','package_include','coupon_texts','cart_items','package_theme_id','package_detail_id','package_partidays','package_event_date','homesliders']));
    }

    public function about()
    {
        $homesliders = Homeslider::latest()->get();
        $coupon_texts = Coupontext::where('trans_type','general')->get();
        $coupon_texts = $coupon_texts->toArray();

        return view('website.about',compact(['coupon_texts','homesliders']));
    }

    public function terms()
    {
        $homesliders = Homeslider::latest()->get();
        $coupon_texts = Coupontext::where('trans_type','general')->get();
        $coupon_texts = $coupon_texts->toArray();

        return view('website.terms',compact(['coupon_texts','homesliders']));
    }

    public function faq()
    {
        $homesliders = Homeslider::latest()->get();
        $coupon_texts = Coupontext::where('trans_type','general')->get();
        $coupon_texts = $coupon_texts->toArray();

        return view('website.faq',compact(['coupon_texts','homesliders']));
    }

    public function homemodalmail(Request $request)
    {
        if($request->ajax())
        {
            $rules = array(
                'name'    =>  'required',
                'email'     =>  'required',
                'mobile'         =>  'required'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $name = $request->name;
            $email = $request->email;
            $mobile = $request->mobile;
            $message = $request->message;

            $resultString = [];

            $data = array('name'=>$name, 'email' => $email, 'mobile' => $mobile, 'message' => $message);
            $mailstatus = Mail::to('samsrivastva@gmail.com')->send(new HomemodalMail($data));

            if (Mail::failures())
            {
                return response()->json(['errors' => ['Mail can not send now. Please try later']]);
            }
            else
            {
                return response()->json(['success' => 'Thank you for writing to us. Our team will call back shortly.']);
            }

        }
    }

    public function bdaymodalmail(Request $request)
    {
        if($request->ajax())
        {
            $path = '';

            $rules = array(
                'name'    =>  'required',
                'email'     =>  'required',
                'mobile'         =>  'required',
                'bday_image'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $path = $request->file('bday_image')->store('customer_uploads/custom_bdays');

            $name = $request->name;
            $email = $request->email;
            $mobile = $request->mobile;
            $message = $request->message;

            $form_data = array(
                'name'         =>  $name,
                'email'         =>  $email,
                'mobile'       =>  $mobile,
                'file'       =>  $path,
                'message'    =>  $message
            );

            CustomBdayRequested::create($form_data);

            $resultString = [];

            $data = array('name'=>$name, 'email' => $email, 'mobile' => $mobile, 'message' => $message, 'file' => $path);

            $mailstatus = Mail::to('samsrivastva@gmail.com')->send(new BdayModalMail($data));

            if (Mail::failures())
            {
                return response()->json(['errors' => ['Mail can not send now. Please try later']]);
            }
            else
            {
                return response()->json(['success' => 'Thank you for writing to us. Our team will call back shortly.']);
            }

        }
    }

    public function additemstosinglecart(Request $request)
    {
        if($request->ajax())
        {
            $particular = $request->particular;
            $item_qty = $request->item_qty;
            $item_height = $request->item_height;
            $item_width = $request->item_width;
            $item_price = $request->item_price;
            $net_price = $request->net_price;
            $pack_desc = $request->pack_desc;
            $partidays = $request->partidays;
            $trans_type = $request->trans_type;
            $booking_type = $request->booking_type;

            if (empty($item_qty)) { 
                $item_qty = "";
            } 
            if (empty($item_height)) { 
                $item_height = "";
            } 
            if (empty($item_width)) { 
                $item_width = "";
            } 
            if (empty($item_price)) { 
                $item_price = "";
            } 

            $single_cart_total = $net_price;

            $singlecart = ['particular' => $particular,'item_qty' => $item_qty,'item_height' => $item_height,'item_width' => $item_width,'item_price' => $item_price,'net_price' => $net_price,'pack_desc' => $pack_desc,'partidays' => $partidays,'trans_type' => $trans_type,'single_cart_total' => $single_cart_total];

            $cartdata = $request->session()->put('singlecart', $singlecart);
            $request->session()->put('booking_type', $booking_type);

            if ($request->session()->has('promocode')) 
            {
                $request->session()->forget('promocode');
                Session::save();
            }

            //Session::put('singlecart', $singlecart);

            $resultString = [];

            if ($request->session()->has('singlecart'))
            {
                $resultString['status'] = 1;
                echo(json_encode($resultString));
            }
            else
            {
                $resultString['status'] = 2;
                echo(json_encode($resultString));
            }

        }
    }

    //below function is to create a cart like structure for single booking of package when "book now" is clicked from packages listing page, 
    //its actual booking takes place in completesinglecart(), where we make entry to the cart.
    public function additemstosinglecartpackage(Request $request)
    {
        if($request->ajax())
        {
            $event_date = $request->event_date;
            $no_of_days = $request->no_of_days;
            $package_detail_id = $request->package_detail_id;
            $package_price = $request->package_price;
            $trans_type = 'package';
            $booking_type = 'cart_single';

            $single_cart_total = $no_of_days * $package_price;

            $singlecart = ['event_date' => $event_date,'no_of_days' => $no_of_days,'package_detail_id' => $package_detail_id,'package_price' => $package_price,'trans_type' => $trans_type,'single_cart_total' => $single_cart_total];

            $cartdata = $request->session()->put('singlecart', $singlecart);
            $request->session()->put('booking_type', $booking_type);
            //Session::put('singlecart', $singlecart);

            $resultString = [];

            if ($request->session()->has('singlecart'))
            {
                $resultString['status'] = 1;
                echo(json_encode($resultString));
            }
            else
            {
                $resultString['status'] = 2;
                echo(json_encode($resultString));
            }

        }
    }

    public function completesinglecart(Request $request)
    {
        if($request->ajax())
        {
            if ($request->session()->has('singlecart'))
            {
                $resultString = [];
                $mydateinsert = date('d/m/Y');

                $cus_name = '';$cus_email = '';$cus_mobile = '';$no_of_days = 0;$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
                $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
                $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = 0;$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
                $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = 0;$manual_discount_row = '';$razorid_city = '';
                $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;$discount_amnt = '';$promocode = '';$discountgiven = '';
                $discount_percent = '';

                $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';$event_coordinate = '';$pricetoinsert = 0;$item_json = '';$itemsadded_json ='';
                $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';
                $packages_row = '';$coupon_row = ''; $total_data_amount = 0;$total_price = 0;$db_cart_type = 'general';$show_amount = 'yes';
                $today = date("d-m-y-his");
                $curdate = date("d-m-Y-h:i:s");

                /****************Data from single booking form comes here***************************/

                $name = urldecode($request->cus_name);
                $email = urldecode($request->cus_email);
                $mobile = $request->cus_mobile;
                $event_date = $request->event_date;
                $description = urldecode($request->cus_description);

                /****************Data from single booking form ends here***************************/

                $data =  new Quotation();
                $singlecart = $request->session()->get('singlecart'); 
                $trans_type = $singlecart['trans_type'];

                if($trans_type == 'wedding')
                {
                    $db_cart_type = 'wedding';
                    $show_amount = 'no';
                }
                else
                {
                    $db_cart_type = 'general';
                    $show_amount = 'yes';
                }

                if($trans_type == 'package')
                {
                    $package_detail_id = $singlecart['package_detail_id'];
                    $no_of_days = $singlecart['no_of_days'];
                    $package_price = $singlecart['package_price'];
                    
                    $package_detail = PackageDetail::whereId($package_detail_id)->first();

                    $package_name = $package_detail->package->package_name;
                    $no_of_guest_exp = $package_detail->no_of_guest_exp;

                    $total_data_amount = $package_price* $no_of_days;

                    $items = array();
                    $item_json = json_encode($items);
                    $itemsadded = array();
                    $itemsadded_json = json_encode($itemsadded);
                }
                else 
                {
                    $package_detail_id = NULL;

                    $listitems = $singlecart['particular'].'^'.$singlecart['item_qty'].'^'.$singlecart['item_height'].'^'.$singlecart['item_width'].'^'.$singlecart['item_price'].'^'.$singlecart['net_price'].'^'.$singlecart['pack_desc'].'^'.$singlecart['partidays'].'*';
                    $listitems = substr($listitems, 0, -1);
                            
                    $listitems = explode("*",$listitems);
                    $itemCount = count($listitems);
    
                    $items = array();
                    $j = 0;
                    
                    if(count(array_filter($listitems)) != 0) 
                    {
                        for($i=1; $i<= $itemCount; $i++)
                        {
                            $oneitem = $listitems[$j];
                            $oneitem = explode("^",$oneitem);

                            $items[$i]['particular'] = $oneitem[0];
                            $items[$i]['item_qty'] = $oneitem[1];
                            $items[$i]['item_height'] = $oneitem[2];
                            $items[$i]['item_width'] = $oneitem[3];
                            $items[$i]['item_price'] = $oneitem[4];
                            $items[$i]['net_price'] = $oneitem[5];
                            $items[$i]['pack_desc'] = $oneitem[6];
                            $items[$i]['partidays'] = $oneitem[7];

                            $pricetoinsert = $pricetoinsert + $oneitem[5];                            
                            $j++;
                        }
                    }
                        
                    $item_json = json_encode($items);
    
                    $itemsadded = array();
                    $k = 0;
                    $itemsadded_json = json_encode($itemsadded);
                }
              
                /*----------------------------------Adding data to the Quotation-------------------------------*/

                if($trans_type != 'wedding')
                {
                    if($request->filled('manual_discount') && $request->manual_discount > 0) 
                    {
                        $manual_discount = $request->manual_discount ? $request->manual_discount : 0;
                    }
                    else
                    {
                        $manual_discount = 0;
                    }

                    if (Auth::check() && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) 
                    {
                        $discount_percent = Auth::user()->discount;
                        $discount_amnt = round($pricetoinsert - ( $pricetoinsert *( $discount_percent/100 ) ) );
                    }
                    else 
                    {
                            if($request->filled('promocode')) 
                            {
                                $promocode = $request->promocode;
                                $coupon = Coupon::where('couponcode','=',$promocode)->where('trans_type','=',$trans_type)->latest()->first();
                                $min_amount = $coupon->min_amount;
        
                                if($pricetoinsert >= $min_amount)
                                {
                                    $discountgiven = $coupon->discount;
                                    $pricetoinsert = $pricetoinsert - $discountgiven;                    
                                }
                                else
                                {
                                    $promocode = '';
                                    $discountgiven = '';
                                }                            
                            }
                            else
                            {
                                $promocode = '';
                                $discountgiven = '';
                                $discount_percent = '';
                                $discount_amnt = '';
                            }
                    }

                    $total_data_amount = $pricetoinsert;
                }

                if($request->filled('event_coordinate') && $request->event_coordinate > 0) {
                    $event_coordinate = $request->event_coordinate ? $request->event_coordinate : 0;
                }
                else {
                    $event_coordinate = '0';
                }

                if($request->filled('transport_cost') && $request->transport_cost > 0) {
                    $transport_cost = $request->transport_cost ? $request->transport_cost : NULL;
                }
                else {
                    $transport_cost = NULL;
                }

                if($request->filled('crew_cost') && $request->crew_cost > 0) {
                    $crew_cost = $request->crew_cost ? $request->crew_cost : NULL;
                }
                else  {
                    $crew_cost = NULL;
                }

                /* if($add_gst == 'yes') { $add_gst_value = '1'; } else { $add_gst_value = '0'; } */

                if(true) { $add_gst_value = '1'; } else { $add_gst_value = '0'; }
                
                /*----------------------------------Adding data to the Quotation-------------------------------*/

                $filename = $today.'-Tosshead.pdf';     
                $filename = 'admin_uploads/pdf/'.$filename;           

                $data->item = $item_json;
                $data->name = $name;
                $data->email = $email;
                $data->mobile = $mobile;
                $data->pdf = $filename;
                $data->event_date = $event_date;
                $data->total_price = $total_data_amount;
                $data->added_item = $itemsadded_json;
                $data->crew_cost = $crew_cost;
                $data->transport_cost = $transport_cost;
                $data->add_gst = $add_gst_value;
                $data->manual_discount = $manual_discount;
                $data->cc_mails = $cc_mails;
                $data->bcc_mails = $bcc_mails;
                $data->booking_type = $db_cart_type;
                $data->no_of_days = $no_of_days;
                $data->show_amount = $show_amount;

                $data->package_detail_id = $package_detail_id;
                $data->package_price = $package_price;
                
                $data->description = $description;
                $data->event_coordinate = $event_coordinate;
                $data->quote_type = 'qtsuccessownagent';

                if($transport_cost == NULL) {
                    $data->transport_cost = NULL;
                }
                if($crew_cost == NULL) {
                    $data->crew_cost = NULL;
                }
                if($package_detail_id == NULL) {
                    $data->package_detail_id = NULL;
                }

                $data->save();

                /*---------------add data to payorders-------------*/

                $payorder = new Payorder();
                $payorder->quoteid = $data->id;
                $payorder->invoicedate = $mydateinsert;
                $payorder->quotedate = $mydateinsert;
                $payorder->razorid = '';
                $payorder->city = 'Bangalore';

                $payorder->save();

                /*---------------ends data to payorders-------------*/

                /*---------------add discount to quotations-------------*/              

                if($discount_percent != 0 && !empty($discount_amnt)) 
                {
                    if (Auth::check() && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) 
                    {
                        $package_price_data = $data->package_price;
                        $no_of_days_data = $data->no_of_days;
                
                        /* if($package_detail_id != NULL) 
                        {
                            $total_data_amount = $pricetoinsert + $package_price_data * $no_of_days_data;
                        }
                        else 
                        {
                            $total_data_amount = $pricetoinsert;
                        } */

                        $user = Auth::user();
                        $onlineinquiry = new OnlineInquiry();
                        $onlineinquiry->created_users = $user->id;
                        $onlineinquiry->quotation = $data->id;
                        $onlineinquiry->discount = $discount_percent;
                        $onlineinquiry->amount = $total_data_amount;
                        $onlineinquiry->save();
                    }
                }
                else if($discountgiven != 0 && !empty($promocode)) 
                {
                    $onlinecoupon = new OnlineCoupon();
                    $onlinecoupon->quotation = $data->id;
                    $onlinecoupon->promocode = $promocode;
                    $onlinecoupon->discountgiven = $discountgiven;
                    $onlinecoupon->save();
                }

                /*---------------ends discount to quotations-------------*/                       
                /*---------------generate pdf and send mail to the user comes-------------*/  

                $rowcounts = Quotation::where('id','=',$data->id)->count();

                if($rowcounts == 1)
                {
                    $quote_data = $this->getquotedetails($data->id);
                    
                    $cus_name = $quote_data['cus_name'];
                    $cus_mobile = $quote_data['cus_mobile'];
                    $cus_email = $quote_data['cus_email'];
                    $mailbody = $quote_data['mailbody'];
                    $cc_mails = $quote_data['cc_mails'];
                    $bcc_mails = $quote_data['bcc_mails'];
                    $amountpaypable = $quote_data['amountpaypable'];
                    $db_cart_type = $quote_data['db_cart_type'];
                    $db_show_amount = $quote_data['db_show_amount'];

                    if($db_show_amount == 'no')
                    {
                        $bodytop = '<div class="col-md-12" style="margin-top:2%;">
                            <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                            <p style="text-align:left;"><strong>Your Wedding Essential is successfully registered !</strong></p>
                            <p style="text-align:left;">Your enquiry id is <b>'.$cus_mobile.'</b>. Our wedding expert will revert back to you with the best possible price. 
                            Below are your wedding requirements submitted for your reference</p>
                        </div>';
                    }
                    else
                    {
                        $bodytop = '<div class="col-md-12" style="margin-top:2%;">
                            <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                            <p style="text-align:left;"><strong>Your Event Booking is Confirmed !</strong></p>
                            <p style="text-align:left;">Your booking id <b>'.$cus_mobile.'</b>. Kindly check below quotation and proceed to make payment.</p>
                        </div>';
                    }

                    $message = $bodytop.''.$mailbody;

                    $base_url = env('APP_URL').'/payment/';
                    $quotelink = $base_url.encrypt($data->id);
                     
                    if($db_show_amount == 'yes')
                    {
                        $message .= '<div style="text-align: center;width: 100%;"><br><br></div><div style="text-align: center;width: 100%;">
                            <a href="'.$quotelink.'" target="_blank" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Pay Now</a>
                        </div>';
                    }

                    $message .= '<p>Thank you for choosing Tosshead for your Event requirement ,incase of any queries kindly call us <strong>@ 8448444942</strong> or email us <strong>support@tosshead.com</strong> we will be happy to assist you.<br><br>Warm Regards<br>Team TOSSHEAD</p>';

                    $to = $cus_email;

                    /*----------Added for auto sms sending when booking is done-----------*/

                    if($db_show_amount == 'yes')
                    {
                        $msg_data = "Dear $cus_name,
                        Your Event Booking with Tosshead Events is confirmed. Please find the quotation link shared below and proceed to make the payment:

                        $quotelink";
                        $msg_data = urlencode($msg_data);
                        $ch=curl_init();
                        curl_setopt($ch,CURLOPT_URL,"https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=NfWOdck7dc5&MobileNo=".$cus_mobile."&SenderID=TOSSHE&Message=".$msg_data."&ServiceName=TEMPLATE_BASED");
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
                        $output =curl_exec($ch);
                        curl_close($ch);
                    }
                    else
                    {
                        $msg_data = "Dear $cus_name,
                        Your Wedding Essential is successfully registered. Please find the details in the link shared below :

                        $quotelink";
                        $msg_data = urlencode($msg_data);
                        $ch=curl_init();
                        curl_setopt($ch,CURLOPT_URL,"https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=NfWOdck7dc5&MobileNo=".$cus_mobile."&SenderID=TOSSHE&Message=".$msg_data."&ServiceName=TEMPLATE_BASED");
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
                        $output =curl_exec($ch);
                        curl_close($ch);
                    }

                    /*----------Ends Added for auto sms sending when booking is done-----------*/

                    $pdf = PDF::loadView('pdfs.invoices', array('mailbody'=>$mailbody))->setPaper('a4', 'landscape');
                    $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true]);

                    //$filepath = 'admin_uploads/pdf/'.$filename;
                    Storage::put($filename, $pdf->output());

                    $mailstatus = Mail::to($cus_email)->cc(['support@tosshead.com','samsrivastva@gmail.com'])->send(new CustomerQuotation($message));

                    if (Mail::failures())
                    {
                        $resultString['status'] = 2;
                        $resultString['trans_type'] = $trans_type;
                        $resultString['quotelink'] = $quotelink;
                        echo(json_encode($resultString));
                    }
                    else
                    {
                        $resultString['status'] = 1;
                        $resultString['trans_type'] = $trans_type;
                        $resultString['quotelink'] = $quotelink;
                        echo(json_encode($resultString));
                    }
                }

                /*---------------generate pdf and send mail to the user ends-------------*/   
            }
            else 
            {
                $resultString['status'] = 2;
                $resultString['trans_type'] = '';
                $resultString['quotelink'] = '';
                echo(json_encode($resultString));
            }
        }
    }

    public function additemstocart(Request $request)
    {
        if($request->ajax())
        {
            $particular = $request->particular;
            $item_qty = $request->item_qty;
            $item_height = $request->item_height;
            $item_width = $request->item_width;
            $item_price = $request->item_price;
            $net_price = $request->net_price;
            $pack_desc = $request->pack_desc;
            $partidays = $request->partidays;
            $trans_type = $request->trans_type;
            $theme_name = $request->theme_name;
            
            $booking_type = $request->booking_type;
            $added_item_type = $request->added_item_type;
            //$request->session()->put('booking_type', $booking_type);
            //above session will be set with the checkout buttons after clicking add to cart 

            if (empty($item_qty)) { 
                $item_qty = "";
            } 
            if (empty($item_height)) { 
                $item_height = "";
            } 
            if (empty($item_width)) { 
                $item_width = "";
            } 
            if (empty($item_price)) { 
                $item_price = "";
            } 

            $single_cart_total = $net_price;

            $item_details = ['particular' => $particular,'item_qty' => $item_qty,'item_height' => $item_height,'item_width' => $item_width,'item_price' => $item_price,'net_price' => $net_price,'pack_desc' => $pack_desc,'partidays' => $partidays,'trans_type' => $trans_type,'single_cart_total' => $single_cart_total,'theme_name' => $theme_name,'added_item_type' => $added_item_type];

            $resultString = [];

            if($booking_type == 'cart_wedding')
            {
                $request->session()->push('weddingcart.items', $item_details);

                $resultString['status'] = 1;
                $resultString['cart_items'] = $request->session()->get('weddingcart');
                echo(json_encode($resultString));
            }
            else if($booking_type == 'cart_general')
            {
                $cart_details = 'generalcart.'.$trans_type ;

                $request->session()->push($cart_details, $item_details);

                $resultString['status'] = 1;
                $resultString['cart_items'] = $request->session()->get('generalcart');
                echo(json_encode($resultString));
            }
            else
            {
                $resultString['status'] = 2;
                $resultString['cart_items'] = [];
                echo(json_encode($resultString));
            }
        }
    }

    //below function is to create a cart like structure for single booking of package when "book now" is clicked from packages listing page, 
    //its actual booking takes place in completesinglecart(), where we make entry to the cart.
    public function additemstocartpackage(Request $request)
    {
        if($request->ajax())
        {
            $event_date = $request->event_date;
            $no_of_days = $request->no_of_days;
            $package_detail_id = $request->package_detail_id;
            $package_price = $request->package_price;
            $trans_type = 'package';
            $added_item_type = 'cart_entry';

            $item_qty = "1";
            $item_height = "";
            $item_width = "";
            $item_price = $package_price;

            //$package_name = $package_detail_id->package->package_name;
            $package_details = PackageDetail::whereId($package_detail_id)->with('package')->first();
            $package_name = $package_details->package->package_name;
            $package_theme_id = $package_details->package_name;
            $package_desc = $package_details->package->description;

            $single_cart_total = $no_of_days * $package_price;

            $item_details = ['particular' => $package_name,'item_qty' => $item_qty,'item_height' => $item_height,'item_width' => $item_width,'item_price' => $item_price,'net_price' => $single_cart_total,
            'pack_desc' => $package_desc,'partidays' => $no_of_days,'trans_type' => $trans_type,'single_cart_total' => $single_cart_total,'theme_name' => $package_name,'package_theme_id' => $package_theme_id,
            'added_item_type' => $added_item_type,'package_detail_id' => $package_detail_id];       
            
            $cart_details = 'generalcart.'.$trans_type ;

            if ($request->session()->has($cart_details)) 
            {
                $request->session()->forget($cart_details);
                Session::save();
                $request->session()->push($cart_details, $item_details);
            }
            else
            {
                $request->session()->push($cart_details, $item_details);
            }

            if ($request->session()->has('event_date')) 
            {
                $request->session()->forget('event_date');
                $request->session()->push('event_date', $event_date);
            }
            else
            {
                $request->session()->push('event_date', $event_date);
            }

            $resultString = [];

            if ($request->session()->has('generalcart'))
            {
                $resultString['status'] = 1;
                echo(json_encode($resultString));
            }
            else
            {
                $resultString['status'] = 2;
                echo(json_encode($resultString));
            }

        }
    }

    /*----------------------complete cart comes here-------------------*/

    public function completecart(Request $request)
    {
        if($request->ajax())
        {
            if ($request->session()->has('booking_type'))
            {
                $resultString = [];
                $mydateinsert = date('d/m/Y');
    
                $cus_name = '';$cus_email = '';$cus_mobile = '';$no_of_days = 0;$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
                $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
                $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = 0;$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
                $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = 0;$manual_discount_row = '';$razorid_city = '';
                $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;$discount_amnt = '';$promocode = '';$discountgiven = '';
                $discount_percent = '';
    
                $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';$event_coordinate = '';$pricetoinsert = 0;$item_json = '';$itemsadded_json ='';$trans_type ='';
                $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';$package_detail_id = NULL;
                $packages_row = '';$coupon_row = ''; $total_data_amount = 0;$total_price = 0;$db_cart_type = 'general';$show_amount = 'yes';
                $today = date("d-m-y-his");
                $curdate = date("d-m-Y-h:i:s");
    
                /****************Data from single booking form comes here***************************/
    
                $name = urldecode($request->cus_name);
                $email = urldecode($request->cus_email);
                $mobile = $request->cus_mobile;
                $event_date = $request->event_date;
                $description = urldecode($request->cus_description);
    
                /****************Data from single booking form ends here***************************/
    
                $booking_type = $request->session()->get('booking_type'); 
    
                /****************Generate Quotation based on booking type is cart_wedding***************************/
    
                if($booking_type == 'cart_wedding')
                {
                    $db_cart_type = 'wedding';
                    $show_amount = 'no';
                    $listitems = '';$listitems2 = '';
                    $cart_wedding = $request->session()->get('weddingcart'); 
                    $package_detail_id = NULL;
    
                    foreach($cart_wedding  as $key => $cart)
                    {
                        foreach($cart as $cart_item)
                        {
                            $added_item_type =  $cart_item['added_item_type'];
        
                            if($added_item_type == 'cart_entry')
                            {
                                $listitems .= $cart_item['particular'].'^'.$cart_item['item_qty'].'^'.$cart_item['item_height'].'^'.$cart_item['item_width'].'^'.$cart_item['item_price'].'^'.$cart_item['net_price'].'^'.$cart_item['pack_desc'].'^'.$cart_item['partidays'].'*';
                            }
                            else if($added_item_type == 'manual_entry')
                            {
                                $listitems2 .= $cart_item['parti'].'^'.$cart_item['qtt'].'^'.$cart_item['heightee'].'^'.$cart_item['widthee'].'^'.$cart_item['ammt'].'^'.$cart_item['calc'].'^'.$cart_item['partidays'].'*';
                            }
                        }
                    }
    
                    $listitems = substr($listitems, 0, -1);
                    $listitems = explode("*",$listitems);
                    $itemCount = count($listitems);
    
                    $items = array();
                    $j = 0;
                    
                    if(count(array_filter($listitems)) != 0) 
                    {
                        for($i=1; $i<= $itemCount; $i++)
                        {
                            $oneitem = $listitems[$j];
                            $oneitem = explode("^",$oneitem);
    
                            $items[$i]['particular'] = $oneitem[0];
                            $items[$i]['item_qty'] = $oneitem[1];
                            $items[$i]['item_height'] = $oneitem[2];
                            $items[$i]['item_width'] = $oneitem[3];
                            $items[$i]['item_price'] = $oneitem[4];
                            $items[$i]['net_price'] = $oneitem[5];
                            $items[$i]['pack_desc'] = $oneitem[6];
                            $items[$i]['partidays'] = $oneitem[7];
    
                            $pricetoinsert = $pricetoinsert + $oneitem[5];                            
                            $j++;
                        }
                    }
                        
                    $item_json = json_encode($items);
    
                    $itemsadded = array();
                    $k = 0;
                    $itemsadded_json = json_encode($itemsadded);                
                }
                
                /****************Generate Quotation based on booking type is cart_wedding ends***************************/
                /****************Generate Quotation based on booking type is cart_general***************************/
    
                if($booking_type == 'cart_general')
                {
                    $listitems = '';$listitems2 = '';$added_item_type =  ''; $db_cart_type = 'general';$show_amount = 'yes';
                    $cart_general = $request->session()->get('generalcart'); 
    
                    foreach($cart_general  as $key => $cart)
                    {
                        foreach($cart as $cart_item)
                        {
                            $trans_type = $cart_item['trans_type'];
    
                            if($trans_type == 'package')
                            {
                                $package_detail_id = $cart_item['package_detail_id'];
                                $package_price = $cart_item['item_price'];
                                //$no_of_days = $cart_item['no_of_days'];
                                $no_of_days = $cart_item['partidays'];

                                $total_data_amount = $package_price * $no_of_days;
                            }
                            else
                            {
                                $added_item_type =  $cart_item['added_item_type'];
        
                                if($added_item_type == 'cart_entry')
                                {
                                    $listitems .= $cart_item['particular'].'^'.$cart_item['item_qty'].'^'.$cart_item['item_height'].'^'.$cart_item['item_width'].'^'.$cart_item['item_price'].'^'.$cart_item['net_price'].'^'.$cart_item['pack_desc'].'^'.$cart_item['partidays'].'*';
                                }
                                else if($added_item_type == 'manual_entry')
                                {
                                    $listitems2 .= $cart_item['parti'].'^'.$cart_item['qtt'].'^'.$cart_item['heightee'].'^'.$cart_item['widthee'].'^'.$cart_item['ammt'].'^'.$cart_item['calc'].'^'.$cart_item['partidays'].'*';
                                }
                            }
                        }
                    }
    
                    $listitems = substr($listitems, 0, -1);                        
                    $listitems = explode("*",$listitems);
                    $itemCount = count($listitems);
    
                    $items = array();
                    $j = 0;
                    
                    if(count(array_filter($listitems)) != 0) 
                    {
                        for($i=1; $i<= $itemCount; $i++)
                        {
                            $oneitem = $listitems[$j];
                            $oneitem = explode("^",$oneitem);
    
                            $items[$i]['particular'] = $oneitem[0];
                            $items[$i]['item_qty'] = $oneitem[1];
                            $items[$i]['item_height'] = $oneitem[2];
                            $items[$i]['item_width'] = $oneitem[3];
                            $items[$i]['item_price'] = $oneitem[4];
                            $items[$i]['net_price'] = $oneitem[5];
                            $items[$i]['pack_desc'] = $oneitem[6];
                            $items[$i]['partidays'] = $oneitem[7];
    
                            $pricetoinsert = $pricetoinsert + $oneitem[5];                            
                            $j++;
                        }
                    }
                        
                    $item_json = json_encode($items);
    
                    $listitems2 = substr($listitems2, 0, -1);
                    $listitems2 = explode("#",$listitems2);
                    $itemCount2 = count($listitems2);                
                    
                    $itemsadded = array();
                    $k = 0;
    
                    if(count(array_filter($listitems2)) != 0)
                    {
                        for($i=1; $i<= $itemCount2; $i++)
                        {
                            $oneitems = $listitems2[$k];
                            $oneitems = explode("^",$oneitems);
    
                            $sent_particular = $oneitems[0];
                            //$sent_particular = mysqli_real_escape_string($con, $sent_particular);
    
                            $itemsadded[$i]['parti'] = $sent_particular;
                            $itemsadded[$i]['qtt'] = $oneitems[1];
                            $itemsadded[$i]['heightee'] = $oneitems[2];
                            $itemsadded[$i]['widthee'] = $oneitems[3];
                            $itemsadded[$i]['ammt'] = $oneitems[4];
                            $itemsadded[$i]['calc'] = $oneitems[5];
                            $itemsadded[$i]['partidays'] = $oneitems[6];
    
                            $pricetoinsert = $pricetoinsert + $oneitems[5];
                            $k++;
                        }
                    }
    
                    $itemsadded_json = json_encode($itemsadded);            
                    
                    if($package_detail_id != NULL)
                    {
                        $total_data_amount = $total_data_amount + $pricetoinsert;
                    }
                    else
                    {
                        $total_data_amount = $pricetoinsert; 
                    }

                    if($request->filled('manual_discount') && $request->manual_discount > 0) 
                    {
                        $manual_discount = $request->manual_discount ? $request->manual_discount : 0;
                    }
                    else
                    {
                        $manual_discount = 0;
                    }
    
                    if (Auth::check() && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) 
                    {
                        $discount_percent = Auth::user()->discount;
                        $discount_amnt = round($total_data_amount - ( $total_data_amount *( $discount_percent/100 ) ) );
                    }
                    else 
                    {
                            if($request->filled('promocode')) 
                            {
                                $promocode = $request->promocode;
                                $coupon = Coupon::where('couponcode','=',$promocode)->where('trans_type','=',$trans_type)->latest()->first();
                                $min_amount = $coupon->min_amount;
        
                                if($total_data_amount >= $min_amount)
                                {
                                    $discountgiven = $coupon->discount;
                                    $total_data_amount = $total_data_amount - $discountgiven;                    
                                }
                                else
                                {
                                    $promocode = '';
                                    $discountgiven = '';
                                }                            
                            }
                            else
                            {
                                $promocode = '';
                                $discountgiven = '';
                                $discount_percent = '';
                                $discount_amnt = '';
                            }
                    }
    
                    //$total_data_amount = $pricetoinsert; // this line is commented as we have calculated "total_data_amount" above in line no 2839
                }
    
                /****************Generate Quotation based on booking type is cart_general ends***************************/
    
                if($request->filled('event_coordinate') && $request->event_coordinate > 0) {
                    $event_coordinate = $request->event_coordinate ? $request->event_coordinate : 0;
                }
                else {
                    $event_coordinate = '0';
                }

                if($request->filled('transport_cost') && $request->transport_cost > 0) {
                    $transport_cost = $request->transport_cost ? $request->transport_cost : NULL;
                }
                else {
                    $transport_cost = NULL;
                }

                if($request->filled('crew_cost') && $request->crew_cost > 0) {
                    $crew_cost = $request->crew_cost ? $request->crew_cost : NULL;
                }
                else  {
                    $crew_cost = NULL;
                }
    
                /* if($add_gst == 'yes') { $add_gst_value = '1'; } else { $add_gst_value = '0'; } */

                if(true) { $add_gst_value = '1'; } else { $add_gst_value = '0'; }
    
                /****************Code Written to insert data into quotation***************************/
    
                $data =  new Quotation();
    
                $filename = $today.'-Tosshead.pdf';
                $filename = 'admin_uploads/pdf/'.$filename;
    
                $data->item = $item_json;
                $data->name = $name;
                $data->email = $email;
                $data->mobile = $mobile;
                $data->pdf = $filename;
                $data->event_date = $event_date;
                $data->total_price = $total_data_amount;
                $data->added_item = $itemsadded_json;
                $data->crew_cost = $crew_cost;
                $data->transport_cost = $transport_cost;
                $data->add_gst = $add_gst_value;
                $data->manual_discount = $manual_discount;
                $data->cc_mails = $cc_mails;
                $data->bcc_mails = $bcc_mails;
                $data->no_of_days = $no_of_days;
                $data->booking_type = $db_cart_type;
                $data->show_amount = $show_amount;
    
                $data->package_detail_id = $package_detail_id;
                $data->package_price = $package_price;
                
                $data->description = $description;
                $data->event_coordinate = $event_coordinate;
                $data->quote_type = 'qtsuccessownagent';
    
                if($transport_cost == NULL) {
                    $data->transport_cost = NULL;
                }
                if($crew_cost == NULL) {
                    $data->crew_cost = NULL;
                }
                if($package_detail_id == NULL) {
                    $data->package_detail_id = NULL;
                }
                
                $data->save();
    
                /*---------------add data to payorders-------------*/
    
                $payorder = new Payorder();
                $payorder->quoteid = $data->id;
                $payorder->invoicedate = $mydateinsert;
                $payorder->quotedate = $mydateinsert;
                $payorder->razorid = '';
                $payorder->city = 'Bangalore';
    
                $payorder->save();
    
                /*---------------ends data to payorders-------------*/
    
                /*---------------add discount to quotations-------------*/              
    
                if($discount_percent != 0 && !empty($discount_amnt)) 
                {
                    if (Auth::check() && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) 
                    {
                        $package_price_data = $data->package_price;
                        $no_of_days_data = $data->no_of_days;
                
                        /* if($package_detail_id != NULL) 
                        {
                            $total_data_amount = $pricetoinsert + $package_price_data * $no_of_days_data;
                        }
                        else 
                        {
                            $total_data_amount = $pricetoinsert;
                        } */
    
                        $user = Auth::user();
                        $onlineinquiry = new OnlineInquiry();
                        $onlineinquiry->created_users = $user->id;
                        $onlineinquiry->quotation = $data->id;
                        $onlineinquiry->discount = $discount_percent;
                        $onlineinquiry->amount = $total_data_amount;
                        $onlineinquiry->save();
                    }
                }
                else if($discountgiven != 0 && !empty($promocode)) 
                {
                    $onlinecoupon = new OnlineCoupon();
                    $onlinecoupon->quotation = $data->id;
                    $onlinecoupon->promocode = $promocode;
                    $onlinecoupon->discountgiven = $discountgiven;
                    $onlinecoupon->save();
                }
    
                /*---------------ends discount to quotations-------------*/
                
                /*---------------generate pdf and send mail to the user comes-------------*/  
    
                $rowcounts = Quotation::where('id','=',$data->id)->count();
    
                if($rowcounts == 1)
                {
                    $quote_data = $this->getquotedetails($data->id);

                    $cus_name = $quote_data['cus_name'];
                    $cus_mobile = $quote_data['cus_mobile'];
                    $cus_email = $quote_data['cus_email'];
                    $mailbody = $quote_data['mailbody'];
                    $cc_mails = $quote_data['cc_mails'];
                    $bcc_mails = $quote_data['bcc_mails'];
                    $amountpaypable = $quote_data['amountpaypable'];
                    $db_cart_type = $quote_data['db_cart_type'];
                    $db_show_amount = $quote_data['db_show_amount'];
    
                    if($db_show_amount == 'no')
                    {
                        $bodytop = '<div class="col-md-12" style="margin-top:2%;">
                            <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                            <p style="text-align:left;"><strong>Your Wedding Essential is successfully registered !</strong></p>
                            <p style="text-align:left;">Your enquiry id is <b>'.$cus_mobile.'</b>. Our wedding expert will revert back to you with the best possible price. 
                            Below are your wedding requirements submitted for your reference</p>
                        </div>';
                    }
                    else
                    {
                        $bodytop = '<div class="col-md-12" style="margin-top:2%;">
                            <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                            <p style="text-align:left;"><strong>Your Event Booking is Confirmed !</strong></p>
                            <p style="text-align:left;">Your booking id <b>'.$cus_mobile.'</b>. Kindly check below quotation and proceed to make payment.</p>
                        </div>';
                    }
    
                    $message = $bodytop.''.$mailbody;
    
                    $base_url = env('APP_URL').'/payment/';
                    $quotelink = $base_url.encrypt($data->id);
                    
                    if($db_show_amount == 'yes')
                    {
                        $message .= '<div style="text-align: center;width: 100%;"><br><br></div><div style="text-align: center;width: 100%;">
                            <a href="'.$quotelink.'" target="_blank" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Pay Now</a>
                        </div>';
                    }
    
                    $message .= '<p>Thank you for choosing Tosshead for your Event requirement ,incase of any queries kindly call us <strong>@ 8448444942</strong> or email us <strong>support@tosshead.com</strong> we will be happy to assist you.<br><br>Warm Regards<br>Team TOSSHEAD</p>';
    
                    $to = $cus_email;
    
                    /*----------Added for auto sms sending when booking is done-----------*/
    
                    if($db_show_amount == 'yes')
                    {
                        $msg_data = "Dear $cus_name,
                        Your Event Booking with Tosshead Events is confirmed. Please find the quotation link shared below and proceed to make the payment:

                        $quotelink";
                        $msg_data = urlencode($msg_data);
                        $ch=curl_init();
                        curl_setopt($ch,CURLOPT_URL,"https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=NfWOdck7dc5&MobileNo=".$cus_mobile."&SenderID=TOSSHE&Message=".$msg_data."&ServiceName=TEMPLATE_BASED");
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
                        $output =curl_exec($ch);
                        curl_close($ch);
                    }
                    else
                    {
                        $msg_data = "Dear $cus_name,
                        Your Wedding Essential is successfully registered. Please find the details in the link shared below :

                        $quotelink";
                        $msg_data = urlencode($msg_data);
                        $ch=curl_init();
                        curl_setopt($ch,CURLOPT_URL,"https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=NfWOdck7dc5&MobileNo=".$cus_mobile."&SenderID=TOSSHE&Message=".$msg_data."&ServiceName=TEMPLATE_BASED");
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
                        $output =curl_exec($ch);
                        curl_close($ch);
                    }
    
                    /*----------Ends Added for auto sms sending when booking is done-----------*/
    
                    $pdf = PDF::loadView('pdfs.invoices', array('mailbody'=>$mailbody))->setPaper('a4', 'landscape');
                    $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true]);
    
                    //$filepath = 'admin_uploads/pdf/'.$filename;
                    Storage::put($filename, $pdf->output());
    
                    $mailstatus = Mail::to($cus_email)->cc(['support@tosshead.com','samsrivastva@gmail.com'])->send(new CustomerQuotation($message));
    
                    if (Mail::failures())
                    {
                        $resultString['status'] = 2;
                        $resultString['trans_type'] = $trans_type;
                        $resultString['quotelink'] = '';
                        echo(json_encode($resultString));
                    }
                    else
                    {
                        $resultString['status'] = 1;
                        $resultString['trans_type'] = $trans_type;
                        $resultString['quotelink'] = $quotelink;
                        echo(json_encode($resultString));
                    }
                }
    
                /****************Code Written to insert data into quotation ends ***************************/
            }
            else 
            {
                $resultString['status'] = 2;
                $resultString['trans_type'] = '';
                $resultString['quotelink'] = '';
                echo(json_encode($resultString));
            }
        }
    }

    /*----------------------complete cart ends here-------------------*/

    public function checkout(Request $request)
    {
        $homesliders = Homeslider::latest()->get();

        $trans_type = ''; $event_date = ''; $coupon_texts = ''; $promocode = ''; $booking_type = '';

        if ($request->session()->has('booking_type'))
        {
            $booking_type = $request->session()->get('booking_type'); 

            if($booking_type == 'cart_single')
            {
                $cart_single = $request->session()->get('singlecart'); 
                $trans_type = $cart_single['trans_type'];

                if($trans_type == 'package')
                {
                    $event_date = $cart_single['event_date'];
                }

                $coupon_texts = Coupontext::where('trans_type',$trans_type)->get();
                $coupon_texts = $coupon_texts->toArray();
            }
            else if($booking_type == 'cart_wedding' || $booking_type == 'cart_general')
            {
                if($booking_type == 'cart_wedding')
                {
                    $cart_wedding = $request->session()->get('weddingcart'); 
                }
                else if($booking_type == 'cart_general')
                {
                    $cart_general = $request->session()->get('generalcart');            
                }

                $coupon_texts = Coupontext::where('trans_type','homepage')->get();
                $coupon_texts = $coupon_texts->toArray();

                $event_date = $request->session()->get('event_date'); 
            }

            if ($request->session()->has('promocode'))
            {
                $promocode = $request->session()->get('promocode'); 
            }

            return view('website.checkout',compact(['trans_type','event_date','coupon_texts','promocode','booking_type','homesliders']));    
        }
        else
        {
            return redirect()->route('homepage');
        }            
    }

    public function getotp(Request $request)
    {
        if($request->ajax())
        {
            $name = urldecode($request->name);
            $email = urldecode($request->email);
            $mobile = $request->mobile;
            $event_date = $request->event_date;
            $description = urldecode($request->description);

            $opt_input = rand(1000,9999);
            $request->session()->put('opt_input', $opt_input);

            $customer = new Customer();

            $customer->name = $name;
            $customer->email = $email;
            $customer->mobile = $mobile;
            $customer->lead_source = 'Website';
            $customer->save();

            $message = "Your otp is ".$opt_input.".";
            $message = urlencode($message); 
            $ch=curl_init();
            curl_setopt($ch,CURLOPT_URL,"https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=NfWOdck7dc5&MobileNo=".$mobile."&SenderID=TOSSHE&Message=".$message."&ServiceName=TEMPLATE_BASED");
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
            $output =curl_exec($ch);
            curl_close($ch);

            if (!empty($opt_input))
            {
                $resultString['status'] = 1;
                $resultString['opt_input'] = $opt_input;
                echo(json_encode($resultString));
            }
            else
            {
                $resultString['status'] = 2;
                $resultString['opt_input'] = $opt_input;
                echo(json_encode($resultString));
            }

        }
    }

    public function resentotp(Request $request)
    {
        if($request->ajax())
        {
            $name = urldecode($request->name);
            $email = urldecode($request->email);
            $mobile = $request->mobile;
            $event_date = $request->event_date;
            $description = urldecode($request->description);

            $opt_input = $request->session()->get('opt_input');     

            $message = "Your otp is ".$opt_input.".";
            $message = urlencode($message); 
            $ch=curl_init();
            curl_setopt($ch,CURLOPT_URL,"https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=NfWOdck7dc5&MobileNo=".$mobile."&SenderID=TOSSHE&Message=".$message."&ServiceName=TEMPLATE_BASED");
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
            $output =curl_exec($ch);
            curl_close($ch);

            if (!empty($opt_input))
            {
                $resultString['status'] = 1;
                $resultString['opt_input'] = $opt_input;
                echo(json_encode($resultString));
            }
            else
            {
                $resultString['status'] = 2;
                $resultString['opt_input'] = $opt_input;
                echo(json_encode($resultString));
            }

        }
    }

    public function checkotp(Request $request)
    {
        if($request->ajax())
        {
            $entered_otp = $request->entered_otp;
            $opt_input = $request->session()->get('opt_input');

            if ($entered_otp == $opt_input)
            {
                $resultString['status'] = 1;
                echo(json_encode($resultString));
            }
            else
            {
                $resultString['status'] = 2;
                echo(json_encode($resultString));
            }
        }
    }

    public function applypromocode(Request $request)
    {
        if($request->ajax())
        {
            if ($request->session()->has('booking_type')) 
            {
                $booking_type = $request->session()->get('booking_type');

                if($booking_type == 'cart_single') 
                {
                        $cart_single = $request->session()->get('singlecart'); 
                        $trans_type = $cart_single['trans_type'];
                        $single_cart_total = $cart_single['single_cart_total'];
                        $promocode = $request->promocode;
                        
                        $coupon = Coupon::where('couponcode','=',$promocode)->where('trans_type','=',$trans_type)->latest()->first();
                        $min_amount = $coupon->min_amount;
            
                        if($single_cart_total >= $min_amount)
                        {
                            $resultString['status'] = 1;
                            $resultString['promocode'] = $promocode;
                            $request->session()->put('promocode', $promocode);
                            echo(json_encode($resultString));
                        }
                        else if($single_cart_total < $min_amount)
                        {
                            $resultString['status'] = 2;
                            $resultString['promocode'] = $promocode;
                            if ($request->session()->has('promocode')) 
                            {
                                $request->session()->forget('promocode');
                                Session::save();
                            }
                            echo(json_encode($resultString));
                        }
                        else
                        {
                            $resultString['status'] = 3;
                            $resultString['promocode'] = '';
                            if ($request->session()->has('promocode')) 
                            {
                                $request->session()->forget('promocode');
                                Session::save();
                            }
                            echo(json_encode($resultString));
                        }
                }
                else if($booking_type == 'cart_general') 
                {
                        $cart_general = $request->session()->get('generalcart'); 
                        $promocode = $request->promocode;

                        $single_cart_total =  0;

                        foreach($cart_general as $key => $value)
                        {
                            foreach($value as $item_key => $item_value)
                            {
                                $single_cart_total = $single_cart_total + $item_value['single_cart_total'];
                            }
                        }

                        $coupon = Coupon::where('couponcode','=',$promocode)->latest()->first();
                        $min_amount = $coupon->min_amount;
            
                        if($single_cart_total >= $min_amount)
                        {
                            $resultString['status'] = 1;
                            $resultString['promocode'] = $promocode;
                            $request->session()->put('promocode', $promocode);
                            echo(json_encode($resultString));
                        }
                        else if($single_cart_total < $min_amount)
                        {
                            $resultString['status'] = 2;
                            $resultString['promocode'] = $promocode;
                            if ($request->session()->has('promocode')) 
                            {
                                $request->session()->forget('promocode');
                                Session::save();
                            }
                            echo(json_encode($resultString));
                        }
                        else
                        {
                            $resultString['status'] = 3;
                            $resultString['promocode'] = '';
                            if ($request->session()->has('promocode')) 
                            {
                                $request->session()->forget('promocode');
                                Session::save();
                            }
                            echo(json_encode($resultString));
                        }
                }
                else if($booking_type == 'cart_wedding') 
                {
                    $resultString['status'] = 3;
                    $resultString['promocode'] = '';
                    if ($request->session()->has('promocode')) 
                    {
                        $request->session()->forget('promocode');
                        Session::save();
                    }
                    echo(json_encode($resultString));
                }
            }
        }
    }

    public function payment(Request $request,$id)
    {
        $homesliders = Homeslider::latest()->get();
        $resultString = [];

        if ($request->session()->has('opt_input')) 
        {
            $request->session()->forget('opt_input');
            Session::save();
        }
        if ($request->session()->has('promocode')) 
        {
            $request->session()->forget('promocode');
            Session::save();
        }
        if ($request->session()->has('event_date')) 
        {
            $request->session()->forget('event_date');
            Session::save();
        }
        if ($request->session()->has('booking_type')) 
        {
            $booking_type = $request->session()->get('booking_type'); 

            if($booking_type == 'cart_single') 
            {
                if ($request->session()->has('singlecart')) 
                {
                    $request->session()->forget('singlecart');
                }
            }
            else if($booking_type == 'cart_wedding') 
            {
                if ($request->session()->has('weddingcart')) 
                {
                    $request->session()->forget('weddingcart');
                }
            }
            else if($booking_type == 'cart_general') 
            {
                if ($request->session()->has('generalcart')) 
                {
                    $request->session()->forget('generalcart');
                }
            }
            $request->session()->forget('booking_type');
            Session::save();
        }
        
        $urls = $id;
        $url = decrypt($id);
        $id = decrypt($id);

        $coupon_texts = Coupontext::where('trans_type','homepage')->get();
        $coupon_texts = $coupon_texts->toArray();

        /*********-----------added for quote display----------*********/

        $data = Quotation::findOrFail($id);
        $mydateinsert = date('d/m/Y');
        $resultString = [];
        $rowcounts= Quotation::where('id','=',$data->id)->count();

        $cus_name = '';$cus_email = '';$cus_mobile = '';$no_of_days = 0;$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
        $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
        $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = 0;$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
        $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = 0;$manual_discount_row = '';$razorid_city = '';
        $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;$discount_amnt = '';$promocode = '';$discountgiven = '';
        $discount_percent = '';

        $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';$event_coordinate = '';$pricetoinsert = 0;$item_json = '';$itemsadded_json ='';
        $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';
        $packages_row = '';$coupon_row = ''; $total_data_amount = 0;$total_price = 0;$db_cart_type = 'general';
        $today = date("d-m-y-his");
        $curdate = date("d-m-Y-h:i:s");                

        if($rowcounts == 1)
        {
            $quote_data = $this->getquotedetails($data->id);

            $cus_name = $quote_data['cus_name'];
            $cus_mobile = $quote_data['cus_mobile'];
            $cus_email = $quote_data['cus_email'];
            $mailbody = $quote_data['mailbody'];
            $cc_mails = $quote_data['cc_mails'];
            $bcc_mails = $quote_data['bcc_mails'];
            $amountpaypable = $quote_data['amountpaypable'];
            $db_cart_type = $quote_data['db_cart_type'];
            $db_show_amount = $quote_data['db_show_amount'];

            if($db_show_amount == 'no')
            {
                $bodytop = '<div class="col-md-12" style="margin-top:2%;">
                    <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                    <p style="text-align:left;"><strong>Your Wedding Essential is successfully registered !</strong></p>
                    <p style="text-align:left;">Your enquiry id is <b>'.$cus_mobile.'</b>. Our wedding expert will revert back to you with the best possible price. 
                    Below are your wedding requirements submitted for your reference</p>
                </div>';
            }
            else
            {
                $bodytop = '<div class="col-md-12" style="margin-top:2%;">
                    <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                    <p style="text-align:left;"><strong>Your Event Booking is Confirmed !</strong></p>
                    <p style="text-align:left;">Your booking id <b>'.$cus_mobile.'</b>. Kindly check below quotation and proceed to make payment.</p>
                </div>';
            }

            $message = $bodytop.''.$mailbody;

            //if($amountpaypable > 0 && $razorid_data == '')
            if($db_show_amount == 'yes' && $amountpaypable > 0 && $razorid_data == '')
            {
                $message .= '<div style="text-align: center;width: 100%;"><br><br></div><div style="text-align: center;width: 100%;">
                    <button id="rzp-button1" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Pay Now</button>
                </div>';
            }

            $message .= '<p><br /></p><p>Thank you for choosing Tosshead for your Event requirement ,incase of any queries kindly call us <strong>@ 8448444942</strong> or email us <strong>support@tosshead.com</strong> we will be happy to assist you.<br><br>Warm Regards<br>Team TOSSHEAD</p>';

        }

        /*********-----------added for quote display ends ----------*********/

        $amountpaypable = $amountpaypable * 100;
        //$amountpaypable = 100;

        return view('website.payment', compact(['message','cus_name','cus_email','cus_mobile','url','urls','amountpaypable','coupon_texts','homesliders']));

    }

    public function updatepayment(Request $request)
    {
        $resultString = [];

        $id = $request->payfor;
        $paymentreference = $request->paymentreference;

        $coupon_texts = Coupontext::where('trans_type','homepage')->get();
        $coupon_texts = $coupon_texts->toArray();

        /*********-----------added for quote display----------*********/

        $data = Quotation::findOrFail($id);
        $mydateinsert = date('d/m/Y');
        $resultString = [];
        $rowcounts= Quotation::where('id','=',$data->id)->count();

        $cus_name = '';$cus_email = '';$cus_mobile = '';$no_of_days = 0;$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
        $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
        $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = 0;$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
        $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = 0;$manual_discount_row = '';$razorid_city = '';
        $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;$discount_amnt = '';$promocode = '';$discountgiven = '';
        $discount_percent = '';

        $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';$event_coordinate = '';$pricetoinsert = 0;$item_json = '';$itemsadded_json ='';
        $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';
        $packages_row = '';$coupon_row = ''; $total_data_amount = 0;$total_price = 0;$db_cart_type = 'general';

        /*Add if payorders is used*/

        $rowcounts_payorders = Payorder::where('quoteid','=',$data->id)->count();

        if($rowcounts_payorders > 0)
        {
            $payorder_item = Payorder::where('quoteid','=',$data->id)->latest()->first();

            $payorder_item->invoicedate = $mydateinsert;
            $payorder_item->razorid = $paymentreference;
            $payorder_item->save();
        }

        /*ends Add if payorders is used*/

        if($rowcounts == 1)
        {
            $quote_data = $this->getquotedetails($data->id);

            $cus_name = $quote_data['cus_name'];
            $cus_mobile = $quote_data['cus_mobile'];
            $cus_email = $quote_data['cus_email'];
            $mailbody = $quote_data['mailbody'];
            $cc_mails = $quote_data['cc_mails'];
            $bcc_mails = $quote_data['bcc_mails'];
            $amountpaypable = $quote_data['amountpaypable'];
            $db_cart_type = $quote_data['db_cart_type'];

            $bodytop = '<div class="col-md-12" style="margin-top:2%;">
                <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                <p style="text-align:left;"><strong>Your Event Booking is Confirmed !</strong></p>
                <p style="text-align:left;">Your booking id <b>'.$cus_mobile.'</b>. Kindly check below quotation and proceed to make payment.</p>
            </div>';            

            $message = $bodytop.''.$mailbody;

            $message .= '<p><br /></p><p>Thank you for choosing Tosshead for your Event requirement ,incase of any queries kindly call us <strong>@ 8448444942</strong> or email us <strong>support@tosshead.com</strong> we will be happy to assist you.<br><br>Warm Regards<br>Team TOSSHEAD</p>';

            $to = $cus_email;

            $mailstatus = Mail::to($cus_email)->cc(['support@tosshead.com','samsrivastva@gmail.com'])->send(new CustomerQuotation($message));

            if (Mail::failures())
            {
                $resultString['status'] = 2;
                echo(json_encode($resultString));
            }
            else
            {
                $resultString['status'] = 1;
                echo(json_encode($resultString));
            }

        }

        /*********-----------added for quote display ends ----------*********/

    }

    public function removecartitem(Request $request)
    {
        if($request->ajax())
        {
            $item_id = $request->item_id;
            $cart_type = $request->cart_type;
            $item_type = $request->item_type;
            $resultString = [];

            if($cart_type == 'weddingcart')
            {
                $remove_id = 'weddingcart.items.'.$item_id;
                $cart_details = 'weddingcart';
            }
            else if($cart_type == 'generalcart')
            {
                $remove_id = 'generalcart.'.$item_type.'.'.$item_id;
                $cart_details = 'generalcart';
            }
           
            $request->session()->forget($remove_id);
            Session::save();

            if ($request->session()->has($cart_details)) 
            {
                $resultString['status'] = 1;
                $resultString['cart_items'] = $request->session()->get($cart_details);
            }
            else
            {
                $resultString['status'] = 2;
                $resultString['cart_items'] = array();
            }
            echo(json_encode($resultString));            
        }
    }

    public function cart(Request $request, $cart_type)
    {
        if($request->ajax())
        {
            $resultString = [];

            if ($request->session()->has($cart_type)) 
            {
                $resultString['status'] = 1;
                $resultString['cart_items'] = $request->session()->get($cart_type);
            }
            else
            {
                $resultString['status'] = 2;
                $resultString['cart_items'] = array();
            }
            echo(json_encode($resultString));            
        }
    }

    public function addeventdate(Request $request)
    {
        if($request->ajax())
        {
            $event_date = $request->event_date;
            $booking_type = $request->booking_type;
            $request->session()->put('event_date', $event_date);
            $request->session()->put('booking_type', $booking_type);

            if ($request->session()->has('promocode')) 
            {
                $request->session()->forget('promocode');
                Session::save();
            }

            $resultString = [];

            if ($request->session()->has('event_date') && $request->session()->has('booking_type')) 
            {
                $resultString['status'] = 1;
                $resultString['booking_type'] = $request->session()->get('booking_type');
                $resultString['event_date'] = $request->session()->get('event_date');
            }
            else
            {
                $resultString['status'] = 2;
                $resultString['booking_type'] = array();
                $resultString['cart_items'] = array();
            }
            echo(json_encode($resultString));            
        }
    }

    public function getquotedetails($quoteid)
    {
        $rowcounts = Quotation::where('id','=',$quoteid)->count();
    
        if($rowcounts == 1)
        {
            $resultString = [];
            $mydateinsert = date('d/m/Y');

            $cus_name = '';$cus_email = '';$cus_mobile = '';$no_of_days = 0;$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
            $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
            $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = 0;$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
            $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = 0;$manual_discount_row = '';$razorid_city = '';
            $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;$discount_amnt = '';$promocode = '';$discountgiven = '';
            $discount_percent = '';

            $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';$event_coordinate = '';$pricetoinsert = 0;$item_json = '';$itemsadded_json ='';$trans_type ='';
            $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';$package_detail_id = NULL;
            $packages_row = '';$coupon_row = ''; $total_data_amount = 0;$total_price = 0;$db_cart_type = 'general';$mailbody = '';$db_show_amount = 'yes';
            $today = date("d-m-y-his");
            $curdate = date("d-m-Y-h:i:s");

            $data = Quotation::findOrFail($quoteid);

            /*Add if remarks is used*/

            $rowcount_remarks = Remark::where('quoteid','=',$data->id)->count();

            if($rowcount_remarks > 0)
            {
                $remarks = Remark::where('quoteid','=',$data->id)->latest()->get();
                foreach($remarks as $remark)
                {
                    $remarks_data .= $remark->remarks.'^^';
                }
                $remarks_data = substr($remarks_data, 0, -2);
            }
            /*ends Add if remarks is used*/            

            /*Add if payorders is used*/

            $rowcounts_payorders = Payorder::where('quoteid','=',$data->id)->count();

            if($rowcounts_payorders > 0)
            {
                $payorders = Payorder::where('quoteid','=',$data->id)->latest()->get();

                foreach($payorders as $payorder)
                {
                    $invoicedate_data = $payorder->invoicedate;
                    $quotedate_data = $payorder->quotedate;
                    $razorid_data = $payorder->razorid;
                    $razorid_city = $payorder->city;
                }
            }
            /*ends Add if payorders is used*/

            $cus_name = ucfirst($data->name);
            $cus_email = $data->email;
            $cus_mobile = $data->mobile;
            $cus_no_of_days = $data->no_of_days;

            $cus_requirements = json_decode($data->item, true);
            $cus_total_price = $data->total_price;
            $cus_eventdate = $data->event_date;

            $package_detail_id = $data->package_detail_id;

            $cus_added_item = json_decode($data->added_item, true);
            $cus_crew_cost = $data->crew_cost;
            $cus_transport_cost = $data->transport_cost;
            $cus_add_gst = $data->add_gst;

            $cus_manual_discount = $data->manual_discount;
            $cus_discount_percent = $data->discount_percent;
            $cus_discount_amnt = $data->discount_amnt;

            $cc_mails = $data->cc_mails;
            $bcc_mails = $data->bcc_mails;

            $event_date = $data->event_date;
            $package_price = $data->package_price;
            $quote_type = $data->quote_type;

            $admin_event_expenses = $data->event_expenses;
            $admin_event_gst = $data->event_gst;
            $admin_event_remarks = $data->event_remarks;
            $quotation_type = $data->quote_type;
            $event_coordinate = $data->event_coordinate;
            $db_cart_type = $data->booking_type;
            $db_show_amount = $data->show_amount;

            if($db_show_amount == 'no')
            {
                if($cus_transport_cost > 0)
                {
                    $transport_row = '';
                }
                if($cus_crew_cost > 0)
                {
                    $crew_row = '';
                }
                if($cus_manual_discount > 0)
                {
                    $manual_discount_row = '';
                }
            }
            else
            {
                if($cus_transport_cost > 0)
                {
                    $transport_row = '<tr>
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Transportation Cost</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$cus_transport_cost.'</td>
                    </tr>';
                }
                if($cus_crew_cost > 0)
                {
                    $crew_row = '<tr>
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Crew Transportation Cost</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$cus_crew_cost.'</td>
                    </tr>';
                }
                if($cus_manual_discount > 0)
                {
                    $manual_discount_row = '<tr>
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Discount</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$cus_manual_discount.'</td>
                    </tr>';
                }
            }

            /*---------Starts Package Details------------*/            

            if($package_detail_id != NULL)
            {
                $package_details = PackageDetail::select("package_details.*","packages.id as packageid","packages.package_name as packagename")
                    ->leftJoin("packages","package_details.package_name","=","packages.id")->where('package_details.id','=',$package_detail_id)->orderBy('Id','Desc')->get();

                foreach($package_details as $package_detail)
                {
                    $pname = $package_detail->package_name;
                    $no_of_pax = $package_detail->no_of_pax;
                    $indoor_outdoor = $package_detail->indoor_outdoor;
                    $package_include = $package_detail->package_include;
                    $package_name = $package_detail->packagename;
                    $package_cost = $data->package_price;

                    if($db_show_amount == 'no')
                    {
                        $packages_row .= '<tr>
                            <th colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Package Details</th>
                        </tr>';

                        $packages_row .= '<tr>
                            <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Package Name </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">'.$package_name.'</td>
                        </tr>';

                        $packages_row .= '<tr>
                            <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Venue </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">'.$indoor_outdoor.'</td>
                        </tr>';

                        $packages_row .= '<tr>
                            <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Package Includes </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">'.$package_include.'</td>
                        </tr>';

                        $packages_row .= '<tr>
                            <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Per day Package Price </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">'.$package_cost.'</td>
                        </tr>'; 

                        $package_row_2 = '<tr>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">'.$package_name.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 25%">'.$cus_no_of_days.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 15%">1</td>
                        </tr>';
                    }
                    else 
                    {
                        $packages_row .= '<tr>
                            <th colspan="4" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff"> Package Details</th>
                        </tr>';

                        $packages_row .= '<tr>
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Package Name </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">'.$package_name.'</td>
                        </tr>';

                        $packages_row .= '<tr>
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Venue </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">'.$indoor_outdoor.'</td>
                        </tr>';

                        $packages_row .= '<tr>
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Package Includes </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">'.$package_include.'</td>
                        </tr>';

                        $packages_row .= '<tr>
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Per day Package Price </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">'.$package_cost.'</td>
                        </tr>';       
                        
                        $package_row_2 = '<tr>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">'.$package_name.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 10%">'.$cus_no_of_days.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 15%">1</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 25%"> Rs. '.($package_cost * $cus_no_of_days).'</td>
                        </tr>';

                    }                    
                }
            }

            /*---------Ends Starts Package Details------------*/

            if($cus_requirements != NULL && count(array_filter([$cus_requirements])) != 0)
            {
                foreach($cus_requirements as $items)
                {
                    $particular = $items['particular'];
                    $item_qty = $items['item_qty'];
                    $item_height = $items['item_height'];
                    $item_width = $items['item_width'];
                    $item_price = $items['item_price'];
                    $net_price = $items['net_price'];
                    $pack_desc = $items['pack_desc'];
                    $partidays = $items['partidays'];

                    $widthheight = $item_height.' * '.$item_width;
                    $extengiven = '';
                    $datashown = ($item_qty == '' ? $widthheight : $item_qty);

                    $fulldata = $datashown.'  '.$extengiven;
                    $totalamount = $totalamount + $net_price;

                    if($db_show_amount == 'no')
                    {
                        $itemselected .= '<tr>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">'.$particular.'<br><span style="font-size:9px;">'.$pack_desc.'</span></td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 30%">'.$partidays.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 20%">'.$fulldata.'</td>
                        </tr>';
                    }
                    else 
                    {
                        $itemselected .= '<tr>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">'.$particular.'<br><span style="font-size:9px;">'.$pack_desc.'</span></td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 10%">'.$partidays.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 15%">'.$fulldata.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 25%"> Rs. '.($net_price).'</td>
                        </tr>';
                    }
                }
            }

            if($cus_added_item != NULL && count(array_filter([$cus_added_item])) != 0)
            {
                foreach ($cus_added_item as $itemsadds)
                {
                    $particular = nl2br($itemsadds['parti']);
                    $item_qty = $itemsadds['qtt'];
                    $item_height = $itemsadds['heightee'];
                    $item_width = $itemsadds['widthee'];
                    $item_price = $itemsadds['ammt'];
                    $net_price = $itemsadds['calc'];
                    $partidays = $itemsadds['partidays'];

                    $widthheight = $item_height.' * '.$item_width;

                    //$extengiven = ($item_qty == '' ? 'Sq. ft' : 'Qty');
                    $extengiven = '';
                    $datashown = ($item_qty == '' ? $widthheight : $item_qty);

                    $fulldata = $datashown.'  '.$extengiven;
                    $totalamount = $totalamount + $net_price;

                    $particulars = '';
                    $counter_items_data = 0;
                    $particular = explode("\n",$particular);

                    foreach($particular  as $particular_data) {
                        $counter_items_data++;
                        $particulars .= $particular_data.'<br>';
                    }

                    if($db_show_amount == 'no')
                    {
                        $itemselected .= '<tr>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">'.$particulars.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 30%">'.$partidays.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 20%">'.$fulldata.'</td>
                        </tr>';
                    }
                    else
                    {
                        $itemselected .= '<tr>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">'.$particulars.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 10%">'.$partidays.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 15%">'.$fulldata.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 25%"> Rs. '.($net_price).'</td>
                        </tr>';
                    }
                }
            }

            /* condition check for quote type ends  */

            if($package_detail_id != NULL)
            {
                $totalamount = $totalamount + $package_cost * $cus_no_of_days;
            }
            $finalamount = $totalamount;

            $onlineinquiries_count = OnlineInquiry::where('quotation','=',$data->id)->count();

            if($onlineinquiries_count > 0)
            {
                $onlineinquiries = OnlineInquiry::where('quotation','=',$data->id)->latest()->first();

                $discount = $onlineinquiries->discount;
                $amount = $onlineinquiries->amount;
                $amount = round(($discount/100) * $finalamount);

                if($db_show_amount == 'no')
                {
                    $discount_row = '';

                    $discountamt_row = '';
                }
                else
                {
                    $discount_row = '<tr>
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Discount </td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">'.$discount.' % </td>
                    </tr>';

                    $discountamt_row = '<tr>
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Discounted Amount </td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">Rs. '.$amount.' </td>
                    </tr>';
                }

                $finalamount = $finalamount - $amount;
            }

            /*Add if coupon code id used*/

            $onlinecoupon_count = OnlineCoupon::where('quotation','=',$data->id)->count();

            if($onlinecoupon_count > 0)
            {
                $onlinecoupon = OnlineCoupon::where('quotation','=',$data->id)->latest()->first();

                $promocode_db = $onlinecoupon->promocode;
                $discountgiven_db = $onlinecoupon->discountgiven;

                if($db_show_amount == 'no')
                {
                    $coupon_row = '';                    
                }
                else
                {
                    $coupon_row = '<tr>
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Discount (Coupon Code -- '.$promocode_db.')</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">Rs. '.$discountgiven_db.' </td>
                    </tr>';
                }

                $finalamount = $finalamount - $discountgiven_db;
            }

            /*ends Add if coupon code id used*/

            if($cus_transport_cost > 0) {
                $finalamount = $finalamount + $cus_transport_cost;
            }
            if($cus_crew_cost > 0) {
                $finalamount = $finalamount + $cus_crew_cost;
            }
            if($cus_manual_discount > 0) {
                $finalamount = $finalamount - $cus_manual_discount;
            }

            $cgst_data = round($finalamount*0.09);
            $sgst_data = round($finalamount*0.09);

            /*---------Ends Items Details------------*/

            /*Added for GST */

            $taxes_row = '';
            if($cus_add_gst == 1)
            {
                $amountpaypable = $finalamount + $cgst_data + $sgst_data;

                if($db_show_amount == 'no')
                {
                    $taxes_row = '';
                }
                else
                {
                    $taxes_row = '<tr style="">
                        <td colspan="3" class="right" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Add CGST: 9%</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$cgst_data.'</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="right" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Add SGST: 9%</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$sgst_data.'</td>
                    </tr>';
                }
            }
            else
            {
                $amountpaypable = $finalamount;
                $taxes_row = '';
            }

            if($db_show_amount == 'no')
            {
                $amountpaypable_row = '';
            }
            else
            {
                $amountpaypable_row = '<tr>
                    <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%;font-weight:bold;font-size:18px;">Payable Amount </td>
                    <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%;font-weight:bold;font-size:18px;">Rs. '.$amountpaypable.' </td>
                </tr>';
            }

            if($db_show_amount == 'no')
            {
                $mailbody = '<table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;max-width: 100%  !important;table-layout: fixed  !important;width: 100%  !important;">
                    <tr>
                        <th colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 12px;">
                            <div class="logo" style="text-align: center">
                                <img src="'.env('APP_URL').'/frontend/images/logo-black.png'.'"><h2>support@tosshead.com &nbsp;&nbsp;&nbsp; +91 84484 44942</h2>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Quotation</th>
                    </tr>
                    <tr style="">
                        <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">Customer Name: '.$cus_name.'</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">Quotation Date: '.$quotedate_data.'</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">Email: '.$cus_email.'</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">Event Date : '.$event_date.'</td>
                    </tr>
                    <tr style="">
                        <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%"> Contact No. : '.$cus_mobile.' </td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">City : '.$razorid_city.'</td>
                    </tr>
                    '.$packages_row.'
                    <tr>
                        <th colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff"> Requirements</th>
                    </tr>
                    <tr style="">
                        <td style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 50%;font-weight:bold;">Description</td>
                        <td style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 30%;font-weight:bold;">Day(s)</td>
                        <td style="border: 1px solid #d52a33;text-align: center;padding: 8px;font-weight:bold;">Qty / Sq Ft</td>
                    </tr>
                    '.$package_row_2.''.$itemselected.'
                    '.$discount_row.''.$discountamt_row.''.$manual_discount_row.''.$coupon_row.''.$transport_row.''.$crew_row.''.$taxes_row.''.$amountpaypable_row.'
                    <tr style="">
                        <th colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Company Details</th>
                    </tr>
                    <tr>
                        <td colspan="2" class="center" style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 50%">GST NO - 29AAHCT0372J1Z2</td>
                        <td class="center" style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 50%">PAN NO - AAHCT0372J</td>
                    </tr>
                    <tr style="">
                        <th colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Account Details</th>
                    </tr>
                    <tr>
                        <td class="center" colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 100%">Tosshead Events India Pvt Ltd</td>
                    </tr>
                    <tr style="">
                        <td class="center" colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 100%">ICICI Bank Limited, A/C No: 315705000207, IFSC: ICIC0003157, MICR: 560229129<br/>Bengaluru</td>
                    </tr>
                    <tr style="">
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 100%">
                            <p><strong>Terms &amp; Conditions Applicable </strong></p>
                            <ol>
                                <li>100% payment at the time of booking.</li>
                                <li>All Event Pacakges and Event Essentials are subject to availability.</li>
                                <li>Power to be provided at your end or we can provide generator at additional cost.</li>
                                <li>Any required Licences/permissions from the venue to be procured at your end or we can procure on your behalf at additional cost.</li>
                                <li>100% refund if cancelled before 48Hrs of the event, If cancelled later refund cannot be processed.</li>
                            </ol>
                            <p style="text-align:center;font-weight:bold;font-size:21px;">Transportation cost will be additional based on location, will be communicated over call</p>
                        </td>
                    </tr>
                </table>';
            }
            else
            {
                $mailbody = '<table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;max-width: 100%  !important;table-layout: fixed  !important;width: 100%  !important;">
                    <tr>
                        <th colspan="4" style="border: 1px solid #d52a33;text-align: center;padding: 12px;">
                            <div class="logo" style="text-align: center">
                                <img src="'.env('APP_URL').'/frontend/images/logo-black.png'.'"><h2>support@tosshead.com &nbsp;&nbsp;&nbsp; +91 84484 44942</h2>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Quotation</th>
                    </tr>
                    <tr style="">
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Customer Name: '.$cus_name.'</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">Quotation Date: '.$quotedate_data.'</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Email: '.$cus_email.'</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">Event Date : '.$event_date.'</td>
                    </tr>
                    <tr style="">
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%"> Contact No. : '.$cus_mobile.' </td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">City : '.$razorid_city.'</td>
                    </tr>
                    '.$packages_row.'
                    <tr>
                        <th colspan="4" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff"> Requirements</th>
                    </tr>
                    <tr style="">
                        <td style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 50%;font-weight:bold;">Description</td>
                        <td style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 10%;font-weight:bold;">Day(s)</td>
                        <td style="border: 1px solid #d52a33;text-align: center;padding: 8px;font-weight:bold;">Qty / Sq Ft</td>
                        <td style="border: 1px solid #d52a33;text-align: center;padding: 8px;font-weight:bold;">Amount</td>
                    </tr>
                    '.$package_row_2.''.$itemselected.'
                    <tr>
                        <td colspan="3" class="right" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Total Value</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$totalamount.'</td>
                    </tr>
                    '.$discount_row.''.$discountamt_row.''.$manual_discount_row.''.$coupon_row.''.$transport_row.''.$crew_row.''.$taxes_row.''.$amountpaypable_row.'
                    <tr style="">
                        <th colspan="4" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Company Details</th>
                    </tr>
                    <tr>
                        <td colspan="3" class="center" style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 60%">GST NO - 29AAHCT0372J1Z2</td>
                        <td class="center" style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 40%">PAN NO - AAHCT0372J</td>
                    </tr>
                    <tr style="">
                        <th colspan="4" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Account Details</th>
                    </tr>
                    <tr>
                        <td class="center" colspan="4" style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 100%">Tosshead Events India Pvt Ltd</td>
                    </tr>
                    <tr style="">
                        <td class="center" colspan="4" style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 100%">ICICI Bank Limited, A/C No: 315705000207, IFSC: ICIC0003157, MICR: 560229129<br/>Bengaluru</td>
                    </tr>
                    <tr style="">
                        <td colspan="4" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 100%">
                            <p><strong>Terms &amp; Conditions Applicable </strong></p>
                            <ol>
                                <li>100% payment at the time of booking.</li>
                                <li>All Event Pacakges and Event Essentials are subject to availability.</li>
                                <li>Power to be provided at your end or we can provide generator at additional cost.</li>
                                <li>Any required Licences/permissions from the venue to be procured at your end or we can procure on your behalf at additional cost.</li>
                                <li>100% refund if cancelled before 48Hrs of the event, If cancelled later refund cannot be processed.</li>
                            </ol>
                            <p style="text-align:center;font-weight:bold;font-size:21px;">Transportation cost will be additional based on location, will be communicated over call</p>
                        </td>
                    </tr>
                </table>';
            }

            $resultString['cc_mails'] = $cc_mails;
            $resultString['bcc_mails'] = $bcc_mails;
            $resultString['cus_name'] = $cus_name;
            $resultString['cus_mobile'] = $cus_mobile;
            $resultString['cus_email'] = $cus_email;
            $resultString['mailbody'] = $mailbody;
            $resultString['db_cart_type'] = $db_cart_type;
            $resultString['amountpaypable'] = $amountpaypable;
            $resultString['db_show_amount'] = $db_show_amount;

            return $resultString;

        } /*ends main if condition*/
        else
        {
            $resultString = [];
            $resultString['cc_mails'] = '';
            $resultString['bcc_mails'] = '';
            $resultString['cus_name'] = '';
            $resultString['cus_mobile'] = '';
            $resultString['cus_email'] = '';
            $resultString['mailbody'] = '';
            $resultString['db_cart_type'] = '';
            $resultString['amountpaypable'] = '';
            $resultString['db_show_amount'] = '';

            return $resultString;
        }
    }

    /*----------------------completeagentcart comes here-------------------*/

    public function completeagentcart(Request $request)
    {
        if($request->ajax())
        {
            $resultString = [];
            $mydateinsert = date('d/m/Y');

            $cus_name = '';$cus_email = '';$cus_mobile = '';$no_of_days = 0;$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
            $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
            $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = 0;$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
            $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = 0;$manual_discount_row = '';$razorid_city = '';
            $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;$discount_amnt = 0;$promocode = '';$discountgiven = 0;
            $discount_percent = 0;

            $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';$event_coordinate = '';$pricetoinsert = 0;$item_json = '';$itemsadded_json ='';$trans_type ='';
            $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';$package_detail_id = NULL;
            $packages_row = '';$coupon_row = ''; $total_data_amount = 0;$total_price = 0;$db_cart_type = 'general';$show_amount = 'yes';
            $today = date("d-m-y-his");
            $curdate = date("d-m-Y-h:i:s");

            /****************Data from single booking form comes here***************************/

            $name = urldecode($request->name);
            $email = urldecode($request->email);
            $mobile = $request->mobile;
            $event_date = $request->event_date;
            $description = urldecode($request->description);

            $listitems = $request->listitems;
            $listitems2 = $request->listitems2;

            $cc_mails = $request->cc_mails;
            $bcc_mails = $request->bcc_mails;
            $add_gst = $request->add_gst;
            $manual_discount = $request->manual_discount;
            $cityid = $request->cityid;
            $cityname = $request->cityname;
            $manual_notes_added = $request->manual_notes_added;

            /****************Data from single booking form ends here***************************/

            $booking_type = 'cart_general'; 

            /****************Generate Quotation based on booking type is cart_general***************************/

            if($booking_type == 'cart_general')
            {
                $added_item_type =  'cart_entry'; $db_cart_type = 'general';$show_amount = 'yes';

                $listitems = substr($listitems, 0, -1);                        
                $listitems = explode("*",$listitems);
                $itemCount = count($listitems);

                $items = array();
                $j = 0;
                
                if(count(array_filter($listitems)) != 0) 
                {
                    for($i=1; $i<= $itemCount; $i++)
                    {
                        $oneitem = $listitems[$j];
                        $oneitem = explode("^",$oneitem);

                        $items[$i]['particular'] = $oneitem[0];
                        $items[$i]['item_qty'] = $oneitem[1];
                        $items[$i]['item_height'] = $oneitem[2];
                        $items[$i]['item_width'] = $oneitem[3];
                        $items[$i]['item_price'] = $oneitem[4];
                        $items[$i]['net_price'] = $oneitem[5];
                        $items[$i]['pack_desc'] = $oneitem[6];
                        $items[$i]['partidays'] = $oneitem[7];

                        $pricetoinsert = $pricetoinsert + $oneitem[5];                            
                        $j++;
                    }
                }
                    
                $item_json = json_encode($items);

                $listitems2 = substr($listitems2, 0, -1);
                $listitems2 = explode("#",$listitems2);
                $itemCount2 = count($listitems2);                
                
                $itemsadded = array();
                $k = 0;

                if(count(array_filter($listitems2)) != 0)
                {
                    for($i=1; $i<= $itemCount2; $i++)
                    {
                        $oneitems = $listitems2[$k];
                        $oneitems = explode("^",$oneitems);

                        $sent_particular = $oneitems[0];

                        $itemsadded[$i]['parti'] = $sent_particular;
                        $itemsadded[$i]['qtt'] = $oneitems[1];
                        $itemsadded[$i]['heightee'] = $oneitems[2];
                        $itemsadded[$i]['widthee'] = $oneitems[3];
                        $itemsadded[$i]['ammt'] = $oneitems[4];
                        $itemsadded[$i]['calc'] = $oneitems[5];
                        $itemsadded[$i]['partidays'] = $oneitems[6];

                        $pricetoinsert = $pricetoinsert + $oneitems[5];
                        $k++;
                    }
                }

                $itemsadded_json = json_encode($itemsadded);            
                
                if($package_detail_id != NULL)
                {
                    $total_data_amount = $total_data_amount + $pricetoinsert;
                }
                else
                {
                    $total_data_amount = $pricetoinsert; 
                }

                if($request->filled('manual_discount') && $request->manual_discount > 0) 
                {
                    $manual_discount = $request->manual_discount ? $request->manual_discount : 0;
                }
                else
                {
                    $manual_discount = 0;
                }

                if (Auth::check() && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) 
                {
                    $discount_percent = Auth::user()->discount;
                    $discount_amnt = round($total_data_amount - ( $total_data_amount *( $discount_percent/100 ) ) );
                }
                else 
                {
                        if($request->filled('promocode')) 
                        {
                            $promocode = $request->promocode;
                            $coupon = Coupon::where('couponcode','=',$promocode)->where('trans_type','=',$trans_type)->latest()->first();
                            $min_amount = $coupon->min_amount;
    
                            if($total_data_amount >= $min_amount)
                            {
                                $discountgiven = $coupon->discount;
                                $total_data_amount = $total_data_amount - $discountgiven;                    
                            }
                            else
                            {
                                $promocode = '';
                                $discountgiven = '';
                            }                            
                        }
                        else
                        {
                            $promocode = '';
                            $discountgiven = '';
                            $discount_percent = '';
                            $discount_amnt = '';
                        }
                }

                //$total_data_amount = $pricetoinsert; // this line is commented as we have calculated "total_data_amount" above in line no 2839
            }

            /****************Generate Quotation based on booking type is cart_general ends***************************/

            if($request->filled('event_coordinate') && $request->event_coordinate > 0) {
                $event_coordinate = $request->event_coordinate ? $request->event_coordinate : 0;
            }
            else {
                $event_coordinate = '0';
            }

            if($request->filled('transport_cost') && $request->transport_cost > 0) {
                $transport_cost = $request->transport_cost ? $request->transport_cost : NULL;
            }
            else {
                $transport_cost = NULL;
            }

            if($request->filled('crew_cost') && $request->crew_cost > 0) {
                $crew_cost = $request->crew_cost ? $request->crew_cost : NULL;
            }
            else  {
                $crew_cost = NULL;
            }

            if($add_gst == 'yes') { $add_gst_value = '1'; } else { $add_gst_value = '0'; } 

            /****************Code Written to insert data into quotation***************************/

            $data =  new Quotation();

            $filename = $today.'-Tosshead.pdf';
            $filename = 'admin_uploads/pdf/'.$filename;

            $data->item = $item_json;
            $data->name = $name;
            $data->email = $email;
            $data->mobile = $mobile;
            $data->pdf = $filename;
            $data->event_date = $event_date;
            $data->total_price = $total_data_amount;
            $data->added_item = $itemsadded_json;
            $data->crew_cost = $crew_cost;
            $data->transport_cost = $transport_cost;
            $data->add_gst = $add_gst_value;
            $data->manual_discount = $manual_discount;
            $data->cc_mails = $cc_mails;
            $data->bcc_mails = $bcc_mails;
            $data->no_of_days = $no_of_days;
            $data->booking_type = $db_cart_type;
            $data->show_amount = $show_amount;

            $data->package_detail_id = $package_detail_id;
            $data->package_price = $package_price;
            
            $data->description = $description;
            $data->event_coordinate = $event_coordinate;
            $data->quote_type = 'qtsuccessownagent';

            if($transport_cost == NULL) {
                $data->transport_cost = NULL;
            }
            if($crew_cost == NULL) {
                $data->crew_cost = NULL;
            }
            if($package_detail_id == NULL) {
                $data->package_detail_id = NULL;
            }
            
            $data->save();

            $data_remark = new Remark();
            $data_remark->remarks = $manual_notes_added;

            /*---------------add data to payorders-------------*/

            $payorder = new Payorder();
            $payorder->quoteid = $data->id;
            $payorder->invoicedate = $mydateinsert;
            $payorder->quotedate = $mydateinsert;
            $payorder->razorid = '';
            $payorder->city = $cityname;

            $payorder->save();

            /*---------------ends data to payorders-------------*/

            /*---------------add discount to quotations-------------*/              

            if($discount_percent != 0 && !empty($discount_amnt)) 
            {
                if (Auth::check() && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) 
                {
                    $package_price_data = $data->package_price;
                    $no_of_days_data = $data->no_of_days;

                    $user = Auth::user();
                    $onlineinquiry = new OnlineInquiry();
                    $onlineinquiry->created_users = $user->id;
                    $onlineinquiry->quotation = $data->id;
                    $onlineinquiry->discount = $discount_percent;
                    $onlineinquiry->amount = $total_data_amount;
                    $onlineinquiry->save();
                }
            }
            else if($discountgiven != 0 && !empty($promocode)) 
            {
                $onlinecoupon = new OnlineCoupon();
                $onlinecoupon->quotation = $data->id;
                $onlinecoupon->promocode = $promocode;
                $onlinecoupon->discountgiven = $discountgiven;
                $onlinecoupon->save();
            }

            /*---------------ends discount to quotations-------------*/
            
            /*---------------generate pdf and send mail to the user comes-------------*/  

            $rowcounts = Quotation::where('id','=',$data->id)->count();

            if($rowcounts == 1)
            {
                $quote_data = $this->getquotedetails($data->id);

                $cus_name = $quote_data['cus_name'];
                $cus_mobile = $quote_data['cus_mobile'];
                $cus_email = $quote_data['cus_email'];
                $mailbody = $quote_data['mailbody'];
                $cc_mails = $quote_data['cc_mails'];
                $bcc_mails = $quote_data['bcc_mails'];
                $amountpaypable = $quote_data['amountpaypable'];
                $db_cart_type = $quote_data['db_cart_type'];
                $db_show_amount = $quote_data['db_show_amount'];

                if($db_show_amount == 'no')
                {
                    $bodytop = '<div class="col-md-12" style="margin-top:2%;">
                        <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                        <p style="text-align:left;"><strong>Your Wedding Essential is successfully registered !</strong></p>
                        <p style="text-align:left;">Your enquiry id is <b>'.$cus_mobile.'</b>. Our wedding expert will revert back to you with the best possible price. 
                        Below are your wedding requirements submitted for your reference</p>
                    </div>';
                }
                else
                {
                    $bodytop = '<div class="col-md-12" style="margin-top:2%;">
                        <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                        <p style="text-align:left;"><strong>Your Event Booking is Confirmed !</strong></p>
                        <p style="text-align:left;">Your booking id <b>'.$cus_mobile.'</b>. Kindly check below quotation and proceed to make payment.</p>
                    </div>';
                }

                $message = $bodytop.''.$mailbody;

                $base_url = env('APP_URL').'/payment/';
                $quotelink = $base_url.encrypt($data->id);
                
                if($db_show_amount == 'yes')
                {
                    $message .= '<div style="text-align: center;width: 100%;"><br><br></div><div style="text-align: center;width: 100%;">
                        <a href="'.$quotelink.'" target="_blank" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Pay Now</a>
                    </div>';
                }

                $message .= '<p>Thank you for choosing Tosshead for your Event requirement ,incase of any queries kindly call us <strong>@ 8448444942</strong> or email us <strong>support@tosshead.com</strong> we will be happy to assist you.<br><br>Warm Regards<br>Team TOSSHEAD</p>';

                $to = $cus_email;

                /*----------Added for auto sms sending when booking is done-----------*/

                if($db_show_amount == 'yes')
                {
                    $msg_data = "Dear $cus_name,
                    Your Event Booking with Tosshead Events is confirmed. Please find the quotation link shared below and proceed to make the payment:

                    $quotelink";
                    $msg_data = urlencode($msg_data);
                    $ch=curl_init();
                    curl_setopt($ch,CURLOPT_URL,"https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=NfWOdck7dc5&MobileNo=".$cus_mobile."&SenderID=TOSSHE&Message=".$msg_data."&ServiceName=TEMPLATE_BASED");
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
                    $output =curl_exec($ch);
                    curl_close($ch);
                }
                else
                {
                    $msg_data = "Dear $cus_name,
                    Your Wedding Essential is successfully registered. Please find the details in the link shared below :

                    $quotelink";
                    $msg_data = urlencode($msg_data);
                    $ch=curl_init();
                    curl_setopt($ch,CURLOPT_URL,"https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=NfWOdck7dc5&MobileNo=".$cus_mobile."&SenderID=TOSSHE&Message=".$msg_data."&ServiceName=TEMPLATE_BASED");
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
                    $output =curl_exec($ch);
                    curl_close($ch);
                }

                /*----------Ends Added for auto sms sending when booking is done-----------*/

                $pdf = PDF::loadView('pdfs.invoices', array('mailbody'=>$mailbody))->setPaper('a4', 'landscape');
                $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true]);

                //$filepath = 'admin_uploads/pdf/'.$filename;
                Storage::put($filename, $pdf->output());

                $mailstatus = Mail::to($cus_email)->cc(['support@tosshead.com','samsrivastva@gmail.com'])->send(new CustomerQuotation($message));

                if (Mail::failures())
                {
                    $resultString['status'] = 2;
                    $resultString['trans_type'] = $trans_type;
                    $resultString['quotelink'] = '';
                    echo(json_encode($resultString));
                }
                else
                {
                    $resultString['status'] = 1;
                    $resultString['trans_type'] = $trans_type;
                    $resultString['quotelink'] = $quotelink;
                    echo(json_encode($resultString));
                }
            }

            /****************Code Written to insert data into quotation ends ***************************/
        }
        else 
        {
            $resultString['status'] = 2;
            $resultString['trans_type'] = '';
            $resultString['quotelink'] = '';
            echo(json_encode($resultString));
        }
    }
    
    /*----------------------completeagentcart ends here-------------------*/

}
