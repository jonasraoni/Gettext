<?php
declare(strict_types = 1);

namespace Gettext;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * Class to manage the flags of a translation.
 */
class Flags implements JsonSerializable, Countable, IteratorAggregate
{
    protected $flags = [];

    public static function __set_state(array $state): Flags
    {
        return new static(...$state['flags']);
    }

    public function __construct(string ...$flags)
    {
        if (!empty($flags)) {
            $this->add(...$flags);
        }
    }

    public function __debugInfo()
    {
        return $this->toArray();
    }

    public function add(string ...$flags): self
    {
        foreach ($flags as $flag) {
            if (!$this->has($flag)) {
                $this->flags[] = $flag;
            }
        }

        sort($this->flags);

        return $this;
    }

    public function has(string $flag): bool
    {
        return in_array($flag, $this->flags, true);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function getIterator()
    {
        return new ArrayIterator($this->flags);
    }

    public function count(): int
    {
        return count($this->flags);
    }

    public function toArray(): array
    {
        return $this->flags;
    }

    public function mergeWith(Flags $flags): Flags
    {
        $merged = clone $this;
        $merged->add(...$flags->flags);

        return $merged;
    }
}