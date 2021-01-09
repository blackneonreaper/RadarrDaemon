<?php
declare(strict_types=1);

namespace RadarrDaemon\DTO;

final class QueueItemDTO
{
    public const STATUS_DOWNLOADING = 'downloading';

    /** @var int */
    private $id;
    /** @var int */
    private $movieId;
    /** @var string */
    private $title;
    /** @var float */
    private $size;
    /** @var float */
    private $sizeLeft;
    /** @var string */
    private $status;
    /** @var string */
    private $protocol;
    /** @var string */
    private $downloadClient;
    /** @var string */
    private $indexer;
    /** @var string */
    private $outputPath;

    /**
     * QueueItemDTO constructor.
     * @param int $id
     * @param int $movieId
     * @param string $title
     * @param float $size
     * @param float $sizeLeft
     * @param string $status
     * @param string $protocol
     * @param string $downloadClient
     * @param string $indexer
     * @param string $outputPath
     */
    public function __construct(
        int $id,
        int $movieId,
        string $title,
        float $size,
        float $sizeLeft,
        string $status,
        string $protocol,
        string $downloadClient,
        string $indexer,
        string $outputPath
    ) {
        $this->id = $id;
        $this->movieId = $movieId;
        $this->title = $title;
        $this->size = $size;
        $this->sizeLeft = $sizeLeft;
        $this->status = $status;
        $this->protocol = $protocol;
        $this->downloadClient = $downloadClient;
        $this->indexer = $indexer;
        $this->outputPath = $outputPath;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getMovieId(): int
    {
        return $this->movieId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return float
     */
    public function getSize(): float
    {
        return $this->size;
    }

    /**
     * @return float
     */
    public function getSizeLeft(): float
    {
        return $this->sizeLeft;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    public function getDownloadClient(): string
    {
        return $this->downloadClient;
    }

    /**
     * @return string
     */
    public function getIndexer(): string
    {
        return $this->indexer;
    }

    /**
     * @return string
     */
    public function getOutputPath(): string
    {
        return $this->outputPath;
    }
}
