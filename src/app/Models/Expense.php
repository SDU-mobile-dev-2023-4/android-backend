<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="Expense",
 *     title="Expense schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Expense id",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="group_id",
 *         type="integer",
 *         description="Group id",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="payer_id",
 *         type="integer",
 *         description="Payer id",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="created_by",
 *         type="integer",
 *         description="Creator id",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Expense name",
 *         example="Pizza",
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="integer",
 *         description="Expense price",
 *         example="100",
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Date and time of last update",
 *         example="2020-01-01 00:00:00"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Date and time of creation",
 *         example="2020-01-01 00:00:00"
 *     ),
 * )
 */

class Expense extends Model
{
    use HasApiTokens, HasFactory;


    protected $fillable = [
        'name',
        'amount',
        'group_id',
        'user_id',
    ];

    protected $hidden = [

    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
