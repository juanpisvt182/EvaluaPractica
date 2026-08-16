<?php 
 
namespace App\Models; 
 
use Database\Factories\UserFactory; 
use Illuminate\Database\Eloquent\Attributes\Fillable; 
use Illuminate\Database\Eloquent\Attributes\Hidden; 
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable; 
 
#[Fillable(['name', 'email', 'password', 'rol'])] 
#[Hidden(['password', 'remember_token'])] 
class User extends Authenticatable 
{ 
    /** @use HasFactory<UserFactory> */ 
    use HasFactory, Notifiable; 
 
    /** 
     * Verifica si el usuario es aprendiz. 
     */ 
    public function esAprendiz(): bool 
    { 
        return $this->rol === 'aprendiz'; 
    } 
 
    /** 
     * Verifica si el usuario es instructor. 
     */ 
    public function esInstructor(): bool 
    { 
        return $this->rol === 'instructor'; 
    } 
 
    /** 
     * Verifica si el usuario es administrador. 
     */ 
    public function esAdministrador(): bool 
    { 
        return $this->rol === 'administrador'; 
    } 
 
    /** 
     * Verifica si el usuario posee alguno de los roles permitidos. 
     */ 
    public function tieneRol(string ...$roles): bool 
    { 
        return in_array($this->rol, $roles, true); 
    }

    /**
     * Intentos de evaluación realizados por el usuario.
     */
    public function intentos()
    {
        return $this->hasMany(Intento::class, 'user_id');
    }
 
    /** 
     * Define las conversiones automáticas del modelo. 
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
}