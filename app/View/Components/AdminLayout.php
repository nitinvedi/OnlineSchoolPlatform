<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminLayout extends Component
{
    public $title;

    public function __construct($title = 'Admin Dashboard')
    {
        $this->title = $title;
    }

    public function render()
    {
        return view('admin.layouts.app');
    }
}
