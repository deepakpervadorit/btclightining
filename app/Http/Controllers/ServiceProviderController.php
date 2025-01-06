<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceProviderController extends Controller
{
    public function index()
    {
        $games = DB::table('games')->get();
        return view('admin.service_provider',compact('games'));
    }
     public function addGame(Request $request)
    {
        DB::table('games')->insert([
            'game' => $request->name,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back();
    }

    public function updateGame(Request $request)
    {
        DB::table('games')->where('id', $request->id)->update([
            'game' => $request->name,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->back();
    }

    public function deleteGame($id)
    {
        DB::table('games')->where('id', $id)->delete();

        return redirect()->back();
    }
}
