<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    protected $table = 'leagues';
    protected $fillable = ['name', 'all_stats', 'selected_stats'];

    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'league_club');
    }

    public function fixture()
    {
        return $this->hasMany(LeagueFixture::class);
    }
}
