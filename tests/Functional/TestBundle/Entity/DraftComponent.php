<?php

/*
 * This file is part of the Silverback API Component Bundle Project
 *
 * (c) Daniel West <daniel@silverback.is>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Silverback\ApiComponentBundle\Tests\Functional\TestBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Silverback\ApiComponentBundle\Entity\Core\AbstractComponent;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Pierre Rebeilleau <pierre@les-tilleuls.coop>
 * @ApiResource
 * @ORM\Entity
 */
class DraftComponent extends AbstractComponent
{
    /**
     * @var string a greeting name
     *
     * @ORM\Column
     * @Assert\NotBlank
     */
    public string $name = '';
}
