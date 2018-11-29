<?php

namespace LaravelDay\Article;


final class Article
{
    /** @var string */
    private $title;
    /**
     * @var string
     */
    private $body;
    /**
     * @var \DateTime
     */
    private $creationDate;
    /**
     * @var int
     */
    private $id;

    public function __construct(int $id, string $title, string $body, \DateTimeImmutable $creationDate)
    {

        $this->title = $title;
        $this->body = $body;
        $this->creationDate = $creationDate;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTimeImmutable
    {
        return $this->creationDate;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


}