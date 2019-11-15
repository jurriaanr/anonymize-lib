<?php

namespace Oberon\Anonymize\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;

class AnnotationSubscriber implements EventSubscriber
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $classMetadata = $args->getClassMetadata();
    }

    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata'
        ];
    }
}
