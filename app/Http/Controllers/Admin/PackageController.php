<?php

namespace App\Http\Controllers\Admin;

use App\City;
use Validator;

use App\Package;
use App\PackageDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::latest()->get();

        if(request()->ajax())
        {
            //return datatables()->of(Package::latest());

                      return datatables()->of(Package::select("packages.*","cities.id as cityid","cities.name as cityname")
                    ->leftJoin("cities","packages.city","=","cities.id"))
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.packages',compact('cities'));
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
            'city'     =>  'required',
            'description'     =>  'required',
            'package_image'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('package_image');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();

        //$image->move(public_path('images'), $new_name); //have to check here

        $path = $request->file('package_image')->store('admin_uploads/packages');

        $form_data = array(
            'package_name'        =>  $request->package_name,
            'city'         =>  $request->city,
            'description'         =>  $request->description,
            'package_image'             =>  $path
        );

        Package::create($form_data);
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
            $data = Package::findOrFail($id);
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
        $image = $request->file('package_image');

        if($image != '')
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

            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            //$image->move(public_path('images'), $image_name);

            $oldimage = Package::findOrFail($request->hidden_id);
            Storage::delete($oldimage->package_image);

            $path = $request->file('package_image')->store('admin_uploads/packages');
        }
        else
        {
            $rules = array(
                'package_name'    =>  'required',
                'city'     =>  'required',
                'description'     =>  'required'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }

        $form_data = array(
            'package_name'       =>   $request->package_name,
            'city'       =>   $request->city,
            'description'     =>   $request->description,
            'package_image'       =>   $path
        );

        Package::whereId($request->hidden_id)->update($form_data);
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
        $data = Package::findOrFail($id);

        $package_id = $data->id;
        $subpackages_count = PackageDetail::where('package_name','=', $package_id)->get()->count();

        if($subpackages_count > 0)
        {
            echo 'There are subthemes alloted to this package. So sorry cant be deleted.';
        }
        else
        {
            Storage::delete($data->package_image);
            $data->delete();
            echo 'Data Deleted';
        }   
    }
}
