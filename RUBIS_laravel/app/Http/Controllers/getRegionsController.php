<?php

namespace App\Http\Controllers;



use App\Region;

class getRegionsController extends Controller {

    public function All() {
        $regions = Region::all('id', 'name');
        $title = "RUBiS available regions";
        $filename = "BrowseRegions.php";
        return view('regions')->with("regions", $regions)->withTitle($title)->withFilename($filename);
    }
}
