<?php

namespace App\Http\Controllers;
use File;
use App\DataType;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
class UploadFilesController extends Controller
{

    public static function uploadFile($fieldName, $ext, $formData, $model, $size){

            $filename  = $model->id  .'.' . $ext;
            $slug = DataType::where('model_name', class_basename($model))->first()->slug;
            $resizedImage = Image::make($formData->getRealPath())->heighten($size)->stream();
            Storage::disk('public')->put($slug . DIRECTORY_SEPARATOR. $filename,  $resizedImage);
            $model->$fieldName = $slug.DIRECTORY_SEPARATOR.$filename;
            $model->update();

    }

    public static function deleteFile($fieldName, $model){

       if(Storage::disk('public')->exists($model->$fieldName)){
           if(Storage::disk('public')->delete($model->$fieldName)){
               $model->$fieldName = null;
               $model->update();
           }else{
               throw new Exception('Ошибка удаления файла');
           }
       }
    }
/*
    public function resizeSaveImage($image_file, $model){
        $slug = DataType::where('model_name', class_basename($model))->first();
        switch(class_basename($model)){
            case 'UserForAdmin':
            case 'User':
                $width = 300;
                $height =  300;
                $ext = 'png';
                $field_name = 'avatar';
                break;
            case 'Settings':
                $width = 300;
                $height =  300;
                $ext = 'png';
                $field_name = 'default_user_avatar';
                break;
            case 'Article':
                $width = 700;
                $height =  700;
                $ext = 'jpg';
                $field_name = 'image';
                break;
            case 'Certificate':
                $width = 1000;
                $height =  1000;
                $ext = 'jpg';
                $field_name = 'image';
                break;
        }

        $ret = $this->resizeFile($slug->slug, $image_file, $model->id, $width, $height, $ext);
        if($ret === true) {
            $model->$field_name = $slug->slug . DIRECTORY_SEPARATOR . $model->id . '.'.$ext;
            $model->save();
        }
*/
        /*
        switch($slug){
            case 'users':
                $ret = $this->resizeFile($slug, $image_file, $id, 300, 300, 'png');
                if($ret === true) {
                    $users = User::find($id);
                    $users->avatar = $slug . DIRECTORY_SEPARATOR . $id . '.png';
                    $users->save();
                }
                break;
            case 'settings':
                $ret = $this->resizeFile($slug, $image_file, $id, 300, 300, 'png');
                if($ret === true) {
                    $settings= Settings::find($id);
                    $settings->default_user_avatar = $slug . DIRECTORY_SEPARATOR . $id . '.png';
                    $settings->save();
                }
                break;
            case 'articles':
                $ret = $this->resizeFile($slug, $image_file, $id, 700, 700, 'jpg');
                if($ret === true) {
                    $articles = Article::find($id);
                    $articles->image = $slug . DIRECTORY_SEPARATOR . $id . '.jpg';
                    $articles->save();
                }
                break;
            case 'certificates':
                $ret = $this->resizeFile($slug, $image_file, $id, 1000, 1000, 'jpg');
                if($ret === true) {
                    $certificates = Certificate::find($id);
                    $certificates->image = $slug . DIRECTORY_SEPARATOR . $id . '.jpg';
                    $certificates->save();
                }
                break;
        }

        return $ret;*/
    }
/*
    private function resizeFile($fileDir, $fileName, $id, $max_width, $max_height, $ext)
    {

        $newFile    = imagecreatefromstring(file_get_contents($fileName));
        $width      = imagesx($newFile);
        $height     = imagesy($newFile);
        $percentage = 1;

        if ($width > $max_width) {
            $percentage = ($height / ($width / $max_width)) > $max_height ?
                $height / $max_height :
                $width / $max_width;
        } elseif ($height > $max_height) {
            $percentage = ($width / ($height / $max_height)) > $max_width ?
                $width / $max_width :
                $height / $max_height;
        }
        $new_width  = $width / $percentage;
        $new_height = $height / $percentage;

        $out = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($out, $newFile, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        $path = public_path() . DIRECTORY_SEPARATOR . $fileDir ;
        File::isDirectory($path)or File::makeDirectory($path,0777,true,true);

        if ($ext == 'png') {
            if (!@imagepng($out, $path . DIRECTORY_SEPARATOR . $id . '.'.$ext)) {
                $errors = error_get_last();
                return $errors;
            } else {
                return true;
            }
        } else {
            if (!@imagejpeg($out, $path . DIRECTORY_SEPARATOR . $id . '.'.$ext)) {
                $errors = error_get_last();
                return $errors;
            } else {
                return true;
            }
        }
    }
}*/
