<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-08 15:38
 */
namespace Notadd\Mall\Handlers\Administration\Order\Rate;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Mall\Models\ProductRate;

/**
 * Class EditHandler.
 */
class EditHandler extends Handler
{
    /**
     * Execute Handler.
     *
     * @throws \Exception
     */
    public function execute()
    {
        $id = $this->request->input('id');
        $rate = ProductRate::query()->find($id);
        if ($rate && $rate->update($this->request->all())) {
            $this->withCode(200)->withMessage('');
        } else {
            $this->withCode(500)->withMessage('');
        }
    }
}
