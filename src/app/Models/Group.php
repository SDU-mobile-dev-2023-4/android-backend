<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *  schema="Group",
 *  title="Group schema",
 *  @OA\Property(
 *      property="id",
 *      type="integer",
 *      description="Group id",
 *      example="1"
 *  ),
 *  @OA\Property(
 *      property="name",
 *      type="string",
 *      description="Group name",
 *      example="Group 1"
 *  ),
 *  @OA\Property(
 *      property="created_at",
 *      type="string",
 *      format="date-time",
 *      description="Date and time of creation",
 *      example="2020-01-01 00:00:00"
 *  ),
 *  @OA\Property(
 *      property="updated_at",
 *      type="string",
 *      format="date-time",
 *      description="Date and time of last update",
 *      example="2020-01-01 00:00:00"
 *  )
 * )    
 */
class Group extends Model
{
    use HasApiTokens, HasFactory;


    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'pivot',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function scopeUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
}
