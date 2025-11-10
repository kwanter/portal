<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class Application extends Model implements Auditable
{
    use HasFactory, HasUuids, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "url",
        "description",
        "category",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime",
        "deleted_at" => "datetime",
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set created_by on creation
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        // Automatically set updated_by on update
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        // Automatically set deleted_by on soft delete
        static::deleting(function ($model) {
            if (Auth::check() && !$model->isForceDeleting()) {
                $model->deleted_by = Auth::id();
                $model->save();
            }
        });
    }

    /**
     * Get the user who created the application.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, "created_by");
    }

    /**
     * Get the user who last updated the application.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, "updated_by");
    }

    /**
     * Get the user who deleted the application.
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, "deleted_by");
    }

    /**
     * Scope a query to only include applications of a given category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where("category", $category);
    }

    /**
     * Scope a query to filter by search term.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query
                ->where("name", "like", "%{$search}%")
                ->orWhere("url", "like", "%{$search}%")
                ->orWhere("description", "like", "%{$search}%");
        });
    }
}
