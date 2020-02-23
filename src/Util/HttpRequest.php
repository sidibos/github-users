<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: sidibos
 * Date: 22/02/2020
 * Time: 02:26
 */
namespace App\Util;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;
use App\Exception\HttpRequestException;

class HttpRequest
{
    public static function processResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $responseData = $response->toArray();
        if ($statusCode !== Response::HTTP_OK) {
            $message = $responseData['message'] . ': ' . $responseData['errors']['title'];
            throw new \Exception($statusCode, $message);
        }
        return $responseData;
    }

}

