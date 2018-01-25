<?php

namespace Silverback\ApiComponentBundle\Entity\Component\Feature\TextList;

use Doctrine\ORM\Mapping as ORM;
use Silverback\ApiComponentBundle\Entity\Component\Feature\AbstractFeatureItem;

/**
 * Class FeatureTextListItem
 * @package Silverback\ApiComponentBundle\Entity\Component\FeatureList
 * @author Daniel West <daniel@silverback.is>
 * @ORM\Entity()
 */
class FeatureTextListItem extends AbstractFeatureItem
{
    /**
     * @ORM\ManyToOne(targetEntity="FeatureTextList", inversedBy="items")
     * @var FeatureTextList
     */
    protected $feature;
}
