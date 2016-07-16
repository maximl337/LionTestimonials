<?php

namespace App\Contracts;

interface VideoToGif {

	public function convert($video_url);

	public function save(array $input);
}