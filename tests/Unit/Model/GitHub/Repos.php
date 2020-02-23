<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: sidibos
 * Date: 23/02/2020
 * Time: 21:01
 */

namespace App\Tests\Unit\Model\GitHub;

use PHPUnit\Framework\TestCase;
use App\Model\GitHub\Repos as ReposModel;

class Repos extends TestCase
{
    private $reposModel;

    public function setUp(): void
    {
        $data = [
            'name'      => 'dummy repos',
            'language'  => 'PHP',
        ];
        $this->reposModel = new ReposModel($data);
    }

    public function testGetName()
    {
        $this->assertEquals('dummy repos', $this->reposModel->getName());
    }

    public function testGetLanguage()
    {
        $this->assertEquals('PHP', $this->reposModel->getLanguage());
    }
}
