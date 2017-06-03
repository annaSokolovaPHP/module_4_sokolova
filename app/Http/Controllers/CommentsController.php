<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\CommentsArticle;
use Illuminate\Support\Facades\DB;
use App\Article;
use Illuminate\Support\Facades\Auth;
use App\Settings;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use App\MenuItem;
use App\CategoriesArticles;
use App\Http\Controllers\ArticlesController;
use App\Teg;
use Illuminate\Support\Facades\File;

class CommentsController extends Controller
{
    public function saveComment(Request $request, $slug)
    {
        if ($request->ajax()){

            $validator = Validator::make($request->all(), [
                'content' => 'required'
            ]);

            if ($validator->fails()) {
                return Response::json(['success' => false, 'errors' => "Заполните поле Комментарий"], 400);
            }

            DB::beginTransaction();
            try {
                $formData = $request->except('_token');
                $formData['article_id'] = Article::findBySlug($slug)->id;
                $formData['author']     = Auth::user()->id;

                $categories = Article::find($formData['article_id'])->categories;

               $formData['public']     = $categories->moderation == 1 ? 'NO' : 'YES';
                if (!empty ($formData['parent_id'])) {
                    $parentComment = CommentsArticle::find($formData['parent_id']);
                }
                $formData ['order_parent'] = isset($parentComment) ? $parentComment->order_parent : null;
                $formData ['parent_id']    = isset($parentComment) ? $parentComment->id : null;
                $formData['level']         = isset($parentComment) ? $parentComment->level + 1 : 0;
                $formData['path']          = isset($parentComment) ? $parentComment->getChildPath() : CommentsArticle::getParentPath($formData['article_id']);
                $formData['created_at']    = Carbon::now();

                $newModel = CommentsArticle::create($formData);

                if (isset($newModel) && $newModel->order_parent == NULL) {
                    $newModel->order_parent = $newModel->id;
                    $newModel->update();
                }
                DB::commit();

                if (isset($newModel)) {
                    if($newModel->public == 'NO'){
                        return Response::json([
                            'success' => true,
                            'public'  => 'no',
                            'level'   => $newModel->level,
                            'message' => 'Спасибо за комментарий. После проверки он будет опубликован'
                        ], 200);
                    }else{
                        return Response::json([
                            'success'   => true,
                            'public'    => 'yes',
                            'level'     => $newModel->level,
                            'created_at'=> $newModel->created_at->format('d.m.y H:i:s'),
                            'comment'   => $newModel->toArray(),
                        ], 200);
                    }
                } else {
                    return Response::json(['success' => false], 400);
                }
            } catch (Exception $e) {
                DB::rollback();
            }
        }
    }

    public function down_like(Request $request)
    {
        if ($request->ajax()) {

            DB::beginTransaction();
            try {
                $comment = CommentsArticle::find($request->id);
                $comment->down_like = $comment->down_like + 1;
                $comment->update();
                DB::commit();
                return Response::json([
                    'success' => true,
                    'count'  => $comment->down_like,
                ], 200);
            } catch (Exception $e) {
                DB::rollback();
                return Response::json(['success' => false], 400);
            }
        }
    }

    public function up_like(Request $request)
    {
        if ($request->ajax()) {

            DB::beginTransaction();
            try {
                $comment = CommentsArticle::find($request->id);
                $comment->up_like = $comment->up_like + 1;


                if(!empty($comment->parent_id)) {
                    DB::table('commentsArticle')
                        ->where('parent_id', $comment->parent_id)
                        ->update(['total_like' => $comment->total_like + 1]);
                }else{
                    DB::table('commentsArticle')
                        ->where('parent_id', $comment->id)
                        ->update(['total_like' => $comment->total_like + 1]);
                    $comment->total_like = $comment->total_like + 1;
                    $comment->total_like = $comment->total_like + 1;
                }
                $comment->update();

                DB::commit();
                return Response::json([
                    'success' => true,
                    'count'  => $comment->up_like,
                ], 200);
            } catch (Exception $e) {
                DB::rollback();
                return Response::json(['success' => false], 400);
            }
        }
    }

    public function userComments($id){
        $comments = CommentsArticle::where("author", '=', $id)->paginate(5);

        $menuItems = MenuItem::where('menu_id' , Settings::first()->user_top_menu)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('user_comments',[
            'user'         => User::find($id),
            'comments'     => $comments,
            'menuItems'    => $menuItems,
        ]);
    }

    public function editComment($slug, $id)
    {
        $comment = CommentsArticle::find($id);
        $menuItems = MenuItem::where('menu_id' , Settings::first()->user_top_menu)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
        return view('comment',[
        'comment' => $comment,
        'menuItems' => $menuItems,
        ]);
    }

