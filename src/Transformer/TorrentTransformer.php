<?php
declare(strict_types=1);

namespace RadarrDaemon\Transformer;

use RadarrDaemon\DTO\TorrentDTO;
use Transmission\Model\Torrent;

final class TorrentTransformer
{
    /**
     * @param Torrent $torrent
     * @return TorrentDTO
     *
     */
    public function transform(Torrent $torrent): TorrentDTO
    {
        return new TorrentDTO(
            $torrent->getId(),
            $torrent->getName(),
            $torrent->getHash(),
            $torrent->isFinished(),
            date('Y-m-d', $torrent->getStartDate()),
            $torrent->getPercentDone()
        );
    }
}
