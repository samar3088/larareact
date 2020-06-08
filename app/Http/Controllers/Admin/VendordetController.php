<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\VendorDet;
use Validator;

class VendordetController extends Controller
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
                return datatables()->of(VendorDet::latest())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edits btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="'.$data->id.'" class="deletes btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.vendordet');
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
            'showtime'    =>  'required',
            'selvalue'     =>  'required',
            'selects'     =>  'required',
            'name'     =>  'required',
            'email'     =>  'required',
            'mobile'     =>  'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'showtime'  =>  $request->showtime,
            'selvalue'     =>  $request->selvalue,
            'selects'       =>  $request->selects,
            'name'         =>  $request->name,
            'email'         =>  $request->email,
            'mobile'       =>  $request->mobile,
        );

        VendorDet::create($form_data);
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
            $data = VendorDet::findOrFail($id);
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
        $rules = array(
            'showtime'    =>  'required',
            'selvalue'     =>  'required',
            'selects'     =>  'required',
            'name'     =>  'required',
            'email'     =>  'required',
            'mobile'     =>  'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'showtime'  =>  $request->showtime,
            'selvalue'     =>  $request->selvalue,
            'selects'       =>  $request->selects,
            'name'         =>  $request->name,
            'email'         =>  $request->email,
            'mobile'       =>  $request->mobile,
        );

        VendorDet::whereId($request->hidden_id)->update($form_data);
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
        $data = VendorDet::findOrFail($id);
        if($data->delete())
        {
            echo 'Data Deleted';
        }
    }

}
