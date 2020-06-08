<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Package;
use App\PackageDetail;
use App\City;
use Validator;
use Illuminate\Support\Facades\Storage;
use DataTables;

class PackageDetailController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::latest()->get();
        $packages = Package::latest()->get();

        if(request()->ajax())
        {
            //return datatables()->of(PackageDetail::latest())

            return datatables()->of(PackageDetail::select("package_details.*","cities.id as cityid","cities.name as cityname","packages.id as packageid","packages.package_name as packagename")
                    ->Join("packages","package_details.package_name","=","packages.id")
                    ->Join("cities","package_details.city","=","cities.id"))
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.packagedetails',compact(['cities','packages']));
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
            'package_name'    =>  'required',
            'no_of_pax'     =>  'required',
            'indoor_outdoor'     =>  'required',
            'price'     =>  'required',
            'package_include'     =>  'required',
            'city'     =>  'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'package_name'        =>  $request->package_name,
            'no_of_pax'         =>  $request->no_of_pax,
            'indoor_outdoor'         =>  $request->indoor_outdoor,
            'price'             =>  $request->price,
            'package_include'             =>  $request->package_include,
            'city'             =>  $request->city
        );

        PackageDetail::create($form_data);
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
            $data = PackageDetail::findOrFail($id);
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
            'package_name'    =>  'required',
            'no_of_pax'     =>  'required',
            'indoor_outdoor'     =>  'required',
            'price'     =>  'required',
            'package_include'     =>  'required',
            'city'     =>  'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'package_name'        =>  $request->package_name,
            'no_of_pax'         =>  $request->no_of_pax,
            'indoor_outdoor'         =>  $request->indoor_outdoor,
            'price'             =>  $request->price,
            'package_include'             =>  $request->package_include,
            'city'             =>  $request->city
        );

        PackageDetail::whereId($request->hidden_id)->update($form_data);
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
        $data = PackageDetail::findOrFail($id);
        if($data->delete())
        {
            echo 'Data Deleted';
        }
    }

}
