<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Responses\FailedPasswordConfirmationResponse as FortifyFailedPasswordConfirmationResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fortify's password-confirmation failure message, minus the empty-box case.
 *
 * The confirm-password form is `novalidate`, so an untouched box now reaches Fortify's
 * response instead of a browser bubble — and its one sentence, "The provided password was
 * incorrect.", describes a mistake the user did not make. Same treatment as the auth
 * requests: keep Fortify's answer for a wrong password, name the empty field plainly.
 *
 * Only the copy branches here. Whether the password is accepted is still decided by
 * Fortify's ConfirmPassword action and the guard, untouched.
 *
 * Bound over Fortify's own implementation of the contract in FortifyServiceProvider.
 */
class FailedPasswordConfirmationResponse extends FortifyFailedPasswordConfirmationResponse
{
    /**
     * Create an HTTP response for a confirmation that did not succeed.
     *
     * @param Request $request
     *
     * @throws ValidationException
     */
    public function toResponse($request): Response
    {
        if (trim((string) $request->input('password', '')) !== '') {
            return parent::toResponse($request);
        }

        $message = 'Enter your password to continue.';

        if ($request->wantsJson()) {
            throw ValidationException::withMessages(['password' => [$message]]);
        }

        return back()->withErrors(['password' => $message]);
    }
}
