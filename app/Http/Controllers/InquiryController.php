<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;

class InquiryController extends Controller
{
     
    public function index(Request $request)
    {
        $data= Inquiry::with(['users','users.roles'])->orderBy('id','desc')->paginate(10);
        return view('Inquiry.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        Inquiry::find($id)->delete();
        return redirect()->route('inquiry.index')
                        ->with('success','Inquiry deleted successfully');

    }
}
