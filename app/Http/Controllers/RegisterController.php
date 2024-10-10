<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Registration;
use Illuminate\Http\Request;
use QrCode;

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

        $registration = Registration::create($validated);
        $code = $registration->getUniqueCode();
        
        $filename = "$code.png";
        $path = storage_path("app/public/qr/$filename");
        QrCode::format('png')->size(200)->generate($code, $path);

        return back()->with([
            'swal-register-success' => '-',
            'unique_code' => $code,
            'qr_path' => asset("storage/qr/$filename"),
        ]);
    }
}
