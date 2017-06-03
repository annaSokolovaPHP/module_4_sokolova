<?php
namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Storage;
use App\Settings;
use App\Http\Controllers\UploadFilesController;
class UserObserver
{
    public function deleted(User $user)
    {
        if($user->avatar && Storage::disk('public')->exists($user->avatar)){
            if(!Storage::disk('public')->delete($user->avatar)){
                throw new Exception('Ошибка удаления файла');
            }
        }
    }
}