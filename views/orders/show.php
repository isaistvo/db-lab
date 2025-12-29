    <h1>Перегляд замовлення</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['item_added'])): ?>
        <div class="alert alert-success">Позицію додано.</div>
    <?php endif; ?>
    <?php if (isset($_GET['item_removed'])): ?>
        <div class="alert alert-success">Позицію видалено.</div>
    <?php endif; ?>

    <?php if (!empty($order)): ?>
        <div class="details">
            <p><strong>ID:</strong> <span class="id-badge">#<?= (int)$order->id ?></span></p>
            <p><strong>ID клієнта:</strong> <?= (int)$order->customerId ?></p>
            <p><strong>ID співробітника:</strong> <?= (int)$order->employeeId ?></p>
            <p><strong>Місто доставки:</strong> <?= htmlspecialchars((string)($order->shipCity ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Вулиця доставки:</strong> <?= htmlspecialchars((string)($order->shipStreet ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Поштовий індекс:</strong> <?= htmlspecialchars((string)($order->shipZip ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Дата доставки:</strong> <?= htmlspecialchars((string)($order->shipDate?->format('Y-m-d') ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <?php
        $fmtMoney = function(float $n): string {
            return number_format($n, 2, '.', ' ') . ' ₴';
        };
        ?>

        <?php if (!empty($inventory)): ?>
            <div style="margin-top:20px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <h2 style="margin:0;">Позиції замовлення</h2>
                <a href="#add-items" class="btn-submit" title="Перейти до додавання позицій" aria-label="Перейти до додавання позицій">Додати позиції</a>
            </div>
            <?php if (!empty($inventory['items'])): ?>
                <table class="table" style="width:100%; margin-top:8px;">
                    <thead>
                    <tr>
                        <th style="text-align:left;">Товар</th>
                        <th style="text-align:right;">Кількість</th>
                        <th style="text-align:right;">Ціна</th>
                        <th style="text-align:right;">Сума</th>
                        <th style="text-align:right;">Дії</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($inventory['items'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)$row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td style="text-align:right;"><?= (int)$row['quantity'] ?></td>
                            <td style="text-align:right;"><?= $fmtMoney((float)$row['soldPrice']) ?></td>
                            <td style="text-align:right; font-weight: 600;"><?= $fmtMoney((float)$row['lineTotal']) ?></td>
                            <td style="text-align:right;">
                                <form method="post" action="index.php?r=order/removeItem&id=<?= (int)$order->id ?>" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= (int)$row['productId'] ?>">
                                    <button type="submit" class="btn" onclick="return confirm('Видалити позицію?');">Видалити</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="card" style="margin-top:12px; padding:12px;">
                    <div><strong>Всього позицій:</strong> <?= (int)($inventory['totals']['itemCount'] ?? 0) ?></div>
                    <div><strong>Загальна кількість:</strong> <?= (int)($inventory['totals']['totalQuantity'] ?? 0) ?></div>
                    <div><strong>Загальна вартість:</strong> <?= $fmtMoney((float)($inventory['totals']['totalValue'] ?? 0)) ?></div>
                </div>
            <?php else: ?>
                <p class="muted" style="margin-top:8px;">Немає позицій у цьому замовленні.</p>
            <?php endif; ?>
        <?php endif; ?>

        <div id="add-items" class="add-items-section" style="display:none;">
        <h2 style="margin-top:20px;">Додати позицію</h2>
        <div class="card" style="padding:12px; display:block; max-width:100%;">
            <table class="table" style="width:100%;">
                <thead>
                <tr>
                    <th style="text-align:left;">Товар</th>
                    <th style="text-align:right;">Базова ціна</th>
                    <th style="text-align:right;">Кількість</th>
                    <th style="text-align:right;">Дії</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach (($allItems ?? []) as $it): ?>
                    <tr>
                        <td><?= htmlspecialchars($it->name, ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="text-align:right; white-space:nowrap;"><?= $fmtMoney((float)$it->price) ?></td>
                        <td style="text-align:right;">
                            <!-- Hidden per-row form to collect fields; inputs/buttons reference it via the form attribute -->
                            <form id="add-form-<?= (int)$it->id ?>" method="post" action="index.php?r=order/addItem&id=<?= (int)$order->id ?>" style="display:none;">
                                <input type="hidden" name="product_id" value="<?= (int)$it->id ?>">
                                <input type="hidden" name="sold_price" value="<?= htmlspecialchars(number_format((float)$it->price, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>">
                            </form>
                            <input class="qty-input" form="add-form-<?= (int)$it->id ?>" type="number" min="1" step="1" name="quantity" value="1" style="width:90px; text-align:right;">
                        </td>
                        <td style="text-align:right;">
                            <button type="submit" form="add-form-<?= (int)$it->id ?>" class="btn-submit" style="text-align:right;">Встановити</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>

        <div style="margin-top: 16px; display:flex; gap:8px;">
            <a href="index.php?r=order/edit&id=<?= (int)$order->id ?>" class="btn">Редагувати</a>
            <a href="#add-items" class="btn">Додати позиції</a>
            <a href="index.php?r=order/index" class="btn">Назад до списку</a>
        </div>
    <?php else: ?>
        <p class="muted">Замовлення не знайдено.</p>
        <a href="index.php?r=order/index" class="btn">Повернутися</a>
    <?php endif; ?>
</div>
<script>
  (function(){
    var section = document.getElementById('add-items');
    function showSection() {
      if (!section) return;
      if (section.style.display === 'none' || section.style.display === '') {
        section.style.display = 'block';
      } else {
        
      }
      try { section.scrollIntoView({behavior: 'smooth', block: 'start'}); } catch(_) {}
    }
    
    if (location.hash === '#add-items') {
      showSection();
    }
    
    var triggers = document.querySelectorAll('a[href="#add-items"]');
    for (var i = 0; i < triggers.length; i++) {
      triggers[i].addEventListener('click', function(e){
        e.preventDefault();
        showSection();
      });
    }
  })();
</script>

