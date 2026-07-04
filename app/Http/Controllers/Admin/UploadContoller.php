<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;


class UploadContoller extends Controller
{
    public function index()
    {
        return view('pages.upload.index');
    }

    public function list()
    {
        $uploads = Uploads::where('type', 'image')
            ->latest()
            ->get()
            ->map(fn($upload) => [
                'id' => $upload->id,
                'url' => $upload->file_name,
                'name' => $upload->file_original_name,
            ]);

        return response()->json($uploads);
    }



    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);
        $file = $request->file('image');
        $encoded = Image::read($file)
            ->scaleDown(width: 1200)
            ->toWebp(75);

        $folderPath = 'uploads/' . Str::uuid() . '.webp';

        Storage::disk('s3')->put($folderPath, (string) $encoded);
        $baseUrl = rtrim(config('filesystems.disks.s3.url'), '/');
        $fullUrl = "{$baseUrl}/{$folderPath}";

        $upload = Uploads::create([
            'file_original_name' => $file->getClientOriginalName(),
            'file_name' => $fullUrl,
            'user_id' => auth()->id(),
            'extension' => 'webp',
            'type' => 'image',
            'file_size' => Storage::disk('s3')->size($folderPath),
        ]);

        return response()->json([
            'id' => $upload->id,
            'url' => $upload->file_name,
            'name' => $upload->file_original_name,
        ]);
    }
}
