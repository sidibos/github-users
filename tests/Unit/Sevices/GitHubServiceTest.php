<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: sidibos
 * Date: 23/02/2020
 * Time: 19:34
 */
namespace App\Tests\Unit\Services;

use App\Services\GitHubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use App\Exception\GitHubServiceException;

class GitHubServiceTest extends TestCase
{
    private $gitHubService;

    public function setUp(): void
    {
        $this->loggerMock       = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $this->gitHubService = new GitHubService(HttpClient::create(), $this->loggerMock);
    }

    public function testGetUserInfo()
    {
        $userInfoModel = $this->gitHubService->getUserInfo('sidibos');

        $this->assertEquals('sidibos', $userInfoModel->getLogin());
        $this->assertNotNull($userInfoModel->getReposUrl());
    }

    public function testGetInvalidUserInfo()
    {
        $this->expectException(GitHubServiceException::class);
        $userInfo = $this->gitHubService->getUserInfo('dummyUsernameXXX');
    }

    public function testGetUserPopularLanguage()
    {
        $popularLanguage = $this->gitHubService->getUserPopularLanguage('sidibos');
        $this->assertEquals('PHP', $popularLanguage);
    }

}