<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CanvasController extends Controller
{
    public function uploadCanvas(Request $request)
{
    $base64Image = $request->input('canvas_image');

    if ($base64Image) {
        $image = str_replace('data:image/png;base64,', '', $base64Image);
        $image = str_replace(' ', '+', $image);

        $imageData = base64_decode($image);
        $fileName = uniqid() . '.png';
        
        Storage::disk('public')->put('canvas-images/' . $fileName, $imageData);

        return response()->json(['message' => 'Image uploaded successfully!', 'path' => $fileName], 200);
    }

    return response()->json(['message' => 'No image uploaded'], 400);
}

}
