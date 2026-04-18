<?php

namespace App\Domains\Bookings\Policies;

use App\Models\User;
use App\Domains\Bookings\Models\Booking;
use Illuminate\Auth\Access\HandlesAuthorization;

final class BookingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Assuming hasRole is implemented on User model
        return method_exists($user, 'hasRole') ? $user->hasRole('staff') : false;
    }

    public function update(User $user, Booking $booking): bool
    {
        $isOwner = $user->id === $booking->user_id;
        $isStaff = method_exists($user, 'hasRole') ? $user->hasRole('staff') : false;
        
        return $isOwner || $isStaff;
    }

    public function delete(User $user, Booking $booking): bool
    {
        return method_exists($user, 'hasRole') ? $user->hasRole('admin') : false;
    }
}
