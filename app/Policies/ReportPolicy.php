<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function view(User $user, Report $report): bool
    {
        return $user->id === $report->user_id
            || $user->isRelawan()
            || $user->isKoordinator();
    }

    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->user_id;
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->id === $report->user_id;
    }

    public function review(User $user, Report $report): bool
    {
        return $user->isRelawan() || $user->isKoordinator();
    }

    public function forward(User $user, Report $report): bool
    {
        return $user->isKoordinator();
    }
}