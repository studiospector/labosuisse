<?php

namespace Caffeina\LaboSuisse\Resources;

use Caffeina\LaboSuisse\Option\Option;

class Message
{
    private string $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $messages = [];

        foreach ($this->query() as $message) {
            $messages[] = [
                'title' => $message['lb_messages_item_title'],
                'message' => $message['lb_messages_item_message']
            ];

            echo $message['lb_messages_item_message'] . "\n";
        }

        return $messages;
    }

    /**
     * @return array
     */
    private function query(): array
    {
        $messages = (new Option())->getMessages();

        //get messages for location
        $messages = array_filter($messages, function ($item) {
            if ($item['lb_messages_item_location'] == $this->location and $item['lb_messages_item_is_active']) {
                return $item;
            }
        });

        return array_filter($messages, function ($item) {
            $from = strtotime($item['lb_messages_item_date_from']);
            $to = strtotime($item['lb_messages_item_date_to']);

            if ($this->isValidData($from, $to)) {
                return $item;
            }
        });
    }

    /**
     * @param $from
     * @param $to
     * @return bool
     */
    private function isValidData($from, $to): bool
    {
        $today = strtotime(date('Y-m-d h:i'));

        return (!$from and !$to)
            or ($today >= $from and $today <= $to)
            or ($today >= $from and !$to)
            or ($today <= $to and !$from);
    }

    public static function register()
    {
        $locations = ['cart', 'single_product', 'archive_product'];

        foreach ($locations as $location) {
            add_action("get_messages_{$location}", function () use ($location) {
                return (new self($location))->get();
            });
        }
    }
}