    public function update_comment(Request $request){

        $formData        = $request->except('_token');
        $comment = CommentsArticle::find($formData['id']);
        DB::beginTransaction();
        try {
            if(!empty($formData['password'])){
                $formData['password'] = bcrypt($formData['password']);
            }
            $comment->update($formData);
            DB::commit();
            $article = Article::find($comment->article_id);
            $menuItems = MenuItem::where('menu_id' , Settings::first()->user_top_menu)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();
            $articlesController = new ArticlesController;
            $articlesController->getData();
            $comments=CommentsArticle::where('public','YES')
                ->where('article_id', $article->id)
                ->orderBy('order_parent', 'desc')
                ->orderBy('path', 'asc')
                ->get();
            return redirect('articles/'.$article->slug)->with([
                'article'            => $article,
                'popularArticle'     => $articlesController->popularArticle,
                'categoriesArticles' => $articlesController->categoriesArticles,
                'comments'           => $comments,
                'page'               => $article,
                'menuItems'          => $menuItems,
                'tegs'               => Teg::all(),
                'advertising'        => $articlesController->advertising,
                'user_top_comments'  => $articlesController->user_top_comments,
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function job(){
        for ($i = 1; $i<=300; $i++) {
            $data['category_id'] = rand(1, 6);
            $data['title'] = 'Title for news ' . $i;
            $data['body'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec erat urna, pellentesque at rutrum quis, auctor vel massa. Curabitur ex enim, dapibus a nisl in, gravida fermentum metus. Morbi eu gravida velit, vitae ultrices eros. Quisque sit amet velit viverra, laoreet nisl at, consectetur tellus. Sed porttitor nunc quis urna condimentum, in condimentum sem feugiat. In maximus, odio at molestie scelerisque, sem enim viverra sapien, et rhoncus augue nibh eu diam. Ut vel interdum arcu. Nulla posuere augue felis, ac rhoncus odio vehicula vel. Etiam eleifend euismod ante. Quisque sed condimentum tortor, id iaculis felis. Donec posuere nisl sed orci vestibulum, sit amet consectetur sem malesuada. Donec metus leo, pharetra ac justo quis, consectetur efficitur augue. Suspendisse pharetra suscipit nisl, a fringilla turpis.

Vivamus efficitur, est eu lacinia ultricies, ligula purus gravida tellus, ut ullamcorper urna ligula non lorem. Fusce vel enim in mauris pellentesque gravida sit amet eu enim. Pellentesque ac justo et mauris commodo fringilla sit amet malesuada ante. Praesent cursus nunc quis varius elementum. Fusce elementum nibh ornare ipsum mattis ultrices. Suspendisse potenti. In molestie tempus lectus non rutrum. Donec leo elit, gravida a commodo fringilla, pellentesque nec sem.

Vivamus justo nulla, viverra non condimentum ac, eleifend ac massa. Etiam ultricies et nibh eu ultricies. Nullam ac tincidunt arcu. Suspendisse nisi sem, malesuada nec vestibulum eget, volutpat tincidunt odio. Praesent vel massa id mauris commodo semper. Morbi condimentum consequat ex eu sagittis. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed vel luctus diam. Sed egestas fringilla velit, vel accumsan metus sagittis vitae. Vivamus faucibus mollis vehicula. Integer dapibus nunc nec orci varius, vitae blandit nibh sagittis.';
            $data['slug'] = 'new_slug_' . $i;
            $data['count_read'] = rand(1, 25);
            $data['analytics'] = rand(0, 1);
            $newArticle = Article::create($data);
            $arr = [];
            for ($j = 1; $j <= 3; $j++) {
                $teg = rand(1, 7);
                if(!in_array($teg,$arr)){
                    DB::table('article_teg')->insert(
                        ['teg_id' => $teg, 'article_id' => $newArticle->id]
                    );
                    $arr[] = $teg;
                }

            }
        }


        }

    public function job_upd(){
        for ($i = 23; $i<=322; $i++) {

            $article = Article::find($i);
            $start_date = '2017-03-06';
            $end_date = '2017-03-06 - 1 year';
            $date = $this->randomDate($start_date, $end_date);

            $article->created_at = $date;
                $article->update();
        }
    }
    public function randomDate($start_date, $end_date)
    {
        // Convert to timetamps
        $min = strtotime($start_date);
        $max = strtotime($end_date);

        // Generate random number using above bounds
        $val = rand($min, $max);

        // Convert back to desired date format
        return date('Y-m-d H:i:s', $val);
    }






}

