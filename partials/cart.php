<h2>Your Cart</h2>

<!-- This is whats called defensive programming. If $records_in_cart is not defined; it will instead make an empty array -->
<?php $records = $records_in_cart ?? []; ?>

<?php if (empty($records)): ?>
  <p>Your cart is empty.</p>
<?php else: ?>

  <table class="table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Artist</th>
        <th>Format</th>
        <th>Price</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($records as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= htmlspecialchars($row['artist']) ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>$<?= number_format($row['price'], 2) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <form method="post">
    <input type="hidden" name="action" value="checkout">
    <button class="btn btn-success">Complete Purchase</button>
  </form>

<?php endif; ?>