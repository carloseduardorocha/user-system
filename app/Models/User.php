<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Passport\HasApiTokens;

use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class User
 *
 * @property int $id
 * @property string $uuid
 * @property string $email
 * @property string $password
 * @property string|null $deleted_at
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory; // @phpstan-ignore-line
    use SoftDeletes;
    use HasApiTokens;

    public const ID         = 'id';
    public const UUID       = 'uuid';
    public const NAME       = 'name';
    public const EMAIL      = 'email';
    public const PASSWORD   = 'password';
    public const DELETED_AT = 'deleted_at';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        self::UUID,
        self::EMAIL,
        self::NAME,
        self::PASSWORD,
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        self::DELETED_AT => 'datetime',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array<mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
