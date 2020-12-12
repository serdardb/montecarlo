<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueFixture extends Model
{
    use HasFactory;

    protected $table = 'league_fixture';
    protected $fillable = ['home_id', 'away_id', 'week', 'home_score', 'away_score'];

    public function home_club()
    {
        return $this->belongsTo(Club::class, 'home_id','id');
    }

    public function away_club()
    {
        return $this->belongsTo(Club::class, 'away_id', 'id');
    }
}
