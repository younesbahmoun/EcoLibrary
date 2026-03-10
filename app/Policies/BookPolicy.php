<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
    // public function before(User $user, $ability): bool|null
    // {
    //     if ($user->is_admin) {
    //         return true; // Admins can do everything
    //     }
    //     return null; // Defer to other methods for non-admins
    // }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Book $book): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Book $book): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Book $book): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Book $book): bool
    {
        return false;
    }

    public function reserve(User $user, Book $book)
    {
        if ($user->is_admin) {
            return Response::deny('Only users can reserve books.');
            // return true;
        }
        if ($book->statut === 'disponible') {
            return Response::allow();
        } else {
            return Response::deny('Book is not available for reservation.');
        }
    }

    public function cancel(User $user, Book $book) {
        if ($user->is_admin) {
            return Response::deny('Only users can reserve books.');
            // return true;
        }
        if ($book->statut === 'reserved') {
            return Response::allow();
        } else {
            return Response::deny('Book is already canceled.');
        }
    }

    // public function reserve(User $user, Book $book): Response
    // {
    //     if ($user->is_admin) {
    //         return Response::deny('Admins cannot reserve books.', 403);
    //     }

    //     return match($book->statut) {
    //         'disponible' => Response::allow(),
    //         'reserved'   => Response::deny('Book is already reserved.', 409),
    //         'degraded'   => Response::deny('Book is degraded and unavailable.', 422),
    //         default      => Response::deny('Book is not available.', 400),
    //     };
    // }
}
