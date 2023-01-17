<?php

namespace Plugins\PageBuilder\Helpers\Traits;

trait RenderViews
{
    public static function renderView($filename, $data = [])
    {
        return view('pagebuilder::'. $filename, compact('data'))->render();
    }
}
