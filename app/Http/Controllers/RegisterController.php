<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index() {
        return view('user.register');
    }

    public function register(RegistrationRequest $request)
    {
        $validated = $request->validated();

        $newsDirectory = '';
        if (config('app.env') == 'local') {
            $newsDirectory = public_path() . '/images';
        } else {
            $newsDirectory = base_path() . '/../images';
        }

        $imageName = $request->file('image')->hashName();
        $validated['image'] = $imageName;
        $request->file('image')->move($newsDirectory, $imageName);

        $additionalInfo = [
            'bpc' => $validated['bpc'],
            'membership' => $validated['membership'],
            'image' => $validated['image'],
        ];
        $validated['additional_info'] = json_encode($additionalInfo);
        $validated['email'] = $validated['phone'] . '@email.com';

        Registration::create($validated);

        return back()->with('swal-register-success', '-');
    }
}
