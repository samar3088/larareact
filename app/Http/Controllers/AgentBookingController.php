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
use App\Savecart;
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

class AgentBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
		$user = Auth::user();
		
		if($user->role_id != 1 &&  $user->role_id != 2)
		{
			return redirect()->route('homepage');
		}

		$homesliders = Homeslider::latest()->get();
        $coupon_texts = Coupontext::where('trans_type','general')->get();
        $coupon_texts = $coupon_texts->toArray();

        return view('website.agentbooking', compact(['coupon_texts', 'homesliders']));
	}
	
	public function agentsavecustomer(Request $request)
    {
		$resultString = [];

		$name = $request->name;
		$email = $request->email;
		$mobile = $request->mobile;
		$lead_source = 'Website';

		$customer = new Customer();
		$customer->name = $name;
		$customer->email = $email;
		$customer->mobile = $mobile;
		$customer->lead_source = $lead_source;

		$customer->save();

		$resultString['status'] = '';            
		echo(json_encode($resultString));
	}

    public function savecartdetails(Request $request)
    {
        $resultString = [];
        
        $cus_name = '';$cus_email = '';$cus_mobile = '';$cus_no_of_days = '';$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
        $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = '';$event_coordinate_row = '';
        $quotationids = '';$crew_row = '';$transport_row = '';$additional_row = '';$add_gst = '';$add_gst_value = 0;$manual_discount = '';$manual_discount_row = '';$coupon_row = '';
        $promocode_db = '';$cc_mails = '';$bcc_mails = '';$cus_cc_mails = '';$cus_bcc_mails = '';$discountgiven_db = 0;$manual_notes_added_row = '';$cus_manual_notes_added = '';
        
        $chosehtml = 0;$event_coordinate = 0;$cus_partidays = '';
        $mailbody = '';

        if($request->filled('ordertosave') && strlen($request->ordertosave) > 0)
        {
            $chosehtml = 1;

    		$ordertosave = $request->ordertosave;
    		$event_date = $request->event_date;
    		$total_price = $request->total_price;
    		
    		$manual_discount = 0;
            if($request->filled('manual_discount') && $request->manual_discount > 0) 
            {
                $manual_discount = $request->manual_discount ? $request->manual_discount : 0;
            }
    		
    		$discount_percent = 0;
            if($request->filled('discount_percent') && $request->discount_percent > 0) 
            {
                $discount_percent = $request->discount_percent ? $request->discount_percent : 0;
            }

    		$discount_amnt = 0;
    		if($request->filled('discount_amnt') && $request->discount_amnt > 0) 
    		{
    			$discount_amnt =  $request->discount_amnt ? $request->discount_amnt : 0;
    		}
    		
    		$listitems = $request->listitems;
    		$listitems = substr($listitems, 0, -1);
    		$listitems = explode("#",$listitems);
    		$itemCount = count($listitems);
    		
    		$transport_cost = $request->transport_cost;		
    		$crew_cost = $request->crew_cost;
    		
    		//aded
    		$listitems2 = $request->listitems2;
    		$listitems2 = substr($listitems2, 0, -1);
    		$listitems2 = explode("#",$listitems2);
    		$itemCount2 = count($listitems2);
    		//ends
    		
    		$cityname =  $request->cityname;
    		$cityid =  $request->cityid;
    		
    		/*Added for GST */
    		$add_gst = $request->add_gst;
            if($add_gst == 'yes') 
            {
    		    $add_gst_value = 1;
    		}
    		/*Ends Added for GST */
    		
            $manual_notes_added = $request->manual_notes_added;
            
    		$pricetoinsert = 0;
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
    		if(count(array_filter($listitems2)) != 0) 
    		{
        		for($i=1; $i<= $itemCount2; $i++) 
        		{
        		    $oneitems = $listitems2[$k];
        		    $oneitems = explode("^",$oneitems);
        			
        			$sent_particular = $oneitems[0];
        			$sent_particular = str_replace("'","`",$sent_particular);
                    
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
    		
            $find_data = Savecart::where('ordertosave',$ordertosave)->latest()->first();
            $rowcount_find_data = $find_data->count();

    		if($rowcount_find_data > 0) 
    		{
                $find_data->event_date = $event_date;
                $find_data->item = $item_json;
                $find_data->added_item = $itemsadded_json;
                $find_data->manual_discount = $manual_discount;
                $find_data->discount_percent = $discount_percent;
                $find_data->discount_amnt = $discount_amnt;
                $find_data->total_price = $pricetoinsert;
                $find_data->crew_cost = $crew_cost;

                $find_data->transport_cost = $transport_cost;
                $find_data->add_gst = $add_gst_value;
                $find_data->cityname = $cityname;
                $find_data->cityid = '1';
                $find_data->remarks = $manual_notes_added;
    		    
    		    if($find_data->save()) 
        		{
        		    $resultString['lastquoteid'] = $ordertosave;            
    		        echo(json_encode($resultString));
        		}
        		else
        		{
        		    $resultString['lastquoteid'] = '';            
    		        echo(json_encode($resultString));
        		}
    		}
    		else 
    		{
                $find_data = new Savecart();

                $find_data->ordertosave = $ordertosave;
                $find_data->event_date = $event_date;
                $find_data->item = $item_json;
                $find_data->added_item = $itemsadded_json;
                $find_data->manual_discount = $manual_discount;
                $find_data->discount_percent = $discount_percent;
                $find_data->discount_amnt = $discount_amnt;
                $find_data->total_price = $pricetoinsert;
                $find_data->crew_cost = $crew_cost;
                $find_data->transport_cost = $transport_cost;
                $find_data->add_gst = $add_gst_value;
                $find_data->cityname = $cityname;
                $find_data->cityid = '1';
                $find_data->remarks = $manual_notes_added;
        		
        		if($find_data->save()) 
        		{
        		    $resultString['lastquoteid'] = $ordertosave;            
    		        echo(json_encode($resultString));
        		}
        		else
        		{
        		    $resultString['lastquoteid'] = '';            
    		        echo(json_encode($resultString));
        		}
    		}
        }
        else 
        {
            $resultString['lastquoteid'] = '';            
		    echo(json_encode($resultString));
        }
	}
	
}
