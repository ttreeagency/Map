<?php
namespace Ttree\Map\TypoScript;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Http\Client\Browser;
use TYPO3\Flow\Http\Client\CurlEngine;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject;
use TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation;

/**
 * Convert the given adresse to longitude / latitude
 */
class AddressCoordinateImplementation extends AbstractTypoScriptObject {

	const ENDPOINT_URI_PATTERN = 'http://maps.google.com/maps/api/geocode/json?address={address}&sensor=false';

	/**
	 * @Flow\Inject
	 * @var Browser
	 */
	protected $browser;

	/**
	 * @return void
	 */
	public function initializeObject() {
		$requestEngine = new CurlEngine();
		$requestEngine->setOption(CURLOPT_TIMEOUT, 30);
		$this->browser->setRequestEngine($requestEngine);
	}

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->tsValue('address');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return array
	 */
	public function evaluate() {
		$address = trim($this->getAddress());
		if (strlen($address) < 2) {
			return NULL;
		}
		$address = rawurlencode(str_replace(' ', '+', $address));

		$endpoint = str_replace(array('{address}'), array($address), self::ENDPOINT_URI_PATTERN);
		$response = $this->browser->request($endpoint);

		if ($response->getStatusCode() !== 200) {
			return NULL;
		}
		$output = json_decode($response->getContent());

		$coordinates = [
			'longitude' => $output->results[0]->geometry->location->lat,
			'latitude' => $output->results[0]->geometry->location->lng,
		];
		return $coordinates;
	}

}