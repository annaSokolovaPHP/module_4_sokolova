<?php


Route::get('/', 'ArticlesController@articles')->name('articles');
Route::get('articles/', 'ArticlesController@articles')->name('articles');
Route::get('articles/analytics', 'ArticlesController@analytics');
Route::get('articles/category_{slug}', 'ArticlesController@articlesCategory')->name('articles');
Route::get('articles/{slug}','ArticlesController@article')->name('articles');
Route::post('articles/{slug}/create_comment', 'CommentsController@saveComment');
Route::get('articles/{slug}/count_total', 'ArticlesController@countTotal');
Route::get('articles/teg/{id}', 'ArticlesController@articlesByTeg');
Route::post('articles/teg', 'ArticlesController@articlesFind');
Route::post('articles/comment_down_like', 'CommentsController@down_like');
Route::post('articles/comment_up_like', 'CommentsController@up_like');
Route::get('articles/comments/{id}', 'CommentsController@userComments');
Route::match(['get', 'post'], 'find_param', 'ArticlesController@findByparam');
Route::post('/subscribe', 'ArticlesController@subscribe');
Route::get('articles/{slug}/edit_comment/{id}', 'CommentsController@editComment');
Route::post('articles/update_comment', 'CommentsController@update_comment');


Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', 'AdminController@index');
    Route::get('/{slug}/browse', 'AdminController@browse');
    Route::get('/{slug}/show/{id}', 'AdminController@show');
    Route::get('/{slug}/edit/{id}', 'AdminController@edit');
    Route::post('/{slug}/update/{id}', 'AdminController@update');
    Route::get('/{slug}/add', 'AdminController@add');
    Route::post('/{slug}/create', 'AdminController@createRecord');
    Route::delete('/{slug}/delete/{id}', 'AdminController@deleteRecord');
    Route::get('/articles/{id}/tegs', 'AdminController@tegs');
    Route::get('/articles/{id}/add_tegs', 'AdminController@add_tegs');
    Route::post('/articles/{id}/save_tegs', 'AdminController@save_tegs');
    Route::delete('articles/{article_id}/delete_teg/{teg_id}', 'AdminController@delete_teg');
    Route::get('/{slug}/browse/policy', 'AdminController@comment_policy');
});

Auth::routes();


