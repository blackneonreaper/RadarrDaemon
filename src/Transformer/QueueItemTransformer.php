<?php
declare(strict_types=1);

namespace RadarrDaemon\Transformer;

use RadarrDaemon\DTO\QueueItemDTO;

final class QueueItemTransformer
{
    public function transform(array $queueItem): QueueItemDTO
    {
        return new QueueItemDTO(
            $queueItem['id'],
            $queueItem['movieId'],
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
