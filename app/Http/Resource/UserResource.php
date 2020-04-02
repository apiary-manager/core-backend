<?php

namespace App\Http\Resource;

use App\Repositories\DialogMessageRepository;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * @mixin User
 * @extends JsonResource<User>
 *
 * @OA\Schema(type="object")
 */
class UserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     *
     * @OA\Property(property="id", type="integer")
     */
    public function toArray($request): array
    {
        $permissions = Collection::make();
        foreach ($this->roles as $role) {
            $permissions = $permissions->merge($role->permissions);
        }

        $this->loadMissing('notifications');

        $messageUnReadCount = 0;
        $messages = DialogMessageRepository::findRecievedMessagesByCurrentUser();
        foreach ($messages as $message) {
            if ($message->isNotRead()) {
                $messageUnReadCount++;
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'patronymic' => $this->patronymic,
            'family' => $this->family,
            'position' => $this->position,
            'roles' => RoleResource::collection($this->roles),
            'permissions' => PermissionResource::collection($permissions),
            'notifications' => UserNotificationResource::collection($this->notifications),
            'notifications_unread_count' => $this->getUnreadNotificationsCount(),
            'avatar_url' => $this->getAvatarUrl(),
            'is_active' => $this->is_active,
            'email' => $this->email,
            'messages' => $messages,
            'messages_unread_count' => $messageUnReadCount,
        ];
    }
}
