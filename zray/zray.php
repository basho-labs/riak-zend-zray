<?php

/*********************************
	Composer Z-Ray Extension
	Version: 1.00
**********************************/
namespace ZRay;

use ZRayExtension;

class Riak
{
    /**
     * @var \Basho\Riak
     */
    private $riak;

    public function init($nodes, $api = null)
    {
        $this->riak = new \Basho\Riak($nodes, [], $api);
    }

    function constructExit($context, &$storage)
    {
        $result = (new \Basho\Riak\Command\Builder\FetchStats($this->riak))
            ->build()
            ->execute()
            ->getAllStats();

        foreach(array_keys($result) as $key) {
          $storage['ringStats'][] = [
            'Key' => $key,
            'Value' => json_encode($result[$key])
          ];
        }
    }

    function executeExit($context, &$storage)
    {
        $command = $context['functionArgs'][0];
        $api = $context['functionArgs'][1];
        $response = $context['returnValue'];

        $item = array(
            'Command'       => get_class($command),
            'Response'      => get_class($response),
            'Success'       => $response->isSuccess(),
            'Message'       => $response->getMessage(),
            'Api'           => get_class($api),
            'Duration'      => $context['durationExclusive'] . ' ms',
            'Called from File' => $context['calledFromFile'],
            'Called from Line' => $context['calledFromLine']
        );

        if ($item['Command'] == 'Basho\Riak\Command\Object\Fetch') {
            $storage['fetchedValues'][] = array_merge($item,
                [
                    'Location'  => "{$command->getLocation()}",
                    'NotFound'  => $response->isNotFound(),
                    'Value'     => $response->getObject() ? json_encode($response->getObject()->getData()) : '',
                    'VClock'    => $response->getObject() ? $response->getObject()->getVclock() : '',
                    'Siblings'  => $response->hasSiblings() ? count($response->getSiblings()) - 1 : 0,
                ]
            );
        } elseif ($item['Command'] == 'Basho\Riak\Command\Object\Store') {
            $storage['storedValues'][] = array_merge($item,
                [
                    'Location'  => "{$command->getLocation()}",
                    'Value'     => json_encode($command->getObject()->getData()),
                ]
            );
        } elseif ($item['Command'] == 'Basho\Riak\Command\DataType\Counter\Fetch') {
            $storage['fetchedCounters'][] = array_merge($item,
                [
                    'Location'  => "{$command->getLocation()}",
                    'NotFound'  => $response->isNotFound(),
                    'Value'     => $response->getCounter() ? $response->getCounter()->getData() : 0,
                ]
            );
        } elseif ($item['Command'] == 'Basho\Riak\Command\DataType\Set\Fetch') {
            $storage['fetchedSets'][] = array_merge($item,
                [
                    'Location'  => "{$command->getLocation()}",
                    'NotFound'  => $response->isNotFound(),
                    'Value'     => json_encode($response->getSet()),
                    'ElementCount' => $response->getSet() ? count($response->getSet()->getData()) : 0,
                    'Context'   => $response->getSet() ? $response->getSet()->getContext() : '',
                ]
            );
        } elseif ($item['Command'] == 'Basho\Riak\Command\DataType\Map\Fetch') {
            $storage['fetchedMaps'][] = array_merge($item,
                [
                    'Location'  => "{$command->getLocation()}",
                    'NotFound'  => $response->isNotFound(),
                    'Value'     => json_encode($response->getMap()),
                    'Context'   => $response->getMap() ? $response->getMap()->getContext() : '',
                ]
            );
        } else {
            $storage['otherOperations'][] = array_merge($item,
                [
                ]
            );
        }

    }
}

$zre = new ZRayExtension('riak');
$riak = new Riak();

$zre->setMetadata(array(
    'logo' => __DIR__ . DIRECTORY_SEPARATOR . 'logo.png'
));

$zre->setEnabledAfter('Basho\Riak\Node\Builder::build');

$zre->traceFunction(
    'Basho\Riak::__construct',
    function($context, &$storage) use ($riak) {
    	$nodes = $context['functionArgs'][0];
    	$api = isset($context['functionArgs'][1]) ? $context['functionArgs'][1] : null;
        $riak->init($nodes, $api);
    },
    array($riak, 'constructExit')
);

$zre->traceFunction('Basho\Riak\Node::execute', function () {}, array($riak, 'executeExit'));
