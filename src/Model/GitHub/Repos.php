<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: sidibos
 * Date: 23/02/2020
 * Time: 20:44
 */

namespace App\Model\GitHub;


class Repos
{
    /**
     * @var mixed|null
     */
    private $name;

    /**
     * @var mixed|null
     */
    private $language;

    public function __construct(array $data = null)
    {
        if (empty($data)) {
            return;
        }
        $this->name = $data['name'] ?? null;
        $this->language = $data['language'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }
}
