<?php
declare(strict_types=1);

namespace RadarrDaemon\Command;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use RadarrDaemon\Collection\MovieCollection;
use RadarrDaemon\DTO\QueueItemDTO;
use RadarrDaemon\Service\Radarr;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class MovieSearch
{
    /** @var Radarr */
    private $radarrService;

    /**
     * MovieSearch constructor.
     * @param Radarr $radarrService
     */
    public function __construct(Radarr $radarrService)
    {
        $this->radarrService = $radarrService;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        $searchAmount = (int)$_ENV['SEARCH_AMOUNT'];
        $maxDownloading = (int)$_ENV['MAX_DOWNLOADING'];
        $downloading = 0;
        try {
            $incompleteMovies = $this->radarrService->getMovies([
                'cutoff' => false
            ]);

            if ($incompleteMovies->getItems() === null) {
                return;
            }

            $currentMovieQueue = $this->radarrService->getQueueDetails();
            foreach ($currentMovieQueue->getItems() as $queueItemDTO) {
                if ($queueItemDTO->getStatus() === QueueItemDTO::STATUS_DOWNLOADING) {
                    $downloading++;
                    $incompleteMovies->removeByMovieId($queueItemDTO->getMovieId());
                }
            }

            if ($maxDownloading <= $downloading) {
                return;
            }

            if ($searchAmount > ($maxDownloading - $downloading)) {
                $searchAmount = $maxDownloading - $downloading;
            }

            $randomNumbers = $this->generateRandomNumbers($searchAmount, count($incompleteMovies->getItems()));
            $searchMovieCollection = new MovieCollection();
            foreach ($randomNumbers as $randomNumber) {
                $searchMovieCollection->addMovie($incompleteMovies->getItems()[$randomNumber]);
            }

            $this->radarrService->searchMovies($searchMovieCollection);
        } catch (Exception $exception) {
            $output->writeln($exception->getMessage());
        } catch (GuzzleException $exception) {
            $output->writeln($exception->getMessage());
        }
    }

    /**
     * @param int $amountToGenerate
     * @param int $maxValue
     * @return array
     * @throws Exception
     */
    private function generateRandomNumbers(int $amountToGenerate, int $maxValue): array
    {
        $uniqueRandomNumbers = [];
        if ($amountToGenerate > $maxValue) {
            $amountToGenerate = $maxValue;
        }

        while (count($uniqueRandomNumbers) !== $amountToGenerate) {
            $randomNumber = random_int(0, $maxValue);
            $uniqueRandomNumbers[$randomNumber] = $randomNumber;
        }
        return $uniqueRandomNumbers;
    }
}
