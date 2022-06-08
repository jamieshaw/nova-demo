<?php

namespace App\Models;

use Dotenv\Util\Regex;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Determine if user has "Super Admin" role (determined by regex).
     *
     * @return PhpOption\T|PhpOption\S|void
     */
    public function isSuperAdmin()
    {
        /* Pattern for applying "Super Admin" rights to user. */
        $pattern = '/^.*super.?admin.*$/i';

        foreach ($this->roles as $role) {
            return Regex::matches($pattern, $role->name)->success()->getOrElse(false);
        }
    }

    /**
     * Determine whether user has scope to access the requested resource.

     * @param string $scope_search
     * @param string $action_search
     * @return bool|void
     */
    public function hasScope(string $scope_search, string $action_search)
    {
        /* Quick pass if user is "Super Admin" */
        if ($this->isSuperAdmin())
            return true;

        foreach ($this->roles as $role) {

            /* Lookup permissions for provided key */
            $permissions = DB::table('scope_permissions')->where('name', $action_search)->first();

            /* Lookup scopes for provided key from user's roles */
            $scope = $role->scopes()->where('scope_key', $scope_search)->first();

            /* Stop if no scopes provided. */
            if (!isset($scope) || empty($scope))
                return false;

            /* Compare bitwise values */
            return $scope->scope_bit & $permissions->value;
        }
    }
}
