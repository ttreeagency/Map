<?php
namespace Ttree\Map\Domain\Model;

use Neos\Flow\Annotations as Flow;

/**
 * Address
 *
 * @api
 */
class Address {

	/**
	 * @var string
	 */
	protected $streetAddress;

	/**
	 * @var string
	 */
	protected $postalCode;

	/**
	 * @var string
	 */
	protected $addressLocality;

	public function __construct($streetAddress, $postalCode, $addressLocality) {
		$this->streetAddress = $streetAddress;
		$this->postalCode = $postalCode;
		$this->addressLocality = $addressLocality;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$streetAddress = trim(preg_replace('/CP[ ]?[0-9]*/m', '', $this->streetAddress));
		if ($streetAddress !== '') {
			$address = sprintf('%s, %s %s', $streetAddress, $this->postalCode, $this->addressLocality);
		} else {
			$address = sprintf('%s %s', $this->postalCode, $this->addressLocality);
		}
		$address = trim($address);

		return $address;
	}

}
