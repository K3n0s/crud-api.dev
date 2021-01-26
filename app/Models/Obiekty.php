<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model dotyczący obiektów
 *
 * @property integer $id klucz główny rekordu w tabeli
 * @property integer $status
 * @property \Illuminate\Support\Carbon $created_at datetime
 */
class Obiekty extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'obiekty';

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
        'numer',
        'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    public $timestamps = false;

    //------------ Relation ------------//

    /**
     * Relacja Obiekt historia historia jest sortowana po dacie utworzenia, aby przypisać aktualny status dla obiektu
     *
     * @return HasMany
     */
    public function historia() : HasMany
    {
        return $this->hasMany(Historia::class, 'obiekt_id', 'id')->orderBy('created_at', 'desc');
    }

    //------------ Function ------------//

    /**
     * Funkcja pobiera wszystkie obiekty w relacji z historia
     *
     * @return Collection
     */
    public static function getAllObiekty() : Collection
    {
        return self::with('historia')->get();
    }

    /**
     * Funkcja tworząca zapytanie
     *
     * @param boolean $withOutHistory
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function ObiektyQuery($withOutHistory = false)
    {
        $mainQuery = self::query();

        return $withOutHistory ? $mainQuery : $mainQuery->with('historia');
    }

    /**
     * Funkcja dodaje do zapytania where
     *
     * @param int $numer
     * @return Collection
     */
    public static function findByNumer($numer)
    {
        return self::ObiektyQuery()->where('numer', $numer)->get();
    }

    /**
     * Funkcja wyszukujaca rekordy według daty
     *
     * @param string $date
     * @return Collection
     */
    public static function findByDate($date)
    {
        $data = Carbon::parse($date)->format('Y-m-d');

        return self::ObiektyQuery()->whereDate('created_at', $data)->get();
    }

    /**
     * Funkcja wyszukujaca wedlug aktualnego statusu
     *
     * @param string $status
     * @return Collection
     */
    public static function findByStatus($status)
    {
        $status = strtoupper($status);

        return self::ObiektyQuery()->where('status', $status)->get();
    }

    public static function findByHistoryStatus($status)
    {
        return self::ObiektyQuery(true)->whereHas('historia', function ($subquery) use ($status) {
            $subquery->where('status_name', $status);
            $subquery->orderBy('created_at', 'desc');
        })->with('historia')->get();

    }

    /**
     * Funkcja tworzy atrybut na modelu aktualny_status i wyświetla aktualny status jako text
     *
     * @param string $value
     * @return string
     */
    public function getStatusAttribute($value) : string
    {
        $value = $this->historia->first()->status_name;

        return $value;
    }

    public function getCreatedAtAttribute($value)
    {
        if ($value == null) {
            return false;
        }

        $value = Carbon::parse($value)->format('Y-m-d H:i:s');

        return $value;
    }
}
