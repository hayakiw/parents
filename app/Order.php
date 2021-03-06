<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Order extends Model
{
    use SoftDeletes;

    const ORDER_STATUS_NEW = 'new';
    const ORDER_STATUS_PAID = 'paid';
    const ORDER_STATUS_OK = 'ok';
    const ORDER_STATUS_NG = 'ng';
    const ORDER_STATUS_ENDED = 'ended';
    const ORDER_STATUS_CANCEL = 'cancel';

    protected static $status = [
        self::ORDER_STATUS_NEW => '新規',
        self::ORDER_STATUS_PAID => '進行中',
        self::ORDER_STATUS_OK => '契約中',
        self::ORDER_STATUS_NG => '不成立',
        self::ORDER_STATUS_ENDED => '完了',
        self::ORDER_STATUS_CANCEL => 'キャンセル',
    ];

    protected static $statusForStaff = [
        self::ORDER_STATUS_NEW => '新規',
        self::ORDER_STATUS_PAID => '進行中',
        self::ORDER_STATUS_OK => '契約中',
        self::ORDER_STATUS_NG => '不成立',
        self::ORDER_STATUS_ENDED => '完了',
        self::ORDER_STATUS_CANCEL => 'キャンセル',
    ];

    protected $fillable = [
        'user_id', 'staff_id', 'item_id',
        'title', 'hours', 'price',
        'prefer_at', 'prefer_at2', 'prefer_at3',
        'work_at',
        'status', 'comment', 'staff_comment', 'ordered_token',
        'fee', 'total_price', 'payment_at',
    ];

    protected static $meetingTypes = [
        '対面',
        '電話',
        'メッセージ',
    ];

    public static function getMeetingTypes()
    {
        return static::$meetingTypes;
    }

    public function getStatus()
    {
        return self::$status[$this->status];
    }

    public function getStatusForStaff()
    {
        return self::$statusForStaff[$this->status];
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function pay()
    {
        return $this->hasOne('App\Pay');
    }
}
