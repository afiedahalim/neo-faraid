<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="display-1 text-muted">404</h1>
                <h2 class="mb-4">Page Not Found</h2>
                <p class="lead mb-4">
                    The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>
                <a href="<?php echo e(url('/')); ?>" class="btn btn-primary">Go to Homepage</a>
                <a href="<?php echo e(url()->previous()); ?>" class="btn btn-outline-secondary ms-2">Go Back</a>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\neo-faraid\resources\views/errors/404.blade.php ENDPATH**/ ?>