<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ImportService;


class StatisticsController extends Controller
{
    protected ImportService $importService;

    public function __construct(ImportService $importService)
    {
    
        $this->importService = $importService;
    }
    public function index()
    {
        
        return view('statistics.content');
    }



    

}
