<?php
namespace App\Observers;

use App\Settings;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UploadFilesController;
class SettingsObserver
{
    public function deleted(Settings $settings)
    {
        if($settings->default_user_avatar && Storage::disk('public')->exists($settings->default_user_avatar)){
            if(!Storage::disk('public')->delete($settings->default_user_avatar)){
                throw new Exception('Ошибка удаления файла');
            }
        }
    }
}