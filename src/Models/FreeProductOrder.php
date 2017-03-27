<?php

namespace Notadd\Shop\Models;

/*
 * Antvel - Free Prodcuts Order Model
 *
 * @author  Gustavo Ocanto <gustavoocanto@gmail.com>
 */

use Notadd\Shop\Eloquent\Model;

class FreeProductOrder extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shop_freeproduct_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'freeproduct_id',
        'order_id',
    ];

    /**
     * Obtains information order header.
     *
     * @return Collection Order Information
     */
    public function Orders()
    {
        return $this->hasMany(Order::class);
    }

    public function FreeProducts()
    {
        return $this->hasMany(FreeProduct::class);
    }
}
