<?php

namespace App\Repositories;

use App\Based\Contracts\Repository;
use App\Based\Support\Avatar;
use App\Support\SqlRawString;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository
{
    protected function getModelClass(): string
    {
        return User::class;
    }

    public function paginate(int $page): LengthAwarePaginator
    {
        return $this->newQuery()
            ->latest('id')
            ->paginate(10, ['*'], 'UsersList', $page);
    }

    public function liveFind(string $find): Collection
    {
        $findWithoutFirstSymbol = mb_substr($find, 1);

        return $this->newQuery()
            ->where(User::FIELD_IS_ACTIVE, true)
            ->where(function (Builder $query) use ($find, $findWithoutFirstSymbol) {
                $query
                    ->where(...SqlRawString::byLowerAndLike(User::FIELD_NAME, $find))
                    ->orWhere(...SqlRawString::byLowerAndLike(User::FIELD_NAME, $findWithoutFirstSymbol))
                    ->orWhere(...SqlRawString::byLowerAndLike(User::FIELD_PATRONYMIC, $find))
                    ->orWhere(...SqlRawString::byLowerAndLike(User::FIELD_PATRONYMIC, $findWithoutFirstSymbol))
                    ->orWhere(...SqlRawString::byLowerAndLike(User::FIELD_FAMILY, $find))
                    ->orWhere(...SqlRawString::byLowerAndLike(User::FIELD_FAMILY, $findWithoutFirstSymbol))
                    ->orWhere(...SqlRawString::byLowerAndLike(User::FIELD_POSITION, $find))
                    ->orWhere(...SqlRawString::byLowerAndLike(User::FIELD_POSITION, $findWithoutFirstSymbol));
            })
            ->get();
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where(User::FIELD_EMAIL, $email)->first();
    }

    public function create(array $data): User
    {
        return User::query()->create(array_merge(
            $data,
            [
                'is_active' => false,
                'password' => Hash::make($data['password']),
                'avatar_url' => Avatar::random(),
            ]
        ));
    }

    public function findActive(int $id): ?User
    {
        return $this->newQuery()
            ->where(User::FIELD_ID, $id)
            ->where(User::FIELD_IS_ACTIVE, true)
            ->first();
    }
}
