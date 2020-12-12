<?php

namespace App\Models;

use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use KubAT\PhpSimple\HtmlDomParser;

class Club extends Model
{
    use HasFactory;

    protected $table = 'clubs';
    protected $fillable = ['name', 'url', 'stats', 'league_id'];

    protected $casts = [
        'stats' => 'array'
    ];

    public function leagues()
    {
        return $this->belongsToMany(League::class,'league_club');
    }

    public function scopeGetStats(){

        if (!$this->stats){
            $dom = HtmlDomParser::file_get_html( $this->url );
            $table = $dom->find('.topStatList',0);
            $arrayKeys = [
                0 => 'matches_played',
                1 => 'wins',
                2 => 'losses',
                3 => 'goals',
                4 => 'goals_conceded',
                5 => 'clean_sheets'
            ];
            $return = [];
            foreach ($table->find('.allStatContainer') as $key => $topStat){
                $return[$arrayKeys[$key]] = intval(str_replace(',','',trim($topStat->plaintext)));
            }
            $this->stats =  $return;
            $this->save();
        }
        return $this->stats;
    }
}
