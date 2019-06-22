<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Org;

class CreateOrgsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::create('orgs', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('short_name');
			$table->string('website');
			$table->string('slug');
			$table->string('gnz_code')->nullable()->default(NULL);
			$table->string('type')->default('contest');
			$table->timestamps();
		});

		$org = new Org;
		$org->name='Glide Omarama / Southern Soaring';
		$org->short_name='Omarama';
		$org->slug='glideomarama';
		$org->gnz_code='GOM';
		$org->type='club';
		$org->website='www.glideomarama.com';
		$org->save();

		$org = new Org;
		$org->name='Whenuapai Aviation Sports Club';
		$org->short_name='Whenuapai';
		$org->slug='whenuapai';
		$org->gnz_code='AAV';
		$org->type='club';
		$org->website='www.ascgliding.org';
		$org->save();

		$org = new Org;
		$org->name='Auckland Gliding Club';
		$org->short_name='Auckland';
		$org->slug='auckland';
		$org->gnz_code='AKL';
		$org->type='club';
		$org->website='www.glidingauckland.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Canterbury Gliding Club';
		$org->short_name='Canterbury';
		$org->slug='canterbury';
		$org->gnz_code='CTY';
		$org->type='club';
		$org->website='www.glidingcanterbury.org.nz';
		$org->save();

		$org = new Org;
		$org->name='Central Otago Flying Club';
		$org->short_name='Otago';
		$org->slug='otago';
		$org->gnz_code='COT';
		$org->type='club';
		$org->website='www.cofc.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Gliding Hutt Valley';
		$org->short_name='Hutt';
		$org->slug='huttvalley';
		$org->gnz_code='GHV';
		$org->type='club';
		$org->website='www.glidinghuttvalley.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Gliding Manawatu';
		$org->short_name='Manawatu';
		$org->slug='manawatu';
		$org->gnz_code='GOM';
		$org->type='club';
		$org->website='www.glidingmanawatu.org.nz';
		$org->save();

		$org = new Org;
		$org->name='Gliding Hawkes Bay & Waipukurau Inc';
		$org->short_name='Hawkes Bay';
		$org->slug='hawkesbay';
		$org->gnz_code='HBY';
		$org->type='club';
		$org->website='www.glidinghbw.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Kaikohe Gliding Club';
		$org->short_name='Kaikohe';
		$org->slug='kaikohe';
		$org->gnz_code='KKE';
		$org->type='club';
		$org->website='www.glidingkaikohe.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Marlborough Gliding Club';
		$org->short_name='Marlborough';
		$org->slug='marlborough';
		$org->gnz_code='MLB';
		$org->type='club';
		$org->website='';
		$org->save();

		$org = new Org;
		$org->name='Nelson Lakes Gliding Club';
		$org->short_name='Nelson';
		$org->slug='nelsonlakes';
		$org->gnz_code='NLN';
		$org->type='club';
		$org->website='www.glidingnelson.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Norfolk Aviation Sports Club';
		$org->short_name='Norfolk';
		$org->slug='norfolk';
		$org->gnz_code='NRF';
		$org->type='club';
		$org->website='';
		$org->save();

		$org = new Org;
		$org->name='Omarama Gliding Club';
		$org->short_name='Omarama';
		$org->slug='omarama';
		$org->gnz_code='OGC';
		$org->type='club';
		$org->website='www.omarama.com';
		$org->save();

		$org = new Org;
		$org->name='Piako Gliding Club';
		$org->short_name='Piako';
		$org->slug='piako';
		$org->gnz_code='PKO';
		$org->type='club';
		$org->website='www.glidingmatamata.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Rotorua Gliding Club';
		$org->short_name='Rotorua';
		$org->slug='rotorua';
		$org->gnz_code='ROT';
		$org->type='club';
		$org->website='www.rotoruaglidingclub.blogspot.com';
		$org->save();

		$org = new Org;
		$org->name='South Canterbury Gliding Club';
		$org->short_name='South Canterbury';
		$org->slug='sthcanterbury';
		$org->gnz_code='SCY';
		$org->type='club';
		$org->website='www.glidingsouthcanterbury.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Taranaki Gliding Club';
		$org->short_name='Taranaki';
		$org->slug='taranaki';
		$org->gnz_code='TRK';
		$org->type='club';
		$org->website='www.glidingtaranaki.com';
		$org->save();

		$org = new Org;
		$org->name='Taupo Gliding Club';
		$org->short_name='Taupo';
		$org->slug='taupo';
		$org->gnz_code='TPO';
		$org->type='club';
		$org->website='www.taupoglidingclub.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Tauranga Gliding Club';
		$org->short_name='Tauranga';
		$org->slug='tauranga';
		$org->gnz_code='TGA';
		$org->type='club';
		$org->website='www.glidingtauranga.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Wellington Gliding Club';
		$org->short_name='Wellington';
		$org->slug='wellington';
		$org->gnz_code='WLN';
		$org->type='club';
		$org->website='www.soar.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Whangarei Gliding Club';
		$org->short_name='Whangarei';
		$org->slug='whangarei';
		$org->gnz_code='WHG';
		$org->type='club';
		$org->website='';
		$org->save();

		$org = new Org;
		$org->name='Gliding Wairarapa';
		$org->short_name='Wairarapa';
		$org->slug='wairarapa';
		$org->gnz_code='GWR';
		$org->type='club';
		$org->website='glidingwairarapa.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Balclutha Gliding Club';
		$org->short_name='Balclutha';
		$org->slug='balclutha';
		$org->gnz_code='CLV';
		$org->type='club';
		$org->website='';
		$org->save();

		$org = new Org;
		$org->name='Masterton';
		$org->short_name='Masterton';
		$org->slug='masterton';
		$org->gnz_code='MSN';
		$org->type='club';
		$org->website='';
		$org->save();

		$org = new Org;
		$org->name='Gliding Manawatu';
		$org->short_name='manawatu';
		$org->slug='manawatu';
		$org->gnz_code='WGM';
		$org->type='club';
		$org->website='www.glidingmanawatu.org.nz';
		$org->save();

		$org = new Org;
		$org->name='Matamata Soaring Centre';
		$org->short_name='msc';
		$org->slug='msc';
		$org->gnz_code='MSC';
		$org->type='soaringcentre';
		$org->website='msc.gliding.co.nz';
		$org->save();

		$org = new Org;
		$org->name='Omarama Soaring Centre';
		$org->short_name='osc';
		$org->slug='osc';
		$org->gnz_code='OSC';
		$org->type='soaringcentre';
		$org->website='www.osc.net.nz';
		$org->save();

		$org = new Org;
		$org->name='Greytown Soaring Centre';
		$org->short_name='gsc';
		$org->slug='gsc';
		$org->gnz_code='GSC';
		$org->type='soaringcentre';
		$org->website='';
		$org->save();

		$org = new Org;
		$org->name='Youth Glide New Zealand';
		$org->short_name='youthglide';
		$org->slug='youthglide';
		$org->gnz_code='';
		$org->type='org';
		$org->website='youthglide.org.nz';
		$org->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orgs');
	}
}
