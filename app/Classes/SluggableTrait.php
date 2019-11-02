<?php

namespace App\Classes;

trait SluggableTrait
{
	public function setSlugAttribute($slug)
	{
		// check if the slug has changed. If it hasn't, don't do anything otherwise we end up with an incremented number when we don't want one
		if (isset($this->attributes['slug']) && $this->attributes['slug']==$slug) return;

		$slug = simple_string(strtolower($slug));

		$query = $this::query();

		// count the number of times this slug has already been used
		$slug_matches = $query->where('slug', 'like', $slug . '-%')->orWhere('slug', $slug)->get();
		// figure out the biggest
		$biggest = 0;
		foreach ($slug_matches AS $slug_match)
		{
			$biggest=1;
			preg_match("/^.*\-([0-9]*)$/", $slug_match['slug'], $matches);
			if (sizeof($matches)>0)
			{
				$found = (int)$matches[1];
				if ($found > $biggest) $biggest = $found;
			}
		}

		if ($biggest>0)
		{
			$slug_before_number = $slug;
			preg_match("/^(.*)\-[0-9]*$/", $slug, $matches2);
			if (sizeof($matches2)>0) {
				$slug_before_number = $matches2[1];
			}
			$biggest = $biggest + 1;
			$slug = $slug_before_number . '-' . $biggest;
		}

		$this->attributes['slug'] = $slug;
	}
}