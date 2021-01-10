<?php
declare(strict_types=1);

namespace RadarrDaemon\Collection;

use RadarrDaemon\DTO\QueueItemDTO;

final class QueueCollection
{
    /** @var QueueItemDTO[] */
    private $queue = [];

    public function addQueueItem(QueueItemDTO $queueItemDTO): void
    {
        $this->queue[] = $queueItemDTO;
    }

    /**
     * @return QueueItemDTO[]s
     */
    public function getItems(): array
    {
        return $this->queue;
    }
}
