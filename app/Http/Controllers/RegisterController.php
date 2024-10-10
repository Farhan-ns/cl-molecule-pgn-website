<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view('user.invitation.index');
    }

    public function showRsvp()
    {
        return view('user.invitation.invitation');
    }

    public function register(RegistrationRequest $request)
    {
        $validated = $request->validated();

        $additionalInfo = [
            'will_attend' => $validated['will_attend'],
        ];
        $validated['additional_info'] = json_encode($additionalInfo);
        $validated['attendance_lateness'] = 'false';

        Registration::create($validated);

        return back()->with('swal-register-success', '-');
    }
}
