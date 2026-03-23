<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'homestay_id',
        'question',
        'answer',
        'keywords',
        'category',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'keywords'  => 'array',
        'is_active' => 'boolean',
    ];

    public function homestay()
    {
        return $this->belongsTo(Homestay::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function matchesQuery(string $query): bool
    {
        $query    = mb_strtolower($query);
        $keywords = array_map('mb_strtolower', $this->keywords ?? []);

        foreach ($keywords as $keyword) {
            if (str_contains($query, $keyword)) return true;
        }

        // fallback: khớp từ trong câu hỏi gốc
        $questionWords = explode(' ', mb_strtolower($this->question));
        foreach ($questionWords as $word) {
            if (mb_strlen($word) >= 3 && str_contains($query, $word)) return true;
        }

        return false;
    }
}
