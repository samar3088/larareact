<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Foodbooking;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;

class FoodbookingsController extends Controller
{
    public function index()
    {
        return view('admin.foodbooking');
    }

    function getdata(Request $request)
    {
        $foodbookings= DB::table('foodbookings')->select('id', 'name', 'email', 'mobile', 'city')->orderBy('created_at','desc');
        return Datatables::of($foodbookings)
                ->addIndexColumn()
                ->addColumn('action', function($foodbooking){
                    return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$foodbooking->id.'" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;<a href="#" class="btn btn-xs btn-danger delete" id="'.$foodbooking->id.'" title="Delete"><i class="fas fa-trash"></i></a>';
                })
                ->addColumn('checkbox', '<input type="checkbox" name="foodbooking_checkbox[]" class="foodbooking_checkbox" value="{{$id}}" />')
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
            'peopleinvited'  => 'required',
            'fooditems'  => 'required',
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
                $fooddataitem = '';
                $selected_food = $request->get('fooditems');
                $count = count($selected_food);

                for($i = 0;$i<$count;$i++)
                {
                    $fooddataitem .= $selected_food[$i].'*';
                }
                $fooddataitem = substr($fooddataitem, 0, -1);

                $foodbooking = new Foodbooking([
                    'name'    =>  $request->get('name'),
                    'email'     =>  $request->get('email'),
                    'mobile'     =>  $request->get('mobile'),
                    'city'     =>  $request->get('city'),
                    'date'     =>  $request->get('date'),
                    'peopleinvited'     =>  $request->get('peopleinvited'),
                    'fooditems'     =>  $fooddataitem,
                    'status'     =>  $request->get('status')
                ]);

                $foodbooking->save();
                $success_output = '<div class="alert alert-success">Data Inserted</div>';
            }
            else if($request->get('button_action') == 'update')
            {
                $foodbooking = Foodbooking::find($request->get('foodbooking_id'));

                $fooddataitem = '';
                $selected_food = $request->get('fooditems');
                $count = count($selected_food);

                for($i = 0;$i<$count;$i++)
                {
                    $fooddataitem .= $selected_food[$i].'*';
                }
                $fooddataitem = substr($fooddataitem, 0, -1);

                $foodbooking->name = $request->get('name');
                $foodbooking->email = $request->get('email');
                $foodbooking->mobile = $request->get('mobile');
                $foodbooking->city = $request->get('city');
                $foodbooking->date = $request->get('date');
                $foodbooking->peopleinvited = $request->get('peopleinvited');
                $foodbooking->fooditems = $fooddataitem;
                $foodbooking->status = $request->get('status');
                $foodbooking->save();
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
        $foodbooking = Foodbooking::find($id);
        $output = array(
            'name'    =>  $foodbooking->name,
            'email'     =>  $foodbooking->email,
            'mobile'     =>  $foodbooking->mobile,
            'city'     =>  $foodbooking->city,
            'date'     =>  $foodbooking->date,
            'peopleinvited'     =>  $foodbooking->peopleinvited,
            'fooditems'     =>  $foodbooking->fooditems,
            'status'     =>  $foodbooking->status
        );
        echo json_encode($output);
    }

    function removedata(Request $request)
    {
        $foodbooking = Foodbooking::find($request->input('id'));

        if($foodbooking->delete())
        {
            echo 'Data Deleted';
        }
    }

    function massremove(Request $request)
    {
        $foodbooking_id_array = $request->input('id');
        $foodbooking = Foodbooking::whereIn('id', $foodbooking_id_array);

        if($foodbooking->delete())
        {
            echo 'Data Deleted';
        }
    }
}
