<?php
declare(strict_types=1);

namespace RadarrDaemon\DTO;

final class TorrentDTO
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $hash;
    /** @var bool */
    private $finished;
    /** @var string */
    private $startDate;
    /** @var float */
    private $percentDone;

    /**
     * TorrentDTO constructor.
     * @param int $id
     * @param string $name
     * @param string $hash
     * @param bool $finished
     * @param string $startDate
     * @param float $percentDone
     */
    public function __construct(
        int $id,
        string $name,
        string $hash,
        bool $finished,
        string $startDate,
        float $percentDone
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->hash = $hash;
        $this->finished = $finished;
        $this->startDate = $startDate;
        $this->percentDone = $percentDone;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->finished;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @return float
     */
    public function getPercentDone(): float
    {
        return $this->percentDone;
    }

}
