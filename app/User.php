<?php

namespace App;

use App\Models\Role;
use App\Models\RolePrefix;
use App\Models\UserRole;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'avatar', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getNameAttribute($value)
    {
        return $this->username;
    }

    /**
     * Add a mutator to ensure hashed passwords
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * @return bool
     */
    public function isAvatar() : bool
    {
        if (!empty($this->avatar) && file_exists(public_path() . '/storage/images/users/' . md5($this->id) .  '/' . $this->avatar)) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getSmallAvatarAttribute() : string
    {
        return $this->getImgRequest('small');
    }

    /**
     * @return string
     */
    public function getBigAvatarAttribute() : string
    {
        return $this->getImgRequest('big');
    }

    /**
     * @return string
     */
    public function getNormalAvatarAttribute() : string
    {
        return $this->getImgRequest('normal');
    }

    private function getImgRequest(string $size) : string
    {
        $img = '/storage/images/users/' . md5($this->id) . '/' . $size .'_' . $this->avatar;
        if (!empty($this->avatar) && file_exists(public_path() . $img)) {
            return $img;
        }

        return asset('/images/users/' . $size . '.jpg');
    }

    /**
     * Return role name
     *
     * @return string
     */
    public function getRoleNameAttribute() : string
    {
        switch ($this->role) {
            case 1:
                return 'Admin';
                break;
            case 2:
                return 'Manager';
                break;
        }

        return 'User';
    }

    /**
     * @return string
     */
    private function f_patch() : string
    {
        return '/images/users/' . md5($this->id) . '/' . mb_strtolower($this->username);
    }

    /**
     * Return boolen of role for action
     *
     * @param string $action
     * @return bool
     */
    public function isRoleAction( string $action) : bool
    {
        // If user is admin
        if (Auth::check() && Auth::user()->role == 1) {
            return true;
        }

        $action_array = explode('_', $action);

        if (count($action_array) == 2) {
            // Get Role prefix
            if ($role_prefix_id = RolePrefix::where('prefix', $action_array[0])->value('id')) {
                // Get Role
                if ($rele_id = Role::where([
                    ['role_prefix', '=', $role_prefix_id],
                    ['alias', '=', $action_array[1]],
                ])->value('id')) {
                    return (bool)UserRole::where([
                        ['user_id', '=', $this->id],
                        ['role_id', '=', $rele_id]
                    ])->first();
                }
            }
        }

        return false;
    }
}
