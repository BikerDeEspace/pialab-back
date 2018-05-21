<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PiaApi\Entity\Pia;

use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use PiaApi\Entity\Pia\Traits\ResourceTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="pia_structure")
 */
class Structure implements Timestampable
{
    use ResourceTrait,
        TimestampableEntity;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="StructureType", inversedBy="structures")
     *
     * @var StructureType
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="Pia", mappedBy="structure")
     * @JMS\Exclude()
     *
     * @var Collection
     */
    protected $pias;

    /**
     * @ORM\OneToMany(targetEntity="PiaApi\Entity\Oauth\User", mappedBy="structure")
     * @JMS\Exclude()
     *
     * @var Collection
     */
    protected $users;

    public function __construct(string $name)
    {
        $this->name = $name;

        $this->pias = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection
     */
    public function getPias(): Collection
    {
        return $this->pias;
    }

    /**
     * @param Collection $pias
     */
    public function setPias(Collection $pias): void
    {
        $this->pias = $pias;
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param Collection $users
     */
    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }

    /**
     * @return StructureType
     */
    public function getType(): ?StructureType
    {
        return $this->type;
    }

    /**
     * @param StructureType $type
     */
    public function setType(?StructureType $type): void
    {
        $this->type = $type;
    }
}