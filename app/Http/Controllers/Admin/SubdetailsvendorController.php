<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Subdetailsvendor;
use App\Mainvendor;
use App\Maincategory;
use Validator;

class SubdetailsvendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Mainvendor::latest()->get();
        $categories= Maincategory::latest()->get();

        if(request()->ajax())
        {
            /* Subdetailsvendor::select("subdetailsvendors.*","mainvendor.id as mainvendorId","maincategories.id as maincategoriesId")
                ->join("mainvendor","mainvendor.id","=","subdetailsvendor.vendortitle")
                ->leftJoin('maincategories', 'maincategories.id', '=', 'subdetailsvendors.maincategory')
                ->get(); */

                    return datatables()->of(Subdetailsvendor::select("subdetailsvendors.*","mainvendors.id as mainvendorsId","maincategories.id as maincategoriesId")
                    ->join("mainvendors","mainvendors.id","=","subdetailsvendors.vendortitle")
                    ->leftJoin('maincategories', 'maincategories.id', '=', 'subdetailsvendors.maincategory'))
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.subdetailsvendor',compact(['vendors','categories']));
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
            'vendortitle'    =>  'required',
            'include'     =>  'required',
            'price'     =>  'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'vendortitle'        =>  $request->vendortitle,
            'include'         =>  $request->include,
            'price'         =>  $request->price,
            'maincategory'             =>  $request->maincategory
        );

        Subdetailsvendor::create($form_data);
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
            $data = Subdetailsvendor::findOrFail($id);
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
            'vendortitle'    =>  'required',
            'include'     =>  'required',
            'price'     =>  'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'vendortitle'        =>  $request->vendortitle,
            'include'         =>  $request->include,
            'price'         =>  $request->price,
            'maincategory'             =>  $request->maincategory
        );

        Subdetailsvendor::whereId($request->hidden_id)->update($form_data);
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
        $data = Subdetailsvendor::findOrFail($id);
        if($data->delete())
        {
            echo 'Data Deleted';
        }
    }

}
