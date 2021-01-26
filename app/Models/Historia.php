<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model dotyczący historii
 *
 * @property integer $id klucz główny rekordu w tabeli
 * @property integer $obiekt_id
 * @property string $status_name
 * @property \Illuminate\Support\Carbon $created_at datetime
 */
class Historia extends Model
{
    use HasFactory;

    /**
     * Stała określająca status jako nowy
     */
    const STATUS_NOWY = 'NOWY';

    /**
     * Stała określa status jako edytowany
     */
    const STATUS_EDYCJA = 'EDYTOWANY';

    /**
     *  Stała określa status jako usunięty
     */
    const STATUS_USUNIETY = 'USUNIĘTY';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'historia';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status_name'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'obiekt_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    public $timestamps = false;

    public function getCreatedAtAttribute($value)
    {
        if ($value == null) {
            return false;
        }

        $value = Carbon::parse($value)->format('Y-m-d H:i:s');

        return $value;
    }
}
