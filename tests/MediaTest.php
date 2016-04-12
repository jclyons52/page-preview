<?php

namespace Jclyons52\PagePreview;

class MediaTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_the_embed_version_of_youtube_links()
    {
        $youtubeUrls = [
            'http://www.youtube.com/watch?v=EIQQnoeepgU',
            'http://www.youtube.com/watch?v=EIQQnoeepgU&feature=related',
            'http://youtu.be/EIQQnoeepgU',
            'https://youtu.be/EIQQnoeepgU',
            'http://www.youtube.com/embed/watch?feature=player_embedded&v=EIQQnoeepgU',
            'http://www.youtube.com/watch?feature=player_embedded&v=EIQQnoeepgU',
            'https://www.youtube.com/embed/EIQQnoeepgU',
        ];

        foreach ($youtubeUrls as $url) {
            $videos = Media::get($url);
            $this->assertEquals(['https://www.youtube.com/embed/EIQQnoeepgU'], $videos);
        }
    }

    /**
     * @test
     */
    public function it_returns_empty_array_if_media_is_not_found()
    {
        $videos = Media::get('http://youtu.foo/bar ');
        $this->assertEquals([], $videos);
    }
}