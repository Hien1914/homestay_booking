<?php
    $displayDate = $payment->paid_at ?? $payment->created_at;
    $paymentLabel = $payment->payment_status === \App\Models\Payment::STATUS_PENDING && $payment->paid_at
        ? 'Chờ duyệt'
        : $payment->statusLabel();
?>

<div class="admin-table-wrap">
    <table class="admin-table">
        <tbody>
            <tr>
                <th>Mã đơn</th>
                <td><span class="admin-id-badge">#<?php echo e($payment->booking?->id ?? '-'); ?></span></td>
            </tr>
            <tr>
                <th>Người dùng</th>
                <td><?php echo e($payment->booking?->user?->full_name ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Tên homestay</th>
                <td><?php echo e($payment->booking?->homestay?->title ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Số tiền</th>
                <td class="fw-bold text-success"><?php echo e(number_format((float) $payment->amount, 0, ',', '.')); ?> đ</td>
            </tr>
            <tr>
                <th>Trạng thái</th>
                <td><span class="admin-badge <?php echo e($payment->statusBadgeClass()); ?>"><?php echo e($paymentLabel); ?></span></td>
            </tr>
            <tr>
                <th>Ngày</th>
                <td><?php echo e(optional($displayDate)->format('d/m/Y H:i') ?? '-'); ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/payments/show_partial.blade.php ENDPATH**/ ?>