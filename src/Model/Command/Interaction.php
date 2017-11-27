<?php declare(strict_types=1);

namespace Lmc\Matej\Model\Command;

/**
 * Interaction command allows to send one interaction between a user and item.
 * When given user or item identifier is unknown, Matej will create such user or item respectively.
 */
class Interaction extends AbstractCommand
{
    private const INTERACTION_TYPE_DETAILVIEWS = 'detailviews';
    private const INTERACTION_TYPE_PURCHASES = 'purchases';
    private const INTERACTION_TYPE_BOOKMARKS = 'bookmarks';
    private const INTERACTION_TYPE_RATINGS = 'ratings';

    /** @var string */
    private $interactionType;
    /** @var string */
    private $userId;
    /** @var string */
    private $itemId;
    /** @var float */
    private $value;
    /** @var string */
    private $context;
    /** @var int */
    private $timestamp;

    private function __construct(
        string $interactionType,
        string $userId,
        string $itemId,
        float $value = 1.0,
        string $context = 'default',
        int $timestamp = null
    ) {
        $this->interactionType = $interactionType; // TODO: assert one of INTERACTION_TYPE_*
        $this->userId = $userId; // TODO: assert format
        $this->itemId = $itemId; // TODO: assert format
        $this->value = $value; // TODO: assert value between 0-1
        $this->context = $context; // TODO: assert format
        $this->timestamp = $timestamp ?: time(); // TODO: assert format
    }

    /**
     * Detail view interaction occurs when a user views an information page with detailed description of given item
     * (if there is such a feature available in your system).
     */
    public static function detailView(
        string $userId,
        string $itemId,
        float $value = 1.0,
        string $context = 'default',
        int $timestamp = null
    ): self {
        return new static(self::INTERACTION_TYPE_DETAILVIEWS, $userId, $itemId, $value, $context, $timestamp);
    }

    /**
     * Purchase interaction generally refer to buying or downloading a specific item by a user, suggesting that the user
     * believes the item to be of high value for her at the time of purchase. For example in the domain of job boards,
     * the purchase interaction stands for a reply of the user on specific Job Description.
     */
    public static function purchase(
        string $userId,
        string $itemId,
        float $value = 1.0,
        string $context = 'default',
        int $timestamp = null
    ): self {
        return new static(self::INTERACTION_TYPE_PURCHASES, $userId, $itemId, $value, $context, $timestamp);
    }

    /**
     * If your applications supports bookmarks, eg. flagging items as favorite, you may submit the interactions as well.
     * Depending on the nature of your application, bookmarking an item by a user may mean that the user has found the
     * item interesting based on:
     *  - viewing its details, and has added the item to her future "wishlist",
     *  - viewing its contents, and would like to view it once more in the future.
     * In both cases, bookmarking indicates positive relationship of the user to the item, allowing Matej to refine
     * recommendations.
     */
    public static function bookmark(
        string $userId,
        string $itemId,
        float $value = 1.0,
        string $context = 'default',
        int $timestamp = null
    ): self {
        return new static(self::INTERACTION_TYPE_BOOKMARKS, $userId, $itemId, $value, $context, $timestamp);
    }

    /**
     * Ratings are the most valuable type of interaction user may provide to the Matej recommender – they allow users
     * to submit explicit evaluations of items. These may be expressed as a number of stars (1-5), 👍/👎 voting etc.
     * For the recommendation API, the ratings must be scaled to real-valued interval [0, 1].
     */
    public static function rating(
        string $userId,
        string $itemId,
        float $value = 1.0,
        string $context = 'default',
        int $timestamp = null
    ): self {
        return new static(self::INTERACTION_TYPE_RATINGS, $userId, $itemId, $value, $context, $timestamp);
    }

    public function getCommandType(): string
    {
        return 'interaction';
    }

    public function getCommandParameters(): array
    {
        return [
            'interaction_type' => $this->interactionType,
            'user_id' => $this->userId,
            'item_id' => $this->itemId,
            'timestamp' => $this->timestamp,
            'value' => $this->value,
            'context' => $this->context,
        ];
    }
}