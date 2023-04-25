<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;

class FileSystemController extends Controller
{

    public function index()
    {
           $path = public_path('storage');
           $files = File::allFiles($path);
           foreach($files as $file) {
               echo ($file->getPathname());
               echo "<br>";
           }
    }

}

