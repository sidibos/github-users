<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: sidibos
 * Date: 22/02/2020
 * Time: 01:30
 */
namespace App\Contracts;

use App\Model\GitHub\UserInfo as UserInfoModel;
use App\Exception\GitHubServiceException;

interface GitHubServiceInterface
{
    /**
     * Get User info from GitHub.
     *
     * @param string $username
     *
     * @return array
     *
     * @throws GitHubServiceException
     */
    public function getUserInfo(string $username): UserInfoModel;

    /**
     * Get User most popular language from GitHub.
     *
     * @param string $username
     *
     * @return string
     *
     * @throws GitHubServiceException
     */
    public function getUserPopularLanguage(string $username): string;
}