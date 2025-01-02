<?php foreach ($orders as $order): ?>
    <div>
        <h3>Order #<?= htmlspecialchars($order['id']) ?></h3>
        <p>Status: <?= htmlspecialchars($order['status']) ?></p>
        <p>Items: <?= htmlspecialchars($order['items']) ?></p>
    </div>
<?php endforeach; ?>
