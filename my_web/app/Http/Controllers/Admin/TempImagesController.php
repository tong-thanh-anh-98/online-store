<?php

namespace App\Http\Controllers\Admin;

use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class TempImagesController extends Controller
{
    /**
     * Create a new template image.
     * The response for creating the template image.
     * @param Request $request The HTTP request object.
     */
    public function create(Request $request)
    {
        $image = $request->image;

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time().'.'.$ext;

            $tempImage = new TempImage;
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path().'/uploads/template', $newName);

            // generate thumb
            $sourcePath = public_path().'/uploads//template/'.$newName;
            $destPath = public_path().'/uploads/template/thumbnail/'.$newName;
            $image = Image::make($sourcePath);
            $image->fit(300, 275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/uploads//template/thumbnail/'.$newName),
                'message' => 'Image uploaded successfully!',
            ]);
        }
    }
}
