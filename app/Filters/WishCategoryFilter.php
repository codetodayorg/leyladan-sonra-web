<?php

namespace App\Filters;

use App\Models\WishCategory;
use EnesCakir\Helper\Base\Filter;

class WishCategoryFilter extends Filter
{
    protected $filters = ['search', 'download'];

    protected function download()
    {
        WishCategory::download($this->builder);
    }
}
