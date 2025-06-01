<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\Status;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Item extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $table = "items";
    protected $fillable = [
        'name',
        'item_category_id',
        'slug',
        'tax_id',
        'item_type',
        'price',
        'is_featured',
        'description',
        'caution',
        'status',
        'order',
        'brand_id',
    ];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'id'               => 'integer',
        'name'             => 'array', // Changed from 'string' to 'array' to handle JSON
        'item_category_id' => 'integer',
        'slug'             => 'string',
        'tax_id'           => 'integer',
        'item_type'        => 'integer',
        'price'            => 'decimal:2',
        'is_featured'      => 'integer',
        'description' => 'array',
        'caution'          => 'string',
        'status'           => 'integer',
        'order'            => 'integer',
        'brand_id'         => 'integer',
    ];
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function branches()
    {
return $this->belongsToMany(Branch::class, 'branch_item')->withPivot('price');    }

    public function getThumbAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('item'))) {
            $item = $this->getMedia('item')->last();
            return $item->getUrl('thumb');
        }
        return asset('images/item/thumb.png');
    }

    public function getCoverAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('item'))) {
            $item = $this->getMedia('item')->last();
            return $item->getUrl('cover');
        }
        return asset('images/item/cover.png');
    }

    public function getPreviewAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('item'))) {
            $item = $this->getMedia('item')->last();
            return $item->getUrl('preview');
        }
        return asset('images/item/cover.png');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->crop('crop-center', 112, 120)->keepOriginalImageFormat()->sharpen(10);
        $this->addMediaConversion('cover')->crop('crop-center', 260, 180)->keepOriginalImageFormat()->sharpen(10);
        $this->addMediaConversion('preview')->width(400)->keepOriginalImageFormat()->sharpen(10);
    }

    public function variations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemVariation::class)->with('itemAttribute')->where(['status' => Status::ACTIVE]);
    }

    public function extras(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemExtra::class)->where(['status' => Status::ACTIVE]);
    }

    public function addons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemAddon::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id', 'id');
    }

    public function tax(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class, 'item_id', 'id');
    }

    public function offer(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Offer::class, 'offer_items');
    }
	public function getPriceForBranch($branchId)
    {
        $branch = $this->branches()->where('branch_id', $branchId)->first();
        return $branch && $branch->pivot->price !== null ? $branch->pivot->price : $this->price;
    }
	public function getNameEnAttribute(): string
{
    return $this->name['en'] ?? '';
}

public function getNameArAttribute(): string
{
    return $this->name['ar'] ?? '';
}
}
