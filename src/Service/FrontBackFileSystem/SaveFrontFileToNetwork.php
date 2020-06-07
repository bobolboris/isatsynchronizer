<?php

namespace App\Service\FrontBackFileSystem;

use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SaveFrontFileToNetwork implements SaveFrontFileInterface
{
    /** @var HttpClientInterface $httpClient */
    protected $httpClient;

    /**
     * SaveFrontFileToNetwork constructor.
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $path
     * @param string $content
     * @throws TransportExceptionInterface
     */
    public function saveFile(string $path, string $content): void
    {
        $formFields = [
            'file' => $content,
        ];
        $formData = new FormDataPart($formFields);
        $response = $this->httpClient->request('POST', $path, [
            'headers' => $formData->getPreparedHeaders()->toArray(),
            'body' => $formData->bodyToIterable(),
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new UploadException();
        }
    }

    /**
     * @param string $path
     * @throws TransportExceptionInterface
     */
    public function clearFolder(string $path): void
    {
        $formFields = [
            'action' => 'remove',
        ];
        $formData = new FormDataPart($formFields);
        $response = $this->httpClient->request('POST', $path, [
            'headers' => $formData->getPreparedHeaders()->toArray(),
            'body' => $formData->bodyToIterable(),
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new UploadException('The request failed');
        }
    }
}