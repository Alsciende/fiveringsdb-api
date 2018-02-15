<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tokens")
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class Token
{
    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=255, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $accessToken;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="token_type", nullable=false)
     */
    private $tokenType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $expiresAt;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", name="scope", nullable=true)
     */
    private $scope;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", name="refresh_token", nullable=true)
     */
    private $refreshToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    public function __construct(string $accessToken, string $tokenType, \DateTime $expiresAt, string $scope = null, string $refreshToken = null)
    {
        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
        $this->expiresAt = $expiresAt;
        $this->scope = $scope;
        $this->refreshToken = $refreshToken;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @return null|string
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @return null|string
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @return User|null
     */
    public function getUser (): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Token
     */
    public function setUser (User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt (): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt (): \DateTime
    {
        return $this->expiresAt;
    }

    /**
     * @return string
     */
    public function toHeader(): string
    {
        return ucfirst($this->tokenType) . ' ' . $this->accessToken;
    }
}