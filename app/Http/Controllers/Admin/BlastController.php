<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blast;
use Illuminate\Http\Request;

class BlastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.blasts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blasts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $phoneNumbers = explode(PHP_EOL, $request->phone_number);
        
        $phoneNumberRegex = '/^[0-9+]+$/';
        foreach ($phoneNumbers as $phoneNumber) {
            if (!preg_match($phoneNumberRegex, $phoneNumber)) {
                return back()->withInput()->with('error', 'Pastikan input hanya terdiri dari angka dan plus (+)');
            }
        }
        

        foreach ($phoneNumbers as $phoneNumber) {
            $alreadyExist = Blast::where('phone_number', $phoneNumber);
            
            if ($alreadyExist->count() >= 1) {
                continue;
            }

            Blast::create(['phone_number' => $phoneNumber]);
        }

        // return back()->with('success', 'Berhasil');
        return redirect()->route('admin.blast.index')->with('success', 'Berhasil');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
