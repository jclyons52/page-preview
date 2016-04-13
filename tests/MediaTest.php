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
            $this->assertEquals([
                'thumbnail' => "http://i2.ytimg.com/vi/EIQQnoeepgU/default.jpg",
                'url' => 'https://www.youtube.com/embed/EIQQnoeepgU'
            ], $videos);
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

    /**
     * @test
     */
    public function it_gets_vimeo_media()
    {
        $vimeoUrls = [
            'https://vimeo.com/11111111',
            'http://vimeo.com/11111111',
            'https://www.vimeo.com/11111111',
            'http://www.vimeo.com/11111111',
            'https://vimeo.com/channels/11111111',
            'http://vimeo.com/channels/11111111',
            'https://vimeo.com/groups/name/videos/11111111',
            'http://vimeo.com/groups/name/videos/11111111',
            'https://vimeo.com/album/2222222/video/11111111',
            'http://vimeo.com/album/2222222/video/11111111',
            'https://vimeo.com/11111111?param=test',
            'http://vimeo.com/11111111?param=test',
        ];
        foreach ($vimeoUrls as $url) {
            $videos = MediaMock::get($url);
            $this->assertEquals("http://i.vimeocdn.com/video/564612660_640.jpg", $videos['thumbnail']);
            $this->assertEquals("http://player.vimeo.com/video/11111111", $videos['url']);
        }
    }

    /**
     * @test
     */
    public function it_returns_empty_array_if_vimeo_media_not_found()
    {
        $videos = Media::get('http://vimeo.foo/bar ');
        $this->assertEquals([], $videos);
    }
}

class MediaMock extends Media 
{
    protected static function fetch($url)
    {
        return json_decode(file_get_contents(__DIR__ .'/data/API/vimeo.json'));
    }
}