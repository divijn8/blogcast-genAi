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
        'name',
        'email',
        'password',
        'user_token'
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
            'password' => 'hashed',
        ];
    }

    public function getUserProfileAttribute() {
        $url = "https://ui-avatars.com/api/?name={$this->name}&background=random&rounded=true&bold=true&format=svg";
        return $this->profile_pic ? "storage/{$this->profile_pic}" : $url;
    }

    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(){
        return $this->subscriptions()->where('status','active')->first();
    }

    public function canGenerateArticle() {
        if(!$this->activeSubscription()) {
            //Free user - limit to 3 articles
            return $this->articles_generated < 3;
        }
        return $this->activeSubscription()->articles_remaining > 0;
    }
}
