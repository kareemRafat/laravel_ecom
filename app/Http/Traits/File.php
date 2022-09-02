<?php

namespace App\Http\Traits ;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

trait File {

    public $file ;
    public $product ;

    public function upload_file($file , $product)
    {

        $this->file = $file ;
        $this->product = $product ;

        $newImgName = $this->storeImg();

        // insert in image plymorph table
        $new_image = Image::create([
                        'name' => $newImgName ,
                        'imageable_type'=> 'App\Models\Product',
                        'imageable_id' => $this->product->id,
                        'path' => asset("storage/products/{$this->product->name}/{$newImgName}")
                    ]);

        if(request()->method() == 'PUT'){
            // rename($this->product->name)
            // update image directory name
        }

        return $new_image ;

    }

    public function storeImg()
    {
        // for images
        $name = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
        $newImgName = $name . time() . '.' . $this->file->getClientOriginalExtension() ;
        $this->file->storeAs('products/'.$this->product->name, $newImgName);

        return $newImgName ;
    }





}
