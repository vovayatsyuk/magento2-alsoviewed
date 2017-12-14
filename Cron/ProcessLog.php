<?php

namespace Vovayatsyuk\Alsoviewed\Cron;

use Vovayatsyuk\Alsoviewed\Model\ResourceModel\Log;
use Vovayatsyuk\Alsoviewed\Model\ResourceModel\Relation;

class ProcessLog
{
    /**
     * @var \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Log
     */
    protected $log;

    /**
     * @var \Vovayatsyuk\Alsoviewed\Model\ResourceModel\Relation
     */
    protected $relation;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param Log $log
     * @param Relation $relation
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Log $log,
        Relation $relation,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->log = $log;
        $this->relation = $relation;
        $this->logger = $logger;
    }

    public function execute()
    {
        $data = $this->log->getGroupedRelations();
        if ($data) {
            try {
                $this->relation->updateRelations($data);
                $this->log->clean();
            } catch (\Exception $e) {
                $this->logger->critical(
                    sprintf(
                        "Alsoviewed 'ProcessLog' Cron Event exception: %s",
                        $e->getMessage()
                    )
                );
            }
        }
    }
}
