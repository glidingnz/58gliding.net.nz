<?php
namespace App\Models\Presenters;

use Laracasts\Presenter\Presenter;

class WaypointPresenter extends Presenter {

    const Styles = [
        'Unknown',
        'Waypoint',
        'Grass Runway',
        'Outlanding',
        'Gliding Airfield',
        'Sealed Runway',
        'Mountain Pass',
        'Mountain Top',
        'Transmitter Mast',
        'VOR',
        'NDB',
        'Cooling Tower',
        'Dam',
        'Tunnel',
        'Bridge',
        'Power Plant',
        'Castle',
        'Intersection',
    ];

    public function styleName()
    {
        return SELF::Styles[$this->style];
    }

    public function styleNames()
    {
        return SELF::Styles;
    }
}
?>