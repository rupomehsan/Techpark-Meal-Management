<?php 
namespace App\Modules\Management\BatchManagement\Models;


use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{

    use SoftDeletes;

    protected $table = "batches";
    protected $guarded = [];


    public static $Model = \App\Modules\Management\BatchManagement\Models\Model::class;
    public static $userModel = \App\Modules\Management\UserManagement\User\Models\Model::class;


    protected static function booted()
    {
        static::created(function ($data) {
            $random_no = random_int(100, 999) . $data->id . random_int(100, 999);
            $slug = $data->batch_name . " " . $random_no;
            $data->slug = Str::slug($slug); //use Illuminate\Support\Str;
            if (strlen($data->slug) > 50) {
                $data->slug = substr($data->slug, strlen($data->slug) - 50, strlen($data->slug));
            }
            if (auth()->check()) {
                $data->creator = auth()->user()->id;
            }
            $data->save();
        });
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeInactive($q)
    {
        return $q->where('status', 'inactive');
    }
    public function scopeTrased($q)
    {
        return $q->onlyTrashed();
    }

    public function user (){
        return $this->belongsTo(self::$userModel, 'department_id', 'id');
    }


  
}