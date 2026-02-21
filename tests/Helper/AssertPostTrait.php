<?php

namespace App\Tests\Helper;

use App\Domain\Model\PostModel;
use PHPUnit\Framework\TestCase;

trait AssertPostTrait
{

    /**
     * @param TestCase $test
     * @param PostModel $postModelExcepted
     * @param PostModel $postModelResult
     * @return void
     */
    public function assertPostModelExpectedEqualsPostModelResult(TestCase $test, PostModel $postModelExcepted, PostModel $postModelResult): void
    {
        $test->assertSame($postModelExcepted->getId(), $postModelResult->getId());
        $test->assertSame($postModelExcepted->getTitle(), $postModelResult->getTitle());
        $test->assertSame($postModelExcepted->getContent(), $postModelResult->getContent());
        $test->assertSame($postModelExcepted->getSlug(), $postModelResult->getSlug());
        $test->assertSame($postModelExcepted->getSubTitle(), $postModelResult->getSubTitle());
        $test->assertSame($postModelExcepted->getCreatedAt(), $postModelResult->getCreatedAt());
        $test->assertSame($postModelExcepted->getUpdatedAt(), $postModelResult->getUpdatedAt());
        $test->assertSame($postModelExcepted->isPublished(), $postModelResult->isPublished());
        $test->assertSame($postModelExcepted->getTags()[0], $postModelResult->getTags()[0]);
    }

}
