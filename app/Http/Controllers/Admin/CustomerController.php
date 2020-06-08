<?php

namespace App\Http\Controllers\Admin;

use App\Customer;
use Validator;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomersImport;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers');
    }

    function getdata(Request $request)
    {
        $customers = DB::table('customers')->select('id', 'name', 'email', 'mobile', 'lead_source', 'created_at')->orderBy('created_at','desc');
        return Datatables::of($customers)
                ->addIndexColumn()
                ->addColumn('action', function($customer){
                    return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$customer->id.'" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;<a href="#" class="btn btn-xs btn-danger delete" id="'.$customer->id.'" title="Delete"><i class="fas fa-trash"></i></a>';
                })
                ->addColumn('checkbox', '<input type="checkbox" name="customer_checkbox[]" class="customer_checkbox" value="{{$id}}" />')
                ->addColumn('name', '{{$name}}')
                ->addColumn('email', '{{$email}}')
                ->addColumn('mobile', '{{$mobile}}')
                ->addColumn('lead_source', '{{$lead_source}}')
                ->addColumn('created_at', '{{$created_at}}')
                 ->rawColumns(['checkbox','action'])
                ->make(true);
    }

    function postdata(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email'  => 'required',
            'mobile'  => 'required',
            'lead_source'  => 'required',
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
                $customer = new Customer([
                    'name'    =>  $request->get('name'),
                    'email'     =>  $request->get('email'),
                    'mobile'     =>  $request->get('mobile'),
                    'lead_source'     =>  $request->get('lead_source')
                ]);

                $customer->save();
                $success_output = '<div class="alert alert-success">Data Inserted</div>';
            }
            else if($request->get('button_action') == 'update')
            {
                $customer = Customer::find($request->get('customer_id'));
                $customer->name = $request->get('name');
                $customer->email = $request->get('email');
                $customer->mobile = $request->get('mobile');
                $customer->lead_source = $request->get('lead_source');
                $customer->save();
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
        $customer = Customer::find($id);
        $output = array(
            'name'    =>  $customer->name,
            'email'     =>  $customer->email,
            'mobile'     =>  $customer->mobile,
            'lead_source'     =>  $customer->lead_source
        );
        echo json_encode($output);
    }

    function removedata(Request $request)
    {
        $customer = Customer::find($request->input('id'));
        if($customer->delete())
        {
            echo 'Data Deleted';
        }
    }

    function massremove(Request $request)
    {
        $customer_id_array = $request->input('id');
        $customer = Customer::whereIn('id', $customer_id_array);
        if($customer->delete())
        {
            echo 'Data Deleted';
        }
    }


    /* function to import the data in the customers table */

    public function import()
    {
        Excel::import(new CustomersImport,request()->file('select_file'));

        return back()->with('success', 'Excel Data Imported successfully.');
    }

    /* function import(Request $request)
    {
     $this->validate($request, [
      'select_file'  => 'required|mimes:xls,xlsx'
     ]);

     $path = $request->file('select_file')->getRealPath();

     $data = Excel::load($path)->get();

     if($data->count() > 0)
     {
      foreach($data->toArray() as $key => $value)
      {
       foreach($value as $row)
       {
        $insert_data[] = array(
         'name'  => $row['name'],
         'email'   => $row['email'],
         'mobile'   => $row['mobile'],
         'lead_source'    => $row['lead_source']
        );
       }
      }

      if(!empty($insert_data))
      {
       DB::table('customers')->insert($insert_data);
      }
     }
     return back()->with('success', 'Excel Data Imported successfully.');
    } */


    /* public function importData(Request $request)
    {
        $request->validate([
            'import_file' => 'required'
        ]);
        $path = $request->file('import_file')->getRealPath();
        $data = Excel::load($path)->get();
        if($data->count()){
            foreach ($data as $key => $value) {
                $arr[] = ['title' => $value->title, 'description' => $value->description];
            }
            if(!empty($arr)){
                Data::insert($arr);
            }
        }
        return back()->with('success', 'Insert Record successfully.');
    } */

    /* function to import the data in the customers table ends */

}
