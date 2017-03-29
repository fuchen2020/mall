<?php
/**
 * This file is part of Notadd.
 *
 * @author        Qiyueshiyi <qiyueshiyi@outlook.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime      2017-03-29 18:21
 */

namespace Notadd\Shop\Http\Handlers\Product;

use Notadd\Shop\Http\Controllers\UserController;
use Notadd\Shop\Models\Order;
use Notadd\Shop\Models\Product;
use Notadd\Shop\Models\OrderDetail;
use Notadd\Shop\Models\ProductDetail;
use Illuminate\Support\Facades\Session;
use Notadd\Shop\Helpers\FeaturesHelper;
use Notadd\Shop\Models\FreeProductOrder;
use Notadd\Foundation\Passport\Abstracts\DataHandler;

class ShowHandler extends DataHandler
{
    /**
     * Http code.
     *
     * @return int
     */
    public function code()
    {
        return 200;
    }

    /**
     * Data for handler.
     *
     * @return array
     */
    public function data()
    {
        $id   = $this->request->input('id');
        $user = $this->request->user();

        $allWishes = '';

        if ($user) {
            $allWishes = Order::ofType('wishlist')
                ->where('user_id', $user->id)
                ->where('description', '<>', '')
                ->orderBy('id', 'desc')
                ->take(5)
                ->get();
        }

        $product = Product::select([
            'id', 'category_id', 'user_id', 'name', 'description',
            'price', 'stock', 'features', 'condition', 'rate_val',
            'rate_count', 'low_stock', 'status', 'type', 'tags', 'products_group', 'brand',
        ])->with([
            'group' => function ($query) {
                $query->select(['id', 'products_group', 'features']);
            },
        ])->with('categories')->find($id);

        if ($product) {

            // retrieving products features
            $features = ProductDetail::all()->toArray();

            // increasing product counters, in order to have a suggestion orden
            $this->setCounters($product, ['view_counts' => trans('shop::globals.product_value_counters.view')], 'viewed');

            //saving the product tags into users preferences
            if (trim($product->tags) != '') {
                UserController::setPreferences('product_viewed', explode(',', $product->tags));
            }

            //receiving products user reviews & comments
            $reviews = OrderDetail::where('product_id', $product->id)
                ->whereNotNull('rate_comment')
                ->select('rate', 'rate_comment', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            //If it is a free product, we got to retrieve its package information
            if ($product->type == 'freeproduct') {
                $order       = OrderDetail::where('product_id', $product->id)->first();
                $freeproduct = FreeProductOrder::where('order_id', $order->order_id)->first();
            }

            $freeproductId = isset($freeproduct) ? $freeproduct->freeproduct_id : 0;

            // products suggestions control
            // saving product id into suggest-listed, in order to exclude products from suggestions type "view"
            Session::push('suggest-listed', $product->id);
            $suggestions = $this->getSuggestions(['preferences_key' => $product->id, 'limit' => 4]);
            Session::forget('suggest-listed');

            //retrieving products groups of the product shown
            if (count($product->group)) {
                $featuresHelper = new FeaturesHelper();
                $product->group = $featuresHelper->group($product->group);
            }

            return compact('product', 'allWishes', 'reviews', 'freeproductId', 'features', 'suggestions');
        } else {
            return [];
        }
    }

    /**
     * Errors for handler.
     *
     * @return array
     */
    public function errors()
    {
        return [
            '查看产品失败！',
        ];
    }

    /**
     * Messages for handler.
     *
     * @return array
     */
    public function messages()
    {
        return [
            '查看产品成功！',
        ];
    }
}
