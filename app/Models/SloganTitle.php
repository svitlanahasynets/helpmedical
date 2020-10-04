<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SloganTitle extends Model {

    //
  protected $table = 'slogan_titles';
  protected $fillable = ['key'];
  public $timestamps = false;


  public static function getSettings() {
    $keys = ['slogan', 'model_title'];
    $set = self::whereIn('key', $keys)->get();

    $data = [];
    foreach($keys AS $k) {
      $data[$k] = '';
    }

    foreach($set AS $s) {
      $data[$s->key] = $s->value;
    }

    return $data;
  }


}

