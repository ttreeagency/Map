<?php
namespace Ttree\Map\Command;

use Ttree\Map\Domain\Model\Address;
use Ttree\Map\Service\GeocoderService;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Property\PropertyMapper;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;

class GeocoderCommandController extends CommandController {

	/**
	 * @var ContextFactoryInterface
	 * @Flow\Inject
	 */
	protected $contextFactory;

	/**
	 * @var GeocoderService
	 * @Flow\Inject
	 */
	protected $geocodeService;

	/**
	 * @var PropertyMapper
	 * @Flow\Inject
	 */
	protected $propertyMapper;

	/**
	 * Check report status
	 *
	 * @param string $workspace
	 * @param boolean $force
	 */
	public function reverseCommand($workspace = 'live', $force = FALSE) {
		$context = $this->createContext($workspace);
		$flowQuery = new FlowQuery(array($context->getRootNode()));
		$flowQuery = $flowQuery->find('[instanceof Ttree.Map:BaseMap]');

		$unmatchedNodes = [];
		$count = $match = 0;
		/** @var NodeInterface $node */
		foreach ($flowQuery as $node) {
			$this->outputLine('  %s', array($node->getLabel()));
			if ($force === FALSE && trim($node->getProperty('reversedLongitude')) !== '' && trim($node->getProperty('reversedLatitude')) !== '') {
				continue;
			}
			$address = $this->propertyMapper->convert($node, Address::class);
			$coordinates = $this->geocodeService->geocode($address);
			if ($coordinates !== NULL) {
				$node->setProperty('reversedLongitude', $coordinates['longitude']);
				$node->setProperty('reversedLatitude', $coordinates['latitude']);
				++$match;
			} else {
				$unmatchedNodes[] = $node;
			}
			sleep(1);
			++$count;
		}

		$this->outputLine('Number of processed nodes: %d', [$count]);
		$this->outputLine('Node without Reverse Geocoding: %d', [$match]);
		foreach ($unmatchedNodes as $node) {
			$this->outputLine('  Node: %s', [$node->getLabel()]);
		}

	}

	/**
	 * Creates a content context for given workspace and language identifiers
	 *
	 * @param string $workspaceName
	 * @param array $languageIdentifiers
	 * @return \Neos\ContentRepository\Domain\Service\Context
	 */
	protected function createContext($workspaceName, array $languageIdentifiers = NULL) {
		$contextProperties = array(
			'workspaceName' => $workspaceName,
			'invisibleContentShown' => TRUE,
			'inaccessibleContentShown' => TRUE
		);
		if ($languageIdentifiers !== NULL) {
			$contextProperties = array_merge($contextProperties, array(
				'dimensions' => array('language' => $languageIdentifiers)
			));
		}
		return $this->contextFactory->create($contextProperties);
	}

}
