<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', [
    'as' => 'root.index',
    'uses' => 'RootController@index',
]);

// サービス一覧（カテゴリ、都道府県、タグ別に絞込み可）
Route::get('items', [
    'as' => 'item.index',
    'uses' => 'ItemController@index',
]);

// サービス詳細
Route::get('item/{item}', [
    'as' => 'item.show',
    'uses' => 'ItemController@show',
])->where('item', '[0-9]+');

// スタッフプロフィール
Route::get('staff/{staff}', [
    'as' => 'staff.show',
    'uses' => 'StaffController@show',
])->where('staff', '[0-9]+');

// お問い合わせ
Route::resource(
    'contact',
    'ContactController',
    ['only' => ['create', 'store']]
);


// 利用規約
Route::get('agreement', [
    'as' => 'static.agreement',
    function () {
        return view('static/agreement');
    }
]);

// プライバシーポリシー
Route::get('privacy', [
    'as' => 'static.privacy',
    function () {
        return view('static/privacy');
    }
]);


Route::group(['middleware' => ['guest:web']], function () {
});

Route::group(['middleware' => ['auth:web']], function () {
});


Route::group(['namespace' => 'Staff', 'prefix' => 'staff'], function () {
    Route::group(['middleware' => ['guest:staff']], function () {
        Route::get('signin', [
            'as' => 'staff.auth.signin_form',
            'uses' => 'AuthController@signinForm',
        ]);

        Route::post('signin', [
            'as' => 'staff.auth.signin',
            'uses' => 'AuthController@signin',
        ]);

        // パスワード再設定
        Route::get('reset_password/request', [
            'as' => 'staff.reset_password.request_form',
            'uses' => 'ResetPasswordController@requestForm',
        ]);

        Route::post('reset_password/request', [
            'as' => 'staff.reset_password.request',
            'uses' => 'ResetPasswordController@request',
        ]);

        Route::get('reset_password/reset/{token?}', [
            'as' => 'staff.reset_password.reset_form',
            'uses' => 'ResetPasswordController@resetForm',
        ]);

        Route::put('reset_password/reset', [
            'as' => 'staff.reset_password.reset',
            'uses' => 'ResetPasswordController@reset',
        ]);

        // 会員登録
        Route::get('user/create', [
            'as' => 'staff.user.create',
            'uses' => 'UserController@create',
        ]);

        Route::post('user', [
            'as' => 'staff.user.store',
            'uses' => 'UserController@store',
        ]);

        Route::get('user/confirmation/{token?}', [
            'as' => 'staff.user.confirmation',
            'uses' => 'UserController@confirmation',
        ]);
    });

    Route::group(['middleware' => ['auth:staff']], function () {

        Route::get('signout', [
            'as' => 'staff.auth.signout',
            'uses' => 'AuthController@signout',
        ]);

        Route::get('/', [
            'as' => 'staff.root.index',
            'uses' => 'RootController@index',
        ]);

        // マイページ
        Route::get('my', [
            'as' => 'staff.my.index',
            'uses' => 'MyController@index',
        ]);

        // 認証の必要なItemページ（作成、編集、削除）
        Route::resource(
            'item',
            'ItemController',
            ['except' => ['index', 'show']]
        );
    });
});

Route::group(['namespace' => '_Admin', 'prefix' => '_admin'], function () {

    Route::group(['middleware' => ['guest:admin']], function () {

        Route::get('signin', [
            'as' => '_admin.auth.signin_form',
            'uses' => 'AuthController@signinForm',
        ]);

        Route::post('signin', [
            'as' => '_admin.auth.signin',
            'uses' => 'AuthController@signin',
        ]);

    });


    Route::group(['middleware' => ['auth:admin']], function () {

        Route::get('signout', [
            'as' => '_admin.auth.signout',
            'uses' => 'AuthController@signout',
        ]);

        Route::get('/', [
            'as' => '_admin.root.index',
            'uses' => 'RootController@index',
        ]);

        // お知らせ
        Route::resource('notices', 'NoticeController', ['only' => [
            'show', 'index', 'store', 'create', 'edit', 'update', 'destroy',
        ]]);

        // カテゴリ
        Route::resource('categories', 'CategoryController', ['except' => [
            'show', 'index', 'create',
        ]]);

        Route::get('categories/{parent?}', [
            'as' => 'categories.index',
            'uses' => 'CategoryController@index',
        ])->where('parent', '[0-9]+');

        Route::get('categories/create/{parent?}', [
            'as' => 'categories.create',
            'uses' => 'CategoryController@create',
        ])->where('parent', '[0-9]+');

        // 管理者管理
        Route::resource('admins', 'AdminController');

        // スタッフ管理
        Route::resource('staffs', 'StaffController');

        Route::post('staffs/cancel/{staff?}', [
            'as' => 'staffs.cancel',
            'uses' => 'StaffController@cancel',
        ])->where('staff', '[0-9]+');

    });

});
