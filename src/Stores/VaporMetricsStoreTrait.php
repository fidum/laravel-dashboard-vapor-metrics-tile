<?php

namespace Fidum\VaporMetricsTile\Stores;

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

    public function setMetrics(string $key, array $data): self
    {
        $this->tile->putData("metrics:$key", $data);

        return $this;
    }

    public function metrics(string $key): array
    {
        return $this->tile->getData("metrics:$key") ?? [];
    }

    abstract public static function tileName(): string;
}
