<?php $church = \App\Models\Church::instance(); ?>



<?php $__env->startSection('doc-title', 'PROGRAMME DES CULTES DE L\'ÉGLISE BAPTISTE AEBECI DE YOPOUGON NOUVEAU BUREAU'); ?>

<?php $__env->startSection('doc-body'); ?>
<style>
    /* Neutralise le min-height du layout général — pensé pour les fiches
       courtes en portrait, inadapté à ce document paysage déjà rempli. */
    .doc-body {
        min-height: 0;
        padding: 0 2px;
    }

    /* En-tête programme */
    .prog-header {
        text-align: center;
        margin-bottom: 12px;
    }
    .prog-header .theme {
        font-size: 12px;
        font-weight: bold;
        color: #1F4E79;
    }
    .prog-header .periode-info {
        font-size: 10.5px;
        color: #555;
        margin-top: 4px;
    }

    /* Tableau programme */
    table.programme {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5px;
    }
    table.programme th {
        background: #1F4E79;
        color: white;
        padding: 7px 5px;
        text-align: center;
        font-size: 10px;
        font-weight: bold;
        border: 1px solid #1F4E79;
        white-space: nowrap;
    }
    table.programme th.col-date  { background: #0F1E33; width: 8%; }
    table.programme th.col-theme { width: 18%; }
    table.programme th.col-pred  { width: 12%; background: #1F4E79; }
    table.programme th.col-pres  { width: 12%; background: #1a4070; }
    table.programme th.col-lou   { width: 14%; background: #1a5050; }
    table.programme th.col-ann   { width: 10%; background: #1a4030; }
    table.programme th.col-type  { width: 8%; background: #2d1f5e; }

    table.programme td {
        padding: 6px 5px;
        border: 1px solid #d1d5db;
        vertical-align: top;
        font-size: 9.5px;
    }
    table.programme tr:nth-child(even) td { background: #f0f5ff; }
    table.programme tr:nth-child(odd)  td { background: #ffffff; }

    .date-cell {
        font-weight: bold;
        color: #1F4E79;
        text-align: center;
        font-size: 10px;
    }
    .person-main    { font-weight: bold; color: #111; display: block; font-size: 9.5px; }
    .person-backup  { color: #6b7280; font-size: 9px; display: block; font-style: italic; }
    .group-name     { color: #6d28d9; font-size: 9px; font-weight: bold; display: block; margin-bottom: 2px; }
    .theme-text     { font-size: 9px; color: #374151; line-height: 1.4; }
    .scripture-text { font-size: 8px; color: #9ca3af; font-style: italic; }

    .type-badge-commun   { color: #1e40af; font-weight: bold; font-size: 9.5px; }
    .type-badge-francais { color: #065f46; font-weight: bold; font-size: 9.5px; }
    .type-badge-senoufo  { color: #92400e; font-weight: bold; font-size: 9.5px; }
    .type-badge-special  { color: #7c3aed; font-weight: bold; font-size: 9.5px; }

    .no-assign { color: #d1d5db; font-style: italic; font-size: 9.5px; }

    /* Légende */
    .legend {
        margin-top: 10px;
        display: table;
        width: 100%;
        font-size: 9px;
        color: #555;
    }
    .legend-item {
        display: table-cell;
        padding: 4px 8px;
        border: 1px solid #e5e7eb;
        border-radius: 2px;
        text-align: center;
    }
</style>


<div class="prog-header">
    <?php if($periode->general_theme): ?>
    <div class="theme">THÈME : <?php echo e(strtoupper($periode->general_theme)); ?></div>
    <?php endif; ?>
    <div class="periode-info">
        PÉRIODE : <?php echo e(strtoupper($periode->name)); ?> &nbsp;|&nbsp;
        Du <?php echo e($periode->start_date?->format('d/m/Y')); ?> au <?php echo e($periode->end_date?->format('d/m/Y')); ?>

    </div>
</div>


<table class="programme">
    <thead>
        <tr>
            <th class="col-date">DATE</th>
            <th class="col-theme">THÈME DU CULTE</th>
            <th colspan="2" class="col-pred">PRÉDICATEURS</th>
            <th colspan="2" class="col-pres">PRÉSIDENCE</th>
            <th class="col-lou">LOUANGE</th>
            <th class="col-ann">ANNONCES</th>
            <th class="col-type">CULTE</th>
        </tr>
        <tr>
            <th class="col-date" style="background:#0F1E33;"></th>
            <th class="col-theme"></th>
            <th style="background:#1F4E79; font-size:8.5px;">Titulaire</th>
            <th style="background:#1F4E79; font-size:8.5px;">Suppléant</th>
            <th style="background:#1a4070; font-size:8.5px;">Titulaire</th>
            <th style="background:#1a4070; font-size:8.5px;">Suppléant</th>
            <th style="background:#1a5050;"></th>
            <th style="background:#1a4030;"></th>
            <th style="background:#2d1f5e;"></th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $periode->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $slug = fn($s, $b=false) => $service->assignments
                ->filter(fn($a) => $a->role?->slug === $s && $a->is_backup === $b)
                ->first();

            $predTit  = $slug('predicateur', false);
            $predSup  = $slug('suppleant_predicateur', false) ?? $slug('predicateur', true);
            $presTit  = $slug('president', false);
            $presSup  = $slug('suppleant_president', false) ?? $slug('president', true);

            $louangeGroups = $service->assignments
                ->filter(fn($a) => $a->role?->slug === 'louange' && $a->worshipGroup)
                ->pluck('worshipGroup');

            $annonceur = $slug('annonceur', false);

            $typeClass = match($service->service_type) {
                'francais' => 'type-badge-francais',
                'senoufo'  => 'type-badge-senoufo',
                'special'  => 'type-badge-special',
                default    => 'type-badge-commun',
            };

            $groupColors = ['#6d28d9', '#9d174d', '#0f766e'];
        ?>
        <tr>
            
            <td class="date-cell">
                <?php echo e($service->service_date?->translatedFormat('d M')); ?>

            </td>

            
            <td>
                <span class="theme-text"><?php echo e($service->service_theme ?? '—'); ?></span>
            </td>

            
            <td>
                <?php if($predTit): ?>
                    <span class="person-main"><?php echo e($predTit->believer->lastname); ?></span>
                    <span class="person-main" style="font-weight:normal"><?php echo e($predTit->believer->firstname); ?></span>
                <?php else: ?>
                    <span class="no-assign">—</span>
                <?php endif; ?>
            </td>

            
            <td>
                <?php if($predSup): ?>
                    <span class="person-backup"><?php echo e($predSup->believer->full_name); ?></span>
                <?php else: ?>
                    <span class="no-assign">—</span>
                <?php endif; ?>
            </td>

            
            <td>
                <?php if($presTit): ?>
                    <span class="person-main"><?php echo e($presTit->believer->lastname); ?></span>
                    <span class="person-main" style="font-weight:normal"><?php echo e($presTit->believer->firstname); ?></span>
                <?php else: ?>
                    <span class="no-assign">—</span>
                <?php endif; ?>
            </td>

            
            <td>
                <?php if($presSup): ?>
                    <span class="person-backup"><?php echo e($presSup->believer->full_name); ?></span>
                <?php else: ?>
                    <span class="no-assign">—</span>
                <?php endif; ?>
            </td>

            
            <td>
                <?php $__empty_2 = true; $__currentLoopData = $louangeGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                    <span class="group-name" style="color:<?php echo e($groupColors[$i % count($groupColors)]); ?>">
                        <?php echo e($group->name); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                    <span class="no-assign">—</span>
                <?php endif; ?>
            </td>

            
            <td>
                <?php if($annonceur): ?>
                    <span class="person-main"><?php echo e($annonceur->believer->full_name); ?></span>
                <?php else: ?>
                    <span class="no-assign">—</span>
                <?php endif; ?>
            </td>

            
            <td style="text-align:center">
                <span class="<?php echo e($typeClass); ?>">
                    <?php echo e(match($service->service_type) {
                        'commun'   => 'Commun',
                        'francais' => 'Français',
                        'senoufo'  => 'Sénoufo',
                        'special'  => 'Spécial',
                        default    => '—'
                    }); ?>

                </span>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="9" style="text-align:center; padding:24px; color:#9ca3af;">
                Aucun culte programmé pour cette période.
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>


<div class="legend">
    <div class="legend-item">Supp. = Suppléant</div>
    <div class="legend-item">Cultes commun = 3 groupes de louange</div>
    <div class="legend-item">Cultes français/sénoufo = 2 groupes de louange</div>
    <div class="legend-item"><?php echo e($periode->services->count()); ?> cultes programmés</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pdf', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/cultes/programme-pdf.blade.php ENDPATH**/ ?>