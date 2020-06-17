<?php
/**
 * RobotController
 * @package site-product-category
 * @version 0.0.1
 */

namespace SiteProductCategory\Controller;

use LibRobot\Library\Feed;
use SiteProductCategory\Library\Robot;
use ProductCategory\Model\ProductCategory as PCategory;

class RobotController extends \Site\Controller
{
    public function feedAction(){
        $slug = $this->req->param->slug;
        $category = PCategory::getOne(['slug'=>$slug]);
        if(!$category)
            return $this->show404();

        $links = Robot::feedProduct($category->id);

        $feed_opts = (object)[
            'self_url'          => $this->router->to('siteProductCategorySingleFeed', (array)$category),
            'copyright_year'    => date('Y'),
            'copyright_name'    => \Mim::$app->config->name,
            'description'       => '...',
            'language'          => 'id-ID',
            'host'              => $this->router->to('siteHome'),
            'title'             => \Mim::$app->config->name
        ];

        Feed::render($links, $feed_opts);
        $this->res->setCache(3600);
        $this->res->send();
    }
}