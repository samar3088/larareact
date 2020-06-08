<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Banquet;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;

class BanquetsController extends Controller
{
    public function index()
    {
        return view('admin.banquet');
    }

    function getdata(Request $request)
    {
        $banquets= DB::table('banquets')->select('id', 'name', 'email', 'mobile', 'city')->orderBy('created_at','desc');
        return Datatables::of($banquets)
                ->addIndexColumn()
                ->addColumn('action', function($banquet){
                    return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$banquet->id.'" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;<a href="#" class="btn btn-xs btn-danger delete" id="'.$banquet->id.'" title="Delete"><i class="fas fa-trash"></i></a>';
                })
                ->addColumn('checkbox', '<input type="checkbox" name="banquet_checkbox[]" class="banquet_checkbox" value="{{$id}}" />')
                ->addColumn('name', '{{$name}}')
                ->addColumn('email', '{{$email}}')
                ->addColumn('mobile', '{{$mobile}}')
                ->addColumn('city', '{{$city}}')
                 ->rawColumns(['checkbox','action'])
                ->make(true);
    }

    function postdata(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email'  => 'required',
            'mobile'  => 'required',
            'city'  => 'required',
            'date'  => 'required',
            'instruction'  => 'required',
            'status'  => 'required',
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
                $banquet = new Banquet([
                    'name'    =>  $request->get('name'),
                    'email'     =>  $request->get('email'),
                    'mobile'     =>  $request->get('mobile'),
                    'city'     =>  $request->get('city'),
                    'date'     =>  $request->get('date'),
                    'instruction'     =>  $request->get('instruction'),
                    'status'     =>  $request->get('status')
                ]);

                $banquet->save();
                $success_output = '<div class="alert alert-success">Data Inserted</div>';
            }
            else if($request->get('button_action') == 'update')
            {
                $banquet = Banquet::find($request->get('banquet_id'));
                $banquet->name = $request->get('name');
                $banquet->email = $request->get('email');
                $banquet->mobile = $request->get('mobile');
                $banquet->city = $request->get('city');
                $banquet->date = $request->get('date');
                $banquet->instruction = $request->get('instruction');
                $banquet->status = $request->get('status');
                $banquet->save();
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
        $banquet = Banquet::find($id);
        $output = array(
            'name'    =>  $banquet->name,
            'email'     =>  $banquet->email,
            'mobile'     =>  $banquet->mobile,
            'city'     =>  $banquet->city,
            'date'     =>  $banquet->date,
            'instruction'     =>  $banquet->instruction,
            'status'     =>  $banquet->status
        );
        echo json_encode($output);
    }

    function removedata(Request $request)
    {
        $banquet = Banquet::find($request->input('id'));

        if($banquet->delete())
        {
            echo 'Data Deleted';
        }
    }

    function massremove(Request $request)
    {
        $banquet_id_array = $request->input('id');
        $banquet = Banquet::whereIn('id', $banquet_id_array);

        if($banquet->delete())
        {
            echo 'Data Deleted';
        }
    }
}
