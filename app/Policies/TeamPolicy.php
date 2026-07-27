<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    /**
     * Rôles ayant un accès complet à toutes les équipes.
     */
    protected function hasFullAccess(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'pasteur', 'secretariat', 'direction_culte']);
    }

    /**
     * Vérifie si l'utilisateur est responsable dédié de CETTE équipe précise
     * (jaebeci -> J-AEBECI uniquement, afebeci -> AFEBECI uniquement).
     */
    protected function isOwnTeamLeader(User $user, Team $team): bool
    {
        if ($user->hasRole('jaebeci') && $team->slug === 'j-aebeci') {
            return true;
        }

        if ($user->hasRole('afebeci') && $team->slug === 'afebeci') {
            return true;
        }

        return false;
    }

    /**
     * Liste de toutes les équipes (/teams) — réservée aux rôles à accès complet.
     * jaebeci/afebeci n'ont pas besoin de la liste, ils sont redirigés
     * directement sur leur équipe depuis le dashboard.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasFullAccess($user);
    }

    /**
     * Voir le détail d'une équipe (/teams/{team}).
     */
    public function view(User $user, Team $team): bool
    {
        return $this->hasFullAccess($user) || $this->isOwnTeamLeader($user, $team);
    }

    /**
     * Créer une nouvelle équipe — structurel, réservé à l'accès complet.
     */
    public function create(User $user): bool
    {
        return $this->hasFullAccess($user);
    }

    /**
     * Modifier les infos d'une équipe (nom, description, responsable).
     */
    public function update(User $user, Team $team): bool
    {
        return $this->hasFullAccess($user) || $this->isOwnTeamLeader($user, $team);
    }

    /**
     * Supprimer une équipe — structurel, réservé à l'accès complet.
     */
    public function delete(User $user, Team $team): bool
    {
        return $this->hasFullAccess($user);
    }

    /**
     * Gérer les membres (affecter/retirer un fidèle) et le programme
     * d'activité (programmer/terminer/ajourner/PDF). Même périmètre que "update".
     */
    public function manage(User $user, Team $team): bool
    {
        return $this->hasFullAccess($user) || $this->isOwnTeamLeader($user, $team);
    }
}