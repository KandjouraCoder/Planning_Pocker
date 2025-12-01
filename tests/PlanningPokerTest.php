<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../functions.php';

class PlanningPokerTest extends TestCase {

    public function testCalculerMoyenneSimple() {
        $this->assertSame(3.0, calculerMoyenne([1, 3, 5]));
    }

    public function testCalculerMoyenneVide() {
        $this->assertSame(0.0, calculerMoyenne([]));
    }
}
