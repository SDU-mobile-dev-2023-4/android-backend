<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 *  @OA\Schema(
 *      schema="created_at",
 *      type="string",
 *      format="date-time",
 *      description="Date and time of creation",
 *      example="2020-01-01 00:00:00"
 *  ),
 *  @OA\Schema(
 *      schema="updated_at",
 *      type="string",
 *      format="date-time",
 *      description="Date and time of last update",
 *      example="2020-01-01 00:00:00"
 *  )
 * )    
 */
class Group extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
