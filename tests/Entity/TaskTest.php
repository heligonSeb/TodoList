<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testDefault()
    {
        $task = new Task();
        $task->setTitle('test title');
        $task->setContent('test content');

        $this->assertNull($task->getId());
        $this->assertSame('test title', $task->getTitle());
        $this->assertSame('test content', $task->getContent());

        $this->assertNotEmpty($task->getCreatedAt());
        $this->assertFalse($task->isDone());
    }

    public function testToggle()
    {
        $task = new Task();

        $this->assertFalse($task->isDone());

        $task->toggle();

        $this->assertTrue($task->isDone());

        $task->toggle();
        
        $this->assertFalse($task->isDone());
    }
}