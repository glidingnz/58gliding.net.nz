<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Badge;

class BadgeUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $badge = new Badge;
        $badge->name='1st Outlanding';
        $badge->type='Outlanding';
        $badge->slug='1st-outlanding';
        $badge->save();


        $badge = new Badge;
        $badge->name='OLC 750km';
        $badge->type='OLC';
        $badge->slug='olc-750km';
        $badge->save();

        $badge = new Badge;
        $badge->name='OLC 1000km';
        $badge->type='OLC';
        $badge->slug='olc-1000km';
        $badge->save();

        $badge = Badge::where('slug', '5-outlanding')->first();
        $badge->slug='5-outlandings';
        $badge->save();

        $badge = new Badge;
        $badge->name='20 Outlandings';
        $badge->type='Outlanding';
        $badge->slug='20-outlandings';
        $badge->save();

        $badge = new Badge;
        $badge->name='50+ Outlandings';
        $badge->type='Outlanding';
        $badge->slug='50-outlandings';
        $badge->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if ($badge = Badge::where('slug', '5-outlandings')->first())
        {
            $badge->slug='5-outlanding';
            $badge->save();
        }   


        if ($badge = Badge::where('slug', '50-outlandings')->first()) $badge->delete();

        if ($badge = Badge::where('slug', '20-outlandings')->first()) $badge->delete();

        if ($badge = Badge::where('slug', 'olc-1000km')->first()) $badge->delete();

        if ($badge = Badge::where('slug', 'olc-750km')->first()) $badge->delete();

        if ($badge = Badge::where('slug', '1st-outlanding')->first()) $badge->delete();
    }
}
