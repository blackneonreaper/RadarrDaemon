<?php
declare(strict_types=1);

namespace RadarrDaemon\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use RadarrDaemon\Collection\MovieCollection;
use RadarrDaemon\Collection\QueueCollection;
use RadarrDaemon\Collection\TorrentCollection;
use RadarrDaemon\DTO\MovieDTO;
use RadarrDaemon\Transformer\MovieTransformer;
use RadarrDaemon\Transformer\QueueItemTransformer;
use RadarrDaemon\Transformer\TorrentTransformer;
use Transmission\Transmission;

final class Radarr
{
    /** @var string */
    private $apikey;
    /** @var string */
    private $endpoint;
    /** @var Client */
    private $client;
    /** @var MovieTransformer */
    private $movieTransformer;
    /** @var QueueItemTransformer */
    private $queueItemTransformer;
    /** @var TorrentTransformer */
    private $torrentTransformer;

    /**
     * Radarr constructor.
     * @param Client $client
     * @param MovieTransformer $movieTransformer
     * @param QueueItemTransformer $queueItemTransformer
     * @param TorrentTransformer $torrentTransformer
     */
    public function __construct(
        Client $client,
        MovieTransformer $movieTransformer,
        QueueItemTransformer $queueItemTransformer,
        TorrentTransformer $torrentTransformer
    ) {
        $this->client = $client;
        $this->init($_ENV['RADARR_ENDPOINT'], $_ENV['RADARR_APIKEY']);
        $this->movieTransformer = $movieTransformer;
        $this->queueItemTransformer = $queueItemTransformer;
        $this->torrentTransformer = $torrentTransformer;
    }

    /**
     * @param string $endpoint
     * @param string $apikey
     */
    public function init(string $endpoint, string $apikey): void
    {
        $this->setEndpoint($endpoint);
        $this->setApikey($apikey);
    }

    /**
     * @return QueueCollection
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getQueueDetails(): QueueCollection
    {
        $queue = $this->get('queue/details');
        $queueCollection = new QueueCollection();
        foreach ($queue as $queueItem) {
            $queueCollection->addQueueItem($this->queueItemTransformer->transform($queueItem));
        }
        return $queueCollection;
    }

    /**
     * @param array $filters
     * @return MovieCollection
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getMovies(array $filters): MovieCollection
    {
        $movies = $this->get('movie');
        $movieCollection = new MovieCollection();
        foreach ($movies as $movie) {
            $movieDTO = $this->movieTransformer->transform($movie);
            if ($this->shouldAddMovieToCollection($filters, $movieDTO)) {
                $movieCollection->addMovie($movieDTO);
            }
        }
        return $movieCollection;
    }

    /**
     * @return TorrentCollection
     */
    public function getActiveTorrents(): TorrentCollection
    {
        $torrentCollection = new TorrentCollection();
        if (!isset($_ENV['TRANSMISSION_HOST']) || empty($_ENV['TRANSMISSION_HOST'])) {
            return $torrentCollection;
        }

        $transmission = new Transmission($_ENV['TRANSMISSION_HOST']);
        $torrents = $transmission->all();
        foreach ($torrents as $torrent) {
            if (!$torrent->isFinished()) {
                $torrentCollection->addTorrent($this->torrentTransformer->transform($torrent));
            }
        }
        return $torrentCollection;
    }

    /**
     * @param array $filters
     * @param MovieDTO $movieDTO
     * @return bool
     */
    private function shouldAddMovieToCollection(array $filters, MovieDTO $movieDTO): bool
    {
        $filterResult = true;
        if (isset($filters['cutoff'])) {
            if ($filters['cutoff'] && !$movieDTO->isQualityCutoffMet()) {
                $filterResult = false;
            }
            if (!$filters['cutoff'] && $movieDTO->isQualityCutoffMet()) {
                $filterResult = false;
            }
        }

        return $filterResult;
    }

    /**
     * @param MovieCollection $moviesToRemove
     * @param bool $excludeFromImport
     * @param bool $deleteFiles
     * @return bool
     * @throws GuzzleException
     */
    public function deleteMovies(MovieCollection $moviesToRemove, bool $excludeFromImport = false, bool $deleteFiles = false): bool
    {
        $movieIds = array_map(static function (MovieDTO $movieDTO): int {
            return $movieDTO->getId();
        }, $moviesToRemove->getItems());

        $result = $this->deletePost(
            'movie/editor',
            [
                "movieIds" => $movieIds,
                "deleteFiles" => $deleteFiles,
                "addImportExclusion" => $excludeFromImport
            ]);
        return $result;
    }

    /**
     * @param MovieCollection $moviesToSearch
     * @return bool
     * @throws GuzzleException
     */
    public function searchMovies(MovieCollection $moviesToSearch): bool
    {
        $movieIds = array_map(static function (MovieDTO $movieDTO): int {
            return $movieDTO->getId();
        }, $moviesToSearch->getItems());

        $result = $this->postCommand([
            "name" => "MoviesSearch",
            "movieIds" => $movieIds,
        ]);

        return $result;
    }

    /**
     * @param string $apikey
     */
    public function setApikey(string $apikey): void
    {
        $this->apikey = $apikey;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @param string $action
     * @return mixed
     * @throws GuzzleException
     * @throws JsonException
     * @throws Exception
     */
    private function get(string $action)
    {
        $response = $this->client->request('GET', $this->endpoint . $action . '/?apiKey=' . $this->apikey, ['Accept: application/json']);

        if ($response->getStatusCode() !== 200) {
            throw new Exception($response->getReasonPhrase());
        }

        try {
            return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new JsonException('Unable to parse response');
        }
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    private function postCommand(array $payload)
    {
        $response = $this->client->post($this->endpoint . 'command/?apiKey=' . $this->apikey, ['json' => $payload]);

        if ($response->getStatusCode() !== 201) {
            throw new Exception($response->getReasonPhrase());
        }

        return $response->getReasonPhrase() === 'Created';
    }

    /**
     * @param string $action
     * @param array $payload
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    private function deletePost(string $action, array $payload): bool
    {
        $response = $this->client->delete($this->endpoint . $action . '/?apiKey=' . $this->apikey, ['json' => $payload]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception($response->getReasonPhrase());
        }

        return $response->getReasonPhrase() === 'OK';
    }
}
