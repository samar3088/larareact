<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Subtheme;
use App\Subthemeimages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DataTables;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $theme_type = 'equipment';
        $themes = DB::table('themes')->where('theme_type', 'equipment')->orderBy('theme_name','asc')->get();

        $themes_array = [];
        foreach ($themes as $theme) {
            array_push($themes_array, $theme->id);
        }

        if(request()->ajax())
        {
                $model = Subtheme::select([
                    'subthemes.*',
                    'themes.theme_name as themename',
                ])
                ->leftJoin("themes","subthemes.theme_id","=","themes.id")
                ->whereIn('theme_id', $themes_array)->latest()
                ;
                //return datatables()->of(Subtheme::whereIn('theme_id', $themes_array)->latest())
                return DataTables::eloquent($model)
                ->addColumn('themename', function($data){
                    $themename = $data->theme->theme_name;
                    return $themename;
                })
                ->addColumn('action', function($data){
                    $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    return $button;
                })
                ->filterColumn('themename', function($query, $keyword) {
                    $sql = "themes.theme_name like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.equipment',compact('theme_type','themes'));
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
            'theme_id'    =>  'required',
            'type'     =>  'required',
            'sub_theme_name'     =>  'required',
            'actual_price'     =>  'required',
            'file'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        );
        
        /* if ($request->hasFile('file'))
        {
            $rules = array(
                'theme_id'    =>  'required',
                'type'     =>  'required',
                'sub_theme_name'     =>  'required',
                'file'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            );
        }
        else
        {
            $rules = array(
                'theme_id'    =>  'required',
                'type'     =>  'required',
                'sub_theme_name'     =>  'required'
                //'file'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            );
        } */

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $path = '';
        if ($request->hasFile('file'))
        {
            $path = $request->file('file')->store('subthemeimages');
        }

        /* $form_data = array(
            'theme_id'        =>  $request->theme_id,
            'type'         =>  $request->type,
            'sub_theme_name'         =>  $request->sub_theme_name,
            'actual_price'         =>  $request->actual_price,
            'discounted_price'         =>  $request->discounted_price,
            'label'         =>  $request->label,
            'particular'         =>  $request->particular,
            'rating'         =>  $request->rating,
            'views'         =>  $request->views,
            'file'             =>  $path,
            'description'         =>  $request->description,
            'what_included'         =>  $request->what_included,
            'need_to_know'         =>  $request->need_to_know
        );
        Subtheme::create($form_data);
        */

        $subtheme = new Subtheme();

        $subtheme->theme_id  =  $request->theme_id;
        $subtheme->type    =  $request->type;
        $subtheme->sub_theme_name   =  $request->sub_theme_name;
        $subtheme->actual_price =  $request->actual_price;
        $subtheme->discounted_price  = $request->discounted_price;
        $subtheme->label =  $request->label;
        $subtheme->particular = $request->particular;
        $subtheme->rating =  $request->rating;
        $subtheme->views =  $request->views;
        $subtheme->file =  $path;
        $subtheme->description = $request->description;
        $subtheme->what_included =  $request->what_included;
        $subtheme->need_to_know =  $request->need_to_know;

        $subtheme->save();

        $subtheme_id = $subtheme->id;

        if($files = $request->file('images'))
        {
            foreach($files as $file)
            {
                $subthemeimage = new Subthemeimages();
                $new_path = Storage::disk('public')->put('subthemeimages', $file);

                $subthemeimage->sub_theme_id =  $subtheme_id;
                $subthemeimage->path =  $new_path;
                $subthemeimage->save();
            }
        }

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
            $data = Subtheme::findOrFail($id);
            $subimages = DB::table('subthemeimages')->where('sub_theme_id', $id)->get();
            return response()->json(['data' => $data,'subimages' => $subimages]);
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

        //if($image != '')
        if ($request->hasFile('file'))
        {
            $rules = array(
                'theme_id'    =>  'required',
                'type'     =>  'required',
                'sub_theme_name'     =>  'required',
                'actual_price'     =>  'required',
                'file'         =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $subtheme = Subtheme::findorfail($request->hidden_id);
            Storage::delete($subtheme->file);
            $path = $request->file('file')->store('subthemeimages');
        }
        else
        {
            $rules = array(
                'theme_id'    =>  'required',
                'type'     =>  'required',
                'actual_price'     =>  'required',
                'sub_theme_name'     =>  'required'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }

        $form_data = array(
            'theme_id'        =>  $request->theme_id,
            'type'         =>  $request->type,
            'sub_theme_name'         =>  $request->sub_theme_name,
            'actual_price'         =>  $request->actual_price,
            'discounted_price'         =>  $request->discounted_price,
            'label'         =>  $request->label,
            'particular'         =>  $request->particular,
            'rating'         =>  $request->rating,
            'views'         =>  $request->views,
            'file'             =>  $path,
            'description'         =>  $request->description,
            'what_included'         =>  $request->what_included,
            'need_to_know'         =>  $request->need_to_know
        );

        Subtheme::whereId($request->hidden_id)->update($form_data);

        if($files = $request->file('images'))
        {
            foreach($files as $file)
            {
                $subthemeimage = new Subthemeimages();
                $new_path = Storage::disk('public')->put('subthemeimages', $file);

                $subthemeimage->sub_theme_id =  $request->hidden_id;
                $subthemeimage->path =  $new_path;
                $subthemeimage->save();
            }
        }

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
        $data = Subtheme::findOrFail($id);
        Storage::delete($data->file);

        $subimages = Subthemeimages::where('sub_theme_id', $id)->get();
        foreach ($subimages as $subimage) {
            Storage::delete($subimage->path);
            $subimage->delete();
        }
        if($data->delete())
        {
            echo 'Data Deleted';
        }

    }

    public function delsubimage($id)
    {
        $data = Subthemeimages::findOrFail($id);
        Storage::delete($data->path);
        if($data->delete())
        {
            echo 'Data Deleted';
        }
    }

}
