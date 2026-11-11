<?php

declare(strict_types=1);

namespace App\Services\Ai;

use RuntimeException;
use Throwable;

/**
 * The one failure a suggestion endpoint is allowed to show a person.
 *
 * Provider errors, response bodies and credentials stop here: the message is
 * written for the editor, not for a log, so a 429 or a malformed completion can
 * never echo third-party text into the admin panel. The `cause` is the narrow
 * exception to that rule — a short machine-readable label (never provider text)
 * that the broker can write to the server log so an operator can tell a timeout
 * from a bad key without the UI leaking anything.
 */
final class AiSuggestionException extends RuntimeException
{
    /**
     * @param string         $message    Text safe to show in the admin UI.
     * @param int            $httpStatus Status the JSON endpoint should answer with.
     * @param Throwable|null $previous   Underlying transport failure, never rendered.
     * @param string|null    $cause      Short log-only label, e.g. `connection_timeout`.
     */
    public function __construct(
        string $message,
        public readonly int $httpStatus = 503,
        ?Throwable $previous = null,
        public readonly ?string $cause = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * The feature is switched off at the environment or site level.
     */
    public static function disabled(): self
    {
        return new self('AI suggestions are switched off on this site.', 422);
    }

    /**
     * The provider could not be reached, was rate limited, or answered badly.
     *
     * @param Throwable|null $previous Underlying transport failure, never rendered.
     * @param string|null    $cause    Short log-only label for the operator.
     */
    public static function unavailable(?Throwable $previous = null, ?string $cause = null): self
    {
        return new self(
            'Suggestions are unavailable right now. Please try again shortly.',
            503,
            $previous,
            $cause
        );
    }
}
