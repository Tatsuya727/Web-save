<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        "url",
        "title",
        "description",
        "favicon"
    ];

    public function scopeSearchUrl($query, $search)
    {
        if ($search) {
            return $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('url', 'like', "%{$search}%")
                ->orWhere('favicon', 'like', "%{$search}%");
        }

        return $query;
    }

}
