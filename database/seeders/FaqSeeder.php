<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $faqs = [
            [
                'question' => 'Gio nhan phong va tra phong la may gio?',
                'answer'   => 'Gio nhan phong (check-in) tu 14:00 va tra phong (check-out) truoc 12:00. Vui long xem thong tin cu the trong trang chi tiet homestay.',
                'keywords' => ['gio', 'check in', 'check out', 'nhan phong', 'tra phong', 'may gio'],
                'category' => 'checkin',
            ],
            [
                'question' => 'Toi co the huy dat phong khong?',
                'answer'   => 'Co, ban co the huy dat phong. Chinh sach hoan tien tuy thuoc vao chinh sach huy cua tung homestay (linh hoat, vua phai, hoac nghiem ngat). Vui long kiem tra truoc khi dat.',
                'keywords' => ['huy', 'hoan tien', 'cancel', 'refund'],
                'category' => 'policy',
            ],
            [
                'question' => 'Thanh toan bang hinh thuc nao?',
                'answer'   => 'He thong ho tro thanh toan qua VNPay va MoMo. Ban co the chon hinh thuc khi dat phong.',
                'keywords' => ['thanh toan', 'vnpay', 'momo', 'payment', 'chuyen khoan'],
                'category' => 'payment',
            ],
            [
                'question' => 'Phi dich vu la bao nhieu?',
                'answer'   => 'Phi dich vu nen tang la 10% tren tong tien phong. Phi nay da duoc tinh vao tong tien hien thi khi ban dat phong.',
                'keywords' => ['phi', 'phi dich vu', 'service fee', 'bao nhieu'],
                'category' => 'payment',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create([...$faq, 'created_by' => $admin->id, 'is_active' => true]);
        }
    }
}
