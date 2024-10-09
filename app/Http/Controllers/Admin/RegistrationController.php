<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RegistrationsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['registrations'] = Registration::all();
        $data['registrations_count'] = $data['registrations']->count();

        return view('admin.registrations.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegistrationRequest $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Registration $registration)
    {
        $additionalInfo = json_decode($registration->additional_info, true);

        return view('admin.registrations.show', compact('registration', 'additionalInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Registration $registration)
    {
        return view('admin.registrations.edit', compact('registration'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'phone' => ['required', 'numeric'],
            'office' => ['required'],
            'bpc' => ['required'],
            'membership' => ['required'],
            'image' => ['nullable', 'image', 'max:2042'],
        ]);

        $decodedAdditionalInfo = json_decode($registration->additional_info, true);

        if ($request->image) {
            $newsDirectory = '';
            if (config('app.env') == 'local') {
                $newsDirectory = public_path() . '/images';
            } else {
                $newsDirectory = base_path() . '/../images';
            }

            $imageName = $request->file('image')->hashName();
            $validated['image'] = $imageName;
            $request->file('image')->move($newsDirectory, $imageName);
        }

        $additionalInfo = [
            'bpc' => $validated['bpc'],
            'membership' => $validated['membership'],
            'image' => $request->image ? $validated['image'] : $decodedAdditionalInfo['image'] ?? '',
        ];

        $validated['additional_info'] = json_encode($additionalInfo);

        $registration->update($validated);
        $registration->save();

        return redirect()->route('admin.registration.index')->with('message', 'Berhasil edit data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration)
    {
        $registration->delete();

        return back()->with('message', 'Berhasil hapus data');
    }

    public function export(Request $request)
    {
        Carbon::setLocale('id');
        $date = now()->format('d-m-y');
        return Excel::download(new RegistrationsExport, "data-registrasi-pgn-$date.xlsx");
    }

    public function setAttendance(Request $request, Registration $registration)
    {
        $registration->has_attended = $request->attended;
        $registration->attended_at = now();
        $registration->save();

        return response()->success([
            'success' => true,
        ]);
    }
}
