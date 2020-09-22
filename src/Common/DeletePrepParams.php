<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

class DeletePrepParams
{
    private array $id;
    private array $body;

    /**
     * @return array
     */
    public function getId(): array
    {
        return $this->id;
    }

    /**
     * @param array $id
     * @return DeletePrepParams
     */
    public function setId(array $id): DeletePrepParams
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @param array $body
     * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
     */
    public function setBody(array &$body): void
    {
        $this->body = $body;
    }
}