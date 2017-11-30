<?php declare(strict_types=1);

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Model\Assertion;

/**
 * Command to save different item content properties to Matej.
 */
class ItemProperty extends AbstractCommand
{
    /** @var string */
    private $itemId;
    /** @var array */
    private $properties;

    private function __construct(string $itemId, array $properties)
    {
        $this->setItemId($itemId);
        $this->properties = $properties;
    }

    public static function create(string $itemId, array $properties = []): self
    {
        return new static($itemId, $properties);
    }

    protected function setItemId(string $itemId): void
    {
        Assertion::typeIdentifier($itemId);

        $this->itemId = $itemId;
    }

    protected function getCommandType(): string
    {
        return 'item-properties';
    }

    protected function getCommandParameters(): array
    {
        $parameters = $this->properties;

        $parameters['item_id'] = $this->itemId;

        return $parameters;
    }
}
