<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\CustomBdayRequested;
use Validator;
use Illuminate\Support\Facades\Storage;

class CustombdayController extends Controller
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
            return datatables()->of(CustomBdayRequested::latest())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.custombdays');
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
        $rules = array(
            'name'    =>  'required',
            'email'     =>  'required',
            'mobile'     =>  'required',
            'file'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('file');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();

        //$image->move(public_path('images'), $new_name); //have to check here

        $path = $request->file('file')->store('customer_uploads/cutstom_bdays');

        $form_data = array(
            'name'        =>  $request->name,
            'email'         =>  $request->email,
            'mobile'         =>  $request->mobile,
            'message'         =>  $request->message,
            'file'             =>  $path
        );

        CustomBdayRequested::create($form_data);
        return response()->json(['success' => 'Data Added successfully.']);
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
            $data = CustomBdayRequested::findOrFail($id);
            return response()->json(['data' => $data]);
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
        $path = $request->hidden_image;
        $image = $request->file('file');

        if($image != '')
        {
            $rules = array(
                'name'    =>  'required',
                'email'     =>  'required',
                'mobile'     =>  'required',
                'file'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            );

            $error = Validator::make($request->all(), $rules);
            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            //$image->move(public_path('images'), $image_name);

            $oldimage = CustomBdayRequested::findOrFail($request->hidden_id);
            Storage::delete($oldimage->file);

            $path = $request->file('file')->store('customer_uploads/cutstom_bdays');
        }
        else
        {
            $rules = array(
                'name'    =>  'required',
                'email'    =>  'required',
                'mobile'     =>  'required'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }

        $form_data = array(
            'name'       =>   $request->name,
            'email'       =>   $request->email,
            'mobile'     =>   $request->mobile,
            'message'         =>  $request->message,
            'file'       =>   $path
        );

        CustomBdayRequested::whereId($request->hidden_id)->update($form_data);
        return response()->json(['success' => 'Data is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = CustomBdayRequested::findOrFail($id);
        Storage::delete($data->file);
        if($data->delete())
        {
            echo 'Data Deleted';
        }
    }

}
