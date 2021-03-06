<?php

namespace AppBundle\Entity;

use Alsciende\SerializerBundle\Annotation\Skizzle;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Pack
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PackRepository")
 * @ORM\Table(name="packs")
 *
 * @Skizzle()
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class Pack
{
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @Skizzle\Field(type="string")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Skizzle\Field(type="string")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     *
     * @Skizzle\Field(type="integer")
     */
    private $position;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", nullable=true)
     *
     * @Skizzle\Field(type="integer")
     */
    private $size;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="released_at", type="date", nullable=true)
     *
     * @Skizzle\Field(type="date")
     */
    private $releasedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="ffg_id", type="string", nullable=true)
     *
     * @Skizzle\Field(type="string")
     */
    private $ffgId;

    /**
     * @var Cycle
     *
     * @ORM\ManyToOne(targetEntity="Cycle", inversedBy="packs", fetch="EAGER")
     * @ORM\JoinColumn(name="cycle_id", referencedColumnName="id", nullable=false)
     *
     * @Skizzle\Field(type="association")
     */
    private $cycle;

    /**
     * @var Collection|PackCard[]
     *
     * @ORM\OneToMany(targetEntity="PackCard", mappedBy="pack", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    private $cards;

    function __construct ()
    {
        $this->cards = new ArrayCollection();
    }

    public function getId (): string
    {
        return $this->id;
    }

    public function setId (string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName (): string
    {
        return $this->name;
    }

    public function setName (string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition (): int
    {
        return $this->position;
    }

    public function setPosition (int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSize (): int
    {
        return $this->size;
    }

    public function setSize (int $size): self
    {
        $this->size = $size;

        return $this;
    }

    function getReleasedAt (): DateTime
    {
        return $this->releasedAt;
    }

    function setReleasedAt (DateTime $releasedAt): self
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    public function getFfgId (): ?string
    {
        return $this->ffgId;
    }

    public function setFfgId (string $ffgId): self
    {
        $this->ffgId = $ffgId;

        return $this;
    }

    function getCycle (): Cycle
    {
        return $this->cycle;
    }

    function setCycle (Cycle $cycle): self
    {
        $this->cycle = $cycle;

        return $this;
    }

    /**
     * @return Collection|PackCard[]
     */
    function getCards (): Collection
    {
        return $this->cards;
    }
}
