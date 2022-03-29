<?php

declare(strict_types=1);

namespace MarcelStrahl\TemporaryEmailValidatorBundle\Validator;

use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use TemporaryEmailDetection\Client;
use TemporaryEmailDetection\Exception;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class IsNotTemporaryEmailValidator extends ConstraintValidator
{
    private Client $client;
    private LoggerInterface $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param mixed $value
     *
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsNotTemporaryEmail) {
            throw new UnexpectedTypeException($constraint, IsNotTemporaryEmail::class);
        }

        if (empty($value)) {
            return;
        }

        if (!is_string($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new UnexpectedValueException($value, 'iterable');
        }

        try {
            $isValid = $this->client->isTemporary($value);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            $isValid = false;
        }

        if ($isValid) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ email }}', $value)
                ->addViolation();
        }
    }
}
