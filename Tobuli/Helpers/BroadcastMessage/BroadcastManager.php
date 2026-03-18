<?php

namespace Tobuli\Helpers\BroadcastMessage;

use Tobuli\Entities\BroadcastMessage;
use Tobuli\Entities\User;
use Tobuli\Helpers\BroadcastMessage\JobBuilder\JobBuilderInterface;
use Tobuli\Helpers\BroadcastMessage\UsersFilter\BillingPlansFilter;
use Tobuli\Helpers\BroadcastMessage\UsersFilter\DevicesFilter;
use Tobuli\Helpers\BroadcastMessage\UsersFilter\ExpiredFilter;
use Tobuli\Helpers\BroadcastMessage\UsersFilter\ExpiringFilter;
use Tobuli\Helpers\BroadcastMessage\UsersFilter\FilterInterface;
use Tobuli\Helpers\BroadcastMessage\UsersFilter\GroupsFilter;
use Tobuli\Helpers\BroadcastMessage\UsersFilter\UsersFilter;

class BroadcastManager
{
    /**
     * @var FilterInterface[]
     */
    private array $userFilters;

    public function __construct()
    {
        $this->userFilters = array_map(function ($item) {
            return new $item;
        }, [
            GroupsFilter::class,
            UsersFilter::class,
            BillingPlansFilter::class,
            ExpiredFilter::class,
            ExpiringFilter::class,
            DevicesFilter::class,
        ]);
    }

    public function broadcast(User $sender, Message $message): void
    {
        foreach ($message->getChannels() as $channel) {
            $jobBuilder = $this->resolveJobBuilder($channel);

            $usersQuery = $jobBuilder->buildUsersQuery();

            $receiversCriteria = $message->getReceiversCriteria();

            foreach ($this->userFilters as $filter) {
                $filter->apply($usersQuery, $receiversCriteria);
            }

            $broadcastMsg = BroadcastMessage::create([
                'user_id' => $sender->id,
                'title' => $message->getTitle(),
                'channel' => $channel,
                'status' => BroadcastMessage::STATUS_NEW,
                'content' => $message->getContent(),
                'total' => $usersQuery->count(),
                'filters' => $receiversCriteria,
            ]);

            dispatch($jobBuilder->buildBroadcastJob($broadcastMsg));
        }
    }

    public function resolveJobBuilder(string $channel): JobBuilderInterface
    {
        $channel = str_replace('_', '', ucwords($channel, '_'));
        $class = str_replace('JobBuilderInterface', $channel . 'JobBuilder', JobBuilderInterface::class);

        if (class_exists($class)) {
            return new $class();
        }

        throw new \InvalidArgumentException($channel . ' message job builder');
    }

    public function getUserFilters(): array
    {
        return $this->userFilters;
    }
}
