<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-08 15:35
 */
namespace Notadd\Mall\Handlers\Administration\Order\Rate;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Mall\Models\ProductRate;

/**
 * Class ListHandler.
 */
class ListHandler extends Handler
{
    /**
     * Execute Handler.
     *
     * @throws \Exception
     */
    protected function execute()
    {
        $builder = ProductRate::query();
        $this->withCode(200)->withData($builder->get())->withMessage('');
    }
}
