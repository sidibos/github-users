<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: sidibos
 * Date: 23/02/2020
 * Time: 20:52
 */

namespace App\Tests\Unit\Model\GitHub;

use PHPUnit\Framework\TestCase;
use App\Model\GitHub\UserInfo as UserInfoModel;

class UserInfo extends TestCase
{
    private $userInfoModel;

    public function setUp(): void
    {
        $data = [
            'login'     => 'dummyUsername',
            'repos_url' => 'https://api.github.com/users/dummyUsername/repos'
        ];
        $this->userInfoModel = new UserInfoModel($data);
    }

    public function testGetUserLogin()
    {
        $this->assertEquals('dummyUsername', $this->userInfoModel->getLogin());
    }

    public function testGetUserReposUrl()
    {
        $reposUrl = 'https://api.github.com/users/dummyUsername/repos';
        $this->assertEquals($reposUrl, $this->userInfoModel->getRepos());
    }
}