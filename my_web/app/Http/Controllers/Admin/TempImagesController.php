<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use DateTimeZone;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time().'.'.$ext;

            $tempImage = new TempImage;
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path().'/template', $newName);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'message' => 'Image uploaded successfully!',
            ]);
        }
    }
}
