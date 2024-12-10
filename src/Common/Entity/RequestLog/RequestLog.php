<?php

namespace App\Common\Entity\RequestLog;

use App\Common\Enum\CommonEnum;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[ORM\Table(name: 'request_logs')]
#[ORM\Index(name: 'idx_username', fields: ['username'])]
#[ORM\Index(name: 'idx_url', fields: ['url'])]
#[ORM\Index(name: 'idx_path_info', fields: ['pathInfo'])]
#[ORM\Index(name: 'idx_created_at_date', fields: ['createdAtDate'])]
#[ORM\Index(name: 'idx_response_status_code', fields: ['responseStatusCode'])]
class RequestLog
{
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $id;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ['default' => CommonEnum::CURRENT_TIMESTAMP])]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAtDate;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?DateTime $endedAt = null;

    #[ORM\Column(nullable: true)]
    private ?string $username = null;

    #[ORM\Column(nullable: true)]
    private ?string $pathInfo = null;

    #[ORM\Column(nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $queryParams = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bodyParams = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $headers = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $response = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $responseStatusCode = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $responseHeaders = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): RequestLog
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAtDate(): DateTime
    {
        return $this->createdAtDate;
    }

    public function setCreatedAtDate(DateTime $createdAtDate): RequestLog
    {
        $this->createdAtDate = $createdAtDate;
        return $this;
    }

    public function getEndedAt(): ?DateTime
    {
        return $this->endedAt;
    }

    public function setEndedAt(?DateTime $endedAt): RequestLog
    {
        $this->endedAt = $endedAt;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): RequestLog
    {
        $this->username = $username;
        return $this;
    }

    public function getPathInfo(): ?string
    {
        return $this->pathInfo;
    }

    public function setPathInfo(?string $pathInfo): RequestLog
    {
        $this->pathInfo = $pathInfo;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): RequestLog
    {
        $this->url = $url;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): RequestLog
    {
        $this->token = $token;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): RequestLog
    {
        $this->content = $content;
        return $this;
    }

    public function getQueryParams(): ?string
    {
        return $this->queryParams;
    }

    public function setQueryParams(?string $queryParams): RequestLog
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    public function getBodyParams(): ?string
    {
        return $this->bodyParams;
    }

    public function setBodyParams(?string $bodyParams): RequestLog
    {
        $this->bodyParams = $bodyParams;
        return $this;
    }

    public function getHeaders(): ?string
    {
        return $this->headers;
    }

    public function setHeaders(?string $headers): RequestLog
    {
        $this->headers = $headers;
        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(?string $response): RequestLog
    {
        $this->response = $response;
        return $this;
    }

    public function getResponseStatusCode(): ?int
    {
        return $this->responseStatusCode;
    }

    public function setResponseStatusCode(?int $responseStatusCode): RequestLog
    {
        $this->responseStatusCode = $responseStatusCode;
        return $this;
    }

    public function getResponseHeaders(): ?string
    {
        return $this->responseHeaders;
    }

    public function setResponseHeaders(?string $responseHeaders): RequestLog
    {
        $this->responseHeaders = $responseHeaders;
        return $this;
    }
}