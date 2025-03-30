<?php

namespace App\Services;

class RoverService
{
    /** @var int $x */
    private int $x;

    /** @var int $y */
    private int $y;

    /** @var string $direction */
    private string $direction;

    /** @var int $mapWidth */
    private int $mapWidth = 200;

    /** @var int $mapHeight */
    private int $mapHeight = 100;

    /** @var array $obstacles */
    protected $obstacles = []; 

    /** @var array $executedCommands */
    protected $executedCommands = [];

    /** @var array $executedCommands */
    protected $stoppedAtObstacle = [];

    private const DIRECTIONS = ['N', 'E', 'S', 'W'];

    /**
     * Constructor
     * 
     * @param int $x
     * @param int $y
     * @param string $direction
     */
    public function __construct(int $x = 0, int $y = 0, string $direction = 'N')
    {
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
        $this->obstacles = json_decode(env('ROVER_OBSTACLES', '[]'), true); 
    }

    /**
     * Generate movement
     *
     * @param string $commands
     * @return array
     */
    public function move(string $commands): array
    {
        foreach (str_split($commands) as $command) {
            $currentDirection = $this->direction;

            switch ($command) {
                case 'L':
                    $this->turnLeft();
                    break;
                case 'R':
                    $this->turnRight();
                    break;
            }

            if (!$this->moveForward()) {
                $this->direction = $currentDirection;
                break;
            } else {
                $this->executedCommands[] = $command;
            }
        }

        return $this->getPosition();
    }

    /**
     * Get executed commands
     *
     * @return array
     */
    public function getExecutedCommands(): array
    {
        return $this->executedCommands;
    }

    /**
     * Turn left, changing only direction
     *
     * @return void
     */
    private function turnLeft(): void
    {
        $currentIndex = array_search($this->direction, self::DIRECTIONS);

        $this->direction = self::DIRECTIONS[($currentIndex - 1 + 4) % 4];
    }

    /**
     * Turn right, changing only direction
     *
     * @return void
     */
    private function turnRight(): void
    {
        $currentIndex = array_search($this->direction, self::DIRECTIONS);

        $this->direction = self::DIRECTIONS[($currentIndex + 1) % 4];
    }

    /**
     * Generating new coords
     *
     * @return boolean
     */
    private function moveForward(): bool
    {
        $newX = $this->x;
        $newY = $this->y;
        
        switch ($this->direction) {
            case 'N':
                $newY++;
                break;
            case 'S':
                $newY--;
                break;
            case 'E':
                $newX++;
                break;
            case 'W':
                $newX--;
                break;
        }
       
        return $this->checkObstacle($newX, $newY);
    }

    /**
     * Check obstacle
     *
     * @param integer $newX
     * @param integer $newY
     * @return boolean
     */
    private function checkObstacle(int $newX, int $newY): bool
    {
        if (in_array([$newX, $newY], $this->obstacles)) {
            $this->stoppedAtObstacle = [$newX, $newY];   
        } else {
            $this->x = ($newX + $this->mapWidth) % $this->mapWidth;
            $this->y = ($newY + $this->mapHeight) % $this->mapHeight;
        }

        return empty($this->stoppedAtObstacle);
    }
        
    /**
     * Return position
     *
     * @return array
     */
    private function getPosition(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'direction' => $this->direction,     
            'obstacle' => $this->stoppedAtObstacle
        ];
    }
}
