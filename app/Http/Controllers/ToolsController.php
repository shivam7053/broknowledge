<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolsController extends Controller
{
    public function index()
    {
        return view('tools.index');
    }

    public function pdf()
    {
        return view('tools.pdf');
    }

    public function document()
    {
        return view('tools.document');
    }

    public function data()
    {
        return view('tools.data');
    }

    public function image()
    {
        return view('tools.image');
    }

    public function developer()
    {
        return view('tools.developer');
    }
}