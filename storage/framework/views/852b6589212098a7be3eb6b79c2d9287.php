<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #222;
            background: #fff;
            margin: 16mm 14mm 16mm 14mm;
        }

        /* EN-TETE EGLISE */
        .church-header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #1F4E79;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }
        .church-header-logo {
            display: table-cell;
            width: 85px;
            vertical-align: middle;
            text-align: center;
        }
        .church-header-logo img {
            width: 72px;
            height: 72px;
            object-fit: contain;
        }
        .church-header-logo .logo-placeholder {
            width: 70px;
            height: 70px;
            border: 2px solid #1F4E79;
            border-radius: 50%;
            margin: 0 auto;
            font-size: 8px;
            color: #1F4E79;
            font-weight: bold;
            text-align: center;
            line-height: 70px;
            padding: 5px;
        }
        .church-header-info {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .church-header-info .org-name {
            font-size: 15px;
            font-weight: bold;
            color: #1F4E79;
            line-height: 1.4;
        }
        .church-header-info .district {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin-top: 4px;
        }
        .church-header-info .authorization {
            font-size: 10.5px;
            color: #555;
            margin-top: 4px;
        }

        /* TITRE DU DOCUMENT */
        .doc-title {
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            color: #1F4E79;
            margin: 20px 0 24px 0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* CORPS */
        .doc-body {
            padding: 0 6px;
            min-height: 190mm;
        }

        /* PIED DE PAGE */
        .church-footer {
            border-top: 3px solid #1F4E79;
            margin-top: 28px;
            padding-top: 10px;
            text-align: center;
        }
        .church-footer .footer-temple {
            font-size: 11px;
            font-weight: bold;
            color: #1F4E79;
            margin-bottom: 4px;
        }
        .church-footer .footer-contact {
            font-size: 10px;
            color: #555;
            line-height: 1.8;
        }
        .church-footer .footer-generated {
            margin-top: 8px;
            border-top: 1px dashed #ccc;
            padding-top: 6px;
            font-size: 9px;
            color: #999;
        }

        /* UTILITAIRES COMMUNS */
        .row-table  { display: table; width: 100%; }
        .cell-half  { display: table-cell; width: 50%; vertical-align: top; }
        .cell-third { display: table-cell; width: 33.33%; vertical-align: top; }

        .box {
            border: 1px solid #d1d5db;
            border-radius: 5px;
            margin-bottom: 16px;
            overflow: hidden;
        }
        .box-header {
            background: #1F4E79;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 8px 14px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }
        .box-body { padding: 12px 14px; }

        .info-row { display: table; width: 100%; padding: 6px 0; border-bottom: 1px solid #f3f4f6; }
        .info-row:last-child { border-bottom: none; }
        .info-label { display: table-cell; color: #6b7280; font-size: 13px; width: 45%; }
        .info-value { display: table-cell; font-weight: bold; font-size: 13px; color: #111; }

        .tag {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 10px;
            font-size: 9.5px;
            font-weight: bold;
            margin: 2px 3px 2px 0;
        }

        .empty-val { color: #9ca3af; font-style: italic; font-weight: normal; }

        .signature-row { display: table; width: 100%; margin-top: 50px; }
        .signature-cell { display: table-cell; text-align: center; font-size: 10px; font-weight: bold; color: #374151; }
        .signature-line { border-top: 1px solid #333; margin: 0 24px; margin-top: 45px; }
    </style>
</head>
<body>


<div class="church-header">
    <div class="church-header-logo">
        <?php if($church->photo_path && file_exists(public_path('storage/' . $church->photo_path))): ?>
            <img src="<?php echo e(public_path('storage/' . $church->photo_path)); ?>" alt="Logo">
        <?php else: ?>
            <div class="logo-placeholder"><?php echo e($church->organisation); ?></div>
        <?php endif; ?>
    </div>
    <div class="church-header-info">
        <div class="org-name"><?php echo e($church->organisation_name); ?></div>
        <div class="district"><?php echo e($church->district); ?> - EGLISE LOCALE <?php echo e($church->church_name); ?></div>
        <div class="authorization">Autorisation N° : <?php echo e($church->authorization); ?></div>
    </div>
</div>


<div class="doc-title"><?php echo $__env->yieldContent('doc-title'); ?></div>


<div class="doc-body">
    <?php echo $__env->yieldContent('doc-body'); ?>
</div>


<div class="church-footer">
    <div class="footer-temple">
        Temple de <?php echo e($church->church_name); ?>

    </div>
    <div class="footer-contact">
        <?php echo e($church->contact_line); ?>

        <?php if($church->localisation): ?>
            <br>Situation géographique : <?php echo e($church->localisation); ?>

        <?php endif; ?>
    </div>
    <div class="footer-generated">
        <?php echo e(str_repeat('-', 40)); ?><br>
        Fiche générée le <?php echo e(now()->format('d/m/Y à H:i')); ?>

    </div>
</div>

</body>
</html><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/layouts/pdf.blade.php ENDPATH**/ ?>