<?php

namespace App\Http\Controllers\Admin;

use App\Theme;
use App\Subtheme;
use Validator;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ThemeController extends Controller
{
    public function marriagethemes()
    {
        $theme_type = 'marriage';
        return view('admin.theme',compact('theme_type'));
    }

    public function birthdaythemes()
    {
        $theme_type = 'birthday';
        return view('admin.theme',compact('theme_type'));
    }

    public function equipthemes()
    {
        $theme_type = 'equipment';
        return view('admin.theme',compact('theme_type'));
    }

    /* public function packagethemes()
    {
        $theme_type = 'package';
        return view('admin.theme',compact('theme_type'));
    } */

    function getdata(Request $request)
    {
        $themes = DB::table('themes')->select('id', 'theme_name', 'theme_type')->where('theme_type', $request['theme_type'])->orderBy('Id','asc');
        return Datatables::of($themes)
                ->addIndexColumn()
                ->addColumn('action', function($theme){
                    return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$theme->id.'" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;<a href="#" class="btn btn-xs btn-danger delete" id="'.$theme->id.'" title="Delete"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    function postdata(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'theme_name' => 'required',
            'theme_type'  => 'required',
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
                $theme = new Theme([
                    'theme_name'    =>  $request->get('theme_name'),
                    'theme_type'     =>  $request->get('theme_type')
                ]);
                $theme->save();
                $success_output = '<div class="alert alert-success">Data Inserted</div>';
            }
            else if($request->get('button_action') == 'update')
            {
                $theme = Theme::find($request->get('theme_id'));
                $theme->theme_name = $request->get('theme_name');
                $theme->theme_type = $request->get('theme_type');
                $theme->save();
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
        $theme = Theme::find($id);
        $output = array(
            'theme_name'    =>  $theme->theme_name,
            'theme_type'     =>  $theme->theme_type
        );
        echo json_encode($output);
    }

    function removedata(Request $request)
    {
        $theme = Theme::find($request->input('id'));
        $theme_id = $theme->id;
        $subtheme_count = Subtheme::where('theme_id','=', $theme_id)->get()->count();

        if($subtheme_count > 0)
        {
            echo 'There are subthemes alloted to this theme. So sorry cant be deleted.';
        }
        else
        {
            $theme->delete();
            echo 'Data Deleted';
        }    

        /* if($theme->delete())
        {
            echo 'Data Deleted';
        } */
    }

    function massremove(Request $request)
    {
        $theme_id_array = $request->input('id');
        $theme = Theme::whereIn('id', $theme_id_array);
        if($theme->delete())
        {
            echo 'Data Deleted';
        }
    }
}
