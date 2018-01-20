<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //--------------------------------------------------
        // テーブル作成
        //--------------------------------------------------
        // 管理者
        Schema::create('admins', function (Blueprint $t) {
            $t->bigIncrements('id');

            $t->string('email');
            $t->string('password', 255);

            $t->rememberToken();

            $t->timestamps();
            $t->softDeletes();
        });

        // スタッフ
        Schema::create('staffs', function (Blueprint $t) {
            $t->bigIncrements('id');

            $t->string('email');
            $t->string('password', 255);

            $t->string('name', 255);
            $t->text('description')->comment('自己紹介');
            $t->string('area', 255)->comment('エリア');

            $t->rememberToken();

            $t->string('confirmation_token')->nullable()->comment('ユーザー登録時トークン');
            $t->datetime('confimarted_at')->nullable()->comment('ユーザー有効日付');
            $t->datetime('confirmation_sent_at')->nullable()->comment('ユーザー登録メール送信日時');

            $t->string('reset_password_token')->nullable()->comment('パスワード再設定用トークン');
            $t->datetime('reset_password_sent_at')->nullable()->comment('パスワード再設定のメール送信日時');

            $t->string('change_email')->nullable()->comment('変更後メールアドレス');
            $t->string('change_email_token')->nullable()->comment('メールアドレス変更用トークン');
            $t->datetime('change_email_sent_at')->nullable()->comment('メールアドレス変更のメール送信日時');

            $t->string('canceled_reason')->nullable()->comment('退会理由');
            $t->string('canceled_other_reason')->nullable()->comment('退会理由その他');
            $t->datetime('canceled_at')->nullable();

            $t->timestamps();
            $t->softDeletes();
        });

        // サービス
        Schema::create('items', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->bigInteger('staff_id')->unsigned()->comment('ユーザーID');
            $t->bigInteger('category_id')->unsigned()->comment('カテゴリID');

            $t->string('title', 255)->comment('タイトル');
            $t->string('image', 255)->comment('画像');

            $t->string('hours', 10)->comment('販売時間');
            $t->string('price', 10)->comment('1時間あたりの価格');
            $t->string('max_hours', 10)->comment('購入可能な時間');
            $t->string('area', 255)->comment('エリア');
            $t->string('location', 255)->comment('場所の詳細（例: 米子、Skype、電話、メッセージなど）');

            $t->text('description')->comment('詳細説明');

            $t->timestamps();
            $t->softDeletes();
        });

        // サービス希望形式
        Schema::create('item_meeting_types', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->bigInteger('item_id')->unsigned()->comment('アイテムID');
            $t->string('name')->comment('希望形式名称');

            $t->timestamps();
        });

        // オーダー
        Schema::create('orders', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->bigInteger('item_id')->unsigned()->comment('カテゴリID');

            $t->string('title', 255)->comment('タイトル');

            $t->string('hours', 10)->comment('時間');
            $t->string('price', 10)->comment('価格');
            $t->datetime('use_at')->comment('利用日時');

            $t->string('status', 10)->comment('ステータス');

            $t->text('comment')->comment('コメント');

            $t->timestamps();
            $t->softDeletes();
        });

        // カテゴリ
        Schema::create('categories', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->bigInteger('parent_id')->unsigned()->nullable()->comment('親カテゴリID');
            $t->string('name')->comment('カテゴリ名称');

            $t->timestamps();
            $t->softDeletes();
        });

        // お知らせ
        Schema::create('notices', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('title')->comment('タイトル');
            $t->text('content')->comment('内容');
            $t->datetime('start_at')->comment('掲載 開始日時');
            $t->datetime('end_at')->comment('掲載 終了日時');

            $t->timestamps();
            $t->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //--------------------------------------------------
        //テーブル削除
        //--------------------------------------------------
        Schema::dropIfExists('admins');
        Schema::dropIfExists('staffs');
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_meeting_types');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('notices');
    }
}
