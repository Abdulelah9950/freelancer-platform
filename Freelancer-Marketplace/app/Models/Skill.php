<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the freelancers that have this skill.
     */
    public function freelancers()
    {
        return $this->belongsToMany(User::class, 'freelancer_skill', 'skill_id', 'freelancer_id');
    }

    /**
     * Get the jobs that require this skill.
     */
    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_skill', 'skill_id', 'job_id');
    }
}