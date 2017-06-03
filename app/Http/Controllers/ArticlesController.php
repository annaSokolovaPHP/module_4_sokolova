<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\CategoriesArticles;
use App\CommentsArticle;
use Carbon\Carbon;
use App\Pages;
use App\MenuItem;
use App\Settings;
use phpDocumentor\Reflection\Types\Null_;
use SebastianBergmann\Comparator\ArrayComparator;
use Illuminate\Support\Facades\DB;
use App\Teg;
use App\Subscribe;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Advertising;

class ArticlesController extends Controller
{
    public $popularArticle;
    public $categoriesArticles;
    public $carousel;
    public $user_top_comments;
    public $advertising;

    public function __construct()
    {
        $this -> menuItems = MenuItem::where('menu_id' , Settings::first()->user_top_menu)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
    }

    public function getData(){

        $commentsArticle = DB::table('commentsArticle')
            ->select('article_id')
            ->where('created_at', '<=', date('Y-m-d H:i:s'))
            ->where('created_at', '>=', date('Y-m-d H:i:s',time()-86400))
            ->groupBy('article_id')
            ->orderBy(DB::raw('count(*)'), 'desc')
            ->limit(3)
            ->get();

        $commentsArticle = json_decode($commentsArticle, true);
        $this->popularArticle = [];
        foreach ($commentsArticle as $value) {
            $article = Article::find($value['article_id']);
            $this->popularArticle []= [
                'title' => $article->title,
                'image' => $article->image,
                'slug'  => $article->slug
            ];
        }

        $this->categoriesArticles = CategoriesArticles::all();
        $this->carousel = Article::where('image' , '<>', '')->limit(4)->get();

        $this->user_top_comments = DB::table('users')
            ->join('commentsArticle', 'commentsArticle.author', '=', 'users.id')
            ->select('users.id', 'users.name')
            ->groupBy('users.id')
            ->groupBy('users.name')
            ->orderBy(DB::raw('count(*)'), 'desc')
            ->limit(5)
            ->get();
        $this->user_top_comments = json_decode($this->user_top_comments, true);

        $this->advertising = Advertising::orderBy('order', 'asc')->limit(8)->get();

    }

    public function articles(Request $request){

    if(!empty($request->get('find'))){
        $articles = Article::all()
            ->where('title', 'like', '%'.$request->get('find').'%')
            ->orWhere('body', 'like', '%'.$request->get('find').'%')
            ->orderBy('id', 'desc')
            ->paginate(5);
    }else{
        $articles = Article::orderBy('id', 'desc')->paginate(5);
    }

    $this->getData();

    return view('articles',[
        'articles'           => $articles,
        'popularArticle'     => $this->popularArticle,
        'categoriesArticles' => $this->categoriesArticles,
        'menuItems'          => $this->menuItems,
        'carousel'           => $this->carousel,
        'tegs'               => Teg::all(),
        'user_top_comments'  => $this->user_top_comments,
        'advertising'        => $this->advertising,
    ]);
}

    public function findByparam(Request $request){



        $formData = $request->except('_token');

        $articles = Article::query();

        if(isset($formData['teg']) && !empty($formData['teg'])) {
            $articles->whereHas('tegs', function ($query) use ($formData) {
                $query->whereIn('tegs.id', $formData['teg']);
            });
        }

        if(isset($formData['date_from']) && !empty($formData['date_from']))
              $articles = $articles->where('created_at', '>=', $formData['date_from']);
        if(isset($formData['date_to']) && !empty($formData['date_to']))
            $articles = $articles->where('created_at', '<=', $formData['date_to']);
        if(isset($formData['category']) && !empty($formData['category']))
            $articles = $articles->whereIn('category_id', $formData['category']);

        $articles = $articles->orderBy('created_at', 'desc')->paginate(5);

        $this->getData();

        return view('articles',[
            'articles'           => $articles,
            'popularArticle'     => $this->popularArticle,
            'categoriesArticles' => $this->categoriesArticles,
            'menuItems'          => $this->menuItems,
            'carousel'           => $this->carousel,
            'tegs'               => Teg::all(),
            'user_top_comments' => $this->user_top_comments,
            'advertising'        => $this->advertising,
        ]);
    }


