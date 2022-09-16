<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table      = 'menus';
    protected $primaryKey = 'id';
    public $timestamps    = false;
   
    protected $fillable = [
        "total", 
        "sucursal", 
        "name_comercial", 
        "es_lang",
        "en_lang",
        "encuesta_switch",
        "esp_switch",
        "eng_switch",
        "url_switch",
        "youtube_switch",
        "insta_switch",
        "tiktok_switch",
        "facebook_switch",
        "whatsapp_switch",
        "youtube_url",
        "insta_url",
        "tiktok_url",
        "facebook_url",
        "whatsapp_url",
        "page_url",
        "color_title",
        "color_text",
        "color_background",
        "color_buttons"
    ];
}
