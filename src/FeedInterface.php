<?php
/**
 * Created by PhpStorm.
 * User: jimmitjoo
 * Date: 15-08-02
 * Time: 19:47
 */

interface FeedInterface {
    /**
     * @return string
     */
    public function getFeedUrl();

    /**
     * @return string
     */
    public function getFeedContent();

    public function saveFeed($externalFeedUrl);
}