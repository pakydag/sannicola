<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    protected $fillable = ['nome', 'slug', 'parent_id', 'visibile', 'ordine'];

    public function parent()
    {
        return $this->belongsTo(ShopCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ShopCategory::class, 'parent_id');
    }

    /**
     * Restituisce gli ID di tutti i discendenti (figli, nipoti, ecc.)
     */
    public function getAllDescendantIds(array &$visited = []): array
    {
        $descendants = [];
        $visited[$this->id] = true;
        foreach ($this->children as $child) {
            if (isset($visited[$child->id])) {
                continue;
            }
            $descendants[] = $child->id;
            $descendants = array_merge($descendants, $child->getAllDescendantIds($visited));
        }
        return $descendants;
    }
}