    public function articlesCategory(Request $request, $category_slug){


        $category = CategoriesArticles::findBySlug($category_slug);

        $articles = Article::where('category_id', $category->id)
            ->orderBy('id', 'desc')
            ->paginate(5);

        $this->getData();
        return view('articles',[
            'articles'           =>$articles,
            'popularArticle'     => $this->popularArticle,
            'categoriesArticles' => $this->categoriesArticles,
            'menuItems'          => $this->menuItems,
            'category'           => $category_slug,
            'carousel'           => $this->carousel,
            'tegs'               => Teg::all(),
            'user_top_comments' => $this->user_top_comments,
            'advertising'        => $this->advertising,
        ]);
    }
    public function analytics(Request $request){

        $articles = Article::where('analytics', '=', '1')->paginate(5);

        $this->getData();

        return view('articles',[
            'articles'           => $articles,
            'popularArticle'     => $this->popularArticle,
            'categoriesArticles' => $this->categoriesArticles,
            'menuItems'          => $this->menuItems,
            'carousel'          => $this->carousel,
            'tegs'              => Teg::all(),
            'user_top_comments' => $this->user_top_comments,
            'advertising'        => $this->advertising,
        ]);
    }
    public function articlesByTeg($id){
        $articles = Teg::find($id)->articles()
            ->paginate(5);

        $this->getData();

        return view('articles',[
            'articles'           =>$articles,
            'popularArticle'     => $this->popularArticle,
            'categoriesArticles' => $this->categoriesArticles,
            'menuItems'          => $this->menuItems,
            'carousel'           => $this->carousel,
            'tegs'              => Teg::all(),
            'user_top_comments' => $this->user_top_comments,
            'advertising'        => $this->advertising,
        ]);
    }

    public function articlesFind(){


        if(isset($_POST['find']) && !empty($_POST['find'])){
            $articles = Teg::where('name', '=', $_POST['find'])->first()->articles()
                ->paginate(5);
        }else{
            $articles = Article::all()->paginate(5);
        }

        $this->getData();

        return view('articles',[
            'articles'           =>$articles,
            'popularArticle'     => $this->popularArticle,
            'categoriesArticles' => $this->categoriesArticles,
            'menuItems'          => $this->menuItems,
            'carousel'           => $this->carousel,
            'tegs'              => Teg::all(),
            'user_top_comments' => $this->user_top_comments,
            'advertising'        => $this->advertising,
        ]);
    }


    public function  countTotal(Request $request, $slug){
        if ($request->ajax()) {
            if (isset($_GET) && !empty($_GET['read_count']) && $_GET['read_count'] != 0) {
                $article_slug = Article::findBySlug($slug);
                $article_slug->count_read  = $article_slug->count_read + $_GET['read_count'];
                $article_slug->update();

                return response()->json(  );
            }
        }

    }

    public function article($slug){

        $article_slug = Article::findBySlug($slug);

        if(!isset($article_slug)){
            return redirect('/articles');
        }

        $this->getData();

        $comments=CommentsArticle::where('public','YES')
            ->where('article_id', $article_slug->id)
            ->orderBy('order_parent', 'desc')
            ->orderBy('path', 'asc')
            ->get();

        return view('article',[
            'article'            => $article_slug,
            'popularArticle'     => $this->popularArticle,
            'categoriesArticles' => $this->categoriesArticles,
            'comments'           => $comments,
            'page'               => $article_slug,
            'menuItems'          => $this->menuItems,
            'tegs'              => Teg::all(),
            'advertising'        => $this->advertising,
            'user_top_comments'  => $this->user_top_comments,
        ]);
    }

    public function subscribe(Request $request){
        if ($request->ajax()){
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'name'  => 'required'
            ]);
            $errors = implode(' ', $validator->messages()->all());

            if ($validator->fails()){
                return Response::json(['success' => false, 'errors' => $errors], 400);
            }

            $formData = $request->except('_token');

            DB::beginTransaction();
            try {

                Subscribe::create($formData);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
            }
            return Response::json([
                'success' => true
            ], 200);
        }

    }
}
