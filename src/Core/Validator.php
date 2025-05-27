<?php
namespace App\Core;

class Validator
{
    public static function validatePassword(string $password): void
    {
        if (strlen($password) < 6) {
            throw new \RuntimeException('Пароль должен содержать минимум 6 символов');
        }
    }

    public static function validateUsername(string $username): void
    {
        if (strlen($username) < 3) {
            throw new \RuntimeException('Логин должен содержать минимум 3 символа');
        }
        
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            throw new \RuntimeException('Логин может содержать только буквы, цифры и подчеркивания');
        }
    }
}