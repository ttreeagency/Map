<?php


namespace Ttree\Map\Factory;


use Geocoder\Geocoder;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle7\Client;
use Neos\Flow\Annotations as Flow;

class GoogleMapsGeocoderFactory
{
    /**
     * @Flow\InjectConfiguration(path="geocoder")
     * @var array
     */
    protected $configuration;

    /**
     * Factory to create Geocoder with GoogleMaps provider
     * @return Geocoder
     */
    public function create() {
        $httpClient = new Client();
        $provider = new GoogleMaps($httpClient, $this->configuration['region'] ?? null, $this->configuration['apiKey'] ?? null);
        return new StatefulGeocoder($provider, $this->configuration['locale'] ?? null);
    }
}
