<?php
    include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice 01</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .card-product img {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: .5rem
    }

    @media (max-width: 576px) {

        .filters .form-select,
        .filters .form-control {
            margin-top: .5rem
        }
    }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <?php if (empty($products)): ?>
        <p class="text-secondary fst-italic">No products found.</p>
        <?php else: ?>
        <div class="vstack gap-2">
            <?php foreach ($products as $product): ?>
            <div class="card card-product shadow-sm">
                <?php if (!empty($product['image_url'])): ?>
                <img src="<?= h($product['image_url']) ?>" alt="Image">
                <?php endif; ?>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <h2 class="h6 mb-1"><?= h($product['name']) ?></h2>
                        <div class="text-secondary small">
                            <?php if (!empty($product['brand'])): ?>Hãng: <?= h($product['brand']) ?><?php endif; ?>
                            <?php if (!empty($product['category'])): ?>Loại:
                            <?= h($product['category']) ?><?php endif; ?>
                        </div>
                    </div>
                    <span class="badge text-bg-primary"><?= number_format($product['price_vnd']) ?> VND</span>
                </div>
                <p class="mt-2 mb-0 small text-secondary"><?= h($product['description']) ?></p>
                <?php if (!empty($p['description'])): ?>
                <p class="mt-2 mb-0 text-muted"
                    style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    <?= h($p['description']) ?>
                </p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>

</body>

</html>
