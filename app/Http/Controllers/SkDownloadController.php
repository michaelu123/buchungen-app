<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class SkDownloadController extends Controller
{
    public function download($encNr, $type)
    {
        [$sknummer, $jahr] = explode(",", Crypt::decryptString($encNr));
        $skPath = Storage::disk('local')->path('SK/' . $jahr);
        $skPattern = $skPath . '/Saisonkarte_' . $sknummer . '_*.' . $type;
        $files = glob($skPattern);
        abort_unless(count($files) > 0, 404);
        $path = $files[0];
        return response()->download($path);
    }
}
