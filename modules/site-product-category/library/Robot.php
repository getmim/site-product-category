<?php
/**
 * Robot
 * @package site-product-category
 * @version 0.0.1
 */

namespace SiteProductCategory\Library;

use ProductCategory\Model\ProductCategory as PCategory;
use ProductCategory\Model\ProductCategoryChain as PCChain;
use Product\Model\Product;

class Robot
{
    static private function getPages(): ?array{
        $cond = [
            'updated' => ['__op', '>', date('Y-m-d H:i:s', strtotime('-2 days'))]
        ];
        $pages = PCategory::get($cond);
        if(!$pages)
            return null;

        return $pages;
    }

    static private function getCategoryProducts(int $category): ?array{
        $cond = [
            'product_category' => $category,
            'product.status'   => 2,
            'product.updated'  => ['__op', '>', date('Y-m-d H:i:s', strtotime('-2 days'))]
        ];

        $pages = PCChain::get($cond);
        if(!$pages)
            return null;

        $product_ids = array_column($pages, 'product');
        $products    = Product::get(['id'=>$product_ids]);

        return $products;
    }

    static function feed(): array {
        $mim = &\Mim::$app;

        $pages = self::getPages();
        if(!$pages)
            return [];

        $result = [];
        foreach($pages as $page){
            $route = $mim->router->to('siteProductCategorySingle', (array)$page);
            $meta  = json_decode($page->meta);
            $title = $meta->title ?? $page->name;
            $desc  = $meta->description ?? substr($page->content, 0, 100);

            $result[] = (object)[
                'description'   => $desc,
                'page'          => $route,
                'published'     => $page->created,
                'updated'       => $page->updated,
                'title'         => $title,
                'guid'          => $route
            ];
        }

        return $result;
    }

    static function feedProduct(int $category): array{
        $mim = &\Mim::$app;

        $pages = self::getCategoryProducts($category);
        if(!$pages)
            return [];

        $result = [];
        foreach($pages as $page){
            $route = $mim->router->to('siteProductSingle', (array)$page);
            $meta  = json_decode($page->meta);
            $title = $meta->title ?? $page->title;
            $desc  = $meta->description ?? $page->title;

            $result[] = (object)[
                'description'   => $desc,
                'page'          => $route,
                'published'     => $page->created,
                'updated'       => $page->updated,
                'title'         => $title,
                'guid'          => $route
            ];
        }

        return $result;
    }

    static function sitemap(): array {
        $mim = &\Mim::$app;

        $pages = self::getPages();
        if(!$pages)
            return [];

        $result = [];
        foreach($pages as $page){
            $route  = $mim->router->to('siteProductCategorySingle', (array)$page);
            $result[] = (object)[
                'page'          => $route,
                'updated'       => $page->updated,
                'priority'      => '0.8',
                'changefreq'    => 'daily'
            ];
        }

        return $result;
    }
}