<?php

namespace App\Http\Controllers;

use App\Article;
use App\CommentsArticle;
use Illuminate\Http\Request;
use Validator;
use App\MenuItem;
use App\User;
use App\DataType;
use App\DataRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\FileValidator;
use App\Price;
use App\Settings;
use Illuminate\Support\Facades\File;
use App\Teg;

class AdminController extends Controller
{
    private $menuItems;
    private $rightMenuItems;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->menuItems = MenuItem::where('menu_id', Settings::first()->admin_top_menu)->orderBy('order')->get();
        $this->rightMenuItems = MenuItem::where('menu_id', Settings::first()->admin_left_menu)->orderBy('order')->get();
    }

    public function index()
    {
        return view('admin.index', [
            'menuItems'     => $this->menuItems,
            'rightMenuItems'=> $this->rightMenuItems
        ]);
    }

    public function tegs($id)
    {
        $dataType = DataType::where('slug', '=', 'teg')->first();
        $article = Article::find($id);
        $dataTypeContent = $article->tegs()->get();
        return view('admin.articles.tegs_browse', [
            'menuItems'       => $this->menuItems,
            'rightMenuItems'  => $this->rightMenuItems,
            'dataTypeContent' => $dataTypeContent,
            'dataType'        => $dataType,
            'article'         => $article,
        ]);
    }


    public function add_tegs($id)
    {
        $article = Article::find($id);
        return view('admin.articles.tegs_add', [
            'menuItems'       => $this->menuItems,
            'rightMenuItems'  => $this->rightMenuItems,
            'article'         => $article,
        ]);
    }

    public function delete_teg($article_id, $teg_id)
    {
        DB::table('article_teg')
            ->where('article_id', '=', $article_id)
            ->where('teg_id', '=', $teg_id)
            ->delete();
        return redirect()->back()->with('message', "Запись удалена");
    }

    public function save_tegs(Request $request, $id)
    {
        $formData = $request->except('_token');
        foreach ($formData as $teg) {
            $teg_table = Teg::where('name', '=', trim($teg))->first();

            if (!$teg_table) {
                DB::table('tegs')->insert(
                    ['name' => trim($teg)]
                );
            }
            $teg_table = Teg::where('name', '=', trim($teg))->get();
            $article = Article::find($id);
            $tegs_article = $article->tegs()->pluck('name')->toArray();

            if(!in_array(trim($teg), $tegs_article)){
                $article->tegs()->saveMany($teg_table);
            }

        }
        return redirect("admin/articles/$id/tegs")->with('message', "Новая запись обновлена");

    }
    public function browse($slug, Request $request)
    {
        $dataType = DataType::where('slug', '=', $slug)->first();

        if (isset($dataType->model_name) && $dataType->model_name != "Settings") {
            $model           = "App\\".$dataType->model_name;
            $dataTypeContent = $model::orderBy('id', 'DESC')->get();

            if (view()->exists("admin.$slug.browse")) {
                $view = "admin.$slug.browse";
            }
            else {
                $view = "admin.general.browse";
            }
            if($view  && $dataTypeContent){
                return view($view, [
                    'menuItems'       => $this->menuItems,
                    'rightMenuItems'  => $this->rightMenuItems,
                    'dataTypeContent' => $dataTypeContent,
                    'dataType'        => $dataType,
                ]);

            }else{
                return redirect('admin');
            }
        }
        return redirect('admin');
    }

    public function show($slug, $id)
    {
        $dataType = DataType::where('slug', '=', $slug)->first();

        if (isset($dataType->model_name)) {
            $model           = "App\\".$dataType->model_name;
            $dataTypeContent = $model::find($id);

            if (view()->exists("admin.$slug.read")){
                $view = "admin.$slug.read";}
            else {
                $view = "admin.general.read";
            }
            if($view && $dataTypeContent){
                return view($view, [
                    'menuItems'       => $this->menuItems,
                    'rightMenuItems'  => $this->rightMenuItems,
                    'dataTypeContent' => $dataTypeContent,
                    'dataType'        => $dataType
                ]);
            }else{
                return redirect("admin/$slug/browse");
            }
        }
        return redirect("admin/$slug/browse");
    }

    public function edit($slug, $id)
    {
        $dataType = DataType::where('slug', '=', $slug)->first();

        if (isset($dataType->model_name)) {
            $model           = "App\\".$dataType->model_name;
            $dataTypeContent = $model::find($id);

            if (view()->exists("admin.$slug.edit-add")) {
                $view = "admin.$slug.edit-add";
            }
            else {
                $view = "admin.general.edit-add";
            }
            if($view  && $dataTypeContent){
                return view($view, [
                    'menuItems'       => $this->menuItems,
                    'rightMenuItems'  => $this->rightMenuItems,
                    'dataTypeContent' => $dataTypeContent,
                    'dataType'        => $dataType
                ]);
            }else{
                return redirect("admin/$slug/browse");
            }
        }
        return redirect("admin/$slug/browse");
    }

    public function update(Request $request, $slug, $id)
    {
        $dataType = DataType::where('slug', '=', $slug)->first();

        if (isset($dataType->model_name)) {
            $model           = "App\\" . $dataType->model_name;
            $dataTypeContent = $model::find($id);
            $formData        = $request->except('_token');
            $validator       = Validator::make($request->all(), $dataTypeContent->rules($id));

            if ($validator->fails()) {

                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            DB::beginTransaction();
            try {
                if(!empty($formData['password'])){
                    $formData['password'] = bcrypt($formData['password']);
                }
                $dataTypeContent->update($formData);
                DB::commit();

                if(method_exists($dataTypeContent, 'uploadFile')) {
                    $dataTypeContent->uploadFile($formData);
                }

            } catch (Exception $e) {
                DB::rollback();
            }

            if($model == "App\Settings"){
                return redirect("admin/$slug/show/$id")
                    ->with('message', "Информация обновлена");
            }else {
                return redirect("admin/$slug/browse")
                    ->with('message', "Информация обновлена {$dataType->display_name_singular}");
            }
        } else {
            return redirect("admin/$slug/browse");
        }
    }

    public function add($slug){

        $dataType = DataType::where('slug', '=', $slug)->first();

        if (view()->exists("admin.$slug.edit-add")){
            $view = "admin.$slug.edit-add";}
        else {
            $view = "admin.general.edit-add";
        }

        return view($view, [
            'menuItems'       => $this->menuItems,
            'rightMenuItems'  => $this->rightMenuItems,
            'dataTypeContent' => Null,
            'dataType'        => $dataType
        ]);
    }

    public function createRecord(Request $request, $slug)
    {
        $dataType = DataType::where('slug', '=', $slug)->first();

        if (isset($dataType->model_name)) {
            $model    = "App\\" . $dataType->model_name;
            $formData = $request->except('_token');

            if ($model && $formData) {

                $currentModel = new $model;
                $validator    = Validator::make($request->all(), $currentModel->rules());
                if ($validator->fails()){
                    return redirect("admin/$slug/add")
                        ->withErrors($validator)
                        ->withInput();
                }
                DB::beginTransaction();
                try {
                    if(!empty($formData['password'])){
                        $formData['password'] = bcrypt($formData['password']);
                    }
                    $newModel = $model::create($formData);
                    DB::commit();

                    if(method_exists($newModel, 'uploadFile')) {
                        $newModel->uploadFile($formData);
                    }

                } catch (Exception $e) {
                    DB::rollback();
                }
                return redirect("admin/$slug/browse")->with('message', "Новая запись добавлена");
            } else
                return redirect("admin/$slug/browse");
        } else
            return redirect("admin/$slug/browse");
    }

    public function deleteRecord(Request $request, $slug, $id)
    {
        $dataType = DataType::where('slug', '=', $slug)->first();

        if (isset($dataType->model_name)) {
            $model = "App\\" . $dataType->model_name;

            DB::beginTransaction();
            try {
                $delModelRecord = $model::find($id);
                $delModelRecord->delete();
                //$model::destroy($id);
                DB::commit();
            }
            catch (Exception $e) {
                return redirect("admin/$slug/browse")
                    ->withErrors(['errors' => $e->getMessage()]);
            }
            return redirect("admin/$slug/browse")->with('message', "Запись удалена");
        }else
            return redirect("admin/$slug/browse");
    }

    public  function comment_policy($slug){
        $dataType = DataType::where('slug', '=', $slug)->first();

        $dataTypeContent = CommentsArticle::where('public', '=', 'NO')->orderBy('id', 'DESC')->get();

        if (view()->exists("admin.$slug.browse")) {
            $view = "admin.$slug.browse";
        }
        else {
            $view = "admin.general.browse";
        }
        if($view  && $dataTypeContent){
            return view($view, [
                'menuItems'       => $this->menuItems,
                'rightMenuItems'  => $this->rightMenuItems,
                'dataTypeContent' => $dataTypeContent,
                'dataType'        => $dataType,
            ]);

        }else{
            return redirect('admin');
        }
    }
}
