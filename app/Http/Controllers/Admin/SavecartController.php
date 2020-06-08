<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Remark;
use App\Payorder;

use App\Savecart;
use App\Quotation;
use App\OnlineInquiry;
use App\OnlineCoupon;

use App\PackageDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SavecartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            return datatables()->of(Savecart::latest())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="move" id="'.$data->id.'" class="move btn btn-danger btn-sm"><i class="fas fa-truck"></i></button>';
                        return $button;
                    })
                    ->addColumn('checkbox', '<textarea onchange="saveremarks(\'{{$id}}\')" value="{{ $remarks }}" cols="40" rows="5" style="color:#000;margin-right:10px;" name="{{ $id }}" id="{{ $id }}">{{ $remarks }}</textarea>')
                    ->rawColumns(['checkbox','action'])
                    ->make(true);
        }
        return view('admin.savedcart');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Savecart::findOrFail($id);
            $mydateinsert = date('d/m/Y');
            $resultString = [];

            $cus_name = '';$cus_email = '';$cus_mobile = '';$cus_no_of_days = '';$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
            $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
            $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = '';$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
            $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = '';$manual_discount_row = '';$razorid_city = '';
            $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;

            $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';
            $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';
            $packages_row = '';$coupon_row = '';

            $rowcounts = Savecart::where('id','=',$data->id)->count();
            if($rowcounts == 1)
            {
                $cus_ordertosave = ucfirst($data->ordertosave);
                $cus_eventdate = $data->event_date;
                $cus_requirements = json_decode($data->item, true);
                $cus_added_item = json_decode($data->added_item, true);
                $cus_manual_discount = $data->manual_discount;
                $cus_discount_percent = $data->discount_percent;
                $cus_discount_amnt = $data->discount_amnt;
                $cus_total_price = $data->total_price;
                $cus_crew_cost = $data->crew_cost;
                $cus_transport_cost = $data->transport_cost;
                $cus_add_gst = $data->add_gst;
                $cus_cityname = $data->cityname;
                $cus_cityid = $data->cityid;
                $cus_no_of_days = $data->no_of_days;
                $event_coordinate = $data->event_coordinate;
                $package_detail_id = $data->package_detail_id;
                $package_price = $data->package_price;
                $quote_type = $data->quote_type;

                if($cus_transport_cost > 0) {
                    $transport_row = '<tr class="transport_cost_row">
                            <td colspan="3" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">Transportation Cost</td>
                            <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. '.$cus_transport_cost.'</td>
                          </tr>';

                    $resultString['transport_cost'] = $cus_transport_cost;
                }
                else
                {
                    $resultString['transport_cost'] = '';
                }

                if($cus_crew_cost > 0) {
                    $crew_row = '<tr class="crew_cost_row">
                            <td colspan="3" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">Crew Transportation Cost</td>
                            <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. '.$cus_crew_cost.'</td>
                          </tr>';

                    $resultString['crew_cost'] = $cus_crew_cost;
                }
                else
                {
                    $resultString['crew_cost'] = '';
                }

                if($cus_manual_discount > 0) {
                    $manual_discount_row = '<tr id="added_final_items_manual" class="manual_discount_row">
                            <td colspan="3" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">Discount (Manually given)</td>
                            <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. '.$cus_manual_discount.'</td>
                          </tr>';

                    $resultString['manual_discount'] = $cus_manual_discount;
                }
                else
                {
                    $resultString['manual_discount'] = '';
                }

                /*---------Starts Package Details------------*/

                if($package_detail_id != NULL)
                {
                    $package_details = PackageDetail::select("package_details.*","packages.id as packageid","packages.package_name as packagename")
                        ->leftJoin("packages","package_details.id","=","packages.package_name")->orderBy('Id','Desc')->get();

                    foreach($package_details as $package_detail)
                    {
                        $pname = $package_detail->package_name;
                        $no_of_pax = $package_detail->no_of_pax;
                        $indoor_outdoor = $package_detail->indoor_outdoor;
                        $package_include = $package_detail->package_include;
                        $package_name = $package_detail->packagename;
                        $package_cost = $package_detail->price;

                        $packages_row .= '<tr>
                            <th colspan="4" style="border: 1px solid #d4603c;text-align: center;padding: 12px;background: #d4603c;color: #fff"> Package Details</th>
                        </tr>';

                        $packages_row .= '<tr>
                                <td colspan="3" style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 60%">Package Name </td>
                                <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 40%">'.$package_name.'</td>
                              </tr>';

                        $packages_row .= '<tr>
                                <td colspan="3" style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 60%">Venue </td>
                                <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 40%">'.$indoor_outdoor.'</td>
                              </tr>';

                        $packages_row .= '<tr>
                                <td colspan="3" style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 60%">Package Includes </td>
                                <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 40%">'.$package_include.'</td>
                              </tr>';

                        $packages_row .= '<tr>
                                <td colspan="3" style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 60%">Per day Package Price </td>
                                <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 40%">'.$package_cost.'</td>
                              </tr>';

                        $package_row_2 = '<tr>
                            <td style="border: 1px solid #d4603c;text-align: center;padding: 8px;width: 50%;font-weight:bold;">Package Details</td>
                            <td style="border: 1px solid #d4603c;text-align: center;padding: 8px;width: 10%;font-weight:bold;">'.$cus_no_of_days.'</td>
                            <td style="border: 1px solid #d4603c;text-align: center;padding: 8px;width: 15%;font-weight:bold;">1</td>
                            <td style="border: 1px solid #d4603c;text-align: center;padding: 8px;width: 25%;font-weight:bold;">Rs. '.($package_cost * $cus_no_of_days).' </td>
                        </tr>';
                    }

                    $resultString['package_id'] = $package_detail_id;
                }
                else
                {
                    $resultString['package_id'] = '';
                }

                /*---------Ends Starts Package Details------------*/

                /*---------Starts Items Details------------*/

                foreach ($cus_requirements as $items)
                {
                    $internalcounter++;
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

                    $items_type = 'generalitems';

                    if($item_qty == '') {
                        $items_type = 'specialitems';
                    }

                    $itemselected .= '<tr class="items_from_db '.$items_type.'">
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:50%;">'.$particular.'<br><span style="font-size:9px;">'.$pack_desc.'</span></td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 10%">'.$partidays.'</td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;" class="invoiceitems_details">'.$fulldata.'
                            <input type="hidden" name="particular[]" value="'.$particular.'" class="manual_particular">
                            <input type="hidden" name="item_qty[]" value="'.$item_qty.'" class="manual_item_qty">
                            <input type="hidden" name="item_height[]" value="'.$item_height.'" class="manual_item_height">
                            <input type="hidden" name="item_width[]" value="'.$item_width.'" class="manual_item_width">
                            <input type="hidden" name="item_price[]" value="'.$item_price.'" class="manual_item_price">
                            <input type="hidden" name="net_price[]" value="'.$net_price.'" class="calamount">
                            <input type="hidden" name="pack_desc[]" value="'.$pack_desc.'" class="manual_pack_desc">
                            <input type="hidden" name="partidays[]" value="'.$partidays.'" class="manual_partidays">
                        </td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;"> Rs. <span class="fill_item_total">'.($net_price).'</span></td>
                    </tr>';
                }

                if($cus_added_item != NULL)
                {
                    foreach ($cus_added_item as $itemsadds)
                    {
                        $internalcounter++;

                        $particular = nl2br($itemsadds['parti']);
                        $item_qty = $itemsadds['qtt'];
                        $item_height = $itemsadds['heightee'];
                        $item_width = $itemsadds['widthee'];
                        $item_price = $itemsadds['ammt'];
                        $net_price = $itemsadds['calc'];
                        $partidays = $itemsadds['partidays'];

                        $widthheight = $item_height.' * '.$item_width;

                        $extengiven = '';
                        $datashown = ($item_qty == '' ? $widthheight : $item_qty);

                        $fulldata = $datashown.'  '.$extengiven;
                        $totalamount = $totalamount + $net_price;

                        $items_type = 'generalitems';

                        if($item_qty == '') {
                            $items_type = 'specialitems';
                        }

                        $itemselected .= '<tr class="items_from_manual_entry '.$items_type.'">
                            <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:50%;">'.$particular.'</td>
                            <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 10%">'.$partidays.'</td>
                            <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;" class="invoiceitems_details">'.$fulldata.'
                                <input type="hidden" name="parti[]" value="'.$particular.'" class="manual_parti">
                                <input type="hidden" name="qtt[]" value="'.$item_qty.'" class="manual_qtt">
                                <input type="hidden" name="heightee[]" value="'.$item_height.'" class="manual_heightee">
                                <input type="hidden" name="item_width[]" value="'.$item_width.'" class="manual_item_width">
                                <input type="hidden" name="ammt[]" value="'.$item_price.'" class="manual_ammt">
                                <input type="hidden" name="calc[]" value="'.$net_price.'" class="calamount">
                                <input type="hidden" name="partidays[]" value="'.$partidays.'" class="manual_partidays">
                            </td>
                            <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;"> Rs. <span class="fill_item_total">'.($net_price).'</span></td>
                        </tr>';
                    }

                }

                if($package_detail_id != NULL)
                {
                    $totalamount = $totalamount + $package_cost * $cus_no_of_days;
                }

                $finalamount = $totalamount;

                $onlineinquiries_count= OnlineInquiry::where('quotation','=',$data->id)->count();

                if($onlineinquiries_count > 0)
                {
                    $onlineinquiries = OnlineInquiry::where('quotation','=',$data->id)->latest()->first();

                    $discount = $onlineinquiries->discount;
                    $amount = $onlineinquiries->amount;
                    $amount = round(($discount/100) * $finalamount);

                    $discount_row = '<tr class="discount_for_dealers_row">
                        <td colspan="3" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">Discount (% for dealers -- '.$discount.')</td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"><input class="editfields" name="discount_for_dealers_percent" id="discount_for_dealers_percent" value="'.$discount.'" readonly><span data-name="discount_for_dealers_row" class="remove_item_row"><i class="fas fa-window-close"></i></span><span data-name="discount_for_dealers_percent" class="edititem"><i class="fas fa-edit"></i> | </span></td>
                    </tr>';

                    $discountamt_row = '<tr id="added_final_items_dealers" class="discount_for_dealers_row">
                        <td colspan="3" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">Discounted Amount </td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:40%;">Rs. <span class="discount_percent_amount">'.$amount.'</span> </td>
                    </tr>';

                    $finalamount = $finalamount - $amount;
                    $resultString['discount_percent'] = $discount;
                    $resultString['discount_amount'] = $amount;

                }
                else
                {
                    $resultString['discount_percent'] = '';
                    $resultString['discount_amount'] = '';
                }

                /*Add if coupon code id used*/

                $onlinecoupon_count= OnlineCoupon::where('quotation','=',$data->id)->count();

                if($onlinecoupon_count > 0)
                {
                    $onlinecoupon = OnlineCoupon::where('quotation','=',$data->id)->latest()->first();

                    $promocode_db = $onlinecoupon->promocode;
                    $discountgiven_db = $onlinecoupon->discountgiven;

                    $coupon_row = '<tr id="added_final_items_coupon" class="discount_coupon_code_row">
                                    <td colspan="3" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width: 300px">Discount (Coupon Code -- '.$promocode_db.')</td>
                                    <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width: 640px">Rs. <input class="editfields" name="discount_coupon_code" id="discount_coupon_code" value="'.$discountgiven_db.'" readonly><span data-name="discount_coupon_code_row" class="remove_item_row"><i class="fas fa-window-close"></i></span><span data-name="discount_coupon_code" class="edititem"><i class="fas fa-edit"></i> | </span></td>
                                  </tr>';

                            $finalamount = $finalamount - $discountgiven_db;

                            $resultString['coupon_code'] = $promocode_db;
                            $resultString['coupon_discount'] = $discountgiven_db;

                }
                else
                {
                    $resultString['coupon_code'] = '';
                    $resultString['coupon_discount'] = '';
                }

                /*ends Add if coupon code id used*/

                if($cus_transport_cost > 0){
                    $finalamount = $finalamount + $cus_transport_cost;
                }
                if($cus_crew_cost > 0){
                    $finalamount = $finalamount + $cus_crew_cost;
                }
                if($cus_manual_discount > 0){
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

                    $taxes_row = '<tr style="" class="show_gst_data">
                        <td colspan="3" class="right" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">Add CGST: 9%</td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. <span class="add_cgst_data">'.$cgst_data.'</span></td>
                    </tr>
                    <tr class="show_gst_data">
                        <td colspan="3" class="right" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">Add SGST: 9%</td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. <span class="add_sgst_data">'.$sgst_data.'</span></td>
                    </tr>';

                    $resultString['added_gst'] = 'yes';
                }
                else
                {
                    $amountpaypable = $finalamount;
                    $taxes_row = '';
                    $resultString['added_gst'] = 'no';
                }

                $amountpaypable_row = '<tr id="amount_payable_row">
                        <td colspan="3" style="border: 1px solid #d4603c;text-align: right;padding: 8px;;font-weight:bold;font-size:18px;width:300px;">Payable Amount </td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;;font-weight:bold;font-size:18px;width:640px;">Rs. <span class="fill_total_amount_pay">'.$amountpaypable.'</span> </td>
                    </tr>';

                /*Ends Added for GST */

                $mailbody = '<tr>
                        <th colspan="4" style="border: 1px solid #d4603c;text-align: center;padding: 12px;background: #d4603c;color: #fff">Cart Details</th>
                    </tr>
                    <tr style="">
                        <td colspan="3" style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:300px;">Cart Id: '.$cus_ordertosave.'</td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;">Event Date: '.$cus_eventdate.'</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:300px;">City : '.$cus_cityname.'</td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"></td>
                    </tr>
                    <tr>
                        <th colspan="4" style="border: 1px solid #d4603c;text-align: center;padding: 12px;background: #d4603c;color: #fff"> Requirements</th>
                    </tr>
                    <tr style="">
                        <td style="border: 1px solid #d4603c;text-align: center;padding: 8px;width: 50%;font-weight:bold;">Description</td>
                        <td style="border: 1px solid #d4603c;text-align: center;padding: 8px;width: 10%;font-weight:bold;">Day(s)</td>
                        <td style="border: 1px solid #d4603c;text-align: center;padding: 8px;font-weight:bold;">Qty / Sq Ft</td>
                        <td style="border: 1px solid #d4603c;text-align: center;padding: 8px;font-weight:bold;">Amount</td>
                    </tr>
                    '.$itemselected.''.$package_row_2.'
                    <tr id="added_final_items">
                        <td colspan="3" class="right" style="border: 1px solid #d4603c;text-align: right;padding: 8px;width:300px;">Total Value</td>
                        <td style="border: 1px solid #d4603c;text-align: left;padding: 8px;width:640px;"> Rs. <span class="fill_total_amount_pre">'.$totalamount.'</span></td>
                    </tr>
                    '.$discount_row.''.$discountamt_row.''.$manual_discount_row.''.$coupon_row.''.$transport_row.''.$crew_row.''.$taxes_row.''.$amountpaypable_row.'
                    <input name="finalamount" id="finalamount" type="hidden" value="'.$finalamount.'" readonly>
                ';

                $resultString['mailbody'] = $mailbody;
                $resultString['internalcounter'] = $internalcounter;
            }
            else
            {
                $resultString['mailbody'] = '';
                $resultString['internalcounter'] = '';
            }

            echo(json_encode($resultString));
            //return response()->json(['data' => $resultString]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Savecart::findOrFail($id);
        if($data->delete())
        {
            echo 'Data Deleted';
        }
    }

    public function saveremarks($id,$remarks)
    {
        $resultString = [];
        $data = Savecart::findOrFail($id);
        $data->remarks = $remarks;

        if($data->save())
        {
            $resultString['lastquoteid'] = 'updated';
            echo(json_encode($resultString));
        }
        else
        {
            $resultString['lastquoteid'] = '';
            echo(json_encode($resultString));
        }
    }

    public function move($id)
    {
        $resultString = [];
        $data = Savecart::findOrFail($id);

        $package_detail_id= $data->package_detail_id ;
        $discount_percent = $data->discount_percent ;
        $discount_amnt = $data->discount_amnt ;
        $cityname = $data->cityname ;
	    $cityid = $data->cityid ;

        $quotation = new Quotation();

        $quotation->item = $data->item;
        $quotation->name = '';
        $quotation->email = '';
        $quotation->mobile = $data->ordertosave;
        $quotation->pdf = '';
        $quotation->event_date = $data->event_date;
        $quotation->event_coordinate = $data->event_coordinate;
        $quotation->total_price = $data->total_price;
        $quotation->no_of_days = $data->no_of_days;
        $quotation->description = '';
        $quotation->added_item = $data->added_item;
        $quotation->crew_cost = $data->crew_cost;
        $quotation->transport_cost = $data->transport_cost;
        $quotation->add_gst = $data->add_gst;
        $quotation->manual_discount = $data->manual_discount;
        $quotation->quote_type = $data->quote_type;
        $quotation->cc_mails = '';
        $quotation->bcc_mails = '';
        $quotation->created_at =  Carbon::today()->format('Y-m-d');

        if(!blank($package_detail_id))
        //if($package_detail_id == '' || is_null($package_detail_id))
        {
            $quotation->package_detail_id = $data->package_detail_id;
            $quotation->package_price = $data->package_price;
        }

        if($quotation->save())
        {
            $remark = new Remark();
            $payorder = new Payorder();
            $onlineInquiry = new OnlineInquiry();

            $remark ->quoteid = $quotation->id;
            $remark ->remarks = $data->remarks;
            $remark ->save();

            if($discount_percent != 0 && !empty($discount_amnt))
            {
                $onlineInquiry ->created_users = Auth::user()->id;
                $onlineInquiry ->quotation = $quotation->id;
                $onlineInquiry ->discount_percent = $discount_percent;
                $onlineInquiry ->discount_amnt = $discount_amnt;

                $onlineInquiry ->save();
            }

            $mydateinsert = date('d/m/Y');
            $payorder ->quoteid = $quotation->id;
            $payorder ->invoicedate = $mydateinsert;
            $payorder ->quotedate = $mydateinsert;
            $payorder ->razorid = "";
            $payorder ->city = $cityname;

            $payorder ->save();
            $data->delete();

            $resultString['lastquoteid'] = 'moved';
            echo(json_encode($resultString));
        }
        else
        {
            $resultString['lastquoteid'] = '';
            echo(json_encode($resultString));
        }
    }

}
