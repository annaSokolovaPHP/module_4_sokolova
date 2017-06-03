<?php
namespace App\Observers;

use App\Article;
use Illuminate\Support\Facades\Storage;
use App\Settings;
use App\Http\Controllers\UploadFilesController;
class ArticleObserver
{
    public function deleted(Article $article)
    {
        if($article->image && Storage::disk('public')->exists($article->image)){
            if(!Storage::disk('public')->delete($article->image)){
                throw new Exception('Ошибка удаления файла');
            }
        }
    }
}