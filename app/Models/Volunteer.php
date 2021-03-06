<?php

namespace App\Models;

use EnesCakir\Helper\Traits\BaseActions;
use EnesCakir\Helper\Traits\Filterable;
use EnesCakir\Helper\Traits\HasMobile;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use BaseActions;
    use HasMobile;
    use Filterable;

    // Properties
    protected $table = 'volunteers';
    protected $fillable = [
        'first_name', 'last_name', 'email', 'mobile', 'city',
        'platform', 'notification_token', 'player_id', 'device_token'
    ];
    protected $appends = ['full_name'];

    // Relations
    public function children()
    {
        return $this->hasMany(Child::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        if (is_null($search)) {
            return;
        }

        $query->where(function ($query2) use ($search) {
            $query2->where('id', $search)
                ->orWhere('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('mobile', 'like', '%' . $search . '%')
                ->orWhere(\DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }
}
