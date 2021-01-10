<?php
declare(strict_types=1);

namespace RadarrDaemon\Collection;

use RadarrDaemon\DTO\MovieDTO;

final class MovieCollection
{
    /** @var MovieDTO[] */
    private $movies = [];

    public function addMovie(MovieDTO $movie): void
    {
        $this->movies[] = $movie;
    }

    public function removeByMovieId(int $movieId)
    {
        $this->movies = array_filter($this->movies, static function (MovieDTO $movieDTO) use ($movieId): bool {
            return $movieDTO->getId() !== $movieId;
        });
    }

    /**
     * @return MovieDTO[]
     */
    public function getItems(): array
    {
        return $this->movies;
    }
}
