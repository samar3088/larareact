<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Quote;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;

class QuotesController extends Controller
{
    public function index()
    {
        return view('admin.quote');
    }

    function getdata(Request $request)
    {
        $quotes= DB::table('quotes')->select('id', 'heading', 'description', 'page_type')->orderBy('created_at','desc');
        return Datatables::of($quotes)
                ->addIndexColumn()
                ->addColumn('action', function($quote){
                    return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$quote->id.'" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;<a href="#" class="btn btn-xs btn-danger delete" id="'.$quote->id.'" title="Delete"><i class="fas fa-trash"></i></a>';
                })
                ->addColumn('checkbox', '<input type="checkbox" name="quote_checkbox[]" class="quote_checkbox" value="{{$id}}" />')
                ->addColumn('heading', '{{$heading}}')
                ->addColumn('description', '{{$description}}')
                ->addColumn('page_type', '{{$page_type}}')
                 ->rawColumns(['checkbox','action'])
                ->make(true);
    }

    function postdata(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'heading' => 'required',
            'description'  => 'required',
            'page_type'  => 'required',
        ]);

        $error_array = array();
        $success_output = '';

        if ($validation->fails())
        {
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages;
            }
        }
        else
        {
            if($request->get('button_action') == "insert")
            {
                $quote = new Quote([
                    'heading'    =>  $request->get('heading'),
                    'description'     =>  $request->get('description'),
                    'page_type'     =>  $request->get('page_type')
                ]);

                $quote->save();
                $success_output = '<div class="alert alert-success">Data Inserted</div>';
            }
            else if($request->get('button_action') == 'update')
            {
                $quote = Quote::find($request->get('quote_id'));

                $quote->heading = $request->get('heading');
                $quote->description = $request->get('description');
                $quote->page_type = $request->get('page_type');
                $quote->save();
                $success_output = '<div class="alert alert-success">Data Updated</div>';
            }
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        return json_encode($output);
    }

    function fetchdata(Request $request)
    {
        $id = $request->input('id');
        $quote = Quote::find($id);
        $output = array(
            'heading'    =>  $quote->heading,
            'description'     =>  $quote->description,
            'page_type'     =>  $quote->page_type
        );
        echo json_encode($output);
    }

    function removedata(Request $request)
    {
        $quote = Quote::find($request->input('id'));

        if($quote->delete())
        {
            echo 'Data Deleted';
        }
    }

    function massremove(Request $request)
    {
        $quote_id_array = $request->input('id');
        $quote = Quote::whereIn('id', $quote_id_array);

        if($quote->delete())
        {
            echo 'Data Deleted';
        }
    }
}
