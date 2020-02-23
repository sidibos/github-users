<?php declare(strict_types = 1);
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
    /**
     * GitHub API URI
     */
    private const API_URI                   = 'https://api.github.com';

    /**
     * Maximum number of repos returned by GitHub in a single request.
     */
    private const GITHUB_MAX_REPOS_PER_PAGE = 30;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient   = $httpClient;
        $this->logger       = $logger;

    }

    /**
     * Get User info from GitHub.
     *
     * @param string $username
     *
     * @return array
     *
     * @throws GitHubServiceException
     */
    public function getUserInfo(string $username): array
    {
        $endpoint = "/users/{$username}";
        try {
            $response   = $this->httpClient->request('GET', self::API_URI . $endpoint);
            $responseData = HttpRequest::processResponse($response);
        } catch (\Exception $ex) {
            throw new GitHubServiceException($ex->getMessage(), $ex->getCode() ?: 500);
        }
        return $responseData;
    }

    /**
     * Get User most popular language from GitHub.
     *
     * @param string $username
     *
     * @return string
     *
     * @throws GitHubServiceException
     */
    public function getUserPopularLanguage(string $username): string
    {
        try {
            $userInfo   = $this->getUserInfo($username);
            $reposUrl   = $userInfo['repos_url'];
            $languages  = [];
            $pageNo     = 1;

            while(true) {
                $response       = $this->httpClient->request('GET', $reposUrl);
                $responseData   = HttpRequest::processResponse($response);
                $this->extractUserLanguagesFromrepos($responseData, $languages);

                if (count($responseData) === self::GITHUB_MAX_REPOS_PER_PAGE) {
                    $pageNo++;
                    $reposUrl = $userInfo['repos_url'] . '?page=' . $pageNo;
                } else {
                    break;
                }
            }

        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            $this->logger->error($message, $ex->getTrace());
            throw new GitHubServiceException($message, $ex->getCode() ?: 500);
        }

        return $this->findUserPopularLanguage($languages);
    }

    /**
     * Find User popular programming language.
     *
     * @param array $languages
     *
     * @return string
     */
    private function findUserPopularLanguage(array $languages): string
    {
        $popular = ['count' => 0, 'lang' => ''];

        foreach($languages as $language => $count) {
            if ($count > $popular['count']) {
                $popular['count']   = $count;
                $popular['lang']    = $language;
            }
        }
        return strtoupper($popular['lang']);
    }

    /**
     * Extract User languages from Repos.
     *
     * @param array $data
     *
     * @param array $languages
     */
    private function extractUserLanguagesFromrepos(array $data, array &$languages): void
    {
        foreach($data as $repos) {
            if (isset($repos['language'])) {
                $language = strtolower($repos['language']);
                if (!isset($languages[$language])) {
                    $languages[$language] = 1;
                } else {
                    $languages[$language]++;
                }
            }
        }
    }
}