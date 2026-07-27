<?php $church = \App\Models\Church::instance(); ?>



<?php $__env->startSection('doc-title', 'FICHE DU FIDÈLE'); ?>

<?php $__env->startSection('doc-body'); ?>
<style>
    /* Styles spécifiques à la fiche fidèle */
    .identity-bar {
        background: #1F4E79;
        color: white;
        padding: 9px 12px;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    .identity-bar h2   { font-size: 13px; font-weight: bold; letter-spacing: 0.5px; }
    .identity-bar-row  { display: table; width: 100%; margin-top: 5px; }
    .identity-bar-left { display: table-cell; font-size: 8px; color: #bfdbfe; }
    .identity-bar-right{ display: table-cell; text-align: right; font-size: 8px; color: #bfdbfe; }

    .id-tag {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 7px;
        font-weight: bold;
        margin-right: 3px;
    }
    .id-tag-white  { background: rgba(255,255,255,.2); color: white; }
    .id-tag-jeunes { background: #d1fae5; color: #065f46; }
    .id-tag-adultes{ background: #fef3c7; color: #92400e; }
    .id-tag-ecodim { background: #dbeafe; color: #1e40af; }
    .id-tag-pre    { background: #ede9fe; color: #5b21b6; }
    .id-tag-nourr  { background: #fce7f3; color: #9d174d; }

    .badge-id {
        display: inline-block;
        background: #C9A635;
        color: #0F1E33;
        font-weight: bold;
        font-size: 9px;
        padding: 3px 9px;
        border-radius: 4px;
    }

    .sanction-alert {
        background: #fff5f5;
        border: 1px solid #fca5a5;
        border-radius: 4px;
        padding: 6px 10px;
        margin-bottom: 10px;
    }
    .sanction-alert-title { color: #dc2626; font-weight: bold; font-size: 8px; margin-bottom: 2px; }
    .sanction-alert-text  { color: #7f1d1d; font-size: 8px; }

    .baptise-yes { color: #16a34a; }
    .baptise-no  { color: #9ca3af; font-weight: normal; font-style: italic; }

    .tag-equipe  { background: #e0e7ff; color: #3730a3; }
    .tag-groupe  { background: #ede9fe; color: #6d28d9; }
    .tag-cellule { background: #d1fae5; color: #065f46; }

    .identity-bar-top {
        display: table;
        width: 100%;
    }
    .id-photo {
        display: table-cell;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.4);
        vertical-align: middle;
    }
    .id-photo-placeholder {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
        background: rgba(255,255,255,0.15);
        color: #fff;
        font-weight: bold;
        font-size: 18px;
    }
    .identity-bar-main {
        display: table-cell;
        vertical-align: middle;
        padding-left: 14px;
    }
</style>


<div style="text-align:right; margin-bottom:6px;">
    <span class="badge-id">Fidèle N° <?php echo e(str_pad($believer->id, 4, '0', STR_PAD_LEFT)); ?></span>
</div>


<div class="identity-bar">
    <div class="identity-bar-top">

        <?php if($believer->profile_picture && file_exists(storage_path('app/public/' . $believer->profile_picture))): ?>
            <img src="<?php echo e(storage_path('app/public/' . $believer->profile_picture)); ?>" alt="<?php echo e($believer->full_name); ?>" class="id-photo">
        <?php else: ?>
            <div class="id-photo id-photo-placeholder">...</div>
        <?php endif; ?>

        <div class="identity-bar-main">
            <h2><?php echo e(strtoupper($believer->lastname)); ?> <?php echo e($believer->firstname); ?></h2>
            <div class="id-matricule">Matricule : <?php echo e($believer->register_number); ?></div>
            <div class="identity-bar-row">
                <div class="identity-bar-left">
                    <?php
                        $tagClass = match($believer->age_group) {
                            'Jeunes'       => 'id-tag-jeunes',
                            'Adultes'      => 'id-tag-adultes',
                            'ECODIM'       => 'id-tag-ecodim',
                            'Pré-scolaire' => 'id-tag-pre',
                            default        => 'id-tag-nourr',
                        };
                    ?>
                    <span class="id-tag <?php echo e($tagClass); ?>"><?php echo e($believer->age_group); ?></span>
                    <span class="id-tag id-tag-white"><?php echo e($believer->gender_label); ?></span>
                    <span class="id-tag id-tag-white"><?php echo e($believer->marital_status); ?></span>
                    <?php if($believer->age): ?>
                        <span class="id-tag id-tag-white"><?php echo e($believer->age); ?> ans</span>
                    <?php endif; ?>
                </div>
                <div class="identity-bar-right">
                    Statut :
                    <strong style="color:<?php echo e($believer->status === 'actif' ? '#86efac' : '#fca5a5'); ?>">
                        <?php echo e(ucfirst($believer->status)); ?>

                    </strong>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($believer->status === 'sanctionne'): ?>
<?php $sanction = $believer->sanctions()->where('is_active', true)->latest()->first(); ?>
<?php if($sanction): ?>
<div class="sanction-alert">
    <div class="sanction-alert-title">⚠ Sanction disciplinaire active</div>
    <div class="sanction-alert-text">
        Depuis le <?php echo e($sanction->start_date?->format('d/m/Y')); ?>

        <?php if($sanction->end_date): ?> — Jusqu'au <?php echo e($sanction->end_date->format('d/m/Y')); ?> <?php endif; ?>
        | Décidé par : <?php echo e($sanction->decided_by ?? '—'); ?><br>
        Motif : <?php echo e($sanction->reason); ?>

    </div>
</div>
<?php endif; ?>
<?php endif; ?>


<div class="row-table" style="margin-bottom:8px;">
    <div class="cell-half" style="padding-right:4px;">
        <div class="box">
            <div class="box-header">Informations générales</div>
            <div class="box-body">
                <div class="info-row"><div class="info-label">Nom</div><div class="info-value"><?php echo e($believer->lastname); ?></div></div>
                <div class="info-row"><div class="info-label">Prénom(s)</div><div class="info-value"><?php echo e($believer->firstname); ?></div></div>
                <div class="info-row"><div class="info-label">Date de naissance</div><div class="info-value"><?php echo e($believer->birth_date?->format('d/m/Y') ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Lieu de naissance</div><div class="info-value"><?php echo e($believer->birth_place ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Nationalité</div><div class="info-value"><?php echo e($believer->nationality ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">N° CNI</div><div class="info-value"><?php echo e($believer->cni_number ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Situation maritale</div><div class="info-value"><?php echo e($believer->marital_status ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Nombre d'enfants</div><div class="info-value"><?php echo e($believer->number_of_children); ?></div></div>
            </div>
        </div>
    </div>
    <div class="cell-half" style="padding-left:4px;">
        <div class="box">
            <div class="box-header">Adresse & Contact</div>
            <div class="box-body">
                <?php if($believer->address): ?>
                <div class="info-row"><div class="info-label">Commune</div><div class="info-value"><?php echo e($believer->address->commune ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Quartier</div><div class="info-value"><?php echo e($believer->address->quartier ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Sous-quartier</div><div class="info-value"><?php echo e($believer->address->sous_quartier ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Téléphone</div><div class="info-value"><?php echo e($believer->address->phone ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">WhatsApp</div><div class="info-value"><?php echo e($believer->address->whatsapp ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Email</div><div class="info-value"><?php echo e($believer->address->email ?? '—'); ?></div></div>
                <?php else: ?>
                <p class="empty-val">Aucune adresse enregistrée.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="row-table" style="margin-bottom:8px;">
    <div class="cell-half" style="padding-right:4px;">
        <div class="box">
            <div class="box-header">Vie spirituelle</div>
            <div class="box-body">
                <?php if($believer->churchInformation): ?>
                <?php $ci = $believer->churchInformation; ?>
                <div class="info-row"><div class="info-label">Connaissance église</div><div class="info-value"><?php echo e($ci->connaissance_eglise ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Église d'origine</div><div class="info-value"><?php echo e($ci->original_church ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Année d'arrivée</div><div class="info-value"><?php echo e($ci->arrival_year ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Date de conversion</div><div class="info-value"><?php echo e($ci->conversion_date?->format('d/m/Y') ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Lieu de conversion</div><div class="info-value"><?php echo e($ci->conversion_place ?? '—'); ?></div></div>
                <div class="info-row">
                    <div class="info-label">Baptisé(e)</div>
                    <div class="info-value <?php echo e($ci->baptised ? 'baptise-yes' : 'baptise-no'); ?>">
                        <?php echo e($ci->baptised ? '✓ Oui' : 'Non'); ?>

                    </div>
                </div>
                <?php if($ci->baptised): ?>
                <div class="info-row"><div class="info-label">Date de baptême</div><div class="info-value"><?php echo e($ci->baptism_date?->format('d/m/Y') ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Lieu de baptême</div><div class="info-value"><?php echo e($ci->baptism_place ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Pasteur officiant</div><div class="info-value"><?php echo e($ci->baptism_pastor ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">N° carte baptême</div><div class="info-value"><?php echo e($ci->baptism_card_number ?? '—'); ?></div></div>
                <?php endif; ?>
                <?php else: ?>
                <p class="empty-val">Aucune information enregistrée.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="cell-half" style="padding-left:4px;">
        <div class="box" style="margin-bottom:8px;">
            <div class="box-header">Éducation</div>
            <div class="box-body">
                <?php if($believer->education): ?>
                <div class="info-row"><div class="info-label">Niveau d'étude</div><div class="info-value"><?php echo e($believer->education->niveau_etude ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Diplôme</div><div class="info-value"><?php echo e($believer->education->diploma ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Qualification</div><div class="info-value"><?php echo e($believer->education->qualification ?? '—'); ?></div></div>
                <?php else: ?> <p class="empty-val">Non renseigné.</p> <?php endif; ?>
            </div>
        </div>
        <div class="box">
            <div class="box-header">Profession</div>
            <div class="box-body">
                <?php if($believer->profession): ?>
                <div class="info-row"><div class="info-label">Profession</div><div class="info-value"><?php echo e($believer->profession->profession ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Fonction</div><div class="info-value"><?php echo e($believer->profession->function ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Entreprise</div><div class="info-value"><?php echo e($believer->profession->company ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Contact pro.</div><div class="info-value"><?php echo e($believer->profession->professional_contact ?? '—'); ?></div></div>
                <?php else: ?> <p class="empty-val">Non renseigné.</p> <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="row-table">
    <div class="cell-half" style="padding-right:4px;">
        <div class="box">
            <div class="box-header">Responsabilités</div>
            <div class="box-body">
                <?php if($believer->responsibility): ?>
                <div class="info-row"><div class="info-label">Anciennes</div><div class="info-value"><?php echo e($believer->responsibility->old ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Actuelles</div><div class="info-value"><?php echo e($believer->responsibility->current ?? '—'); ?></div></div>
                <div class="info-row"><div class="info-label">Souhait de service</div><div class="info-value"><?php echo e($believer->responsibility->desire ?? '—'); ?></div></div>
                <?php else: ?> <p class="empty-val">Non renseigné.</p> <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="cell-half" style="padding-left:4px;">
        <div class="box">
            <div class="box-header">Appartenance</div>
            <div class="box-body">
                <div class="info-row">
                    <div class="info-label">Équipes</div>
                    <div class="info-value">
                        <?php $__empty_1 = true; $__currentLoopData = $believer->teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <span class="tag tag-equipe"><?php echo e($team->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <span class="empty-val">Aucune</span> <?php endif; ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Groupes de louange</div>
                    <div class="info-value">
                        <?php $__empty_1 = true; $__currentLoopData = $believer->worshipGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <span class="tag tag-groupe"><?php echo e($group->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <span class="empty-val">Aucun</span> <?php endif; ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Cellule</div>
                    <div class="info-value">
                        <?php $__empty_1 = true; $__currentLoopData = $believer->cells; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <span class="tag tag-cellule"><?php echo e($cell->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <span class="empty-val">Aucune</span> <?php endif; ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Famille</div>
                    <div class="info-value"><?php echo e($believer->family?->name ?? '—'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pdf', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/believers/fiche-fidele.blade.php ENDPATH**/ ?>