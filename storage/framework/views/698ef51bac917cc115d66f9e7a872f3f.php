<?php $church = \App\Models\Church::instance(); ?>



<?php $__env->startSection('doc-title', 'DEMANDE DE PRÉSENTATION D\'ENFANT'); ?>

<?php $__env->startSection('doc-body'); ?>
<style>
    .city-date {
        text-align: right;
        font-size: 10px;
        color: #333;
        margin-bottom: 12px;
    }

    .intro-text {
        font-size: 10px;
        line-height: 2;
        color: #222;
        margin-bottom: 14px;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        padding: 12px 14px;
    }
    .intro-text .underlined {
        border-bottom: 1px solid #333;
        display: inline-block;
        min-width: 120px;
        font-weight: bold;
    }
    .intro-text .bold { font-weight: bold; }

    /* Parents bloc */
    .parents-bloc {
        display: table;
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 16px;
    }
    .parent-col {
        display: table-cell;
        width: 50%;
        padding: 10px 14px;
        vertical-align: top;
    }
    .parent-col:first-child {
        border-right: 1px solid #d1d5db;
    }
    .parent-title {
        font-size: 9px;
        font-weight: bold;
        text-transform: uppercase;
        color: #1F4E79;
        margin-bottom: 6px;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 3px;
    }
    .p-row { display: table; width: 100%; padding: 3px 0; }
    .p-label { display: table-cell; width: 45%; font-size: 8.5px; color: #6b7280; }
    .p-value {
        display: table-cell;
        font-size: 8.5px;
        font-weight: bold;
        color: #111;
        border-bottom: 1px dotted #ccc;
        padding-bottom: 1px;
    }

    .sig-section {
        display: table;
        width: 100%;
        margin-top: 20px;
    }
    .sig-col {
        display: table-cell;
        width: 33.33%;
        text-align: center;
        font-size: 8.5px;
        font-weight: bold;
        color: #374151;
    }
    .sig-line {
        border-top: 1px solid #333;
        margin: 25px 10px 0 10px;
    }
</style>


<div class="city-date">
    Abidjan, le <?php echo e($dedication->demande_date?->format('d/m/Y')); ?>

</div>


<div class="intro-text">
    Monsieur <span class="underlined"><?php echo e($dedication->father_display_name); ?></span>
    et Madame <span class="underlined"><?php echo e($dedication->mother_display_name); ?></span>
    <?php if($dedication->father?->churchInformation?->conversion_date): ?>
        Mariés le <span class="underlined"><?php echo e($dedication->father->churchInformation->conversion_date?->format('d/m/Y')); ?></span>
        à <span class="underlined"><?php echo e($dedication->father->churchInformation->conversion_place ?? '.............'); ?></span>
    <?php else: ?>
        Mariés le <span class="underlined">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        à <span class="underlined">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <?php endif; ?>
    <br>
    Sommes reconnaissants que le Seigneur nous ait fait don d'un enfant de sexe
    <span class="bold"><?php echo e($dedication->gender); ?></span>
    <br>
    Que nous avons nommé <span class="underlined"><?php echo e($dedication->child_full_name); ?></span>.
    <br>
    Il/elle est né(e) le <span class="bold"><?php echo e($dedication->child_birthdate?->format('d/m/Y')); ?></span>
    à <span class="bold"><?php echo e($dedication->child_birthplace); ?></span>.
    <br><br>
    Nous voudrions le présenter à Dieu pour lui exprimer toute notre gratitude et lui demander de nous
    aider à assumer notre responsabilité quant à son éducation.
    <br><br>
    <span class="bold">Date de présentation : <?php echo e($dedication->dedication_date?->format('d/m/Y')); ?></span>
</div>


<div class="parents-bloc">

    
    <div class="parent-col">
        <div class="parent-title">Le père</div>
        <div class="p-row">
            <div class="p-label">Nom :</div>
            <div class="p-value"><?php echo e($dedication->father?->lastname ?? explode(' ', $dedication->father_display_name)[0] ?? '—'); ?></div>
        </div>
        <div class="p-row">
            <div class="p-label">Prénoms :</div>
            <div class="p-value"><?php echo e($dedication->father?->firstname ?? (implode(' ', array_slice(explode(' ', $dedication->father_display_name), 1)) ?: '—')); ?></div>
        </div>
        <div class="p-row">
            <div class="p-label">Date de conversion :</div>
            <div class="p-value"><?php echo e($dedication->father?->churchInformation?->conversion_date?->format('d/m/Y') ?? '—'); ?></div>
        </div>
        <div class="p-row">
            <div class="p-label">Baptisé le :</div>
            <div class="p-value"><?php echo e($dedication->father?->churchInformation?->baptism_date?->format('d/m/Y') ?? '—'); ?></div>
        </div>
        <div class="p-row">
            <div class="p-label">Lieu :</div>
            <div class="p-value"><?php echo e($dedication->father?->churchInformation?->baptism_place ?? '—'); ?></div>
        </div>
        <div class="p-row">
            <div class="p-label">Carte de membre N° :</div>
            <div class="p-value"><?php echo e($dedication->father?->churchInformation?->baptism_card_number ?? '—'); ?></div>
        </div>
        <div style="font-size:8.5px; font-weight:bold; margin-top:18px; text-align:center;">
            Signature du père
        </div>
        <div style="border-top:1px solid #333; margin:20px 20px 0 20px;"></div>
    </div>

    
    <div class="parent-col">
        <div class="parent-title">La mère</div>
        <div class="p-row">
            <div class="p-label">Nom :</div>
            <div class="p-value"><?php echo e($dedication->mother?->lastname ?? explode(' ', $dedication->mother_display_name)[0] ?? '—'); ?></div>
        </div>
        <div class="p-row">
            <div class="p-label">Prénoms :</div>
            <div class="p-value"><?php echo e($dedication->mother?->firstname ?? (implode(' ', array_slice(explode(' ', $dedication->mother_display_name), 1)) ?: '—')); ?></div>
        </div>
        <div class="p-row">
            <div class="p-label">Date de conversion :</div>
            <div class="p-value"><?php echo e($dedication->mother?->churchInformation?->conversion_date?->format('d/m/Y') ?? '—'); ?></div>
        </div>
        <div class="p-row">
            <div class="p-label">Baptisée le :</div>
            <div class="p-value"><?php echo e($dedication->mother?->churchInformation?->baptism_date?->format('d/m/Y') ?? '—'); ?></div>
        </div>
        <div class="p-row">
            <div class="p-label">Lieu :</div>
            <div class="p-value"><?php echo e($dedication->mother?->churchInformation?->baptism_place ?? '—'); ?></div>
        </div>
        <div class="p-row">
            <div class="p-label">Carte de membre N° :</div>
            <div class="p-value"><?php echo e($dedication->mother?->churchInformation?->baptism_card_number ?? '—'); ?></div>
        </div>
        <div style="font-size:8.5px; font-weight:bold; margin-top:18px; text-align:center;">
            Signature de la mère
        </div>
        <div style="border-top:1px solid #333; margin:20px 20px 0 20px;"></div>
    </div>

</div>


<div class="sig-section">
    <div class="sig-col"></div>
    <div class="sig-col">
        <div class="sig-line"></div>
        Le Pasteur
    </div>
    <div class="sig-col"></div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pdf', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/dedication/fiche-pdf.blade.php ENDPATH**/ ?>