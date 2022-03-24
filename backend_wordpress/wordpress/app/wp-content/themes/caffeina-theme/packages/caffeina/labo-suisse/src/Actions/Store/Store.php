<?php

namespace Caffeina\LaboSuisse\Actions\Store;

class Store
{

    private $storeId;

    public function __construct($storeId)
    {

        $this->storeId = $storeId;
    }

    public function updatePhoneNumber($phoneNumber)
    {
        $old = get_field('lb_stores_phone_number', $this->storeId);

        if(empty($old)) {
            update_field('lb_stores_phone_number', $phoneNumber, $this->storeId);
        }

        return true;
    }
}
