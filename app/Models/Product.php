<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use App\Models\Scopes\PriceScope;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


// #[ScopedBy([PriceScope::class])]


class Product extends Model implements HasMedia
{

    use HasFactory , HasSlug , InteractsWithMedia;

  

    public function registerMediaCollections(): void
{
    $this->addMediaCollection('products')
       ->singleFile()
    //    ->acceptsMimeTypes(['image/jpeg'])
       ->registerMediaConversions(function (Media $media) {
        $this
            ->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
        
    });
}


    protected function Name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value), //ACCESSORS
            set: fn (string $value) => strtolower($value), //MUTATORS
        );
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';
    
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
