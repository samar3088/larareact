<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Validator;
use App\Remark;
use DataTables;

use App\Payorder;
use App\Quotation;
use App\OnlineCoupon;
use App\OnlineInquiry;

use App\PackageDetail;
use App\UploadInvoice;
use Illuminate\Http\Request;
use App\Mail\CompletedInvoice;
use App\Mail\ConfirmQuotation;
use Illuminate\Support\Carbon;
use App\Mail\CustomerQuotation;
use App\Mail\InvoiceAttachment;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customertype = '';

        if(request()->ajax())
        {
                 $model = Quotation::select([
                    'quotations.*',
                    DB::raw("CONCAT(quotations.name,'<br>','Email : ',quotations.email,'<br>','Mobile : ',quotations.mobile,'<br>','City : ',payorders.city) as cusdetails"),
                    //DB::raw("STR_TO_DATE(`quotations.event_date`, '%d-%m-%Y')  as eventdate"),
                    'remarks.id as remarksid',
                    'remarks.remarks as remarks',
                    'payorders.id as payordersid',
                    'payorders.city as payorderscity',
                    'online_inquiries.id as online_inquiries_id',
                    'online_inquiries.discount as online_inquiries_discount',
                    'online_inquiries.amount as online_inquiries_amount',
                ])
                ->leftJoin("remarks","remarks.quoteid","=","quotations.id")
                ->leftJoin("payorders","payorders.quoteid","=","quotations.id")
                ->leftJoin("online_inquiries","online_inquiries.quotation","=","quotations.id")
                ->whereNull('quotations.order_confirm')
                ;

            return DataTables::eloquent($model)
            ->addColumn('action', function($data) {

                $date_allowed = date('2019-09-19');

                $button = '<button type="button" name="edit" id="'.$data->id.'" class="manualbtn showpopup btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>';
                $button .= '<br/>';
                $button .= '<button type="button" name="delete" id="'.$data->id.'" class="manualbtn delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                $button .= '<br/>';
                $button .= '<button type="button" name="resend" id="'.$data->id.'" class="manualbtn resend btn btn-danger btn-sm" title="resend quote" onclick="event.preventDefault();resendquote(\''.$data->id.'\');"><i class="fas fa-envelope"></i></button>';
                return $button;
            })
            ->addColumn('customertype', function ($data) {
                if ($data->customertype > 0) return 'B2B';
                return 'R';
            })
            ->addColumn('payorderscity', function ($data) {
                return $data->payorderscity;
            })
            ->addColumn('checkbox', function ($data) {
                $button = '<input type="button" class="edit_button btnedit" id="edit_button'.$data->id.'" value="edit" onclick="event.preventDefault();showpopup(\''.$data->id.'\');">';
                $button .= '&nbsp;&nbsp;';
                $button .= '<input type="button" class="save_button btnsave" id="save_button'.$data->id.'" value="save" onclick="event.preventDefault();showpopup(\''.$data->id.'\');" style="display:none;">';
                return $button;
            })
            ->addColumn('custimp', function ($data) {
                $button = '<textarea cols="6" rows="5" style="color:#000;margin-right:10px;background: #eee;" readonly>'.$data->description.'</textarea>';
                $button .= '&nbsp;&nbsp;';
                return $button;
            })
            ->addColumn('cusdetails', function ($data) {
                $cusdetails = $data->cusdetails;
                $cusdetails = nl2br($data->cusdetails);
                return $cusdetails;
            })
            /* ->filterColumn('cusdetails', function($query, $keyword) {
                $sql = "CONCAT(quotations.name,'<br>','Email : ',quotations.email,'<br>','Mobile : ',quotations.mobile,'<br>','City : ',payorders.city)  like ?";
                $$query->whereRaw($sql, ["%{$keyword}%"])->toJson();;
            }) */
            ->addColumn('quoterow', function ($data) {

                $date_allowed = date('2019-09-19');
                $quoterow = '<a href="'.Storage::url($data->pdf).'" target="_blank" id="pdflink-'.$data->id.'">Quote</a><br>';
                return $quoterow;

                /* if ($date_allowed <= $data->created_at)
                {
                    $quoterow = '<a href="admin_uploads/pdf/'.$data->pdf.'" target="_blank" id="pdflink-'.$data->id.'">Quote</a><br>';
                    return $quoterow;
                }
                else
                {
                    $quoterow = '<a href="admin_uploads/pdf/'.$data->pdf.'" target="_blank">Quote</a><br>';

                    if($data->new_file)
                    {
                        $quoterow .= 'Edited: <a href="admin_uploads/newpdf/'.$data->new_file.'" target="_blank" style="color: #8BC34A;">'.$data->pdf.'</a><br/>';
                    }

                    $quoterow .= '<a name="Resend1" style="background:black;color:white;padding-left: 5px;padding-right: 5px;cursor:pointer;" onclick="resend1(\''.$data->id.'\')">RESEND MAIL - 1</a><br/>';

                    if($data->new_file)
                    {
                        $quoterow .= '<a name="Resend2" style="background:#337acc;color:white;padding-left: 5px;padding-right: 5px;cursor:pointer;" onclick="resend2(\''.$data->id.'\')">RESEND MAIL - 2</a>';
                    }

                    return $quoterow;
                } */

            })
            ->addColumn('daterow', function ($data) {
                $daterow = '<span style="background:black;color:white;padding-left: 5px;padding-right: 5px;"> Quote date-</span><br/>';
                $quotations_date = date("d/m/y h:i:s",strtotime($data->update_time));
                $daterow .= $quotations_date;

                return $daterow;
            })
            ->addColumn('statusrow', function ($data) {

                if($data->statussel == 'confirmed')
                {
                    $statusrow = '<select name="select_goal" class="goalselect" id="selectid'.$data->id.'">
                        <option value="">--Select---</option>
                        <option value="confirmed" selected>Confirmed</option>
                        <option value="not-confirmed">Not Confirmed</option>
                    </select><button type="submit" class="btn btn-primary add_confirm_details" name="add_confirm_details" onclick="event.preventDefault();setvalue(\''.$data->id.'\');">ok</button>';
                }
                else
                {
                    $statusrow = '<select name="select_goal" class="goalselect" id="selectid'.$data->id.'">
                        <option value="">--Select---</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="not-confirmed" selected>Not Confirmed</option>
                    </select><button type="submit" class="btn btn-primary add_confirm_details" name="add_confirm_details" onclick="event.preventDefault();setvalue(\''.$data->id.'\');">ok</button>';
                }

                return $statusrow;
            })
            ->addColumn('remarksrow', '<textarea onchange="saveremarks(\'{{$id}}\')" value="{{ $remarks }}" cols="15" rows="5" style="color:#000;margin-right:10px;" name="remarks-{{ $id }}" id="remarks-{{ $id }}">{{ $remarks }}</textarea>')
            ->rawColumns(['checkbox','action','custimp','quoterow','daterow','cusdetails','cusdetails','statusrow','remarksrow'])
            ->filterColumn('cusdetails', function($query, $keyword) {
                $sql = "CONCAT(quotations.name,'<br>','Email : ',quotations.email,'<br>','Mobile : ',quotations.mobile,'<br>','City : ',payorders.city)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->toJson();

        }
        return view('admin.quotations');
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        $data = Quotation::findOrFail($id);
        Storage::delete($data->pdf);
        if($data->delete())
        {
            echo 'Data Deleted';
        }
    }

    public function saveremarks($id,$remarks)
    {
        $resultString = [];
        $data = Quotation::findOrFail($id);
        $data->event_remarks = $remarks;

        $data_remark = new Remark();
        $data_remark->remarks = $remarks;

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

    public function setvalue($id,$value)
    {
        $resultString = [];
        $data = Quotation::findOrFail($id);

        if($value == 'confirmed')
        {
            $data->statussel = $value;
            $data->order_confirm = $value;
        }
        else if($value == 'completed')
        {
            $data->completesel = $value;
            $data->order_complete = $value;
        }
        else if($value == 'not-confirmed')
        {
            $data->statussel = NULL;
            $data->order_confirm = NULL;
            $data->completesel = NULL;
            $data->order_complete = NULL;
        }
        else if($value == 'not-completed')
        {
            $data->completesel = NULL;
            $data->order_complete = NULL;
        }

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

    public function editfields($id,$item_id,$item_value)
    {
        $resultString = [];

        if($item_id == 'discount_coupon_code')
        {
            $data = OnlineCoupon::where('quotation',$id)->latest()->first();
            $data->discountgiven = $item_value;
        }
        else if($item_id == 'discount_for_dealers_percent')
        {
            $data = OnlineInquiry::where('quotation',$id)->latest()->first();
            $data->discount = $item_value;
        }
        else
        {
            $data = Quotation::findOrFail($id);
            $data->$item_id = $item_value;
        }

		//$inserted = mysqli_insert_id($con);

		if($data->save())
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

    public function updategst($id,$add_gst)
    {
        $resultString = [];

        if($add_gst == 'yes')
        {
            $data = Quotation::findOrFail($id);
            $data->add_gst = '1';
        }
        else
        {
            $data = Quotation::findOrFail($id);
            $data->add_gst = '0';
        }

		if($data->save())
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

    /* general quote details are being updated */

    public function genquote($id)
    {
        if(request()->ajax())
        {
            $data = Quotation::findOrFail($id);
            $mydateinsert = date('d/m/Y');
            $resultString = [];
            $rowcounts= Quotation::where('id','=',$data->id)->count();

            $cus_name = '';$cus_email = '';$cus_mobile = '';$cus_no_of_days = '';$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
            $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
            $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = 0;$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
            $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = 0;$manual_discount_row = '';$razorid_city = '';
            $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;$discount_percent = '';

            $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';$event_coordinate = '';
            $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';
            $packages_row = '';$coupon_row = '';

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

            if($rowcounts == 1)
            {
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

                $resultString['admin_event_expenses'] = $admin_event_expenses;
                $resultString['admin_event_gst'] = $admin_event_gst;
                $resultString['admin_event_remarks'] = $admin_event_remarks;

                $event_coordinate = $data->event_coordinate;

                if($cus_transport_cost > 0){
                    $transport_row = '<tr class="transport_cost_row">
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width:300px;">Transportation Cost</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;"> Rs. <input class="editfields" name="transport_cost" id="transport_cost" value="'.$cus_transport_cost.'" readonly><span data-name="transport_cost_row" class="remove_item_row"><i class="fas fa-window-close"></i></span><span data-name="transport_cost" class="edititem"><i class="fas fa-edit"></i> | </span></td>
                          </tr>';

                    $resultString['transport_cost'] = $cus_transport_cost;
                }
                else
                {
                    $resultString['transport_cost'] = '';
                }

                if($cus_crew_cost > 0) {
                    $crew_row = '<tr class="crew_cost_row">
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width:300px;">Crew Transportation Cost</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;"> Rs. <input class="editfields" name="crew_cost" id="crew_cost" value="'.$cus_crew_cost.'" readonly><span data-name="crew_cost_row" class="remove_item_row"><i class="fas fa-window-close"></i></span><span data-name="crew_cost" class="edititem"><i class="fas fa-edit"></i> | </span></td>
                          </tr>';

                    $resultString['crew_cost'] = $cus_crew_cost;
                }
                else
                {
                    $resultString['crew_cost'] = '';
                }

                if($cus_manual_discount > 0) {
                    $manual_discount_row = '<tr id="added_final_items_manual" class="manual_discount_row">
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width:300px;">Discount (Manually given)</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;"> Rs. <input class="editfields" name="manual_discount" id="manual_discount" value="'.$cus_manual_discount.'" readonly><span data-name="manual_discount_row" class="remove_item_row"><i class="fas fa-window-close"></i></span><span data-name="manual_discount" class="edititem"><i class="fas fa-edit"></i> | </span></td>
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
                        ->leftJoin("packages","package_details.package_name","=","packages.id")->where('package_details.id','=',$package_detail_id)->orderBy('Id','Desc')->get();

                    foreach($package_details as $package_detail)
                    {
                        $pname = $package_detail->package_name;
                        $no_of_pax = $package_detail->no_of_pax;
                        $indoor_outdoor = $package_detail->indoor_outdoor;
                        $package_include = $package_detail->package_include;
                        $package_name = $package_detail->packagename;
                        $package_cost = $data->package_price;

                        $packages_row .= '<tr class="package_row">
                            <th colspan="4" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">
                                Package Details <span data-name="package_row" class="removeitem"><i class="fas fa-window-close"></i></span>
                            </th>
                        </tr>';

                        $packages_row .= '<tr class="package_row">
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:300px;">Package Name </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;">'.$package_name.'</td>
                        </tr>';

                        $packages_row .= '<tr class="package_row">
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:300px;">Venue </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;">'.$indoor_outdoor.'</td>
                        </tr>';

                        $packages_row .= '<tr class="package_row">
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:300px;">Package Includes </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;">'.$package_include.'</td>
                        </tr>';

                        $packages_row .= '<tr class="package_row">
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:300px;">Per day Package Price </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;">'.$package_cost.'</td>
                        </tr>';

                        $package_row_2 = '<tr>
                                <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">'.$package_name.'</td>
                                <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 10%">'.$cus_no_of_days.'</td>
                                <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 15%">1</td>
                                <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 25%"> Rs. '.($package_cost * $cus_no_of_days).'</td>
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

                if($cus_requirements != NULL && count(array_filter([$cus_requirements])) != 0)
                {
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
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:50%;">'.$particular.'<br><span style="font-size:9px;">'.$pack_desc.'</span></td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 10%">'.$partidays.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;" class="invoiceitems_details">'.$fulldata.'
                                <input type="hidden" name="particular[]" value="'.$particular.'" class="manual_particular">
                                <input type="hidden" name="item_qty[]" value="'.$item_qty.'" class="manual_item_qty">
                                <input type="hidden" name="item_height[]" value="'.$item_height.'" class="manual_item_height">
                                <input type="hidden" name="item_width[]" value="'.$item_width.'" class="manual_item_width">
                                <input type="hidden" name="item_price[]" value="'.$item_price.'" class="manual_item_price">
                                <input type="hidden" name="net_price[]" value="'.$net_price.'" class="calamount">
                                <input type="hidden" name="pack_desc[]" value="'.$pack_desc.'" class="manual_pack_desc">
                                <input type="hidden" name="partidays[]" value="'.$partidays.'" class="manual_partidays">
                            </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;"> Rs. <span class="fill_item_total">'.($net_price).'</span><span data-name="items_from_db" class="remove_equipment_row"><i class="fas fa-window-close"></i></span></td>
                        </tr>';
                    }
                }

                if($cus_added_item != NULL && count(array_filter([$cus_added_item])) != 0)
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
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:50%;">'.$particular.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 10%">'.$partidays.'</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;" class="invoiceitems_details">'.$fulldata.'
                                <input type="hidden" name="parti[]" value="'.$particular.'" class="manual_parti">
                                <input type="hidden" name="qtt[]" value="'.$item_qty.'" class="manual_qtt">
                                <input type="hidden" name="heightee[]" value="'.$item_height.'" class="manual_heightee">
                                <input type="hidden" name="item_width[]" value="'.$item_width.'" class="manual_item_width">
                                <input type="hidden" name="ammt[]" value="'.$item_price.'" class="manual_ammt">
                                <input type="hidden" name="calc[]" value="'.$net_price.'" class="calamount">
                                <input type="hidden" name="partidays[]" value="'.$partidays.'" class="manual_partidays">
                            </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;"> Rs. <span class="fill_item_total">'.($net_price).'</span><span data-name="items_from_manual_entry" class="remove_equipment_row"><i class="fas fa-window-close"></i></span></td>
                        </tr>';

                    }

                }

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

                    $discount_row = '<tr class="discount_for_dealers_row">
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width:300px;">Discount (% for dealers -- '.$discount.')</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;"><input class="editfields" name="discount_for_dealers_percent" id="discount_for_dealers_percent" value="'.$discount.'" readonly><span data-name="discount_for_dealers_row" class="remove_item_row"><i class="fas fa-window-close"></i></span><span data-name="discount_for_dealers_percent" class="edititem"><i class="fas fa-edit"></i> | </span></td>
                    </tr>';

                    $discountamt_row = '<tr id="added_final_items_dealers" class="discount_for_dealers_row">
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width:300px;">Discounted Amount </td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:40%;">Rs. <span class="discount_percent_amount">'.$amount.'</span> </td>
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

                $onlinecoupon_count = OnlineCoupon::where('quotation','=',$data->id)->count();

                if($onlinecoupon_count > 0)
                {
                    $onlinecoupon = OnlineCoupon::where('quotation','=',$data->id)->latest()->first();

                    $promocode_db = $onlinecoupon->promocode;
                    $discountgiven_db = $onlinecoupon->discountgiven;

                    $coupon_row = '<tr id="added_final_items_coupon" class="discount_coupon_code_row">
                                <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 300px">Discount (Coupon Code -- '.$promocode_db.')</td>
                                <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 640px">Rs. <input class="editfields" name="discount_coupon_code" id="discount_coupon_code" value="'.$discountgiven_db.'" readonly><span data-name="discount_coupon_code_row" class="remove_item_row"><i class="fas fa-window-close"></i></span><span data-name="discount_coupon_code" class="edititem"><i class="fas fa-edit"></i> | </span></td>
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
                        <td colspan="3" class="right" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width:300px;">Add CGST: 9%</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;"> Rs. <span class="add_cgst_data">'.$cgst_data.'</span></td>
                    </tr>
                    <tr class="show_gst_data">
                        <td colspan="3" class="right" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width:300px;">Add SGST: 9%</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;"> Rs. <span class="add_sgst_data">'.$sgst_data.'</span></td>
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
                    <td colspan="3" style="border: 1px solid #d52a33;text-align: right;padding: 8px;;font-weight:bold;font-size:18px;width:300px;">Payable Amount </td>
                    <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;;font-weight:bold;font-size:18px;width:640px;">Rs. <span class="fill_total_amount_pay">'.$amountpaypable.'</span> </td>
                </tr>';

                /*Ends Added for GST */

                /*---------------Added for packages ------------------*/

                if($package_detail_id != NULL)
                {
                        $name_details_row = '<tr>
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:300px;">Email: <input class="editfields" name="email" id="email" value="'.$cus_email.'" readonly><span data-name="email" class="edititem"><i class="fas fa-edit"></i></span></td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;">Event Date : <input class="editfields" name="event_date" id="event_date" value="'.$event_date.'" readonly><span data-name="event_date" class="edititem"><i class="fas fa-edit"></i></span> <br> City : '.$razorid_city.'</td>
                        </tr>
                        <tr style="">
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:300px;"> Contact No. : <input class="editfields" name="mobile" id="mobile" value="'.$cus_mobile.'" readonly><span data-name="mobile" class="edititem"><i class="fas fa-edit"></i></span></td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;"> No. of Event Days: <input class="editfields" name="no_of_days" id="no_of_days" value="'.$cus_no_of_days.'" readonly><span data-name="no_of_days" class="edititem"><i class="fas fa-edit"></i></span></td>
                        </tr>';
                }
                else
                {
                    $name_details_row = '<tr>
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:300px;">Email: <input class="editfields" name="email" id="email" value="'.$cus_email.'" readonly><span data-name="email" class="edititem"><i class="fas fa-edit"></i></span></td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;">Event Date : <input class="editfields" name="event_date" id="event_date" value="'.$event_date.'" readonly><span data-name="event_date" class="edititem"><i class="fas fa-edit"></i></span></td>
                        </tr>
                        <tr style="">
                            <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:300px;"> Contact No. : <input class="editfields" name="mobile" id="mobile" value="'.$cus_mobile.'" readonly><span data-name="mobile" class="edititem"><i class="fas fa-edit"></i></span></td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;"> City : '.$razorid_city.'</td>
                    </tr>';
                }
                /*---------------Ends Added for packages ------------------*/

                $mailbody = '<tr>
                    <th colspan="4" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Quotation</th>
                    </tr>
                    <tr style="">
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:300px;">Customer Name: <input class="editfields" name="name" id="name" value="'.$cus_name.'" readonly><span data-name="name" class="edititem"><i class="fas fa-edit"></i></span></td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;">Quotation Date: '.$quotedate_data.'</td>
                    </tr>
                    '.$name_details_row.'
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
                    <tr id="added_final_items">
                        <td colspan="3" class="right" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width:300px;">Total Value</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width:640px;"> Rs. <span class="fill_total_amount_pre">'.$totalamount.'</span></td>
                    </tr>
                    '.$discount_row.''.$discountamt_row.''.$manual_discount_row.''.$coupon_row.''.$transport_row.''.$crew_row.''.$taxes_row.''.$amountpaypable_row.'
                <input name="package_detail_id" id="package_detail_id" type="hidden" value="'.$package_detail_id.'" readonly>
                <input name="package_cost" id="package_cost" type="hidden" value="'.$package_cost.'" readonly>
                <input name="finalamount" id="finalamount" type="hidden" value="'.$finalamount.'" readonly>';

                $resultString['mailbody'] = $mailbody;
                $resultString['internalcounter'] = $internalcounter;
                $resultString['no_of_days'] = $cus_no_of_days;
                $resultString['remarks_data'] = $remarks_data;
                $resultString['cc_mails'] = $cc_mails;
                $resultString['bcc_mails'] = $bcc_mails;
            }
            else
            {
                $resultString['mailbody'] = "";
                $resultString['internalcounter'] = "";
                $resultString['no_of_days'] = "";
                $resultString['remarks_data'] = "";
                $resultString['cc_mails'] = "";
                $resultString['bcc_mails'] = "";
            }

            echo(json_encode($resultString));
            //return response()->json(['data' => $resultString]);
        }
    }

    /* general quote details are being updated ends */

    /* general quote details are being mailed */

    public function mailgenquote($id,$quote_type)
    {
        if(request()->ajax())
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

            $data = Quotation::findOrFail($id);
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
        }
    }

    /* general quote details are being mailed  ends */

    /* list of all confirmed quotations comes here */

    public function confirmed()
    {
        $customertype = '';

        if(request()->ajax())
        {
                 $model = Quotation::select([
                    'quotations.*',
                    DB::raw("CONCAT(quotations.name,'<br>','Email : ',quotations.email,'<br>','Mobile : ',quotations.mobile,'<br>','City : ',payorders.city) as cusdetails"),
                    //DB::raw("SELECT STR_TO_DATE('quotations.event_date', '%d/%m/%Y') as quote_event_date"),
                    'remarks.id as remarksid',
                    'remarks.remarks as remarks',
                    'payorders.id as payordersid',
                    'payorders.city as payorderscity',
                    'online_inquiries.id as online_inquiries_id',
                    'online_inquiries.discount as online_inquiries_discount',
                    'online_inquiries.amount as online_inquiries_amount',
                ])
                ->leftJoin("remarks","remarks.quoteid","=","quotations.id")
                ->leftJoin("payorders","payorders.quoteid","=","quotations.id")
                ->leftJoin("online_inquiries","online_inquiries.quotation","=","quotations.id")
                ->whereNull('quotations.order_complete')
                ->where('quotations.order_confirm', '=', 'confirmed')
                ->orderBy('quotations.id', 'Desc')
                ->orderBy('quotations.event_date', 'Desc')
                ;

            return DataTables::eloquent($model)
            ->addColumn('action', function($data) {

                $date_allowed = date('2019-09-19');
                $button = '<button type="button" name="resend" id="'.$data->id.'" class="manualbtn resend btn btn-danger btn-sm" title="resend quote" onclick="event.preventDefault();resendquote(\''.$data->id.'\');"><i class="fas fa-envelope"></i></button>';
                $button .= '<br/>';
                $button .= '<button type="button" name="delete" id="'.$data->id.'" class="manualbtn delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                $button .= '<br/>';
                $button .= '<button type="button" name="send_details" id="'.$data->id.'" class="manualbtn send_details btn btn-primary btn-sm" title="send details" onclick="event.preventDefault();showdetails(\''.$data->id.'\');"><i class="fas fa-share"></i></button>';
                return $button;

            })
            ->addColumn('customertype', function ($data) {
                if ($data->customertype > 0) return 'B2B';
                return 'R';
            })
            ->addColumn('payorderscity', function ($data) {
                return $data->payorderscity;
            })
            ->addColumn('checkbox', function ($data) {
                $button = '<input type="button" class="edit_button btnedit" id="edit_button'.$data->id.'" value="edit" onclick="event.preventDefault();showpopup(\''.$data->id.'\');">';
                $button .= '&nbsp;&nbsp;';
                $button .= '<input type="button" class="save_button btnsave" id="save_button'.$data->id.'" value="save" onclick="event.preventDefault();showpopup(\''.$data->id.'\');" style="display:none;">';
                return $button;
            })
            ->addColumn('custimp', function ($data) {
                $button = '<textarea cols="6" rows="5" style="color:#000;margin-right:10px;background: #eee;" readonly>'.$data->description.'</textarea>';
                $button .= '&nbsp;&nbsp;';
                return $button;
            })
            ->addColumn('cusdetails', function ($data) {
                $cusdetails = $data->cusdetails;
                $cusdetails = nl2br($data->cusdetails);
                return $cusdetails;
            })
            /* ->filterColumn('cusdetails', function($query, $keyword) {
                $sql = "CONCAT(quotations.name,'<br>','Email : ',quotations.email,'<br>','Mobile : ',quotations.mobile,'<br>','City : ',payorders.city)  like ?";
                $$query->whereRaw($sql, ["%{$keyword}%"])->toJson();;
            }) */
            ->addColumn('quoterow', function ($data) {

                $date_allowed = date('2019-09-19');
                $quoterow = '<a href="'.Storage::url($data->pdf).'" target="_blank" id="pdflink-'.$data->id.'">Quote</a><br>';
                return $quoterow;
            })
            ->addColumn('daterow', function ($data) {
                $daterow = '<span style="background:black;color:white;padding-left: 5px;padding-right: 5px;"> Quote date-</span><br/>';
                $quotations_date = date("d/m/y h:i:s",strtotime($data->update_time));
                $daterow .= $quotations_date;

                return $daterow;
            })
            ->addColumn('statusrow', function ($data) {

                if($data->statussel == 'not-confirmed')
                {
                    $statusrow = '<select name="select_complete" class="select_complete" id="selectid'.$data->id.'">
                        <option value="">--Select---</option>
                        <option value="not-confirmed" selected>Not Confirmed</option>
                        <option value="completed" selected>Completed</option>
                        <option value="not-completed" selected>Not Completed</option>
                    </select><button type="submit" class="btn btn-primary add_confirm_details" name="add_confirm_details" onclick="event.preventDefault();setvalue(\''.$data->id.'\');">ok</button>';
                }
                else if($data->statussel == 'completed')
                {
                    $statusrow = '<select name="select_complete" class="select_complete" id="selectid'.$data->id.'">
                        <option value="">--Select---</option>
                        <option value="not-confirmed">Not Confirmed</option>
                        <option value="completed" selected>Completed</option>
                        <option value="not-completed" selected>Not Completed</option>
                    </select><button type="submit" class="btn btn-primary add_confirm_details" name="add_confirm_details" onclick="event.preventDefault();setvalue(\''.$data->id.'\');">ok</button>';
                }
                else
                {
                    $statusrow = '<select name="select_complete" class="select_complete" id="selectid'.$data->id.'">
                        <option value="">--Select---</option>
                        <option value="not-confirmed">Not Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="not-completed" selected>Not Completed</option>
                    </select><button type="submit" class="btn btn-primary add_confirm_details" name="add_confirm_details" onclick="event.preventDefault();setvalue(\''.$data->id.'\');">ok</button>';
                }

                return $statusrow;
            })
            ->addColumn('remarksrow', '<textarea onchange="saveremarks(\'{{$id}}\')" value="{{ $remarks }}" cols="15" rows="5" style="color:#000;margin-right:10px;" name="remarks-{{ $id }}" id="remarks-{{ $id }}">{{ $remarks }}</textarea>')
            ->rawColumns(['checkbox','action','custimp','quoterow','daterow','cusdetails','cusdetails','statusrow','remarksrow'])
            ->filterColumn('cusdetails', function($query, $keyword) {
                $sql = "CONCAT(quotations.name,'<br>','Email : ',quotations.email,'<br>','Mobile : ',quotations.mobile,'<br>','City : ',payorders.city)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->toJson();
        }
        return view('admin.confirmed');
    }

     /* list of all confirmed quotations ends here */

      /* list of all completed quotations comes here */

    public function completed()
    {
        $customertype = '';

        if(request()->ajax())
        {
                 $model = Quotation::select([
                    'quotations.*',
                    DB::raw("CONCAT(quotations.name,'<br>','Email : ',quotations.email,'<br>','Mobile : ',quotations.mobile,'<br>','City : ',payorders.city) as cusdetails"),
                    //DB::raw("SELECT STR_TO_DATE('quotations.event_date', '%d/%m/%Y') as quote_event_date"),
                    'remarks.id as remarksid',
                    'remarks.remarks as remarks',
                    'payorders.id as payordersid',
                    'payorders.city as payorderscity',
                    'online_inquiries.id as online_inquiries_id',
                    'online_inquiries.discount as online_inquiries_discount',
                    'online_inquiries.amount as online_inquiries_amount',
                ])
                ->leftJoin("remarks","remarks.quoteid","=","quotations.id")
                ->leftJoin("payorders","payorders.quoteid","=","quotations.id")
                ->leftJoin("online_inquiries","online_inquiries.quotation","=","quotations.id")
                //->whereNull('quotations.order_complete')
                ->where('quotations.order_complete', '=', 'completed')
                ->orderBy('quotations.id', 'Desc')
                ->orderBy('quotations.event_date', 'Desc')
                ;

            return DataTables::eloquent($model)
            ->addColumn('action', function($data) {

                $date_allowed = date('2019-09-19');
                $button = '<button type="button" name="resend" id="'.$data->id.'" class="manualbtn resend btn btn-danger btn-sm" title="resend quote" onclick="event.preventDefault();resendquote(\''.$data->id.'\');"><i class="fas fa-envelope"></i></button>';
                $button .= '<br/>';
                $button .= '<button type="button" name="delete" id="'.$data->id.'" class="manualbtn delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                $button .= '<br/>';
                $button .= '<button type="button" name="send_details" id="'.$data->id.'" class="manualbtn send_details btn btn-primary btn-sm" title="send details" onclick="event.preventDefault();showdetails(\''.$data->id.'\');"><i class="fas fa-share"></i></button>';
                return $button;
            })
            ->addColumn('customertype', function ($data) {
                if ($data->customertype > 0) return 'B2B';
                return 'R';
            })
            ->addColumn('payorderscity', function ($data) {
                return $data->payorderscity;
            })
            ->addColumn('checkbox', function ($data) {
                $button = '<input type="button" class="edit_button btnedit" id="edit_button'.$data->id.'" value="edit" onclick="event.preventDefault();showpopup(\''.$data->id.'\');">';
                $button .= '&nbsp;&nbsp;';
                $button .= '<input type="button" class="save_button btnsave" id="save_button'.$data->id.'" value="save" onclick="event.preventDefault();showpopup(\''.$data->id.'\');" style="display:none;">';
                return $button;
            })
            ->addColumn('custimp', function ($data) {
                $button = '<textarea cols="6" rows="5" style="color:#000;margin-right:10px;background: #eee;" readonly>'.$data->description.'</textarea>';
                $button .= '&nbsp;&nbsp;';
                return $button;
            })
            ->addColumn('cusdetails', function ($data) {
                $cusdetails = $data->cusdetails;
                $cusdetails = nl2br($data->cusdetails);
                return $cusdetails;
            })
            /* ->filterColumn('cusdetails', function($query, $keyword) {
                $sql = "CONCAT(quotations.name,'<br>','Email : ',quotations.email,'<br>','Mobile : ',quotations.mobile,'<br>','City : ',payorders.city)  like ?";
                $$query->whereRaw($sql, ["%{$keyword}%"])->toJson();;
            }) */
            ->addColumn('quoterow', function ($data) {

                $date_allowed = date('2019-09-19');

                $quoterow = '<a href="'.Storage::url($data->pdf).'" target="_blank" id="pdflink-'.$data->id.'">Quote</a><br>';
                return $quoterow;
            })
            ->addColumn('daterow', function ($data) {
                $daterow = '<span style="background:black;color:white;padding-left: 5px;padding-right: 5px;"> Quote date-</span><br/>';
                $quotations_date = date("d/m/y h:i:s",strtotime($data->update_time));
                $daterow .= $quotations_date;

                return $daterow;
            })
            ->addColumn('statusrow', function ($data) {

                if($data->statussel == 'confirmed')
                {
                    $statusrow = '<select name="select_goal" class="goalselect" id="selectid'.$data->id.'">
                        <option value="">--Select---</option>
                        <option value="confirmed" selected>Confirmed</option>
                        <option value="not-confirmed">Not Confirmed</option>
                    </select><button type="submit" class="btn btn-primary add_confirm_details" name="add_confirm_details" onclick="event.preventDefault();setvalue(\''.$data->id.'\');">ok</button>';
                }
                else
                {
                    $statusrow = '<select name="select_goal" class="goalselect" id="selectid'.$data->id.'">
                        <option value="">--Select---</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="not-confirmed" selected>Not Confirmed</option>
                    </select><button type="submit" class="btn btn-primary add_confirm_details" name="add_confirm_details" onclick="event.preventDefault();setvalue(\''.$data->id.'\');">ok</button>';
                }

                return $statusrow;
            })
            ->addColumn('remarksrow', '<textarea onchange="saveremarks(\'{{$id}}\')" value="{{ $remarks }}" cols="15" rows="5" style="color:#000;margin-right:10px;" name="remarks-{{ $id }}" id="remarks-{{ $id }}">{{ $remarks }}</textarea>')
            ->rawColumns(['checkbox','action','custimp','quoterow','daterow','cusdetails','cusdetails','statusrow','remarksrow'])
            ->filterColumn('cusdetails', function($query, $keyword) {
                $sql = "CONCAT(quotations.name,'<br>','Email : ',quotations.email,'<br>','Mobile : ',quotations.mobile,'<br>','City : ',payorders.city)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->toJson();
        }
        return view('admin.completed');
    }

      /* list of all completed quotations ends here */

      /* get details for pop up of confirmed quotations */

    public function confirmedpop($id)
    {
        $resultString = [];
        $data = Quotation::findOrFail($id);

        $resultString['quote_by'] = $data->quote_by;
		$resultString['payment_received'] = $data->payment_received;
		$resultString['payment_balance'] = $data->payment_balance;
		$resultString['quote_person'] = $data->quote_person;
		$resultString['quote_contact'] = $data->quote_contact;
        $resultString['payment_collected'] = $data->payment_collected;

        echo(json_encode($resultString));

    }
    /* get details for pop up of confirmed quotations ends */

    /* update quotation from quotations page */

    public function updatequotations(Request $request)
    {
        if(request()->ajax())
        {
            $resultString = [];
            $mydateinsert = date('d/m/Y');

            $cus_name = '';$cus_email = '';$cus_mobile = '';$cus_no_of_days = '';$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
            $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
            $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = 0;$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
            $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = 0;$manual_discount_row = '';$razorid_city = '';
            $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;$discount_percent = '';

            $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';$event_coordinate = '';$pricetoinsert = 0;$item_json = '';$itemsadded_json ='';
            $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';
            $packages_row = '';$coupon_row = ''; $total_data_amount = 0;
            $today = date("d-m-y-his");
            $curdate = date("d-m-Y-h:i:s");

            $discount_percent = $request->discount_percent;
            $discount_coupon_code = $request->discount_coupon_code;
            $manual_discount = $request->manual_discount;
            $amount_total = $request->amount_total;
            $package_detail_id = $request->package_detail_id;
            $listitems = $request->listitems;
            $listitems2 = $request->listitems2;
            $add_gst = $request->add_gst;
            $transport_cost = $request->transport_cost;
            $crew_cost = $request->crew_cost;
            $quoteid = $request->quoteid;
            $cc_mails = $request->cc_mails;
            $bcc_mails = $request->bcc_mails;

            $listitems = substr($listitems, 0, -1);
            $listitems = explode("#",$listitems);
            $itemCount = count($listitems);

            $listitems2 = substr($listitems2, 0, -1);
            $listitems2 = explode("#",$listitems2);
            $itemCount2 = count($listitems2);

            $data = Quotation::findOrFail($quoteid); //get quote model oto fetch the quote details
            $quote_type = $data->quote_type;

            /* updating the details for the quotation */

            if($request->filled('discount_percent') && $request->discount_percent > 0) {
                $discount_percent = $request->discount_percent ? $request->discount_percent : 0;
            }
            else
            {
                $discount_percent = '';
                $delete_discount_percent = OnlineInquiry::where('quotation','=',$data->id)->latest()->get();
                $delete_discount_percent->delete();
            }

            if($request->filled('discount_coupon_code') && $request->discount_coupon_code > 0) {
                $discount_coupon_code = $request->discount_coupon_code ? $request->discount_coupon_code : 0;
            }
            else
            {
                $discount_coupon_code = '';
                $delete_coupon_discount = OnlineCoupon::where('quotation','=',$data->id)->latest()->get();
                $delete_coupon_discount->delete();
            }

            if($request->filled('manual_discount') && $request->manual_discount > 0) 
            {
                $manual_discount = $request->manual_discount ? $request->manual_discount : 0;
            }
            else
            {
                $manual_discount = 0;
            }

            if($request->filled('event_coordinate') && $request->event_coordinate > 0) {
                $event_coordinate = $request->event_coordinate ? $request->event_coordinate : 0;
            }
            else {
                $event_coordinate = '0';
            }

            if($request->filled('package_detail_id') && $request->package_detail_id > 0) {    
                $package_detail_id = $request->package_detail_id ? $request->package_detail_id : NULL;
            }
            else {
                $package_detail_id = NULL;
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

            $no_of_days = 0;

            if($request->filled('no_of_days') && $request->no_of_days > 0) {
                $no_of_days = $request->no_of_days ? $request->no_of_days : NULL;
            }

            if($add_gst == 'yes') { $add_gst_value = '1'; } else { $add_gst_value = '0'; }

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

            /*---------remove current pdf from quotations table---------*/

            Storage::delete($data->pdf);
            $filename = $today.'-Tosshead.pdf';
            $filename = 'admin_uploads/pdf/'.$filename;

            /*---------remove current pdf from quotations table ends---------*/

            $data->item = $item_json;
            $data->pdf = $filename;
            $data->total_price = $pricetoinsert;
            $data->added_item = $itemsadded_json;
            $data->crew_cost = $crew_cost;
            $data->transport_cost = $transport_cost;
            $data->add_gst = $add_gst_value;
            $data->manual_discount = $manual_discount;
            $data->no_of_days = $no_of_days;
            $data->cc_mails = $cc_mails;
            $data->bcc_mails = $bcc_mails;

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

            $payorder = Payorder::where('quoteid','=',$data->id)->latest()->first();
            $payorder->invoicedate = $mydateinsert;

            $package_price_data = $data->package_price;
            $no_of_days_data = $data->no_of_days;

            if($package_detail_id != NULL) {
                $total_data_amount = $pricetoinsert + $package_price_data * $no_of_days_data;
            }
            else {
                $total_data_amount = $pricetoinsert;
            }

            $onlineinquiries_count = OnlineInquiry::where('quotation','=',$data->id)->count();

            if($onlineinquiries_count)
            {
                $onlineinquiry = OnlineInquiry::where('quotation','=',$data->id)->latest()->first();
                $onlineinquiry->amount = $total_data_amount;
                $onlineinquiry->save();
            }

            /* updating the details for the quotation ends */

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

                $pdf = PDF::loadView('pdfs.invoices', array('mailbody'=>$mailbody))->setPaper('a4', 'landscape');
                $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true]);

                //$filepath = 'admin_uploads/pdf/'.$filename;
                Storage::put($filename, $pdf->output());

                if(Storage::disk('public')->exists($filename))
                {
                    $resultString['status'] = 1;
                    $resultString['response_file_path'] = $filename;
                    $resultString['cus_name'] = $cus_name;
                    $resultString['cus_email'] = $cus_email;
                    $resultString['cus_mobile'] = $cus_mobile;
                    echo(json_encode($resultString));
                }
                else
                {
                    $resultString['status'] = 2;
                    $resultString['response_file_path'] = $filename;
                    $resultString['cus_name'] = $cus_name;
                    $resultString['cus_email'] = $cus_email;
                    $resultString['cus_mobile'] = $cus_mobile;
                    echo(json_encode($resultString));
                }

            } /* ends rowcount condition*/

        } /* Ajax request check ends here */

    }

    /* update quotation from quotations page ends */

    /* get details for pop up of confirmed quotations */

    public function updateconfirmed(Request $request)
    {
        $chosehtml = 0;

        if(request()->ajax())
        {
            $item_id = $request['item_id'];
            $item_type = $request['item_type'];
            $quote_by = $request['quote_by'];
            $payment_received = $request['payment_received'];
            $payment_balance = $request['payment_balance'];
            $quote_person = $request['quote_person'];
            $quote_contact = $request['quote_contact'];
            $payment_collected = $request['payment_collected'];

            $data = Quotation::findOrFail($item_id);
            $mydateinsert = date('d/m/Y');
            $resultString = [];
            $rowcounts= Quotation::where('id','=',$data->id)->count();

            $data->quote_by = $quote_by;
            $data->payment_received = $payment_received;
            $data->payment_balance = $payment_balance;
            $data->quote_person = $quote_person;
            $data->quote_contact = $quote_contact;
            $data->payment_collected = $payment_collected;

            $data->save();

            $cus_name = '';$cus_email = '';$cus_mobile = '';$cus_no_of_days = '';$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
            $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
            $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = 0;$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
            $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = 0;$manual_discount_row = '';$razorid_city = '';
            $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;$discount_percent = '';

            $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';$chosehtml = 1;$event_coordinate = '';
            $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';$coupon_row = '';

            $cus_payment_received = $cus_quote_person = $cus_quote_contact = $cus_payment_collected	= $cus_payment_balance = '';

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

            if($rowcounts == 1)
            {
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

                $cus_payment_received = $data->payment_received;
                $cus_quote_person = $data->quote_person;
                $cus_quote_contact = $data->quote_contact;
                $cus_payment_collected = $data->payment_collected;
                $cus_payment_balance = $data->payment_balance;
                $event_coordinate = $data->event_coordinate;

                if($event_coordinate > 0) {
                    $event_coordinate_row = '<tr>
                            <td colspan="2" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Event Coordinate</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.($event_coordinate * $cus_no_of_days).'</td>
                        </tr>';
                }
                if($cus_transport_cost > 0){
                    $transport_row = '<tr>
                            <td colspan="2" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Transportation Cost</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$cus_transport_cost.'</td>
                        </tr>';
                }
                if($cus_crew_cost > 0){
                    $crew_row = '<tr>
                            <td colspan="2" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Crew Transportation Cost</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$cus_crew_cost.'</td>
                        </tr>';
                }
                if($cus_manual_discount > 0){
                    $manual_discount_row = '<tr>
                            <td colspan="2" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Discount</td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$cus_manual_discount.'</td>
                        </tr>';
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

                        $packages_row .= '<tr>
                            <th colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff"> Package Details</th>
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

                        $package_row_2 = '<tr>
                                    <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">'.$package_name.'</td>
                                    <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 25%">'.$cus_no_of_days.'</td>
                                    <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 15%">1</td>
                                </tr>';
                        }
                }

                /*---------Ends Starts Package Details------------*/

                if($cus_requirements != NULL && count(array_filter([$cus_requirements])) != 0)
                {
                    foreach ($cus_requirements as $items)
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

                        //$extengiven = ($item_qty == '' ? 'Sq. ft' : 'Qty');
                        $extengiven = '';
                        $datashown = ($item_qty == '' ? $widthheight : $item_qty);

                        $fulldata = $datashown.'  '.$extengiven;

                        $totalamount = $totalamount + $net_price;

                        $itemselected .= '<tr>
                                    <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">'.$particular.'<br><span style="font-size:9px;">'.$pack_desc.'</span></td>
                                    <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 25%">'.$partidays.'</td>
                                    <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 15%">'.$fulldata.'</td>
                                </tr>';
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

                        $itemselected .= '<tr>
                                <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">'.$particulars.'</td>
                                <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 25%">'.$partidays.'</td>
                                <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 15%">'.$fulldata.'</td>
                            </tr>';
                    }
                }

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

                    $discount_row = '<tr>
                            <td colspan="2" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Discount </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">'.$discount.' % </td>
                        </tr>';

                    $discountamt_row = '<tr>
                            <td colspan="2" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Discounted Amount </td>
                            <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">Rs. '.$amount.' </td>
                        </tr>';

                    $finalamount = $finalamount - $amount;
                }

                /*Add if coupon code id used*/

                $onlinecoupon_count = OnlineCoupon::where('quotation','=',$data->id)->count();

                if($onlinecoupon_count > 0)
                {
                    $onlinecoupon = OnlineCoupon::where('quotation','=',$data->id)->latest()->first();

                    $promocode_db = $onlinecoupon->promocode;
                    $discountgiven_db = $onlinecoupon->discountgiven;

                    $coupon_row = '<tr>
                        <td colspan="2" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Discount (Coupon Code -- '.$promocode_db.')</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">Rs. '.$discountgiven_db.' </td>
                    </tr>';

                    $finalamount = $finalamount - $discountgiven_db;
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

                    $taxes_row = '<tr style="">
                        <td colspan="2" class="right" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Add CGST: 9%</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$cgst_data.'</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="right" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%">Add SGST: 9%</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> Rs. '.$sgst_data.'</td>
                    </tr>';
                }
                else
                {
                    $amountpaypable = $finalamount;
                    $taxes_row = '';
                }

                $amountpaypable_row = '<tr>
                    <td colspan="2" style="border: 1px solid #d52a33;text-align: right;padding: 8px;width: 60%;font-weight:bold;font-size:18px;">Payable Amount </td>
                    <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%;font-weight:bold;font-size:18px;">Rs. '.$amountpaypable.' </td>
                </tr>';

                /*Ends Added for GST */

                if($cus_payment_balance > 0)
                {
                    $bodytop = '<div>
                            <div class="col-md-10" style="width:90%;float:left;">
                                <p style="text-align:left;color:#000;">Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                                <p style="text-align:left;color:#000;">We confirm your receipt of '.$cus_payment_collected.' payment of Rs.'.$cus_payment_received.' /- and your balance payment of Rs. '.$cus_payment_balance.' /- to be paid on the day of the event.</p>
                                <p style="text-align:left;color:#000;">Your Event has been assigned to Mr.'.$cus_quote_person.' - Head Production and you can reach him @ +91-'.$cus_quote_contact.'. </p>
                                <p style="text-align:left;color:#000;">Mr '.$cus_quote_person.' and team will be in touch with you at the earliest and take forward your event execution.</p>
                                <p style="text-align:left;color:#000;">Thank you for choosing TOSSHEAD for your event.</p>
                                <h3 style="text-align:left;"><u>Event Details :</u></h3>
                            </div>
                            <div class="col-md-2" style="margin-top:2%;width:10%;float:right;">
                                <img style="max-width:90%;" src="'.env('APP_URL').'/frontend/images/logo-black.png'.'">
                            </div>
                        </div>';
                }
                else
                {
                    $bodytop = '<div>
                            <div class="col-md-10" style="width:90%;float:left;">
                                <p style="text-align:left;color:#000;">Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                                <p style="text-align:left;color:#000;">We confirm your receipt of '.$cus_payment_collected.' payment of Rs.'.$cus_payment_received.' -/</p>
                                <p style="text-align:left;color:#000;">Your Event has been assigned to Mr.'.$cus_quote_person.' - Head Production and you can reach him @ +91-'.$cus_quote_contact.'. </p>
                                <p style="text-align:left;color:#000;">Mr '.$cus_quote_person.' and team will be in touch with you at the earliest and take forward your event execution.</p>
                                <p style="text-align:left;color:#000;">Thank you for choosing TOSSHEAD for your event.</p>
                                <h3 style="text-align:left;"><u>Event Details :</u></h3>
                            </div>
                            <div class="col-md-2" style="margin-top:2%;width:10%;float:right;">
                                <img style="max-width:90%;" src="'.env('APP_URL').'/frontend/images/logo-black.png'.'">
                            </div>
                        </div>';
                }

                $mailbody = '<table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%">
                    <tr>
                        <td colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 12px;">'.$bodytop.'</td>
                    </tr>
                    <tr>
                        <th colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff">Quotation</th>
                    </tr>
                    <tr style="">
                        <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Customer Name: '.$cus_name.'</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">Quotation Date: '.$quotedate_data.'</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Email: '.$cus_email.'</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">Event Date : '.$cus_eventdate.' </td>
                    </tr>
                    <tr style="">
                        <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%"> Contact No. : '.$cus_mobile.' </td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%"> City: '.$razorid_city.'</td>
                    </tr>
                    '.$packages_row.'
                    <tr>
                        <th colspan="3" style="border: 1px solid #d52a33;text-align: center;padding: 12px;background: #d52a33;color: #fff"> Requirements</th>
                    </tr>
                    <tr style="">
                        <td style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 60%;font-weight:bold;">Description</td>
                        <td style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 25%;font-weight:bold;">Day(s)</td>
                        <td style="border: 1px solid #d52a33;text-align: center;padding: 8px;width: 15%;font-weight:bold;">Qty / Sq Ft</td>
                    </tr>
                    '.$itemselected.'
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
                        </td>
                    </tr>
                </table>';

                //$message = $bodytop.''.$mailbody;
                $message = $mailbody;
                $message .= '<p>Incase of any queries kindly call us <strong>@ 8448444942</strong> or email us <strong>support@tosshead.com</strong> we will be happy to assist you.</p>';
                $message .= '<p>Happy Eventing.</p>';
                $message .= '<p><a href="https://tosshead.com" target="_blank">tosshead.com</a></p>';

                $to = $cus_email;

                $mailstatus = Mail::to($cus_email)->cc(['support@tosshead.com','production@tosshead.com','samsrivastva@gmail.com'])->send(new ConfirmQuotation($message));

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

            } /* row counts if condition ends here */

        } /* ajax if condition ends here */

    }

    /* get details for pop up of confirmed quotations ends */

    /* get details for pop up of completed quotations */

    public function completedpop($id)
    {
        $resultString = [];
        $count = UploadInvoice::where('quoteid','=',$id)->count();
        $counter = 0;

        if($count > 0)
        {
            $datas = UploadInvoice::where('quoteid','=',$id)->latest()->get();

            foreach($datas as $data)
            {
                $item_id = $data->id;
                $quoteid = $data->quoteid;
                $invoice_path = $data->invoice_path;
                $invoice_number = $data->invoice_number;
                $customer_gst = $data->customer_gst;
                $created_at = $data->created_at;
                $updated_at = $data->updated_at;

                $resultString[$counter] = ["item_id" => $item_id,"quoteid" => $quoteid,"invoice_path" => $invoice_path,"invoice_number" => $invoice_number,"customer_gst" => $customer_gst,"created_at" => $created_at,"updated_at" => $updated_at];
            }
        }

        echo(json_encode($resultString));
    }

    /* get details for pop up of completed quotations ends */

    /* Save details for completed quotations */

    public function updatecompleted(Request $request)
    {
        $rules = array(
            'package_name'    =>  'required',
            'city'     =>  'required',
            'description'     =>  'required',
            'package_image'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $path = $request->file('file')->store('admin_uploads/pdf');

        $form_data = array(
            'quoteid'        =>  $request->quoteid,
            'invoice_number'         =>  $request->invoice_number,
            'customer_gst'         =>  $request->customer_gst,
            'invoice_path'             =>  $path
        );

        UploadInvoice::create($form_data);
        return response()->json(['success' => 'Data Added successfully.']);
    }

    /* Save details for completed quotations ends */

    /* mail details from completed quotations */

    public function completedgen($item_id,$invoice_number,$gst_no)
    {
        $quoteid = $item_id;
        $invoice_number = $invoice_number;
        $customer_gst = $gst_no;

        if(request()->ajax())
        {
            $data = Quotation::findOrFail($quoteid);
            $mydateinsert = date('d/m/Y');
            $today = date("d-m-y-his");
            $curdate = date("d-m-Y-h:i:s");
            $resultString = [];
            $rowcounts= Quotation::where('id','=',$data->id)->count();

            $cus_name = '';$cus_email = '';$cus_mobile = '';$cus_no_of_days = '';$cus_requirements = '';$itemselected = '';$cgst_data = 0;$sgst_data = 0;$cgstrow = '';$sgstrow = '';
            $discount_row = '';$discountamt_row = '';$totalamount = 0; $finalamount = 0;$amountpaypable = 0;$amountpaypable_row = ''; $package_name = '';$no_of_guest_exp = '';
            $package_row_2 = '';$packages_row = '';$package_cost = 0;$package_price = 0;$cus_total_price = 0;$cus_eventdate = '';$razorid_data = '';$invoicedate_data = '';
            $quotedate_data = '';$chosehtml = 0;$crew_row = '';$transport_row = '';$additional_row = '';$manual_discount = 0;$manual_discount_row = '';$razorid_city = '';
            $mailbody = '';$internalcounter = 0;$cc_mails = '';$bcc_mails = '';$add_gst = '';$add_gst_value = 0;$discount_percent = '';

            $quotationids = '';    $cus_cc_mails = '';$cus_bcc_mails = '';$customer_gst_row = '';$event_coordinate = '';
            $admin_event_expenses = 0;$admin_event_gst = 0;$admin_event_remarks = '';$remarks_data = '';$name_details_row = '';
            $packages_row = '';$coupon_row = '';$paymentreference = '';

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

            if($rowcounts == 1)
            {
                $chosehtml = 1;
                /*-------------adding for quotation invoice date ---------------*/
                    $payorder = Payorder::where('quoteid','=',$data->id)->latest()->first();
                    $payorder->invoicedate = $mydateinsert;
                    $payorder->razorid = $paymentreference;
                    $payorder->save();

                /*-------------adding for quotation invoice date ---------------*/

                if(!empty($invoice_number))
                {
                    $customer_gst_row = '<tr style="">
                        <td colspan="2" style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 60%">Customer GST No.: '.$customer_gst.'</td>
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">Invoice No.: '.$invoice_number.'</td>
                    </tr>';
                }

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

                /*---------Starts Items Details------------*/

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

            /*Ends Added for GST */

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
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 50%">Invoice Date: '.$mydateinsert.'</td>
                    </tr>
                    '.$customer_gst_row.'
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
                        <td style="border: 1px solid #d52a33;text-align: left;padding: 8px;width: 40%">Invoice Date: '.$mydateinsert.'</td>
                    </tr>
                    '.$customer_gst_row.'
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

                $bodytop = '<div class="col-md-12" style="margin-top:2%;">
                    <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
                    <p style="text-align:left;">
                    <p style="text-align:left;">Please find the invoice for your recent event.</p>
                </div>';

                $message = $bodytop.''.$mailbody;

                $message .= '<p>Thank you for choosing Tosshead for your Event requirement ,incase of any queries kindly call us <strong>@ 8448444942</strong> or email us <strong>support@tosshead.com</strong> we will be happy to assist you.</p>';
                $to = $cus_email;

                $mailstatus = Mail::to($cus_email)->cc(['support@tosshead.com','samsrivastva@gmail.com'])->send(new CompletedInvoice($message));

                $pdf = PDF::loadView('pdfs.invoices', array('mailbody'=>$mailbody))->setPaper('a4', 'landscape');
                $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true]);

                $filename = date('dmYHis').$today.'-Tosshead.pdf';
                $filepath = 'admin_uploads/pdf/'.$filename;
                Storage::put($filepath, $pdf->output());

                if (Mail::failures())
                {
                    $resultString = ['status' => 2, 'quoteid' => $quoteid, 'message' => "Error In Sending Mail"];
                    echo(json_encode($resultString));
                }
                else
                {
                    $uploadInvoice = new UploadInvoice();

                    $uploadInvoice->quoteid = $quoteid;
                    $uploadInvoice->invoice_path = $filepath;
                    $uploadInvoice->invoice_number = $invoice_number;
                    $uploadInvoice->customer_gst = $customer_gst;

                    $uploadInvoice->save();

                    $resultString = ['status' => 1, 'quoteid' => $quoteid, 'message' => "Invoice created and sent to customer"];
                    echo(json_encode($resultString));
                }

            } /*ends rowcount check */

        }  /*ends ajax check */

    }

    /* mail details from completed quotations ends */

    /* delete items from pop up of completed quotations */

    public function delcompleteditem($id)
    {
        $resultString = [];
        $count = UploadInvoice::where('quoteid','=',$id)->count();
        $data = UploadInvoice::where('quoteid','=',$id)->latest()->first();

        if(Storage::disk('public')->exists($data->invoice_path))
        {
                Storage::delete($data->invoice_path);
        }

        if($data->delete())
        {
            $resultString = ['status' => 1, 'quoteid' => $id];
            echo(json_encode($resultString));
        }
        else
        {
            $resultString = ['status' => 2, 'quoteid' => $id];
            echo(json_encode($resultString));
        }

    }
    /* delete items from pop up of completed quotations ends */

    /* delete items from pop up of completed quotations */

    public function triggermailcompleted($id)
    {
        $resultString = [];
        $invoice = UploadInvoice::findOrFail($id);
        $quotation = Quotation::where('quoteid','=',$id)->latest()->first();

        $filename = $invoice->invoice_path;
        $quoteid = $id;
        $cus_name = $quotation->name;
        $cus_email = $quotation->email;
        $cus_mobile = $quotation->mobile;

        $message = '<div class="col-md-12" style="margin-top:2%;">
            <p>Dear <b>'.$cus_name.'</b>, <br><br>Greetings from Tosshead !</p>
            <p style="text-align:left;">
            <p style="text-align:left;">Please find the invoice attached for your recent event.</p>
        </div>';

        $message .= '<p>Thank you for choosing Tosshead for your Event requirement ,incase of any queries kindly call us <strong>
        @ 8448444942</strong> or email us <strong>support@tosshead.com</strong> we will be happy to assist you.</p>';

        $mailstatus = Mail::to($cus_email)->cc(['support@tosshead.com','samsrivastva@gmail.com'])->send(new InvoiceAttachment($invoice,$quotation,$message));

        if(Mail::failures())
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
    /* delete items from pop up of completed quotations ends */

    public function reportscompleted()
    {
        $all_total_gst = 0; $all_total_finalamount = 0; $all_total_expense = 0; $all_total_profit_loss = 0;
        $data = array();

        if(request()->ajax())
        {
            $quotations = Quotation::select([
                'quotations.*',
                DB::raw("CONCAT(quotations.name,'<br>','Email : ',quotations.email,'<br>','Mobile : ',quotations.mobile,'<br>','City : ',payorders.city) as cusdetails"),
                'remarks.id as remarksid',
                //DB::raw("STR_TO_DATE(`quotations.event_date`, '%d-%m-%Y')  as eventdate"),
                'remarks.remarks as remarks',
                'payorders.id as payordersid',
                'payorders.city as payorderscity',
                'online_inquiries.id as online_inquiries_id',
                'online_inquiries.discount as online_inquiries_discount',
                'online_inquiries.amount as online_inquiries_amount',
            ])
            ->leftJoin("remarks","remarks.quoteid","=","quotations.id")
            ->leftJoin("payorders","payorders.quoteid","=","quotations.id")
            ->leftJoin("online_inquiries","online_inquiries.quotation","=","quotations.id")
            ->where('quotations.order_complete', '=', 'completed')
            ->orderBy('quotations.id', 'Desc')
            ->orderBy('quotations.event_date', 'Desc')->get();
            ;

            foreach($quotations as $quotation)
            {
                $gross_amount = 0; $amountpaypable = 0;$finalamount = 0;$cus_net_profit_loss_status = '';$cus_net_profit_loss_status = '';

                $cus_name = $quotation->name;
                $cus_eventdate = $quotation->event_date;
                $cus_no_of_days = $quotation->no_of_days;
                $cus_requirements = json_decode($quotation->item, true);
                $cus_added_item = json_decode($quotation->added_item, true);
                $package_detail_id = $quotation->package_detail_id;
                $cus_crew_cost = $quotation->crew_cost;
                $cus_transport_cost = $quotation->transport_cost;
                $cus_add_gst = $quotation->add_gst;
                $cus_manual_discount = $quotation->manual_discount;
                $cus_event_expenses = $quotation->event_expenses;
                $cus_event_gst = $quotation->event_gst;
                $cus_event_remarks = $quotation->event_remarks;
                $elementid = $quotation->id;
                $pdflink = $quotation->pdf;

                if($package_detail_id != NULL)
                {
                    $package_details = PackageDetail::select("package_details.*","packages.id as packageid","packages.package_name as packagename")
                    ->leftJoin("packages","package_details.package_name","=","packages.id")->where('package_details.id','=',$package_detail_id)->orderBy('Id','Desc')->get();

                    foreach($package_details as $package_detail)
                    {
                        $package_cost = $package_detail->price;
                    }

                    $gross_amount = $gross_amount + ($package_cost * $cus_no_of_days);
                }

                if($cus_requirements != NULL && count(array_filter([$cus_requirements])) != 0)
                {
                    foreach ($cus_requirements as $items)
                    {
                        //echo $quotation->id ."--------".$items['particular']."--------".$items['pack_desc']."<br>";
                        $particular = $items['particular'];
                        $item_qty = intval($items['item_qty']);
                        $item_height = intval($items['item_height']);
                        $item_width = intval($items['item_width']);
                        $item_price = intval($items['item_price']);
                        $net_price = intval($items['net_price']);
                        $pack_desc = $items['pack_desc'];
                        $partidays = intval($items['partidays']);

                        if($item_qty == '' || $item_qty == 0) { $item_qty = 1; }
                        if($item_height == '' || $item_height == 0) { $item_height = 1; }
                        if($item_width == '' || $item_width == 0) { $item_width = 1; }
                        if($item_price == '' || $item_price == 0) { $item_price = 1; }

                        $gross_amount = $gross_amount + ($item_qty * $item_height * $item_width * $item_price * $partidays);
                    }
                }

                if($cus_added_item != NULL && count(array_filter([$cus_added_item])) != 0)
                {
                    foreach ($cus_added_item as $itemsadds)
                    {
                        //echo $elementid ."<br>".$itemsadds['parti']."<br>";
                        $particular = nl2br($itemsadds['parti']);
                        $item_qty = intval($itemsadds['qtt']);
                        $item_height = intval($itemsadds['heightee']);
                        $item_width = intval($itemsadds['widthee']);
                        $item_price = intval($itemsadds['ammt']);
                        $net_price = intval($itemsadds['calc']);
                        $partidays = intval($itemsadds['partidays']);

                        if($item_qty == '' || $item_qty == 0) { $item_qty = 1; }
                        if($item_height == '' || $item_height == 0) { $item_height = 1; }
                        if($item_width == '' || $item_width == 0) { $item_width = 1; }
                        if($item_price == '' || $item_price == 0) { $item_price = 1; }

                        $gross_amount = $gross_amount + ($item_qty * $item_height * $item_width * $item_price * $partidays);
                    }
                }

                $finalamount = $gross_amount;

                $onlineinquiries_count = OnlineInquiry::where('quotation','=',$quotation->id)->count();

                if($onlineinquiries_count > 0)
                {
                    $onlineinquiries = OnlineInquiry::where('quotation','=',$quotation->id)->latest()->first();

                    $discount = $onlineinquiries->discount;
                    $amount = $onlineinquiries->amount;
                    $amount = round(($discount/100) * $finalamount);
                    $finalamount = $finalamount - $amount;
                }

                /*Add if coupon code id used*/

                $onlinecoupon_count = OnlineCoupon::where('quotation','=',$quotation->id)->count();

                if($onlinecoupon_count > 0)
                {
                    $onlinecoupon = OnlineCoupon::where('quotation','=',$quotation->id)->latest()->first();

                    $promocode_db = $onlinecoupon->promocode;
                    $discountgiven_db = $onlinecoupon->discountgiven;
                    $finalamount = $finalamount - $discountgiven_db;
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

                if($cus_add_gst == 1)
                {
                    $finalamount = $finalamount + $cgst_data + $sgst_data;
                }

                $cus_net_profit_loss = $finalamount - intval($cus_event_expenses) - intval($cus_event_gst);

                $all_total_gst = $all_total_gst + $cus_event_gst;
                $all_total_finalamount = $all_total_finalamount + $finalamount;
                $all_total_expense = $all_total_expense + $cus_event_expenses;
                $all_total_profit_loss = $all_total_profit_loss + $cus_net_profit_loss;

                if($cus_net_profit_loss > 0)
                {
                    $cus_net_profit_loss_status = '(P)';
                }
                else if($cus_net_profit_loss < 0)
                {
                    $cus_net_profit_loss_status = '(L)';
                }
                else
                {
                    $cus_net_profit_loss_status = '(NIL)';
                }

                $data[] = ['id' => $elementid, 'name' => $cus_name,'cus_eventdate' => $cus_eventdate, 'pdflink' => $pdflink,  'gross' => $finalamount,  'cus_event_expenses' => $cus_event_expenses,  'cus_event_gst' => $cus_event_gst,  'cus_net_profit_loss' => $cus_net_profit_loss,  'cus_net_profit_loss_status' => $cus_net_profit_loss_status,  'cus_event_remarks' => $cus_event_remarks];

            }

            return DataTables::of($data)
            ->addColumn('action', function($data) {
                $date_allowed = date('2019-09-19');
                $button = '<button type="button" name="edit" id="'.$data['id'].'" class="manualbtn showpopup btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>';
                $button .= '<br/>';
                $button .= '<button type="button" name="delete" id="'.$data['id'].'" class="manualbtn delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->with(['all_total_gst' => $all_total_gst,'all_total_finalamount' => $all_total_finalamount,'all_total_expense' => $all_total_expense,'all_total_profit_loss' => $all_total_profit_loss])
            ->toJson();
        }

        return view('admin.reportscompleted');
    }

    public function updatecompletedreports(Request $request)
    {
        $item_selected = $request->item_selected;
        $data = Quotation::findOrFail($item_selected);

        $data->event_expenses = $request->event_expenses;
        $data->event_gst = $request->event_gst;
        $data->event_remarks = $request->event_remarks;

        $resultString = '';
		if($data->save())
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

    public function reportsconsolidated()
    {
        $data_array = array();
        $month_array = array();

        $total_monthly_events = 0;
        $total_monthly_billing = 0;
        $total_monthly_profit = 0;

        $quotations = Quotation::select([
            'quotations.*',
            'remarks.id as remarksid',
            DB::raw("STR_TO_DATE(quotations.event_date, '%d/%m/%Y')  as converted_date"),
            DB::raw("MONTHNAME(STR_TO_DATE(quotations.event_date, '%d/%m/%Y'))  as month_name"),
            DB::raw("month(STR_TO_DATE(quotations.event_date, '%d/%m/%Y'))  as month_number"),
            DB::raw("year(STR_TO_DATE(quotations.event_date, '%d/%m/%Y'))  as quotation_year"),
            'remarks.remarks as remarks',
            'payorders.id as payordersid',
            'payorders.city as payorderscity',
            'online_inquiries.id as online_inquiries_id',
            'online_inquiries.discount as online_inquiries_discount',
            'online_inquiries.amount as online_inquiries_amount',
        ])
        ->leftJoin("remarks","remarks.quoteid","=","quotations.id")
        ->leftJoin("payorders","payorders.quoteid","=","quotations.id")
        ->leftJoin("online_inquiries","online_inquiries.quotation","=","quotations.id")
        ->where('quotations.order_complete', '=', 'completed')
        ->orderBy('quotation_year', 'Desc')
        ->orderBy('month_number', 'Desc')->get();
        ;

        //return ($quotations->toJson());

        foreach($quotations as $quotation)
        {
            $gross_amount = 0; $amountpaypable = 0;$finalamount = 0;$cus_net_profit_loss_status = '';$cus_net_profit_loss_status = '';

            $year = $quotation->quotation_year;
            $month_name = $quotation->month_name;

            $cus_no_of_days = $quotation->no_of_days;
            $cus_requirements = json_decode($quotation->item, true);
            $cus_eventdate = $quotation->event_date;
            $package_detail_id = $quotation->package_detail_id;
            $cus_added_item = json_decode($quotation->added_item, true);

            $cus_crew_cost = $quotation->crew_cost;
            $cus_transport_cost = $quotation->transport_cost;
            $cus_add_gst = $quotation->add_gst;
            $cus_manual_discount = $quotation->manual_discount;

            $package_price = $quotation->package_price;
            $quote_type = $quotation->quote_type;

            $cus_event_expenses = $quotation->event_expenses;
            $cus_event_gst = $quotation->event_gst;
            $cus_event_remarks = $quotation->event_remarks;

            $elementid = $quotation->id;
            $last_inserted = $quotation->id;

            if($package_detail_id != NULL)
            {
                $package_details = PackageDetail::select("package_details.*","packages.id as packageid","packages.package_name as packagename")
                ->leftJoin("packages","package_details.package_name","=","packages.id")->where('package_details.id','=',$package_detail_id)->orderBy('Id','Desc')->get();

                foreach($package_details as $package_detail)
                {
                    $package_cost = $package_detail->price;
                }

                $gross_amount = $gross_amount + ($package_cost * $cus_no_of_days);
            }

            if($cus_requirements != NULL && count(array_filter([$cus_requirements])) != 0)
            {
                foreach ($cus_requirements as $items)
                {
                    $particular = $items['particular'];
                    $item_qty = intval($items['item_qty']);
                    $item_height = intval($items['item_height']);
                    $item_width = intval($items['item_width']);
                    $item_price = intval($items['item_price']);
                    $net_price = intval($items['net_price']);
                    $pack_desc = $items['pack_desc'];
                    $partidays = intval($items['partidays']);

                    if($item_qty == '' || $item_qty == 0) { $item_qty = 1; }
                    if($item_height == '' || $item_height == 0) { $item_height = 1; }
                    if($item_width == '' || $item_width == 0) { $item_width = 1; }
                    if($item_price == '' || $item_price == 0) { $item_price = 1; }

                    $gross_amount = $gross_amount + ($item_qty * $item_height * $item_width * $item_price * $partidays);
                }
            }

            if($cus_added_item != NULL && count(array_filter([$cus_added_item])) != 0)
            {
                foreach ($cus_added_item as $itemsadds)
                {
                    $particular = nl2br($itemsadds['parti']);
                    $item_qty = intval($itemsadds['qtt']);
                    $item_height = intval($itemsadds['heightee']);
                    $item_width = intval($itemsadds['widthee']);
                    $item_price = intval($itemsadds['ammt']);
                    $net_price = intval($itemsadds['calc']);
                    $partidays = intval($itemsadds['partidays']);

                    if($item_qty == '' || $item_qty == 0) { $item_qty = 1; }
                    if($item_height == '' || $item_height == 0) { $item_height = 1; }
                    if($item_width == '' || $item_width == 0) { $item_width = 1; }
                    if($item_price == '' || $item_price == 0) { $item_price = 1; }

                    $gross_amount = $gross_amount + ($item_qty * $item_height * $item_width * $item_price * $partidays);
                }
            }

            $finalamount = $gross_amount;

            $onlineinquiries_count = OnlineInquiry::where('quotation','=',$quotation->id)->count();

            if($onlineinquiries_count > 0)
            {
                $onlineinquiries = OnlineInquiry::where('quotation','=',$quotation->id)->latest()->first();

                $discount = $onlineinquiries->discount;
                $amount = $onlineinquiries->amount;
                $amount = round(($discount/100) * $finalamount);
                $finalamount = $finalamount - $amount;
            }

            /*Add if coupon code id used*/

            $onlinecoupon_count = OnlineCoupon::where('quotation','=',$quotation->id)->count();

            if($onlinecoupon_count > 0)
            {
                $onlinecoupon = OnlineCoupon::where('quotation','=',$quotation->id)->latest()->first();

                $promocode_db = $onlinecoupon->promocode;
                $discountgiven_db = $onlinecoupon->discountgiven;
                $finalamount = $finalamount - $discountgiven_db;
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

            if($cus_add_gst == 1)
            {
                $finalamount = $finalamount + $cgst_data + $sgst_data;
            }

            $cus_net_profit_loss = $finalamount - intval($cus_event_expenses) - intval($cus_event_gst);

            $total_monthly_billing = $finalamount;
            $total_monthly_profit = $cus_net_profit_loss;

            if(empty($data_array[$year][$month_name]['total_monthly_events']))
            {
                $month_array = array();
                array_push($month_array,$month_name);

                $data_array[$year][$month_name]['total_monthly_events'] = 1;
                $data_array[$year][$month_name]['total_monthly_billing'] = $total_monthly_billing;
                $data_array[$year][$month_name]['total_monthly_profit'] = $total_monthly_profit;
            }
            else
            {
                array_push($month_array,$month_name);

                $data_array[$year][$month_name]['total_monthly_events'] = $data_array[$year][$month_name]['total_monthly_events'] + 1;
                $data_array[$year][$month_name]['total_monthly_billing'] = $data_array[$year][$month_name]['total_monthly_billing'] + $total_monthly_billing;
                $data_array[$year][$month_name]['total_monthly_profit'] = $data_array[$year][$month_name]['total_monthly_profit'] + $total_monthly_profit;
            }
        }

        //dd($data_array);

        return view('admin.reportsconsolidated')->with('data_array', $data_array);

    }

    /*--------------------getquotedetails comes here----------------*/

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

    /*--------------------/. getquotedetails ends ----------------*/

}
