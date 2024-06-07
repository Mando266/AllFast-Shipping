<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ContactPeopleTable extends Component
{

    public $contactPeople;

    public function __construct($contactPeople = [])
    {
        $this->contactPeople = $contactPeople;
    }

    public function render()
    {
        return view('components.contact-people-table');
    }
}
