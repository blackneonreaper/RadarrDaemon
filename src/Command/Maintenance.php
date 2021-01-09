<?php
declare(strict_types=1);

namespace RadarrDaemon\Command;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use RadarrDaemon\Service\Radarr;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Maintenance
{
    /** @var Radarr */
    private $radarrService;

    /**
     * Maintenance constructor.
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
        try {
            $completedMovies = $this->radarrService->getMovies(['cutoff' => true]);
            if ($completedMovies->getItems() === null) {
                return;
            }

            $this->radarrService->deleteMovies($completedMovies, (bool)$_ENV['EXCLUDE_FROM_IMPORT'], (bool)$_ENV['DELETE_FILES']);
        } catch (Exception $exception) {
            $output->writeln($exception->getMessage());
        } catch (GuzzleException $exception) {
            $output->writeln($exception->getMessage());
        }
    }
}
