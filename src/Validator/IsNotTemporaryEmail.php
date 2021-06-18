<?php

declare(strict_types=1);

namespace MarcelStrahl\TemporaryEmailValidatorBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
#[\Attribute]
final class IsNotTemporaryEmail extends Constraint
{
    /**
     * @psalm-var non-empty-string
     */
    public string $message = 'The e-mail "{{ email }}" provided is a temporary e-mail address and therefore not valid.';
}
