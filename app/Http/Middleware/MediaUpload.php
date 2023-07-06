<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Models\MediaFile;
use App\Converter\OfficeConverter;
use File;
use Zip;

class MediaUpload
{

    public function __construct()
    {

    }

    public static function fileUpload(Request $request)
    {
/*
        $request->validate([
            'file' => 'required|mimes:jpeg,jpg,png,gif,mkv,mp4,ts,flv'
        ]);
*/
        $user = auth()->user()->id;
        //$filename = 'u'.$user.'-'.time().'.'.$request->file->getClientOriginalExtension();
        $fileName = 'u'.$user.'-'.time();
        $filename_orig = $fileName.'.'.$request->file->getClientOriginalExtension();
        $filename_jpg = $fileName.'.jpg';

        $file = new MediaFile();
        if ($request->file()) {
            if ($request->mime_type == 'image') {
                $filePath = $request->file('file')->storeAs('images', $filename_orig, 'public');
                $filename = $filename_orig;
                $file->name= $filename;
                $file->file_path = '/storage/'.$filePath;
            } else if ($request->mime_type == 'i_video') {
                $filePath = $request->file('file')->storeAs('videos', $filename_orig, 'public');
                $filename = $filename_orig;
                $file->name= $filename;
                $file->file_path = '/storage/'.$filePath;
            } else if ($request->mime_type == 'ppt') {
                $filePath = $request->file('file')->storeAs('videos/ppt', $filename_orig, 'public');
                $filename = MediaUpload::convert($fileName, $filename_orig, $filename_jpg, 'ppt');
                $file->name= $filename;
                $file->file_path = $filename;
            } else if ($request->mime_type == 'pdf') {
                $filePath = $request->file('file')->storeAs('videos/pdf', $filename_orig, 'public');
                $filename = $this->convert($fileName, $filename_orig, $filename_jpg, 'pdf');
                $file->name= $filename;
                $file->file_path = '/storage/'.$filePath;
            }
        }
        return $file;
    }

    public static function convert($fileName, $filename_orig, $filename_jpg, $path)
    {
        $abspath = '/files/www/major/storage/app/public/videos/'.$path;
        $orig = $abspath.'/'.$filename_orig;
        $subpath = $abspath.'/'.$fileName;
        if (!File::isDirectory($subpath)) {
            File::makeDirectory($subpath, 0777, true, true);
        }
        $jpeg = $subpath.'/'.$filename_jpg;
        $zipfile = $fileName.'.zip';
        $exec = '/usr/bin/convert '.$orig.' '. $jpeg;
        $converter = new OfficeConverter($orig);
        $converter->exec($exec);
        $zip = Zip::create($abspath.'/'.$zipfile);
        $zip->add($subpath, true);
        $zip->close();
        return $path.'/'.$zipfile;
    }
}
