<?php
/**
 * CategoryController
 * @package site-product-category
 * @version 0.0.1
 */

namespace SiteProductCategory\Controller;

use SiteProductCategory\Library\Meta;
use ProductCategory\Model\ProductCategory as PCategory;
use ProductCategory\Model\ProductCategoryChain as PCChain;
use Product\Model\Product;
use LibFormatter\Library\Formatter;

class CategoryController extends \Site\Controller
{
    public function singleAction() {
        $slug = $this->req->param->slug;

        $category = PCategory::getOne(['slug'=>$slug]);
        if(!$category)
            return $this->show404();

        $category = Formatter::format('product-category', $category, ['user']);

        $products = [];

        $cond = [
            'product.status'   => 2,
            'product_category' => $category->id
        ];

        list($page, $rpp) = $this->req->getPager(12, 24);

        $pchains = PCChain::get($cond, $rpp, $page, ['created'=>false]);
        if($pchains){
            $product_ids = array_column($pchains, 'product');
            $products    = Product::get(['id'=>$product_ids], 0, 1, ['created'=>false]);
            $products    = Formatter::formatMany('product', $products, ['user']);
        }

        $params = [
            'category'  => $category,
            'meta'      => Meta::single($category),
            'products'  => $products,
            'total'     => PCChain::count($cond)
        ];

        $this->res->render('product-category/single', $params);
        $this->res->setCache(86400);
        $this->res->send();
    }
}