<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Parse\PageSpeedManager;

/**
 * @method static bool shouldProcessPageSpeed(\Illuminate\Http\Request $request, \Illuminate\Http\Response $response)
 * @method static mixed parseContent(mixed $content)
 *
 * @see \Modules\Parse\PageSpeedManager
 *
 * @mixins \Modules\Parse\PageSpeedManager
 */
class PageSpeed extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return PageSpeedManager::class;
    }
}
