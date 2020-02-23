<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: sidibos
 * Date: 23/02/2020
 * Time: 20:32
 */

namespace App\Model\GitHub;

class UserInfo
{
    /**
     * @var mixed|null
     */
    private $login;

    /**
     * @var mixed|null
     */
    private $reposUrl;

    public function __construct(array $data = null)
    {
        if (empty($data)) {
            return;
        }
        $this->login    = $data['login'] ?? null;
        $this->reposUrl = $data['repos_url'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @return null|string
     */
    public function getReposUrl(): ?string
    {
        return $this->reposUrl;
    }
}
