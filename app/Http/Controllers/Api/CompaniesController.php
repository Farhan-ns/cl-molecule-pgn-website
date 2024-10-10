<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function getCompanies()
    {
        $companiesJson = $this->readRawFile('companies-domicile.json');

        return response([
            'success' => 'true',
            'data' => $companiesJson,
        ]);
    }

    private function readRawFile($filename)
    {
        // Use base_path() to get the file path in the project root
        $filePath = base_path($filename);

        // Read the file content
        $jsonContent = file_get_contents($filePath);

        return $jsonContent;
    }
}
