<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //用户更新时的权限验证
    public function update(User $currentUser,User $user){

            return $currentUser->id === $user->id;
    }

    // 删除用户授权(只有当前用户拥有管理员权限且删除的用户不是自己时)
    public function destroy(User $currentUser,User $user){
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
