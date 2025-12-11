<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = ['token', 'user_id', 'expires_at'];
    
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function isValid()
    {
        return $this->expires_at > now();
    }
    
    public function isExpired()
    {
        return $this->expires_at < now();
    }
    
    /* public function markAsUsed()
    {
        $this->delete();
    }
    
    public function isUsed()
    {
        return $this->trashed();
    } */
}
