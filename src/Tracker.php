<?php
namespace AzaelCodes\Utils;

class USPSPackageTracker
{
    /**
     * @var string
     */
    private $url = 'https://tools.usps.com/go/TrackConfirmAction';

    /**
     * @var string
     */
    private $endpoint;
    private $userAgent;
    private $domXPath;

    public function __construct($trackingNumber)
    {
        $this->endpoint = '?tLabels=' . $trackingNumber;
        $this->userAgent = $this->getUserAgent();
        $htmlData = $this->getHtmlPage();
        $this->domXPath = $this->getXPathObject($htmlData);
    }

    /**
     * @return bool|string
     */
    public function getStatus()
    {
        $deliveryStatus = $this->domXPath->query('//div[@class="delivery_status"]/h2');
        if ($deliveryStatus->length < 1) {
            return false;
        }

        return $deliveryStatus->item(0)->nodeValue;
    }

    /**
     * @return mixed
     */
    private function getHtmlPage()
    {
        $url = $this->url . $this->endpoint;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($curl, CURLOPT_URL, $url);
        $results = curl_exec($curl);
        curl_close($curl);

        return $results;
    }

    /**
     * @param $htmlData
     * @return \DOMXPath
     */
    private function getXPathObject($htmlData)
    {
        $xmlPageDom = new \DOMDocument();
        @$xmlPageDom->loadHTML($htmlData);
        $xmlPageXPath = new \DOMXPath($xmlPageDom);
        return $xmlPageXPath;
    }
    private function getUserAgent()
    {
        return 'Mozilla/5.0 (Macintosh; Intel Mac OS X x.y; rv:10.0) Gecko/20100101 Firefox/10.0';
    }
}