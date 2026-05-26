<?php

namespace App\Enums;

enum ServiceType: string
{
    case CulteDimanche = 'culte_dimanche';
    case EnseignementMercredi = 'enseignement_mercredi';
    case MaladesVendredi = 'malades_vendredi';
    case SainteCene = 'sainte_cene';
    case GrenierPasteur = 'grenier_pasteur';
    case Autre = 'autre';

    public function label(): string
    {
        return match ($this) {
            self::CulteDimanche => 'Culte du dimanche',
            self::EnseignementMercredi => 'Enseignement (mercredi)',
            self::MaladesVendredi => 'Malades (dernier vendredi)',
            self::SainteCene => 'Sainte Cène',
            self::GrenierPasteur => 'Grenier Pasteur',
            self::Autre => 'Autre',
        };
    }
}
