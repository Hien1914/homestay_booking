<?php
    $paymentClass = 'admin-badge-pending';
    $paymentLabel = 'Đang chờ thanh toán';
    if ($booking->payment) {
        $paymentClass = $booking->payment->displayStatusBadgeClass();
        $paymentLabel = $booking->payment->displayStatusLabel();
    }
?>

<div class="admin-table-wrap">
    <table class="admin-table">
        <tbody>
            <tr>
                <th>Mã đơn</th>
                <td><span class="admin-id-badge">#<?php echo e($booking->id); ?></span></td>
            </tr>
            <tr>
                <th>Tên khách</th>
                <td><?php echo e($booking->user?->full_name ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Homestay</th>
                <td><?php echo e($booking->homestay?->title ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Lịch trình</th>
                <td><?php echo e(optional($booking->check_in)->format('d/m/Y') ?? '-'); ?> - <?php echo e(optional($booking->check_out)->format('d/m/Y') ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Số tiền</th>
                <td class="fw-bold text-success"><?php echo e(number_format((float) $booking->total_amount, 0, ',', '.')); ?>đ</td>
            </tr>
            <tr>
                <th>Trạng thái đơn hàng</th>
                <td><span class="admin-badge <?php echo e($booking->statusBadgeClass()); ?>"><?php echo e($booking->statusLabel()); ?></span></td>
            </tr>
            <tr>
                <th>Trạng thái thanh toán</th>
                <td><span class="admin-badge <?php echo e($paymentClass); ?>"><?php echo e($paymentLabel); ?></span></td>
            </tr>
        </tbody>
    </table>
</div>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/bookings/show_partial.blade.php ENDPATH**/ ?>