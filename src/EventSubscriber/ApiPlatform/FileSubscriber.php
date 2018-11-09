<?php

namespace Silverback\ApiComponentBundle\EventSubscriber\ApiPlatform;

use ApiPlatform\Core\EventListener\EventPriorities;
use Silverback\ApiComponentBundle\Entity\Content\FileInterface;
use Silverback\ApiComponentBundle\Factory\FileDataFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FileSubscriber extends AbstractSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                ['populateFileData', EventPriorities::PRE_SERIALIZE]
            ]
        ];
    }

    public static function getSubscribedServices(): array
    {
        return [
            '?' . FileDataFactory::class
        ];
    }

    public function populateFileData(GetResponseForControllerResultEvent $event): void
    {
        $component = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!($component instanceof FileInterface || Request::METHOD_GET !== $method)) {
            return;
        }

        /** @var FileDataFactory $factory */
        $factory = $this->container->get(FileDataFactory::class);
        $fileData = $factory->create($component);
        $component->setFileData($fileData);
    }
}