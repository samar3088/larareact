<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Eventcoordinator;
use Validator;

class EventcoordinateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventcoordinate = Eventcoordinator::first();
        return view('admin.eventcoordinate',compact('eventcoordinate'));
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
        $request->validate([
            'event_coordinator_price' => 'required'
        ]);

        $itemCounts = Eventcoordinator::all()->count();

         if($itemCounts == 0)
         {
            $eventcoordinator = new Eventcoordinator();
            $eventcoordinator->event_coordinator_price = $request->event_coordinator_price;
            $eventcoordinator->save();
            return back()->with('success', 'Event coordinator created successfully.');
         }
         else
         {
            $item = Eventcoordinator::all()->first();
            $itemId = $item->id;
            $item->update($request->all());
            return redirect()->route('admin.eventcoordinate.index')->with('success','Event coordinator updated successfully');
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
