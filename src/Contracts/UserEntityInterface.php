<?php

namespace Railroad\Referral\Contracts;

interface UserEntityInterface
{
    /**
     * @return int
     */
    public function getId(): ?int;

    /**
     * @return string
     */
    public function getEmail(): ?string;
}
