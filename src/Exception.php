<?php

namespace Mjml;

class Exception extends \Exception
{
    /**
     * @var string|null
     */
    private $requestId;

    /**
     * @var \DateTime|null
     */
    private $startedAt;

    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null, string $requestId = null, \DateTimeInterface $startedAt = null)
    {
        $this->requestId = $requestId;
        $this->startedAt = $startedAt;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string|null
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartedAt(): ?string
    {
        return $this->startedAt;
    }
}
