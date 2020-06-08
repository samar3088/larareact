<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Item;
use Validator;
use Illuminate\Support\Facades\Storage;

class ItemsController extends Controller
{
    public function index()
    {
        if(request()->ajax())
        {
            return datatables()->of(Item::latest())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.items');
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
            'heading'    =>  'required',
            'type'     =>  'required',
            'file'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('file');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $path = $request->file('file')->store('admin_uploads/items');

        $form_data = array(
            'heading'         =>  $request->heading,
            'content'   =>  $request->content,
            'type'     =>  $request->type,
            'additional_line'     =>  $request->additional_line,
            'file'                  =>  $path
        );

        Item::create($form_data);
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
            $data = Item::findOrFail($id);
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
                'heading'    =>  'required',
                'type'     =>  'required',
                'file'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            );

            $error = Validator::make($request->all(), $rules);
            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $oldimage = Item::findOrFail($request->hidden_id);
            Storage::delete($oldimage->file);

            $path = $request->file('file')->store('admin_uploads/items');
        }
        else
        {
            $rules = array(
                'heading'    =>  'required',
                'type'     =>  'required',
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }

        $form_data = array(
            'heading'         =>  $request->heading,
            'content'   =>  $request->content,
            'type'     =>  $request->type,
            'additional_line'     =>  $request->additional_line,
            'file'                  =>  $path
        );

        Item::whereId($request->hidden_id)->update($form_data);
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
        $data = Item::findOrFail($id);
        Storage::delete($data->file);
        if($data->delete())
        {
            echo 'Data Deleted';
        }
    }

}
