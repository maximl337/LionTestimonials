<?php

namespace App\Contracts;

interface VideoToGif {

	public function convert($video_url, $video_id);

	public function save(array $input);
}