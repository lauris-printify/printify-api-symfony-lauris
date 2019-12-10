<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CountryCodes
 *
 * @ORM\Table(name="country_codes")
 * @ORM\Entity(repositoryClass="App\Repository\CountryCodes")
 */

/**
 * @ORM\Entity(repositoryClass="App\Repository\MyClassRepository")
 */
class CountryCodes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=3, nullable=false)
     */
    private $country;

    /**
     * @var int
     *
     * @ORM\Column(name="requests", type="integer", nullable=false)
     */
    private $requests;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getRequests(): ?int
    {
        return $this->requests;
    }

    public function setRequests(int $requests): self
    {
        $this->requests = $requests;

        return $this;
    }


}
