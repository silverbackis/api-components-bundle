<?php

declare(strict_types=1);

namespace Silverback\ApiComponentBundle\Entity\Component;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Silverback\ApiComponentBundle\Entity\Component\Feature\AbstractFeatureItem;
use Silverback\ApiComponentBundle\Entity\Content\AbstractContent;
use Silverback\ApiComponentBundle\Entity\SortableInterface;
use Silverback\ApiComponentBundle\Entity\SortableTrait;
use Silverback\ApiComponentBundle\Validator\Constraints as ACBAssert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class ComponentLocation
 * @package Silverback\ApiComponentBundle\Entity\Component
 * @ACBAssert\ComponentLocation()
 * @ORM\Entity(repositoryClass="Silverback\ApiComponentBundle\Repository\ComponentLocationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
class ComponentLocation implements SortableInterface
{
    use SortableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @var string
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Silverback\ApiComponentBundle\Entity\Content\AbstractContent", inversedBy="componentLocations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"component"})
     * @var AbstractContent
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Silverback\ApiComponentBundle\Entity\Component\AbstractComponent", inversedBy="locations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"default"})
     * @var AbstractComponent
     */
    private $component;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var null|string
     */
    protected $dynamicPageClass;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint(
            'content',
            new Assert\NotBlank()
        );
        $metadata->addPropertyConstraint(
            'component',
            new Assert\NotBlank()
        );
    }

    /**
     * ComponentLocation constructor.
     * @param null|AbstractContent $newContent
     * @param null|AbstractComponent $newComponent
     */
    public function __construct(
        ?AbstractContent $newContent = null,
        ?AbstractComponent $newComponent = null
    ) {
        $this->id = Uuid::uuid4()->getHex();
        if ($newContent) {
            $this->setContent($newContent);
        }
        if ($newComponent) {
            $this->setComponent($newComponent);
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return AbstractContent|null
     */
    public function getContent(): ?AbstractContent
    {
        return $this->content;
    }

    /**
     * @param AbstractContent $content
     * @param bool|null $sortLast
     */
    public function setContent(?AbstractContent $content, ?bool $sortLast = true): void
    {
        if ($content !== $this->content) {
            $this->content = $content;
            if ($content) {
                $content->addComponentLocation($this);
            }
            if (null === $this->sort || $sortLast !== null) {
                $this->setSort($this->calculateSort($sortLast));
            }
        }
    }

    /**
     * @return AbstractComponent
     */
    public function getComponent(): AbstractComponent
    {
        return $this->component;
    }

    /**
     * @param AbstractComponent $component
     */
    public function setComponent(AbstractComponent $component): void
    {
        $this->component = $component;
    }

    /**
     * @return Collection|AbstractFeatureItem[]
     */
    public function getSortCollection(): Collection
    {
        return $this->content ? $this->content->getComponentLocations() : new ArrayCollection;
    }

    /**
     * @return null|string
     */
    public function getDynamicPageClass(): ?string
    {
        return $this->dynamicPageClass;
    }

    /**
     * @param null|string $dynamicPageClass
     */
    public function setDynamicPageClass(?string $dynamicPageClass): void
    {
        $this->dynamicPageClass = $dynamicPageClass;
    }
}