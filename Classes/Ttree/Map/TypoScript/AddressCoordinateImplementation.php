<?php
namespace Ttree\Map\TypoScript;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Ttree.Map".             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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
	 * @flow\inject(setting="styles")
	 * @var array
	 */
	protected $styles;

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