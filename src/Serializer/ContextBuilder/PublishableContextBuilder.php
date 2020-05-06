<?php

/*
 * This file is part of the Silverback API Components Bundle Project
 *
 * (c) Daniel West <daniel@silverback.is>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Silverback\ApiComponentsBundle\Serializer\ContextBuilder;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Silverback\ApiComponentsBundle\Helper\Publishable\PublishableHelper;
use Silverback\ApiComponentsBundle\Serializer\MappingLoader\PublishableLoader;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Vincent Chalamon <vincent@les-tilleuls.coop>
 */
final class PublishableContextBuilder implements SerializerContextBuilderInterface
{
    private SerializerContextBuilderInterface $decorated;
    private PublishableHelper $publishableHelper;

    public function __construct(SerializerContextBuilderInterface $decorated, PublishableHelper $publishableHelper)
    {
        $this->decorated = $decorated;
        $this->publishableHelper = $publishableHelper;
    }

    public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        if (
            empty($resourceClass = $context['resource_class']) ||
            empty($context['groups']) ||
            !$this->publishableHelper->getAnnotationReader()->isConfigured($resourceClass)
        ) {
            return $context;
        }

        $reflectionClass = new \ReflectionClass($resourceClass);
        if ($normalization) {
            $context['groups'][] = sprintf('%s:%s:read', $reflectionClass->getShortName(), PublishableLoader::GROUP_NAME);
        } elseif ($this->publishableHelper->isGranted($resourceClass)) {
            $context['groups'][] = sprintf('%s:%s:write', $reflectionClass->getShortName(), PublishableLoader::GROUP_NAME);
        }

        return $context;
    }
}