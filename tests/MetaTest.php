<?php

namespace Jclyons52\PagePreview;

class MetaTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_og_meta()
    {
        $meta = $this->getMetaInstance();

        $og = $meta->unFlatten()['og'];

        $expected = [
            "title" => "Meta title",
            "description" => "Foo bar bar foo",
            "url" => "http://www.example.com",
        ];

        $this->assertEquals($expected, $og);
    }

    /**
     * @test
     */
    public function it_gets_twitter_app_card_as_multi_level_array()
    {
        $original = [
            "twitter:card" => "app",
            "twitter:site" => "@TwitterDev",
            "twitter:description" => "Cannonball is the fun way to create and share stories and poems on your phone. Start with a beautiful image from the gallery, then choose words to complete the story and share it with friends.",
            "twitter:app:country" => "US",
            "twitter:app:name:iphone" => "Cannonball",
            "twitter:app:id:iphone" => "929750075",
            "twitter:app:url:iphone" => "cannonball://poem/5149e249222f9e600a7540ef",
            "twitter:app:name:ipad" => "Cannonball",
            "twitter:app:id:ipad" => "929750075",
            "twitter:app:url:ipad" => "cannonball://poem/5149e249222f9e600a7540ef",
            "twitter:app:name:googleplay" => "Cannonball",
            "twitter:app:id:googleplay" => "io.fabric.samples.cannonball",
            "twitter:app:url:googleplay" => "http://cannonball.fabric.io/poem/5149e249222f9e600a7540ef",
        ];
        $expected = [
            "card" => "app",
            "site" => "@TwitterDev",
            "description" => "Cannonball is the fun way to create and share stories and poems on your phone. Start with a beautiful image from the gallery, then choose words to complete the story and share it with friends.",
            "app" => [
                "country" => "US",
                "name" => [
                    "iphone" => "Cannonball",
                    "ipad" => "Cannonball",
                    "googleplay" => "Cannonball",
                ],
                "id" => [
                    "iphone" => "929750075",
                    "ipad" => "929750075",
                    "googleplay" => "io.fabric.samples.cannonball",
                ],
                "url" => [
                    "iphone" => "cannonball://poem/5149e249222f9e600a7540ef",
                    "ipad" => "cannonball://poem/5149e249222f9e600a7540ef",
                    "googleplay" => "http://cannonball.fabric.io/poem/5149e249222f9e600a7540ef",
                ],
            ]

        ];

        $meta = new Meta($original);

        $twitterCard = $meta->unFlatten()['twitter'];

        $this->assertEquals($expected, $twitterCard);
    }

    /**
     * @test
     */
    public function it_unFlattens_entire_array()
    {
        $original = [
            "article:published_time" => "2014-08-12T00:01:56+00:00",
            "article:author" => "CNN Karla Cripps",
            "og:title" => "Meta title",
            "twitter:card" => "app",
            "twitter:app:name:iphone" => "Cannonball",
        ];
        $expected = [
            "article" => [
                "published_time" => "2014-08-12T00:01:56+00:00",
                "author" => "CNN Karla Cripps",
            ],
            "og" => [
                "title" => "Meta title",
            ],
            "twitter" => [
                "card" => "app",
                "app" => [
                    "name" => [
                        "iphone" => "Cannonball",
                    ]
                ]
            ]
        ];

        $meta = new Meta($original);

        $actual = $meta->unFlatten();

        $this->assertEquals($expected, $actual);
    }


    /**
     * @return Meta
     */
    private function getMetaInstance()
    {
        return new Meta([
            "og:title" => "Meta title",
            "og:description" => "Foo bar bar foo",
            "title" => "Meta title",
            "description" => "Foo bar bar foo",
            "og:url" => "http://www.example.com",
            "keywords" => ["test", "thing", "stuff"],
            "apple-mobile-web-app-capable" => "yes",
            "viewport" => "minimal-ui",
        ]);
    }
}