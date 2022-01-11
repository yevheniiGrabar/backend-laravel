<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UpdateCalendarEventSubs
{
    use SerializesModels;

    /** @var string $id */
    private string $id;

    /** @var array $data */
    private array $data;

    /** @var array $payload */
    private array $payload;

    /** @var Collection $payload */
    private Collection $subs;

    /**
     * UpdateCalendarEventSubs constructor.
     *
     * @param Collection $subs
     * @param string $id
     * @param array $data
     * @param array $payload
     */
    public function __construct(Collection $subs, string $id, array $data, array $payload)
    {
        $this->data = $data;
        $this->id = $id;
        $this->payload = $payload;
        $this->subs = $subs;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return Collection
     */
    public function getSubs(): Collection
    {
        return $this->subs;
    }
}
