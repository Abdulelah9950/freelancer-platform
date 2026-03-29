<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the jobs posted by the client.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class, 'client_id');
    }

    /**
     * Get the proposals submitted by the freelancer.
     */
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'freelancer_id');
    }

    /**
     * Get the skills of the freelancer.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'freelancer_skill', 'freelancer_id', 'skill_id');
    }

    /**
     * Get the contracts where user is client.
     */
    public function clientContracts()
    {
        return $this->hasMany(Contract::class, 'client_id');
    }

    /**
     * Get the contracts where user is freelancer.
     */
    public function freelancerContracts()
    {
        return $this->hasMany(Contract::class, 'freelancer_id');
    }

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is freelancer.
     *
     * @return bool
     */
    public function isFreelancer(): bool
    {
        return $this->role === 'freelancer';
    }

    /**
     * Check if user is client.
     *
     * @return bool
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}