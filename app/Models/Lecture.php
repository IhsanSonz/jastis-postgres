<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'desc',
    'code',
    'color',
    'notification_key',
  ];

  /**
   * Constant for validation rules.
   *
   * @var array
   */
  public const VALIDATION_RULES = [
    'name' => 'required|string|max:255',
    'desc' => 'required|string',
  ];

  /**
   * Many to many relationship with User model
   *
   * @return array
   */
  public function users()
  {
    //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
    return $this->belongsToMany(User::class)
      ->withPivot('level')
      ->withTimestamps();
  }
}
