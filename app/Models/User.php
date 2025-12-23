<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function assignedLoads()
    {
        return $this->hasMany(Load::class, 'assigned_driver_id');
    }

    public function locations()
    {
        return $this->hasMany(DriverLocation::class);
    }

    public function breadcrumbs()
    {
        return $this->hasMany(DriverBreadcrumb::class);
    }

    public function uploadedDocuments()
    {
        return $this->hasMany(LoadDocument::class, 'uploaded_by');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    public function isDispatcher(): bool
    {
        return $this->hasRole('Dispatcher');
    }

    public function isDriver(): bool
    {
        return $this->hasRole('Driver');
    }
}
