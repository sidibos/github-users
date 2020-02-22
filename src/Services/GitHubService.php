<?php
/**
 * Created by PhpStorm.
 * User: sidibos
 * Date: 22/02/2020
 * Time: 01:29
 */

namespace App\Services;

use App\Util\HttpRequest;
use Psr\Log\LoggerInterface;
use App\Contracts\GitHubServiceInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Exception\GitHubServiceException;

class GitHubService implements GitHubServiceInterface
{
    private const API_URI                   = 'https://api.github.com';
    private const GITHUB_MAX_REPOS_PER_PAGE = 30;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    private $logger;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient   = $httpClient;
        $this->logger       = $logger;
    }

    public function getUserInfo(string $username): array
    {
        $endpoint = "users/{$username}";
        try {
            $response   = $this->httpClient->request('GET', self::API_URI . $endpoint);
            $responseData = HttpRequest::processResponse($response);
        } catch (\Exception $ex) {
            throw new GitHubServiceException($ex->getMessage(), $ex->getCode() ?: 500);
        }
        return $responseData;
    }

    public function getUserPopularLanguage(string $username): string
    {
        try {
            $userInfo   = $this->getUserInfo($$username);
            $reposUrl   = $userInfo['repos_url'];
            $languages  = [];
            $pageNo     = 1;

            while(true) {
                $response       = $this->httpClient->request('GET', $reposUrl);
                $responseData   = HttpRequest::processResponse($response);
                $this->extractUserLanguages($responseData, $languages);

                if (count($responseData) === self::GITHUB_MAX_REPOS_PER_PAGE) {
                    $pageNo++;
                    $reposUrl = urlencode($userInfo['repos_url'] . "?page={$pageNo}");
                } else {
                    break;
                }
            }

        } catch (\Exception $ex) {
            throw new GitHubServiceException($ex->getMessage(), $ex->getCode() ?: 500);
        }

        return $this->getPopularLanguage($languages);
    }

    private function getPopularLanguage(array $languages): string
    {
        $popularLanguage = [];
        foreach($languages as $language) {
            if (empty($popularLanguage)) {
                $popularLanguage = $language;
            } else {
                [$lang, $count] = $language;
                [$curLang, $curCount] = $popularLanguage;

                if ($count > $curCount) {
                    $popularLanguage = $language;
                }
            }
        }

        return strtoupper(key($popularLanguage));
    }

    private function extractUserLanguages(array $data, array &$languages): void
    {
        foreach($data as $repos) {
            if (isset($repos['language'])) {
                $languages[strtolower($repos['language'])]++;
            }
        }
    }
}