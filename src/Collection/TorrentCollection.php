<?php
declare(strict_types=1);

namespace RadarrDaemon\Collection;

use RadarrDaemon\DTO\TorrentDTO;

final class TorrentCollection
{
    /** @var TorrentDTO[] */
    private $torrentList = [];

    public function addTorrent(TorrentDTO $torrentDTO): void
    {
        $this->torrentList[] = $torrentDTO;
    }

    /**
     * @return TorrentDTO[]
     */
    public function getItems(): array
    {
        return $this->torrentList;
    }
}
