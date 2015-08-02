<?php

use App\Brand;
use App\Product;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Storage;
use Orchestra\Parser\Xml\Document;
use Orchestra\Parser\Xml\Reader;


class FeedParser implements FeedInterface {

    protected $feedUrl;
    protected $app;
    protected $document;
    protected $reader;
    public $content;

    public function __construct($feedUrl)
    {
        $this->feedUrl = __DIR__ . '/downloads/'.time().'.xml';

        $this->app = new Container;
        $this->document = new Document($this->app);
        $this->reader = new Reader($this->document);

    }

    /**
     *
     */
    public function loadFeed()
    {
        $xml = $this->reader->load($this->feedUrl);

        $content = $xml->getContent();
        $this->content = $xml->getContent();

        foreach ($content as $product) {


            $item = Product::where('externalUrl', '=', $product->productUrl)->first();

            if (!$item) $item = new Product;

            if (strlen($product->brand) > 1) {

                $brand = Brand::where('name', '=', $product->brand)->first();

                if (!$brand) {
                    $brand = new Brand;
                    $brand->name = $product->brand;
                    $brand->save();
                }

                if ($brand->id) $item->brand = $brand->id;
            }


            $item->name = $product->name;
            $item->description = $product->description;
            $item->price = $product->price;
            $item->regularPrice = $product->regularPrice;
            $item->shippingPrice = $product->shippingPrice;
            $item->externalUrl = $product->productUrl;
            $item->imageUrl = $product->graphicUrl;

            $item->save();

        }
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return $this->feedUrl;
    }

    /**
     * @return string
     */
    public function getFeedContent()
    {
        return $this->content;
    }

    /**
     * @param $feedUrl
     */
    public function saveFeed($feedUrl)
    {
        $curl = new \anlutro\cURL\cURL;

        $request = $curl->newRequest('get', $feedUrl)->setOption(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
        $response = $request->send();

        file_put_contents($this->feedUrl, $response->body);
    }
}