<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Validator;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class CreatedusersController extends Controller
{
    public function index()
    {
        return view('admin.createdusers');
    }

    public function createdusersunreg()
    {
        return view('admin.createdusersunreg');
    }

    function getdata(Request $request)
    {
        $createdusers = DB::table('users')->select('id', 'name', 'email', 'mobile', 'username','toss_actual_pass','discount','created_at')->where('thru_register', '<>', 'yes')->whereNotIn('role_id', [1,2])->orderBy('created_at','desc');

        return Datatables::of($createdusers)
                ->addIndexColumn()
                ->addColumn('action', function($createduser){
                    return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$createduser->id.'" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;<a href="#" class="btn btn-xs btn-danger mail" id="'.$createduser->id.'" title="Mail Details"><i class="fas fa-envelope"></i></a>&nbsp;&nbsp;<a href="#" class="btn btn-xs btn-danger delete" id="'.$createduser->id.'" title="Delete"><i class="fas fa-trash"></i></a>';
                })
                ->addColumn('checkbox', '<input type="checkbox" name="createduser_checkbox[]" class="createduser_checkbox" value="{{$id}}" />')
                ->addColumn('name', '{{$name}}')
                ->addColumn('email', '{{$email}}')
                ->addColumn('mobile', '{{$mobile}}')
                ->addColumn('discount', '{{$discount}}')
                 ->rawColumns(['checkbox','action'])
                ->make(true);
    }

    function unregdata(Request $request)
    {
        $createdusers = DB::table('users')->select('id', 'name', 'email', 'mobile', 'username','toss_actual_pass','discount','created_at')->where('thru_register', '=', 'yes')->whereNotIn('role_id', [1,2])->orderBy('created_at','desc');
        return Datatables::of($createdusers)
                ->addIndexColumn()
                ->addColumn('action', function($createduser){
                    return '<a href="#" class="btn btn-xs btn-primary move" id="'.$createduser->id.'" title="Move"><i class="fas fa-truck-moving"></i></a><a href="#" class="btn btn-xs btn-danger delete" id="'.$createduser->id.'" title="Delete"><i class="fas fa-trash"></i></a>';
                })
                ->addColumn('checkbox', '<input type="checkbox" name="createduser_checkbox[]" class="createduser_checkbox" value="{{$id}}" />')
                ->addColumn('name', '{{$name}}')
                ->addColumn('email', '{{$email}}')
                ->addColumn('mobile', '{{$mobile}}')
                ->addColumn('discount', '{{$discount}}')
                 ->rawColumns(['checkbox','action'])
                ->make(true);
    }

    function postdata(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email'  => 'required',
            'mobile'  => 'required',
            'username'  => 'required',
            'toss_actual_pass'  => 'required',
            'discount'  => 'required',
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
                $createduser = new User([
                    'name'    =>  $request->get('name'),
                    'email'     =>  $request->get('email'),
                    'mobile'     =>  $request->get('mobile'),
                    'username'     =>  $request->get('username'),
                    'toss_actual_pass'     =>  $request->get('toss_actual_pass'),
                    'password' => Hash::make($request->get('toss_actual_pass')),
                    'role_id'     =>  '3',
                    'discount'     =>  $request->get('discount')
                ]);

                $createduser->save();
                $success_output = '<div class="alert alert-success">Data Inserted</div>';
            }
            else if($request->get('button_action') == 'update')
            {
                $createduser = User::find($request->get('createduser_id'));
                $createduser->name = $request->get('name');
                $createduser->email = $request->get('email');
                $createduser->mobile = $request->get('mobile');
                $createduser->username = $request->get('username');
                $createduser->password = $request->get('toss_actual_pass');
                $createduser->toss_actual_pass = Hash::make($request->get('toss_actual_pass'));
                $createduser->discount = $request->get('discount');
                $createduser->save();
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
        $createduser = User::find($id);
        $output = array(
            'name'    =>  $createduser->name,
            'email'     =>  $createduser->email,
            'mobile'     =>  $createduser->mobile,
            'username'     =>  $createduser->username,
            'toss_actual_pass'     =>  $createduser->toss_actual_pass,
            'discount'     =>  $createduser->discount
        );
        echo json_encode($output);
    }

    function removedata(Request $request)
    {
        $createduser = User::find($request->input('id'));
        if($createduser->delete())
        {
            echo 'Data Deleted';
        }
    }

    function movedata(Request $request)
    {
        $createduser = User::find($request->input('id'));
        $createduser->thru_register = 'move';
        if($createduser->save())
        {
            echo 'Data Updated';
        }
    }

    function massremove(Request $request)
    {
        $createduser_id_array = $request->input('id');
        $createduser = User::whereIn('id', $createduser_id_array);
        if($createduser->delete())
        {
            echo 'Data Deleted';
        }
    }
}
