<?php

namespace Fidum\VaporMetricsTile\Stores\Concerns;

use Spatie\Dashboard\Models\Tile;

trait VaporMetricsStoreTrait
{
    private Tile $tile;

    public static function make(): self
    {
        return new static();
    }

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName(static::tileName());
    }

    /** @param string|int $key */
    public function setMetrics($key, array $data): self
    {
        $this->tile->putData("metrics:$key", $data);

        return $this;
    }

    /** @param string|int $key */
    public function metrics($key): array
    {
        return $this->tile->getData("metrics:$key") ?? [];
    }

    abstract public static function tileName(): string;
}
