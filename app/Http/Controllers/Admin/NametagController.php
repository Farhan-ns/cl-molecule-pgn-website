<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class NametagController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Registration $registration)
    {
        $hasAgencyBranch = !empty($registration->agency_branch);
        $encryptedId = Crypt::encrypt($registration->id);
        $additionalInfo = json_decode($registration->additional_info, true);
        
        return view('user.nametag', compact('registration', 'hasAgencyBranch', 'encryptedId', 'additionalInfo'));
    }
}
