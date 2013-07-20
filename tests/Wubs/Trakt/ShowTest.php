<?php

use Wubs\Trakt\Trakt;

class ShowTest extends \PHPUnit_Framework_TestCase{

	public static $sShow;

	public static function setUpBeforeClass(){
		self::$sShow = Trakt::show(153021);
	}


	public function setUp(){
		$this->show = Trakt::show(153021);
	}

	public function tearDown(){
		unset($this->show);
	}

	public function testGetShowObject(){
		$this->assertInstanceOf('Wubs\\Trakt\\Media\\Show', $this->show);
		$this->assertEquals('The Walking Dead', $this->show->title);
	}

	public function testGetShowSeasons(){
		$seasons = $this->show->seasons(); //this should make an request and return an array of season objects
		$this->assertInternalType('array', $seasons);
		foreach ($seasons as $season) {
			$this->assertInternalType('object', $season);
			$this->assertInstanceOf('Wubs\\Trakt\\Media\\Season', $season);
		}
	}

	public function testGetSeasonList(){
		$seasons = $this->show->seasons();
		$this->assertInternalType('array', $seasons);
		$this->assertInstanceOf('Wubs\\Trakt\\Media\\Season', $seasons[0]);
	}

	public function testGetOneSeason(){
		$season = $this->show->season(1); //This should make a request and return a season object
		$this->assertInternalType('object', $season);
		$this->assertInstanceOf('Wubs\\Trakt\\Media\\Season', $season);
		$this->assertEquals(1, $season->season);
	}

	public function testGetAllSeasonsFirstAsArrayThanOneSeasonAsObject(){
		$seasons = $this->show->seasons(false); // This should make a request and return an array of season arrays
		$this->assertInternalType('array', $seasons);
		foreach ($seasons as $season) {
			$this->assertInternalType('array', $season);
			$this->assertArrayHasKey('season', $season);
		}
		$this->assertEquals(4, $seasons[0]['season']); // first element in array is last season of show
		$season1 = $this->show->season(1); //this should not make a request and return an object
		$this->assertInternalType('object', $season1);
		$this->assertInstanceOf('Wubs\\Trakt\\Media\\Season', $season1);
		$this->assertEquals(1, $season1->season);
	}

	public function testGetAllSeasonsFirstAsObjectThanOneSeasonAsArray(){
		$seasons = $this->show->seasons(); //array of objects
		$this->assertInternalType('array', $seasons);
		foreach ($seasons as $season) {
			$this->assertInternalType('object', $season);
		}
		$season = $this->show->season(1, false); //one array
		$this->assertInternalType('array', $season);
		$this->assertArrayHasKey('season', $season);
		$this->assertEquals(1, $season['season']);
	}

	public function testGetShowAllComments(){
		$comments = self::$sShow->comments();
		$this->assertInternalType('array', $comments);
		foreach ($comments as $comment) {
			$this->assertArrayHasKey('inserted', $comment);
		}
	}

	public function testGetShowShoutComments(){
		$comments = self::$sShow->comments('shouts');
		foreach ($comments as $comment) {
			$this->assertArrayHasKey('inserted', $comment);
			$this->assertEquals('shout', $comment['type']);
		}
	}

	public function testGetShowReviewComments(){
		$comments = self::$sShow->comments('reviews');
		foreach ($comments as $comment) {
			$this->assertArrayHasKey('inserted', $comment);
			$this->assertEquals('review', $comment['type']);
		}
	}

	public function testGetShowReviewsByReviewMethod(){
		$reviews = self::$sShow->reviews();
		foreach ($reviews as $review) {
			$this->assertArrayHasKey('inserted', $review);
			$this->assertEquals('review', $review['type']);
		}
	}

	public function testGetShoutsByShoutsMethod(){
		$shouts = self::$sShow->shouts();
		foreach ($shouts as $shout) {
			$this->assertArrayHasKey('inserted', $shout);
			$this->assertEquals('shout', $shout['type']);
		}
	}
}