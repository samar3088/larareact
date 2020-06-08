<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Specialevent;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;

class SpecialeventController extends Controller
{
    public function index()
    {
        return view('admin.specialevent');
    }

    function getdata(Request $request)
    {
        $specialevents= DB::table('specialevents')->select('id', 'name', 'email', 'mobile', 'description', 'event_date')->orderBy('created_at','desc');
        return Datatables::of($specialevents)
                ->addIndexColumn()
                ->addColumn('action', function($specialevent){
                    return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$specialevent->id.'" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;<a href="#" class="btn btn-xs btn-danger delete" id="'.$specialevent->id.'" title="Delete"><i class="fas fa-trash"></i></a>';
                })
                ->addColumn('checkbox', '<input type="checkbox" name="specialevent_checkbox[]" class="specialevent_checkbox" value="{{ $id }}" />')
                ->addColumn('name', '{{ $name }}')
                ->addColumn('email', '{{ $email }}')
                ->addColumn('mobile', '{{ $mobile }}')
                ->addColumn('description', '{{ $description }}')
                ->addColumn('event_date', '{{ $event_date }}')
                 ->rawColumns(['checkbox','action'])
                ->make(true);
    }

    function postdata(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email'  => 'required',
            'mobile'  => 'required',
            'description'  => 'required',
            'event_date'  => 'required',
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
                $specialevent = new Specialevent([
                    'name'    =>  $request->get('name'),
                    'email'     =>  $request->get('email'),
                    'mobile'     =>  $request->get('mobile'),
                    'description'     =>  $request->get('description'),
                    'event_date'     =>  $request->get('event_date')
                ]);

                $specialevent->save();
                $success_output = '<div class="alert alert-success">Data Inserted</div>';
            }
            else if($request->get('button_action') == 'update')
            {
                $specialevent = Specialevent::find($request->get('specialevent_id'));
                $specialevent->name = $request->get('name');
                $specialevent->email = $request->get('email');
                $specialevent->mobile = $request->get('mobile');
                $specialevent->description = $request->get('description');
                $specialevent->event_date = $request->get('event_date');
                $specialevent->save();
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
        $specialevent = Specialevent::find($id);
        $output = array(
            'name'    =>  $specialevent->name,
            'email'     =>  $specialevent->email,
            'mobile'     =>  $specialevent->mobile,
            'description'     =>  $specialevent->description,
            'event_date'     =>  $specialevent->event_date
        );
        echo json_encode($output);
    }

    function removedata(Request $request)
    {
        $specialevent = Specialevent::find($request->input('id'));

        if($specialevent->delete())
        {
            echo 'Data Deleted';
        }
    }

    function massremove(Request $request)
    {
        $specialevent_id_array = $request->input('id');
        $specialevent = Specialevent::whereIn('id', $specialevent_id_array);

        if($specialevent->delete())
        {
            echo 'Data Deleted';
        }
    }

}
