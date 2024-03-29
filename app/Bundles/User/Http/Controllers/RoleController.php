<?php

namespace App\Bundles\User\Http\Controllers;

use App\Based\Contracts\Controller;
use App\Bundles\User\Models\Permission;
use App\Bundles\User\Models\Role;
use App\Bundles\User\Repositories\RoleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class RoleController extends Controller
{
    public const PERMISSIONS = [
        'index' => Permission::ROLE_LIST_VIEW,
    ];

    private $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить роли, доступные для регистрации
     * @tag User
     */
    public function getRolesForSignUp(): Collection
    {
        return $this->repository->getAllowedForSignUp();
    }

    /**
     * @tag User
     * @return LengthAwarePaginator<Role>
     */
    public function index(int $page = 1): LengthAwarePaginator
    {
        return $this->repository->paginate($page);
    }

    /**
     * Разрешить регистрацию пользователя с ролью $role
     * @tag User
     */
    public function attachAllowedForSignUp(Role $role): Role
    {
        return $role->changeAllowedForSignUp(true);
    }

    /**
     * Запретить регистрацию пользователя с ролью $role
     * @tag User
     */
    public function detachAllowedForSignUp(Role $role): Role
    {
        return $role->changeAllowedForSignUp(false);
    }
}
