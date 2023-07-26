<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskEntityInit(): void
    {
        $task = new Task();

        $this->assertIsObject($task);

        $task->setTitle('test title');
        $task->setContent('test content');

        $this->assertNull($task->getId());
        $this->assertSame('test title', $task->getTitle());
        $this->assertSame('test content', $task->getContent());

        $this->assertNotEmpty($task->getCreatedAt());
        $this->assertIsBool($task->isDone());
        $this->assertFalse($task->isDone());
    }

    public function testToggle(): void
    {
        $task = new Task();

        $this->assertIsObject($task);

        $this->assertIsBool($task->isDone());
        $this->assertFalse($task->isDone());

        $task->toggle();

        $this->assertIsBool($task->isDone());
        $this->assertTrue($task->isDone());

        $task->toggle();
        
        $this->assertIsBool($task->isDone());
        $this->assertFalse($task->isDone());
    }
}
