<?php

namespace Tests;

use App\Models\User;
use App\Models\UserIdentity;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    protected function createTestIdentity(array $identityOverrides = [], array $userOverrides = [], string $password = 'password'): UserIdentity
    {
        $id = $identityOverrides['id'] ?? $userOverrides['id'] ?? (string) Str::uuid();
        $email = $identityOverrides['email'] ?? $userOverrides['email'] ?? 'user-'.Str::lower(Str::random(8)).'@example.test';

        User::create(array_merge([
            'id' => $id,
            'email' => $email,
            'full_name' => 'Test User',
            'is_registration_complete' => true,
        ], $userOverrides));

        return UserIdentity::create(array_merge([
            'id' => $id,
            'email' => $email,
            'provider' => 'basic',
            'hash' => Hash::make($password),
            'is_verified' => true,
            'role' => 'user',
        ], $identityOverrides));
    }
}
