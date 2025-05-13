<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    protected $fillable = [
        'tipo_usuario', 
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'password' => 'hashed',
        ];
    }

    public function doador(): HasOne
    {
        return $this->hasOne(Doador::class, 'id_user', 'id_user');
    }

    public function notificacoes()
{
    return $this->hasMany(\App\Models\Notificacao::class, 'id_user');
}
    public function agendamentos() {
        return $this->hasManyThrough(
            Agendamento::class,
            Doador::class,
            'id_user', 
            'id_doador' 
        );
    }
 
    public function centro(): HasOne
    {
        return $this->hasOne(centro::class, 'id_user', 'id_user');
    }

}
