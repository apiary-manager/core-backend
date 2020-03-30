<?php

namespace Tests\Feature;

use App\Support\Avatar;
use App\User;
use Tests\BaseTestCase;

class AvatarTest extends BaseTestCase
{
    public function testGetByUser()
    {
        $user = User::where('gender', User::GENDER_MALE)->first();

        $avatar = Avatar::byUser($user);
        $condition = preg_match('/males/i', $avatar) > 0;

        self::assertIsString($avatar);
        self::assertTrue($condition);
    }
}
