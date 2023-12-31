<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *  schema="User",
 *  title="User schema",
 *  @OA\Property(
 *      property="id",
 *      type="integer",
 *      description="User id",
 *      example=1,
 *      nullable=false
 *  ),
 * 	@OA\Property(
 * 		property="name",
 * 		type="string",
 *      description="User name",
 *      example="John Doe",
 *      nullable=false
 * 	),
 *  @OA\Property(
 *      property="email",
 *      type="string",
 *      description="User email",
 *      example="demo@example.com",
 *      nullable=false
 *  ),
 *  @OA\Property(
 *    property="email_verified_at",
 *    type="string",
 *    example="2021-01-01 00:00:00",
 *    description="User email verified at",
 *    default=null,
 *    nullable=true
 *  ),
 *  @OA\Property(
 *      property="created_at",
 *      type="string",
 *      description="User created at",
 *      example="2021-01-01 00:00:00",
 *      nullable=false
 *  ),
 *  @OA\Property(
 *      property="updated_at",
 *      type="string",
 *      description="User updated at",
 *      example="2021-01-01 00:00:00",
 *      nullable=false
 *  ),   
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The groups that belong to the user.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * The expenses that belong to the user.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Method automaticly encrypts user passwords when a user is created.
     *
     * @param $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
