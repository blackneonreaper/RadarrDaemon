<?php
declare(strict_types=1);

namespace RadarrDaemon\DTO;

final class MovieDTO
{
    /** @var int */
    private $id;
    /** @var string */
    private $title;
    /** @var string */
    private $originalTitle;
    /** @var int */
    private $sizeOnDisk;
    /** @var string */
    private $status;
    /** @var string */
    private $plot;
    /** @var int */
    private $year;
    /** @var bool */
    private $hasFile;
    /** @var bool */
    private $monitored;
    /** @var bool */
    private $isAvailable;
    /** @var string */
    private $folderName;
    /** @var bool */
    private $qualityCutoffMet;

    /**
     * MovieDTO constructor.
     * @param int $id
     * @param string $title
     * @param string $originalTitle
     * @param int $sizeOnDisk
     * @param string $status
     * @param string $plot
     * @param int $year
     * @param bool $hasFile
     * @param bool $monitored
     * @param bool $isAvailable
     * @param string $folderName
     * @param bool $qualityCutoffMet
     */
    public function __construct(
        int $id,
        string $title,
        string $originalTitle,
        int $sizeOnDisk,
        string $status,
        string $plot,
        int $year,
        bool $hasFile,
        bool $monitored,
        bool $isAvailable,
        string $folderName,
        bool $qualityCutoffMet
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->originalTitle = $originalTitle;
        $this->sizeOnDisk = $sizeOnDisk;
        $this->status = $status;
        $this->plot = $plot;
        $this->year = $year;
        $this->hasFile = $hasFile;
        $this->monitored = $monitored;
        $this->isAvailable = $isAvailable;
        $this->folderName = $folderName;
        $this->qualityCutoffMet = $qualityCutoffMet;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getOriginalTitle(): string
    {
        return $this->originalTitle;
    }

    /**
     * @return int
     */
    public function getSizeOnDisk(): int
    {
        return $this->sizeOnDisk;
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
    public function getPlot(): string
    {
        return $this->plot;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return bool
     */
    public function isHasFile(): bool
    {
        return $this->hasFile;
    }

    /**
     * @return bool
     */
    public function isMonitored(): bool
    {
        return $this->monitored;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    /**
     * @return string
     */
    public function getFolderName(): string
    {
        return $this->folderName;
    }

    /**
     * @return bool
     */
    public function isQualityCutoffMet(): bool
    {
        return $this->qualityCutoffMet;
    }
}
