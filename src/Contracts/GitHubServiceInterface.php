<?php
/**
 * Created by PhpStorm.
 * User: sidibos
 * Date: 22/02/2020
 * Time: 01:30
 */
namespace App\Contracts;

interface GitHubServiceInterface
{
    public function getUserInfo(string $username): array;

    public function getUserPopularLanguage(string $username): string;
}