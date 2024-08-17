<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Events\UserSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Event;

class User extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    

    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'email',
        'photo',
        'type',
        'email',
        'password',
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

    public function details() {
        return $this->hasMany(Detail::class);
    }

    protected static function booted()
    {   
        static::saved(function ($user) {
            event(new UserSaved($user));
        });
    }


    static function prefixnames() {
        return ['Mr', 'Mrs', 'Ms'];
    }


    static function types() {
        return ['user', 'admin'];
    }

    public function getFullnameAttribute(){
        return $this->middlename ?  $this->firstname . ' ' .$this->middleinitial . ' ' . $this->lastname :  $this->firstname . ' ' . $this->lastname;
    }


    public function getMiddleinitialAttribute(){
        $middle =  $this->middlename;
        return  $middle ? strtoupper($middle[0]) : '';
    }

    public function getAvatarAttribute(){
        $photo =  $this->photo;
        return  $photo ? $photo : 'avatar.png';
    }
}
