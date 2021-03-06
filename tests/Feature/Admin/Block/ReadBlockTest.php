<?php

namespace Tests\Feature\Admin\Block;

use App\Models\Block;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCaseWithCourse;

class ReadBlockTest extends TestCaseWithCourse {

    private $blockId;

    public function setUp(): void {
        parent::setUp();

        $this->blockId = $this->createBlock('Block 1');
    }

    public function test_shouldRequireLogin() {
        // given
        auth()->logout();

        // when
        $response = $this->get('/course/' . $this->courseId . '/admin/blocks/' . $this->blockId);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_shouldDisplayBlock() {
        // given

        // when
        $response = $this->get('/course/' . $this->courseId . '/admin/blocks/' . $this->blockId);

        // then
        $response->assertOk();
        $response->assertSee('Block 1');
    }

    public function test_shouldNotDisplayBlock_fromOtherCourseOfSameUser() {
        // given
        $otherKursId = $this->createCourse('Zweiter Kurs', '');

        // when
        $response = $this->get('/course/' . $otherKursId . '/admin/blocks/' . $this->blockId);

        // then
        $this->assertInstanceOf(ModelNotFoundException::class, $response->exception);
    }

    public function test_shouldNotDisplayBlock_fromOtherUser() {
        // given
        $otherKursId = $this->createCourse('Zweiter Kurs', '', false);
        $otherBlockId = Block::create(['course_id' => $otherKursId, 'full_block_number' => '1.1', 'name' => 'later date', 'block_date' => '02.01.2019', 'requirements' => null])->id;

        // when
        $response = $this->get('/course/' . $otherKursId . '/admin/blocks/' . $otherBlockId);

        // then
        $this->assertInstanceOf(ModelNotFoundException::class, $response->exception);
    }

    public function test_shouldOrderBlocks() {
        // given
        $this->createBlock('later date', '1.1', '02.01.2019');
        $this->createBlock('earlier date', '1.1', '31.12.2018');
        $this->createBlock('later day number', '2.1', '01.01.2019');
        $this->createBlock('earlier day number', '0.1', '01.01.2019');
        $this->createBlock('later block number', '1.2', '01.01.2019');
        $this->createBlock('earlier block number', '1.0', '01.01.2019');
        $this->createBlock('Block 2 later block name', '1.1', '01.01.2019');
        $this->createBlock('Block 0 earlier block name', '1.1', '01.01.2019');

        // when
        $response = $this->get('/course/' . $this->courseId . '/admin/blocks');

        // then
        $response->assertOk();
        $response->assertSeeInOrder([
          'earlier date',
          'earlier day number',
          'earlier block number',
          'Block 0 earlier block name',
          'Block 1',
          'Block 2 later block name',
          'later block number',
          'later day number',
          'later date',
        ]);
    }
}
