<?php

namespace Illuminate\Contracts\View;

interface View
{
    /**
     * @param string $title
     * @return static
     */
    public function title($title);

    /**
     * @param string $layout
     * @return static
     */
    public function layout($layout);
}
