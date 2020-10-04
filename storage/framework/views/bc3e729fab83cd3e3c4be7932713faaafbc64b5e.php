<!DOCTYPE html>
<html lang="en">
<head>

    <?php echo $__env->yieldContent('title'); ?>
    <?php echo $__env->make('layouts/frontend/partials/head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->yieldContent('additional_css'); ?>

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
    </script>

</head>

<body>
    <?php echo $__env->make('layouts/section/script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- BEGIN CONTAINER -->
    <div class="homepage-container">
        <?php echo $__env->make('layouts/frontend/partials/header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <!-- BEGIN CONTENT -->
        <div class="homepage-content-wrapper">
            <div class="homepage-content">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
        <!-- END CONTENT -->
        <?php echo $__env->make('layouts/frontend/partials/footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <?php echo $__env->make('layouts/frontend/partials/foot', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php echo $__env->yieldContent('additional_js'); ?>

</body>
</html>
