<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'rut',
        'name',
        'email',
        'password',
        'perfil_id',
        'org_id',
        'plan_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
    public function isSuperAdmin()
    {
        return $this->perfil_id == 0;
    }

    public function isAdmin()
    {
        return $this->perfil_id == 1;
    }

    public function isCrc()
    {
        return $this->perfil_id == 3;
    }

    public function isOperator()
    {
        return $this->perfil_id == 5;
    }
// public function organization()
// {
//     return $this->belongsTo(Org::class, 'org_id'); // o cambia 'org_id' por la FK correcta si es distinta
// }
public function member()
{
    return $this->hasOne(Member::class, 'rut', 'rut')
        ->whereNotNull('rut'); // Por seguridad
}
}
