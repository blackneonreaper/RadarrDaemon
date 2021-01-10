<?php
declare(strict_types=1);

namespace RadarrDaemon\Transformer;

use RadarrDaemon\DTO\QueueItemDTO;

final class QueueItemTransformer
{
    /**
     * @param array $queueItem
     * @return QueueItemDTO
     */
    public function transform(array $queueItem): QueueItemDTO
    {
        return new QueueItemDTO(
            $queueItem['id'],
            $queueItem['movieId'] ?? 0,
            $queueItem['title'],
            $queueItem['size'],
            $queueItem['sizeleft'],
            $queueItem['status'],
            $queueItem['protocol'],
            $queueItem['downloadClient'],
            $queueItem['indexer'],
            $queueItem['outputPath'],
        );
    }
}
